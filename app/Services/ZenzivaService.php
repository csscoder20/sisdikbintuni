<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZenzivaService
{
    public static function sendWhatsApp($to, $message)
    {
        $userkey = config('services.zenziva.userkey');
        $passkey = config('services.zenziva.passkey');
        
        // Normalize phone number to start with 62 for Zenziva Official
        $to = preg_replace('/[^0-9]/', '', $to);
        if (str_starts_with($to, '0')) {
            $to = '62' . substr($to, 1);
        }

        $type = config('services.zenziva.type', 'official');
        $url = config('services.zenziva.url');

        if (!$url) {
            $url = $type === 'official' 
                ? 'https://console.zenziva.net/waofficial/api/sendWAOfficial/' 
                : 'https://console.zenziva.net/wareguler/api/sendWA/';
        }
        
        try {
            $params = [
                'userkey' => $userkey,
                'passkey' => $passkey,
                'to' => $to,
            ];

            if ($type === 'official' || str_contains($url, 'waofficial')) {
                $params['brand'] = config('services.zenziva.brand', 'SisdikBintuni');
                $params['otp'] = self::shortenMessage($message); 
            } else {
                $params['message'] = $message;
            }

            $response = Http::asForm()->post($url, $params);

            Log::info("Zenziva WA ({$type}) Response: " . $response->body());

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Zenziva WA API Exception: ' . $e->getMessage());
            return null;
        }
    }

    private static function shortenMessage($message)
    {
        // Short labels for Official OTP template compatibility
        if (str_contains($message, 'berhasil') && str_contains($message, 'diverifikasi')) {
            return 'Daftar Berhasil';
        }
        if (str_contains($message, 'sudah dapat digunakan')) {
            return 'Akun Aktif. Login!';
        }
        if (str_contains($message, 'menolak')) {
            return 'Akun Ditolak';
        }
        if (str_contains($message, 'pendaftaran operator baru')) {
            return 'Cek Admin Dinas';
        }

        return substr($message, 0, 15); // Fallback to first 15 chars
    }
}
