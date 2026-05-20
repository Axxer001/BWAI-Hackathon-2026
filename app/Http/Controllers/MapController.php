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

    public function getGarbagePoints() {
        $points = DB::table('garbage_points')->where('is_active', 1)->get();
        return response()->json($points);
    }

    public function addGarbagePoint(Request $request) {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required|string|max:255',
            'barangay_id' => 'required|uuid'
        ]);

        $id = Str::uuid()->toString();

        // Insert into the garbage_points table
        DB::table('garbage_points')->insert([
            'id' => $id,
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'barangay_id' => $request->barangay_id,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['success' => true, 'id' => $id, 'message' => 'Point saved successfully!']);
    }
}