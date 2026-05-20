<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioSmsService
{
    protected ?Client $client = null;
    protected ?string $from = null;

    public function __construct()
    {
        $sid   = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->from  = config('services.twilio.from');

        if ($sid && $token) {
            try {
                $options = [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ];
                $httpClient = new \Twilio\Http\CurlClient($options);
                $this->client = new Client($sid, $token, null, null, $httpClient);
            } catch (\Exception $e) {
                Log::error("[TwilioSMS] Failed to initialize client: " . $e->getMessage());
            }
        }
    }

    /**
     * Send a raw SMS message to a phone number.
     *
     * @param  string  $to    E.164 format, e.g. "+639171234567"
     * @param  string  $body  The SMS text content
     * @return bool
     */
    public function send(string $to, string $body): bool
    {
        if (!$this->client || !$this->from) {
            Log::error("[TwilioSMS] Client not configured. Check your .env file.");
            return false;
        }

        // Ensure phone number starts with +
        if (!str_starts_with($to, '+')) {
            $to = '+' . ltrim($to, '0');
            if (!str_starts_with($to, '+63')) {
                $to = '+63' . ltrim($to, '+');
            }
        }

        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $body,
            ]);

            Log::info("[TwilioSMS] Sent to {$to}: {$body}");
            return true;
        } catch (\Exception $e) {
            Log::error("[TwilioSMS] Failed to send to {$to}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Notify a resident that the garbage truck is approaching their collection point.
     *
     * @param  string  $phone          Resident phone in E.164 format
     * @param  string  $pointName      Name of the garbage collection point
     * @param  int     $etaMinutes     Estimated minutes until truck arrives
     * @param  string  $barangayName   Name of the barangay
     * @return bool
     */
    public function notifyTruckApproaching(
        string $phone,
        string $pointName,
        int $etaMinutes,
        string $barangayName
    ): bool {
        $body = "🚛 LimpioZambo Alert!\n"
            . "A garbage truck is {$etaMinutes} min away from:\n"
            . "📍 {$pointName} ({$barangayName})\n\n"
            . "Please bring your waste to the collection point. Thank you! ♻️";

        return $this->send($phone, $body);
    }
}
