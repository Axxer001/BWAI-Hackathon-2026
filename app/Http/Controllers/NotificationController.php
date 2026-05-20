<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\TruckApproachingMail;
use App\Models\GarbagePoint;
use App\Models\User;
use App\Models\Barangay;

class NotificationController extends Controller
{
    /**
     * Notify all residents assigned to a garbage point that the truck is approaching.
     *
     * Called by the collector's route-map UI when they tap "Notify" on a point.
     *
     * POST /api/notify/truck-approaching
     * Body: { garbage_point_id, eta_minutes }
     */
    public function notifyApproaching(Request $request)
    {
        $request->validate([
            'garbage_point_id' => 'required|uuid|exists:garbage_points,id',
            'eta_minutes'      => 'required|integer|min:1|max:120',
        ]);

        $point        = GarbagePoint::findOrFail($request->garbage_point_id);
        $barangay     = Barangay::find($point->barangay_id);
        $barangayName = $barangay?->name ?? 'your Barangay';

        // Get all active users assigned to this point who have an email address
        $assignedUsers = User::whereHas('pointAssignments', function ($q) use ($point) {
            $q->where('garbage_point_id', $point->id)
              ->where('is_active', true);
        })->whereNotNull('email')->get();

        if ($assignedUsers->isEmpty()) {
            return response()->json([
                'success'  => false,
                'message'  => 'No residents with email addresses are assigned to this point.',
                'notified' => 0,
            ]);
        }

        $notified = 0;
        $failed   = 0;

        foreach ($assignedUsers as $user) {
            try {
                Mail::to($user->email, $user->full_name)
                    ->send(new TruckApproachingMail(
                        residentName: $user->full_name,
                        pointName:    $point->name,
                        etaMinutes:   (int) $request->eta_minutes,
                        barangayName: $barangayName,
                    ));

                Log::info("[TruckNotify] Email sent to {$user->email} for point: {$point->name}");
                $notified++;

            } catch (\Exception $e) {
                Log::error("[TruckNotify] Failed to email {$user->email}: " . $e->getMessage());
                $failed++;
            }
        }

        return response()->json([
            'success'  => true,
            'message'  => "Notifications sent: {$notified} emailed, {$failed} failed.",
            'notified' => $notified,
            'failed'   => $failed,
        ]);
    }

    /**
     * Send a test notification email (for development/admin use).
     *
     * GET /api/notify/test?email=test@example.com&eta=10
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $etaMinutes = (int) $request->get('eta', 10);

        try {
            Mail::to($request->email)
                ->send(new TruckApproachingMail(
                    residentName: 'Resident',
                    pointName:    'Test Collection Point',
                    etaMinutes:   $etaMinutes,
                    barangayName: 'LimpioZambo Barangay',
                ));

            return response()->json([
                'success' => true,
                'message' => "Test notification email sent to {$request->email}",
            ]);

        } catch (\Exception $e) {
            Log::error("[TruckNotify] Test email failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send: ' . $e->getMessage(),
            ], 500);
        }
    }
}
