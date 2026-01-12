<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PiwapiService
{
    protected $apiUrl;
    protected $apiKey;
    protected $senderId;

    public function __construct()
    {
        $this->apiUrl = config('piwapi.api_url');
        $this->apiKey = config('piwapi.api_key');
        $this->senderId = config('piwapi.sender_id');
    }

    /**
     * Kirim notifikasi pengingat jatuh tempo
     */
    public function sendReminderJatuhTempo($pelanggan, $tagihan, $daysBefore = 0)
    {
        $phoneNumber = $this->formatPhoneNumber($pelanggan->no_hp);
        $message = $this->createReminderMessage($pelanggan, $tagihan, $daysBefore);
        
        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Format nomor telepon ke format internasional (62xxx)
     */
    protected function formatPhoneNumber($phone)
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Jika diawali 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Jika belum ada 62, tambahkan
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Buat pesan notifikasi jatuh tempo
     */
    protected function createReminderMessage($pelanggan, $tagihan, $daysBefore)
    {
        $bulan = Carbon::parse($tagihan->bulan)->format('F Y');
        $jatuhTempo = Carbon::parse($tagihan->jatuh_tempo)->format('d F Y');
        $totalTagihan = number_format($tagihan->jumlah_tagihan + $tagihan->denda, 0, ',', '.');
        
        // Tentukan urgency berdasarkan sisa hari
        if ($daysBefore == 0) {
            $urgency = '🚨 *HARI INI JATUH TEMPO!*';
            $pesan = 'Tagihan WiFi Anda jatuh tempo *HARI INI*. Mohon segera lakukan pembayaran untuk menghindari denda keterlambatan.';
        } elseif ($daysBefore == 1) {
            $urgency = '⏰ *BESOK JATUH TEMPO!*';
            $pesan = 'Tagihan WiFi Anda akan jatuh tempo *BESOK*. Segera lakukan pembayaran sebelum terkena denda.';
        } else {
            $urgency = "📅 *{$daysBefore} HARI LAGI*";
            $pesan = "Tagihan WiFi Anda akan jatuh tempo dalam *{$daysBefore} hari*. Harap segera melakukan pembayaran.";
        }

        return <<<MESSAGE
🔔 *PENGINGAT PEMBAYARAN TAGIHAN*

Halo *{$pelanggan->nama}*,

{$pesan}

📋 *Detail Tagihan:*
• Periode: {$bulan}
• Paket: {$pelanggan->paket_wifi}
• Nominal: Rp {$totalTagihan}
• Jatuh Tempo: {$jatuhTempo}

{$urgency}

💳 *Cara Pembayaran:*
Login ke dashboard pelanggan Anda atau hubungi admin untuk informasi pembayaran.

Terima kasih atas perhatian Anda! 🙏

_Pesan otomatis dari sistem_
MESSAGE;
    }

    /**
     * Kirim pesan via Piwapi API
     */
    protected function sendMessage($phoneNumber, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl . '/messages/send', [
                'sender_id' => $this->senderId,
                'recipient' => $phoneNumber,
                'message' => $message,
                'type' => 'text'
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp notification sent successfully", [
                    'phone' => $phoneNumber,
                    'response' => $response->json()
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Notifikasi berhasil dikirim',
                    'data' => $response->json()
                ];
            }

            Log::error("Failed to send WhatsApp notification", [
                'phone' => $phoneNumber,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return [
                'success' => false,
                'message' => 'Gagal mengirim notifikasi: ' . $response->body()
            ];

        } catch (\Exception $e) {
            Log::error("Exception sending WhatsApp notification", [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Test koneksi ke Piwapi API
     */
    public function testConnection()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/status');

            return [
                'success' => $response->successful(),
                'message' => $response->successful() ? 'Koneksi berhasil' : 'Koneksi gagal',
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
