<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\WasteScan;
use App\Models\AiGarbageLog;
use App\Models\CollectionPoint;
use App\Models\Barangay;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Exception;

class WasteAssessmentController extends Controller
{
    /**
     * Show the user's AI waste scanner dashboard view.
     */
    public function showScanner()
    {
        $user = Auth::user();
        $barangayId = $user->barangay_id ?? Barangay::first()->id;

        $collectionPoints = CollectionPoint::where('barangay_id', $barangayId)
            ->where('is_active', true)
            ->get();

        $scans = WasteScan::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $totalPoints = $scans->count() * 5;

        return view('dashboard.partials.user.my-ai-waste-scanner', compact('collectionPoints', 'scans', 'totalPoints'));
    }
    /**
     * Assesses an uploaded waste image using the Gemini API.
     * 
     * Validates the image, sends it to Google's Gemini 1.5 Flash model for analysis, 
     * extracts the waste name, category, and preparation advice, logs the data 
     * into the AiGarbageLog table, and returns the structured data to the client.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assess(Request $request)
    {
        /**
         * Validate the incoming request payload.
         */
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'user_id' => 'required|uuid', // Requires the user_id (UUID)
            'collection_point_id' => 'nullable|uuid',
        ]);

        try {
            $imageFile = $request->file('image');
            $imagePath = $imageFile->getPathname();
            $mimeType = $imageFile->getMimeType();
            
            /**
             * Save the image to the public disk so we have an image_url for the log.
             */
            $storedPath = $imageFile->store('garbage_logs', 'public');
            $imageUrl = Storage::url($storedPath);
            
            /**
             * Encode the image to Base64 format as required by the 
             * Gemini API for inline data transfer.
             */
            $base64Image = base64_encode(file_get_contents($imagePath));

            $apiKey = config('services.gemini.key');
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

            /**
             * Construct the payload for the Gemini API.
             */
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "Analyze this image of waste. Return a JSON object with strictly these keys: 'name' (a short descriptive name of the waste), 'category' (strictly 'Biodegradable', 'Non-Biodegradable', 'Recyclable' or 'Special Waste'), and 'preparation_advice' (step-by-step paragraph form) advice on how to prepare/segregate this for garbage collectors). Do not include markdown formatting like ```json in the output."],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $base64Image
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ]
            ];

            /**
             * Execute the external HTTP POST request to the Gemini API.
             * Note: withoutVerifying() is added to bypass local Windows SSL cURL errors (error 60).
             */
            $response = Http::withoutVerifying()->post($url, $payload);

            if ($response->failed()) {
                throw new Exception('Failed to communicate with Gemini API: ' . $response->body());
            }

            /**
             * Parse the JSON response received from the Gemini API.
             */
            $result = $response->json();
            $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            
            $aiText = str_replace(['```json', '```'], '', $aiText);
            $wasteData = json_decode(trim($aiText), true);

            if (!$wasteData || !isset($wasteData['name'])) {
                throw new Exception('Failed to parse AI response correctly.');
            }

            /**
             * Persist the AI assessment to the waste_scans table.
             */
            WasteScan::create([
                'user_id' => $request->user_id,
                'collection_point_id' => $request->collection_point_id,
                'image_url' => $imageUrl,
                'ai_advice' => $wasteData['preparation_advice'] ?? 'No advice provided.',
                'ai_classification' => $wasteData['name'] // Storing the specific waste name as ai_classification
            ]);

            /**
             * Also log it to the AiGarbageLog table for redundancy/logs as requested.
             * We dynamically resolve the collection_point_id to the garbage_points table to avoid foreign key violations.
             */
            $garbagePointId = null;
            if ($request->collection_point_id) {
                $gp = \App\Models\GarbagePoint::find($request->collection_point_id);
                if ($gp) {
                    $garbagePointId = $gp->id;
                } else {
                    $cp = CollectionPoint::find($request->collection_point_id);
                    if ($cp) {
                        $matchedGp = \App\Models\GarbagePoint::where('barangay_id', $cp->barangay_id)
                            ->where('name', $cp->name)
                            ->first();
                        if ($matchedGp) {
                            $garbagePointId = $matchedGp->id;
                        } else {
                            \Illuminate\Support\Facades\DB::table('garbage_points')->insert([
                                'id' => $cp->id,
                                'name' => $cp->name,
                                'latitude' => $cp->latitude,
                                'longitude' => $cp->longitude,
                                'address' => $cp->address,
                                'barangay_id' => $cp->barangay_id,
                                'is_active' => $cp->is_active ? 1 : 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $garbagePointId = $cp->id;
                        }
                    }
                }
            }

            AiGarbageLog::create([
                'user_id' => $request->user_id,
                'garbage_point_id' => $garbagePointId,
                'image_url' => $imageUrl,
                'ai_advice' => $wasteData['preparation_advice'] ?? 'No advice provided.',
                'garbage_type' => $wasteData['name'] 
            ]);

            /**
             * Return the successful assessment payload to the client.
             */
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $wasteData['name'],
                    'category' => $wasteData['category'] ?? 'Unknown',
                    'preparation_advice' => $wasteData['preparation_advice'] ?? 'No advice provided.',
                    'image_url' => $imageUrl
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
