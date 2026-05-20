<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index()
    {
        return view('map');
    }

    public function getPoints()
    {
        $points = DB::table('garbage_points')->where('is_active', 1)->get();
        return response()->json($points);
    }

    public function storePoint(Request $request)
    {
        // 1. Validate barangay_id as a normal integer
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required|string|max:255',
            'barangay_id' => 'required|integer' 
        ]);

        // 2. Use insertGetId() so MySQL auto-generates the normal ID
        $newId = DB::table('garbage_points')->insertGetId([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'barangay_id' => $request->barangay_id, 
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true, 
            'id' => $newId, 
            'message' => 'Garbage point successfully added!'
        ]);
    }
}