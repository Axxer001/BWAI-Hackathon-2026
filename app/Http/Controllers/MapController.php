<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MapController extends Controller
{
    public function index() {
        return view('map');
    }

    /**
     * Get active/inactive points for the user's barangay.
     */
    public function getGarbagePoints(Request $request) {
        $barangayId = auth()->user()->barangay_id;
        
        // If the user has a barangay associated, only get points for that barangay
        $query = DB::table('collection_points');
        if ($barangayId) {
            $query->where('barangay_id', $barangayId);
        }
        
        $points = $query->get();
        return response()->json($points);
    }

    /**
     * Add a collection point & garbage point in sync.
     */
    public function addGarbagePoint(Request $request) {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required|string|max:255',
            'barangay_id' => 'required|uuid'
        ]);

        $id = Str::uuid()->toString();
        $address = $request->address ?? 'Zone ' . rand(1, 4) . ', Barangay ' . auth()->user()->barangay->name;

        // Start Transaction to ensure consistency
        DB::transaction(function () use ($id, $request, $address) {
            // Insert into collection_points
            DB::table('collection_points')->insert([
                'id' => $id,
                'name' => $request->name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $address,
                'barangay_id' => $request->barangay_id,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Insert into garbage_points
            DB::table('garbage_points')->insert([
                'id' => $id,
                'name' => $request->name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $address,
                'barangay_id' => $request->barangay_id,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });

        return response()->json(['success' => true, 'id' => $id, 'message' => 'Point saved successfully in both tables!']);
    }

    /**
     * Toggle the active status of a point.
     */
    public function togglePoint($id) {
        $point = DB::table('collection_points')->where('id', $id)->first();
        if (!$point) {
            return response()->json(['success' => false, 'message' => 'Point not found'], 404);
        }

        $newStatus = $point->is_active ? 0 : 1;

        DB::transaction(function () use ($id, $newStatus) {
            DB::table('collection_points')->where('id', $id)->update([
                'is_active' => $newStatus,
                'updated_at' => now()
            ]);

            DB::table('garbage_points')->where('id', $id)->update([
                'is_active' => $newStatus,
                'updated_at' => now()
            ]);
        });

        return response()->json([
            'success' => true, 
            'is_active' => $newStatus, 
            'message' => 'Point status updated successfully!'
        ]);
    }

    /**
     * Move a point (update latitude & longitude).
     */
    public function movePoint(Request $request, $id) {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        DB::transaction(function () use ($id, $request) {
            DB::table('collection_points')->where('id', $id)->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'updated_at' => now()
            ]);

            DB::table('garbage_points')->where('id', $id)->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'updated_at' => now()
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Point moved successfully!']);
    }
}