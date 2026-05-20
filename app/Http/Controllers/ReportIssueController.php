<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ViolationReport;
use App\Models\MissedCollectionReport;
use Exception;

class ReportIssueController extends Controller
{
    /**
     * Submits a violation report for illegal garbage dumping.
     *
     * Validates the request, uploads the photo, and persists the report
     * to the violation_reports table using the user's description directly.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportViolation(Request $request)
    {
        $request->validate([
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'user_id'     => 'required|uuid',
            'barangay_id' => 'required|uuid',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'description' => 'nullable|string|max:2000',
        ]);

        try {
            $storedPath = $request->file('image')->store('reports/violations', 'public');
            $photoUrl   = Storage::url($storedPath);

            $report = ViolationReport::create([
                'reported_by' => $request->user_id,
                'barangay_id' => $request->barangay_id,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'photo_url'   => $photoUrl,
                'description' => $request->description ?? '',
                'status'      => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'          => $report->id,
                    'photo_url'   => $photoUrl,
                    'description' => $report->description,
                    'status'      => 'pending',
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submits a missed collection report for an uncollected garbage pickup.
     *
     * Validates the request, uploads the photo, and persists the report
     * to the missed_collection_reports table using the user's notes directly.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportMissedCollection(Request $request)
    {
        $request->validate([
            'image'               => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'user_id'             => 'required|uuid',
            'session_id'          => 'required|uuid',
            'collection_point_id' => 'required|uuid',
            'notes'               => 'nullable|string|max:2000',
        ]);

        try {
            $storedPath = $request->file('image')->store('reports/missed', 'public');
            $photoUrl   = Storage::url($storedPath);

            $report = MissedCollectionReport::create([
                'session_id'          => $request->session_id,
                'collection_point_id' => $request->collection_point_id,
                'reported_by'         => $request->user_id,
                'photo_url'           => $photoUrl,
                'notes'               => $request->notes ?? '',
                'status'              => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'        => $report->id,
                    'photo_url' => $photoUrl,
                    'notes'     => $report->notes,
                    'status'    => 'pending',
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
