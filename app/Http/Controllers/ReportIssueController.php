<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\ViolationReport;
use App\Models\MissedCollectionReport;
use Exception;

class ReportIssueController extends Controller
{
    public function createViolationForm()
    {
        $reports = ViolationReport::where('reported_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        $barangays = \App\Models\Barangay::orderBy('name', 'asc')->get();

        return view('dashboard.partials.user.my-report-illegal-dumping', compact('reports', 'barangays'));
    }

    public function createMissedCollectionForm()
    {
        $user = Auth::user();

        $reports = MissedCollectionReport::with('collectionPoint')
            ->where('reported_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $collectionPoints = \App\Models\CollectionPoint::where('barangay_id', $user->barangay_id)
            ->orderBy('name', 'asc')
            ->get();

        $activeSession = \App\Models\CollectionSession::whereHas('schedule', function ($query) use ($user) {
            $query->where('barangay_id', $user->barangay_id);
        })
            ->whereIn('status', ['pending', 'active'])
            ->latest('session_date')
            ->first();

        if (!$activeSession) {
            $activeSession = \App\Models\CollectionSession::whereHas('schedule', function ($query) use ($user) {
                $query->where('barangay_id', $user->barangay_id);
            })
                ->latest('session_date')
                ->first();
        }

        return view('dashboard.partials.user.my-report-missed-pickup', compact('reports', 'collectionPoints', 'activeSession'));
    }

    public function reportViolation(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'barangay_id' => 'required|exists:barangays,id',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'required|string|max:2000',
        ]);

        try {
            $imageFile = $request->file('photo');
            $imagePath = $imageFile->getPathname();
            $mimeType = $imageFile->getMimeType();

            $storedPath = $imageFile->store('reports/violations', 'public');
            $photoUrl = Storage::url($storedPath);

            $base64Image = base64_encode(file_get_contents($imagePath));

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

            $userDescription = $request->description ?? '';
            $fullDescription = trim($userDescription . "\n\n[AI Verification]\n" . $aiAnalysis);

            $report = ViolationReport::create([
                'reported_by' => Auth::id(),
                'barangay_id' => $request->barangay_id,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'photo_url' => $photoUrl,
                'description' => $fullDescription,
                'status' => 'pending', // MUST BE LOWERCASE!
            ]);

            return redirect()->back()->with('success', 'Your violation report has been submitted successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to submit report: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function reportMissedCollection(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'session_id' => 'required|uuid',
            'collection_point_id' => 'required|uuid',
            'notes' => 'nullable|string|max:2000',
        ]);

        try {
            $imageFile = $request->file('photo');
            $imagePath = $imageFile->getPathname();
            $mimeType = $imageFile->getMimeType();

            $storedPath = $imageFile->store('reports/missed', 'public');
            $photoUrl = Storage::url($storedPath);

            $base64Image = base64_encode(file_get_contents($imagePath));

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

            $userNotes = $request->notes ?? '';
            $fullNotes = trim($userNotes . "\n\n[AI Verification]\n" . $aiAnalysis);

            $report = MissedCollectionReport::create([
                'session_id' => $request->session_id,
                'collection_point_id' => $request->collection_point_id,
                'reported_by' => Auth::id(),
                'photo_url' => $photoUrl,
                'notes' => $fullNotes,
                'status' => 'pending', // MUST BE LOWERCASE!
            ]);

            return redirect()->back()->with('success', 'Your missed collection report has been submitted.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to submit report: ' . $e->getMessage()])
                ->withInput();
        }
    }

    private function analyzeWithGemini(string $base64Image, string $mimeType, string $prompt): string
    {
        // ... (Keep your Gemini logic exactly the same)
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
                                    'data' => $base64Image
                                ]
                            ]
                        ]
                    ]
                ],
            ];

            $response = Http::withoutVerifying()->post($url, $payload);

            if ($response->failed()) {
                return 'AI verification unavailable at this time.';
            }

            $result = $response->json();
            $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

            return trim($aiText) ?: 'AI verification returned no analysis.';

        } catch (Exception $e) {
            return 'AI verification failed: ' . $e->getMessage();
        }
    }
}