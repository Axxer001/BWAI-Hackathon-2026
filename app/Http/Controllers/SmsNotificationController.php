<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SemaphoreSmsService;
use App\Models\GarbagePoint;
use App\Models\UserPointAssignment;
use App\Models\User;
use App\Models\Barangay;
use Illuminate\Support\Facades\Log;

class SmsNotificationController extends Controller
{
    protected SemaphoreSmsService $sms;

    public function __construct(SemaphoreSmsService $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Notify all residents assigned to a garbage point that the truck is approaching.
     *
     * Called by the collector's route-map UI when they tap "I'm Approaching" on a point.
     *
     * POST /api/notify/truck-approaching
     * Body: { garbage_point_id, eta_minutes }
     */
    public function notifyApproaching(Request $request)
    {
        $request->validate([
            'garbage_point_id' => 'required|uuid|exists:garbage_points,id',
            'eta_minutes'      => 'required|integer|min:1|max:60',
        ]);

        $point = GarbagePoint::findOrFail($request->garbage_point_id);
        $barangay = Barangay::find($point->barangay_id);
        $barangayName = $barangay?->name ?? 'your Barangay';

        // Get all users assigned to this garbage point who have a phone number
        $assignedUsers = User::whereHas('pointAssignments', function ($q) use ($point) {
            $q->where('garbage_point_id', $point->id)
              ->where('is_active', true);
        })->whereNotNull('phone')->get();

        if ($assignedUsers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No residents with phone numbers are assigned to this point.',
                'notified' => 0,
            ]);
        }

        $notified = 0;
        $failed   = 0;

        foreach ($assignedUsers as $user) {
            $sent = $this->sms->notifyTruckApproaching(
                phone: $user->phone,
                pointName: $point->name,
                etaMinutes: (int) $request->eta_minutes,
                barangayName: $barangayName
            );

            $sent ? $notified++ : $failed++;
        }

        return response()->json([
            'success'  => true,
            'message'  => "Notifications sent: {$notified} succeeded, {$failed} failed.",
            'notified' => $notified,
            'failed'   => $failed,
        ]);
    }

    /**
     * Send a test SMS to a single number (admin use only).
     *
     * POST /api/notify/test
     * Body: { phone }
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $sent = $this->sms->send(
            $request->phone,
            "✅ LimpioZambo: SMS integration is live! Residents will be notified when garbage trucks are approaching."
        );

        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'Test SMS sent successfully via Semaphore!' : 'Failed to send SMS. Account may be pending approval.',
        ]);
    }
}
