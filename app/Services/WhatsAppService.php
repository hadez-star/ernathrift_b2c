<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message using a configured API gateway.
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function sendMessage(string $phone, string $message): bool
    {
        // 1. Format the phone number (convert 08... to 628...)
        $formattedPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($formattedPhone, '0')) {
            $formattedPhone = '62' . substr($formattedPhone, 1);
        }
        
        $token = env('WA_API_TOKEN');
        $url = env('WA_API_URL', 'https://api.fonnte.com/send');

        // 2. Fallback to Laravel Log if API Token is not configured (very useful for local development)
        if (empty($token) || $token === 'null' || $token === 'YOUR_TOKEN_HERE') {
            Log::info("=== [SIMULASI WHATSAPP] ===");
            Log::info("Penerima: " . $formattedPhone);
            Log::info("Pesan:\n" . $message);
            Log::info("===========================");
            return true;
        }

        try {
            // 3. Send using standard Fonnte API
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($url, [
                'target' => $formattedPhone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp sent successfully to " . $formattedPhone);
                return true;
            }

            Log::error("Failed to send WhatsApp. Status: " . $response->status() . " Response: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("Error sending WhatsApp to " . $formattedPhone . ": " . $e->getMessage());
            return false;
        }
    }
}
