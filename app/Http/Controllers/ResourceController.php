<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResourceController extends Controller
{
    // GET /api/resources
    public function index(): JsonResponse
    {
        $resources = Resource::all();
        return response()->json($resources, 200);
    }

    // POST /api/resources 
    public function store(Request $request): JsonResponse
    {

        // Validate req data 
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        // Create resource rec'
        $resource = Resource::create($validated);

        // Return response with 201 created status
        return response() -> json($resource, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource)
    {
        //
    }
}
