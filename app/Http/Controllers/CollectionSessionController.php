<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollectionSession;
use App\Models\SessionPoint;
use App\Models\GarbagePoint;
use App\Models\Barangay;
use App\Models\Truck;
use App\Models\CollectionSchedule;
use App\Models\CollectionPoint;
use App\Models\TruckFullEvent;
use App\Models\User;
use App\Models\UserPointAssignment;
use App\Mail\TruckApproachingMail;
use App\Mail\WasteCollectedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class CollectionSessionController extends Controller
{
    public function activeSession()
    {
        $user = auth()->user();

        // 1. Fetch the active or pending session for the collector
        $activeSession = CollectionSession::with('truck') 
            ->where('collector_id', $user->id)
            ->whereIn('status', ['pending', 'ongoing'])
            ->first();

        // 2. Fetch available trucks for the form
        $trucks = Truck::where('barangay_id', $user->barangay_id)
            ->where('is_active', true)
            ->get();

        // 3. Fetch today's schedule for the barangay
        $today = strtolower(now()->format('l')); // e.g., 'monday'
        $todaySchedule = CollectionSchedule::where('barangay_id', $user->barangay_id)
            ->where('day_of_week', $today)
            ->where('is_active', true)
            ->first();
            
        // We also need $boundaryName for the view when there is no active session
        $boundaryName = $user->barangay->name ?? 'Zone';

        // 4. Pass all variables to the view
        return view('dashboard.partials.collector.active-session', compact(
            'activeSession', 
            'trucks', 
            'todaySchedule',
            'boundaryName'
        ));
    }
       public function startSession(Request $request)
    {
        $user = Auth::user();
        $barangayId = $user->barangay_id ?? Barangay::first()->id;

        // Ensure we have some garbage points to route
        $this->ensureGarbagePointsExist($barangayId);

        $request->validate([
            'plate_number' => 'required|string|exists:trucks,plate_number',
        ]);

        $truck = Truck::where('barangay_id', $barangayId)
            ->where('plate_number', $request->plate_number)
            ->firstOrFail();

        $schedule = $this->getCollectionScheduleForBarangay($barangayId);

        // Find or create a pending session for today
        $session = CollectionSession::where('collector_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$session) {
            $session = CollectionSession::create([
                'barangay_id'  => $barangayId,
                'collector_id' => $user->id,
                'schedule_id'  => $schedule->id,
                'truck_id'     => $truck->id,
                'session_date' => now()->toDateString(),
                'status'       => 'pending',
            ]);

            // Link garbage points to this session
            $points = GarbagePoint::where('barangay_id', $barangayId)
                ->where('is_active', true)
                ->get();

            foreach ($points as $index => $point) {
                SessionPoint::create([
                    'session_id' => $session->id,
                    'garbage_point_id' => $point->id,
                    'route_order' => $index + 1,
                    'status' => 'pending',
                ]);
            }
        }

        // Start the route session
        $session->update([
            'status'     => 'ongoing',
            'started_at' => now(),
        ]);

        // ── Notify all residents in this barangay that collection has started ──
        $this->notifyResidentsSessionStarted($barangayId);

        return redirect()->route('dashboard.route-map')->with('success', 'Collection shift started successfully!');
    }

    /**
     * Show the assigned route map.
     */
    public function routeMap()
    {
        $user = Auth::user();
        $barangayId = $user->barangay_id ?? Barangay::first()->id;

        // Auto-seed garbage points if none exist so the page works out of the box
        $this->ensureGarbagePointsExist($barangayId);

        // Find ongoing session first
        $session = CollectionSession::where('collector_id', $user->id)
            ->where('status', 'ongoing')
            ->first();

        // If no active, check pending session
        if (!$session) {
            $session = CollectionSession::where('collector_id', $user->id)
                ->where('status', 'pending')
                ->first();
        }

        // If no session exists at all, auto-create a pending one for demo purposes
        if (!$session) {
            $schedule = $this->getCollectionScheduleForBarangay($barangayId);
            $session = CollectionSession::create([
                'barangay_id'  => $barangayId,
                'collector_id' => $user->id,
                'schedule_id'  => $schedule->id,
                'truck_id'     => optional(Truck::where('barangay_id', $barangayId)->where('is_active', true)->first())->id,
                'session_date' => now()->toDateString(),
                'status'       => 'pending',
            ]);

            // Get garbage points for this barangay
            $points = GarbagePoint::where('barangay_id', $barangayId)
                ->where('is_active', true)
                ->get();

            foreach ($points as $index => $point) {
                SessionPoint::create([
                    'session_id' => $session->id,
                    'garbage_point_id' => $point->id,
                    'route_order' => $index + 1,
                    'status' => 'pending',
                ]);
            }
        }

        // Load points in route order
        $sessionPoints = SessionPoint::where('session_id', $session->id)
            ->with('garbagePoint')
            ->orderBy('route_order')
            ->get();

        return view('dashboard.partials.collector.route-map', compact('session', 'sessionPoints'));
    }

    /**
     * Return a collection schedule for the given barangay.
     *
     * If no active schedule exists, return the first available schedule.
     */
    private function getCollectionScheduleForBarangay($barangayId)
    {
        $schedule = CollectionSchedule::where('barangay_id', $barangayId)
            ->where('is_active', true)
            ->orderBy('collection_time')
            ->first();

        if (!$schedule) {
            $schedule = CollectionSchedule::where('barangay_id', $barangayId)
                ->orderBy('collection_time')
                ->first();
        }
        $this->ensureTrucksExist($barangayId);

        if (!$schedule) {
            $schedule = CollectionSchedule::create([
                'barangay_id' => $barangayId,
                'day_of_week' => strtolower(now()->format('l')),
                'collection_time' => '08:00',
                'frequency' => 'daily',
                'is_active' => true,
            ]);
        }

        return $schedule;
    }

    /**
     * Start the route from the route-map page.
     */
    public function startRoute(Request $request, $id)
    {
        $session = CollectionSession::findOrFail($id);

        if ($session->status === 'pending') {
            $session->update([
                'status' => 'ongoing',
                'started_at' => now(),
            ]);

            // ── Notify all residents in this barangay that collection has started ──
            $this->notifyResidentsSessionStarted($session->barangay_id);
        }

        return redirect()->back()->with('success', 'Route navigation active! Drive safely.');
    }

    /**
     * Complete the route/session.
     */
    public function completeRoute(Request $request, $id)
    {
        $session = CollectionSession::findOrFail($id);

        if ($session->status === 'ongoing') {
            $session->update([
                'status' => 'completed',
                'ended_at' => now(),
            ]);
        }

        return redirect()->route('dashboard.active-session')->with('success', 'Collection shift ended and logged successfully.');
    }

    /**
     * Update the status of a specific session point (collected, skipped).
     */
    public function updatePointStatus(Request $request, $sessionId, $pointId)
    {
        $request->validate([
            'status' => 'required|in:collected,skipped,pending',
        ]);

        $sessionPoint = SessionPoint::where('session_id', $sessionId)
            ->where('id', $pointId)
            ->firstOrFail();

        $updateData = ['status' => $request->status];
        if ($request->status === 'collected') {
            $updateData['collected_at'] = now();
        } else {
            $updateData['collected_at'] = null;
        }

        $sessionPoint->update($updateData);

        // ── Auto-notify residents when truck arrives at their collection point ──
        if ($request->status === 'collected') {
            $this->notifyResidentsAtPoint($sessionPoint->garbage_point_id);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Point status updated successfully.',
                'point'   => $sessionPoint,
            ]);
        }

        return redirect()->back()->with('success', 'Checkpoint status updated.');
    }

    /**
     * Show points logs (Collection History).
     */
    public function pointLogs()
    {
        $user = Auth::user();

        // Fetch session points completed (collected or skipped) by the current collector
        $logs = SessionPoint::whereHas('session', function ($query) use ($user) {
            $query->where('collector_id', $user->id);
        })
            ->whereIn('status', ['collected', 'skipped'])
            ->with(['garbagePoint', 'session'])
            ->orderByDesc('collected_at')
            ->get();

        return view('dashboard.partials.collector.point-logs', compact('logs'));
    }

    /**
     * Show Log Truck Full page.
     */
    public function truckFull()
    {
        $user = Auth::user();
        $barangayId = $user->barangay_id ?? Barangay::first()->id;

        // Auto-seed collection points if none exist so the page has valid foreign keys
        $this->ensureCollectionPointsExist($barangayId);

        // Find ongoing session
        $activeSession = CollectionSession::where('collector_id', $user->id)
            ->where('status', 'ongoing')
            ->first();

        // Get collection points to display where they are at
        $collectionPoints = CollectionPoint::with('barangay')
            ->where('barangay_id', $barangayId)
            ->where('is_active', true)
            ->get();

        return view('dashboard.partials.collector.truck-full', compact('activeSession', 'collectionPoints'));
    }

    /**
     * Post event: Log Truck Full.
     */
    public function logTruckFull(Request $request)
    {
        $request->validate([
            'session_id' => 'required|uuid|exists:collection_sessions,id',
            'collection_point_id' => 'required|uuid|exists:collection_points,id',
            'estimated_load' => 'required|string',
        ]);

        TruckFullEvent::create([
            'id' => (string) Str::uuid(),
            'session_id' => $request->session_id,
            'collection_point_id' => $request->collection_point_id,
            'logged_at' => now(),
            'dumping_site' => 'Calarian Landfill Terminal',
            'resume_status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Truck capacity status broadcasted successfully! Re-routing navigation.');
    }

    /**
     * Email all residents in a barangay when a collector starts their session.
     * Sends an advance notice so residents can prepare their waste.
     */
    private function notifyResidentsSessionStarted(string $barangayId): void
    {
        $barangay     = Barangay::find($barangayId);
        $barangayName = $barangay?->name ?? 'your Barangay';

        // Get all active garbage points in this barangay
        $points = GarbagePoint::where('barangay_id', $barangayId)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        // Fetch all active residents (role = 'user') registered in this barangay
        $residents = User::where('barangay_id', $barangayId)
            ->where('role', 'user')
            ->where('is_active', true)
            ->whereNotNull('email')
            ->with(['pointAssignments' => function ($q) {
                $q->where('is_active', true);
            }])
            ->get();

        foreach ($residents as $resident) {
            // Find which specific point this resident is assigned to (if any)
            $assignedPointId = $resident->pointAssignments->first()?->garbage_point_id;
            $point           = $points->get($assignedPointId);
            $pointName       = $point?->name ?? 'your assigned collection point';

            try {
                Mail::to($resident->email, $resident->full_name)
                    ->send(new TruckApproachingMail(
                        residentName: $resident->full_name,
                        pointName:    $pointName,
                        etaMinutes:   30,  // general ETA when session just started
                        barangayName: $barangayName,
                    ));
                Log::info("[TruckNotify] Session-start email sent to {$resident->email}");
            } catch (\Exception $e) {
                Log::error("[TruckNotify] Failed session-start email to {$resident->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Email all residents assigned to a garbage point when the truck arrives.
     */
    private function notifyResidentsAtPoint(string $garbagePointId): void
    {
        $point    = GarbagePoint::with('barangay')->find($garbagePointId);
        if (!$point) return;

        $barangayName = $point->barangay?->name ?? 'your Barangay';

        // Get all active residents assigned to this specific point who have email
        $residents = User::whereHas('pointAssignments', function ($q) use ($garbagePointId) {
            $q->where('garbage_point_id', $garbagePointId)
              ->where('is_active', true);
        })->whereNotNull('email')->get();

        foreach ($residents as $resident) {
            try {
                Mail::to($resident->email, $resident->full_name)
                    ->send(new WasteCollectedMail(
                        residentName: $resident->full_name,
                        pointName:    $point->name,
                        barangayName: $barangayName,
                    ));
                Log::info("[TruckNotify] Collection confirmation email sent to {$resident->email} — {$point->name}");
            } catch (\Exception $e) {
                Log::error("[TruckNotify] Failed to email {$resident->email}: " . $e->getMessage());
            }
        }
    }

    /**
     * Private helper to seed default garbage points for Calarian / Baliwasan if none exist.
     */
    private function ensureGarbagePointsExist($barangayId)

    {
        if (GarbagePoint::where('barangay_id', $barangayId)->count() === 0) {
            $barangay = Barangay::find($barangayId) ?? Barangay::first();
            $bName = $barangay ? $barangay->name : 'Calarian';

            // Coordinates centered around Calarian, Zamboanga City (Lat: 6.920, Lng: 122.030)
            // or Baliwasan (Lat: 6.915, Lng: 122.050)
            $baseLat = 6.9200;
            $baseLng = 122.0300;

            if (strtolower($bName) === 'baliwasan') {
                $baseLat = 6.9150;
                $baseLng = 122.0500;
            }

            $points = [
                [
                    'name' => 'Main Barangay Hall Drop-off',
                    'latitude' => $baseLat,
                    'longitude' => $baseLng,
                    'address' => 'Barangay Plaza main gate',
                ],
                [
                    'name' => 'Elementary School Collection point',
                    'latitude' => $baseLat + 0.0035,
                    'longitude' => $baseLng + 0.0042,
                    'address' => 'Next to school main gate',
                ],
                [
                    'name' => 'Public Market Center Point',
                    'latitude' => $baseLat - 0.0028,
                    'longitude' => $baseLng + 0.0068,
                    'address' => 'West corner market entrance',
                ],
                [
                    'name' => 'Coastal Road Junction Hub',
                    'latitude' => $baseLat + 0.0015,
                    'longitude' => $baseLng - 0.0055,
                    'address' => 'Highway crossroads near bridge',
                ]
            ];

            foreach ($points as $point) {
                GarbagePoint::create([
                    'id' => (string) Str::uuid(),
                    'name' => $point['name'] . " ($bName)",
                    'latitude' => $point['latitude'],
                    'longitude' => $point['longitude'],
                    'address' => $point['address'],
                    'barangay_id' => $barangayId,
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * Private helper to seed default trucks if none exist.
     */
    private function ensureTrucksExist($barangayId)
    {
        if (Truck::where('barangay_id', $barangayId)->count() === 0) {
            Truck::create([
                'id' => (string) Str::uuid(),
                'plate_number' => 'IX-7701',
                'capacity_tons' => 5.0,
                'barangay_id' => $barangayId,
                'is_active' => true,
            ]);
            Truck::create([
                'id' => (string) Str::uuid(),
                'plate_number' => 'IX-4482',
                'capacity_tons' => 8.0,
                'barangay_id' => $barangayId,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Private helper to seed default collection points if none exist.
     */
    private function ensureCollectionPointsExist($barangayId)
    {
        if (CollectionPoint::where('barangay_id', $barangayId)->count() === 0) {
            $barangay = Barangay::find($barangayId) ?? Barangay::first();
            $bName = $barangay ? $barangay->name : 'Calarian';

            CollectionPoint::create([
                'id' => (string) Str::uuid(),
                'barangay_id' => $barangayId,
                'name' => "Camino Nuevo Collection Hub ($bName)",
                'latitude' => 6.9200,
                'longitude' => 122.0300,
                'address' => 'Main crossroads dropoff point',
                'is_active' => true,
            ]);
        }
    }
}
