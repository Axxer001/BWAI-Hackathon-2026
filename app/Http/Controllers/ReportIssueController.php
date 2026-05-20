<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\ViolationReport;
use App\Models\MissedCollectionReport;
use Exception;

class ReportIssueController extends Controller
{
    /**
     * Submits a violation report for illegal garbage dumping.
     *
     * Validates the request, uploads the photo, sends it to Gemini AI
     * for an initial verification analysis, and persists the report
     * to the violation_reports table. The AI analysis is appended to
     * the user's description so barangay admins can review both.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportViolation(Request $request)
    {
        /**
         * Validate the incoming request payload.
         */
        $request->validate([
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'user_id'     => 'required|uuid',
            'barangay_id' => 'required|uuid',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'description' => 'nullable|string|max:2000',
        ]);

        try {
            $imageFile = $request->file('image');
            $imagePath = $imageFile->getPathname();
            $mimeType  = $imageFile->getMimeType();

            /**
             * Save the photo to the public disk for permanent storage.
             */
            $storedPath = $imageFile->store('reports/violations', 'public');
            $photoUrl   = Storage::url($storedPath);

            /**
             * Encode the image to Base64 for the Gemini API.
             */
            $base64Image = base64_encode(file_get_contents($imagePath));

            /**
             * Send the image to Gemini AI for verification analysis.
             */
            $aiAnalysis = $this->analyzeWithGemini(
                $base64Image,
                $mimeType,
                "Analyze this photo submitted as an illegal garbage dumping report. "
                . "Provide a short analysis (2-3 sentences) describing: "
                . "1) Whether the image appears to show illegal dumping or littering. "
                . "2) The approximate type and volume of waste visible. "
                . "3) Any environmental concerns. "
                . "Keep the response concise and factual."
            );

            /**
             * Combine the user's description with the AI verification.
             */
            $userDescription = $request->description ?? '';
            $fullDescription = trim($userDescription . "\n\n[AI Verification]\n" . $aiAnalysis);

            /**
             * Persist the violation report to the database.
             */
            $report = ViolationReport::create([
                'reported_by' => $request->user_id,
                'barangay_id' => $request->barangay_id,
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'photo_url'   => $photoUrl,
                'description' => $fullDescription,
                'status'      => 'pending',
            ]);

            /**
             * Return the successful response to the client.
             */
            return response()->json([
                'success' => true,
                'data'    => [
                    'id'           => $report->id,
                    'photo_url'    => $photoUrl,
                    'description'  => $fullDescription,
                    'ai_analysis'  => $aiAnalysis,
                    'status'       => 'pending',
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
     * Validates the request, uploads the photo, sends it to Gemini AI
     * for an initial verification analysis, and persists the report
     * to the missed_collection_reports table. The AI analysis is appended
     * to the user's notes so barangay admins can review both.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportMissedCollection(Request $request)
    {
        /**
         * Validate the incoming request payload.
         */
        $request->validate([
            'image'               => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'user_id'             => 'required|uuid',
            'session_id'          => 'required|uuid',
            'collection_point_id' => 'required|uuid',
            'notes'               => 'nullable|string|max:2000',
        ]);

        try {
            $imageFile = $request->file('image');
            $imagePath = $imageFile->getPathname();
            $mimeType  = $imageFile->getMimeType();

            /**
             * Save the photo to the public disk for permanent storage.
             */
            $storedPath = $imageFile->store('reports/missed', 'public');
            $photoUrl   = Storage::url($storedPath);

            /**
             * Encode the image to Base64 for the Gemini API.
             */
            $base64Image = base64_encode(file_get_contents($imagePath));

            /**
             * Send the image to Gemini AI for verification analysis.
             */
            $aiAnalysis = $this->analyzeWithGemini(
                $base64Image,
                $mimeType,
                "Analyze this photo submitted as a missed garbage collection report. "
                . "Provide a short analysis (2-3 sentences) describing: "
                . "1) Whether the image appears to show uncollected garbage at a pickup point. "
                . "2) The approximate type and volume of waste visible. "
                . "3) How long the waste may have been sitting (if determinable). "
                . "Keep the response concise and factual."
            );

            /**
             * Combine the user's notes with the AI verification.
             */
            $userNotes = $request->notes ?? '';
            $fullNotes = trim($userNotes . "\n\n[AI Verification]\n" . $aiAnalysis);

            /**
             * Persist the missed collection report to the database.
             */
            $report = MissedCollectionReport::create([
                'session_id'          => $request->session_id,
                'collection_point_id' => $request->collection_point_id,
                'reported_by'         => $request->user_id,
                'photo_url'           => $photoUrl,
                'notes'               => $fullNotes,
                'status'              => 'pending',
            ]);

            /**
             * Return the successful response to the client.
             */
            return response()->json([
                'success' => true,
                'data'    => [
                    'id'          => $report->id,
                    'photo_url'   => $photoUrl,
                    'notes'       => $fullNotes,
                    'ai_analysis' => $aiAnalysis,
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
     * Sends an image to the Gemini AI for analysis with a custom prompt.
     *
     * This is a shared helper method used by both report types to avoid
     * duplicating the Gemini API call logic. If the API call fails, it
     * returns a graceful fallback string instead of throwing an exception,
     * so the report is still saved even if AI verification is unavailable.
     *
     * @param string $base64Image  The Base64 encoded image data.
     * @param string $mimeType     The MIME type of the image (e.g., image/jpeg).
     * @param string $prompt       The text prompt to send alongside the image.
     * @return string              The AI's analysis text, or a fallback message.
     */
    private function analyzeWithGemini(string $base64Image, string $mimeType, string $prompt): string
    {
        try {
            $apiKey = config('services.gemini.key');
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data'      => $base64Image
                                ]
                            ]
                        ]
                    ]
                ],
            ];

            /**
             * Execute the HTTP POST request to the Gemini API.
             * Note: withoutVerifying() bypasses local Windows SSL cURL errors (error 60).
             */
            $response = Http::withoutVerifying()->post($url, $payload);

            if ($response->failed()) {
                return 'AI verification unavailable at this time.';
            }

            $result = $response->json();
            $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

            return trim($aiText) ?: 'AI verification returned no analysis.';

        } catch (Exception $e) {
            /**
             * Gracefully handle AI failures so the report is still saved.
             */
            return 'AI verification failed: ' . $e->getMessage();
        }
    }
}
