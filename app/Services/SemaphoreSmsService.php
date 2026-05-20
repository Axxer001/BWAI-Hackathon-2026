<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SemaphoreSmsService
{
    protected string $apiKey;
    protected string $senderName;
    protected string $endpoint = 'https://api.semaphore.co/api/v4/messages';

    public function __construct()
    {
        $this->apiKey     = config('services.semaphore.api_key');
        $this->senderName = config('services.semaphore.sender_name', 'SEMAPHORE');
    }

    /**
     * Send an SMS via Semaphore.
     *
     * @param  string  $to    PH mobile number (09XXXXXXXXX or +639XXXXXXXXX)
     * @param  string  $body  SMS text content
     * @return bool
     */
    public function send(string $to, string $body): bool
    {
        // Normalize: ensure number starts with 0 (Semaphore expects 09XXXXXXXXX format)
        $to = preg_replace('/^\+?63/', '0', $to);

        if (!$this->apiKey) {
            Log::error('[Semaphore] API key not configured. Check SEMAPHORE_API_KEY in .env.');
            return false;
        }

        try {
            $response = Http::withoutVerifying()
                ->asForm()
                ->post($this->endpoint, [
                    'apikey'     => $this->apiKey,
                    'number'     => $to,
                    'message'    => $body,
                    'sendername' => $this->senderName,
                ]);

            $status = $response->status();
            $responseBody = $response->body();

            Log::info("[Semaphore] HTTP {$status} → to={$to}, body={$responseBody}");

            if ($response->successful()) {
                Log::info("[Semaphore] SMS queued successfully to {$to}");
                return true;
            }

            Log::error("[Semaphore] Failed to send to {$to} (HTTP {$status}): {$responseBody}");
            return false;

        } catch (\Exception $e) {
            Log::error("[Semaphore] Exception sending to {$to}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Notify a resident that the garbage truck is approaching their collection point.
     */
    public function notifyTruckApproaching(
        string $phone,
        string $pointName,
        int $etaMinutes,
        string $barangayName
    ): bool {
        $body = "LimpioZambo Alert! "
            . "A garbage truck is {$etaMinutes} min away from "
            . "{$pointName} ({$barangayName}). "
            . "Please prepare your waste for collection. Salamat!";

        return $this->send($phone, $body);
    }
}
