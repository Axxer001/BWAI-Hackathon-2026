<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\WasteAssessment;
use Exception;

class WasteAssessmentController extends Controller
{
    /**
     * Assesses an uploaded waste image using the Gemini API.
     * 
     * Validates the image, sends it to Google's Gemini 1.5 Flash model for analysis, 
     * extracts the waste name, category, and preparation advice, logs the name 
     * for analytics, and returns the structured data to the client.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assess(Request $request)
    {
        /**
         * Validate the incoming request payload.
         * Ensures that 'image' is present, is a valid image file,
         * matches supported mime types, and is under 5MB in size.
         */
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'barangay_id' => 'required|integer',
        ]);

        try {
            $imagePath = $request->file('image')->getPathname();
            $mimeType = $request->file('image')->getMimeType();
            
            /**
             * Encode the image to Base64 format as required by the 
             * Gemini API for inline data transfer.
             */
            $base64Image = base64_encode(file_get_contents($imagePath));

            $apiKey = config('services.gemini.key');
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

            /**
             * Construct the payload for the Gemini API.
             * Defines the prompt instructions and attaches the base64 encoded image.
             * Requests a strictly formatted JSON response.
             */
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "Analyze this image of waste. Return a JSON object with strictly these keys: 'name' (a short descriptive name of the waste), 'category' (strictly 'Biodegradable', 'Non-Biodegradable', 'Recyclable' or 'Special Waste'), and 'preparation_advice' (concise advice on how to prepare/segregate this for garbage collectors). Do not include markdown formatting like ```json in the output."],
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
             */
            $response = Http::post($url, $payload);

            if ($response->failed()) {
                throw new Exception('Failed to communicate with Gemini API: ' . $response->body());
            }

            /**
             * Parse the JSON response received from the Gemini API.
             * Cleans up any potential markdown formatting block quotes that 
             * the LLM might have included in its response string.
             */
            $result = $response->json();
            $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            
            $aiText = str_replace(['```json', '```'], '', $aiText);
            $wasteData = json_decode(trim($aiText), true);

            if (!$wasteData || !isset($wasteData['name'])) {
                throw new Exception('Failed to parse AI response correctly.');
            }

            /**
             * Persist the name of the identified waste to the database.
             * This record serves exclusively for system analytics and tracking.
             */
            WasteAssessment::create([
                'name' => $wasteData['name'],
                'barangay_id' => $request->barangay_id
            ]);

            /**
             * Return the successful assessment payload to the client.
             */
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $wasteData['name'],
                    'category' => $wasteData['category'] ?? 'Unknown',
                    'preparation_advice' => $wasteData['preparation_advice'] ?? 'No advice provided.'
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
