<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PiwapiService
{
    protected $apiUrl;
    protected $apiSecret;
    protected $account;

    public function __construct()
    {
        $this->apiUrl = config('piwapi.api_url');
        $this->apiSecret = config('piwapi.api_secret');
        $this->account = config('piwapi.account');
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
   /**
 * Buat pesan notifikasi jatuh tempo
 */
protected function createReminderMessage($pelanggan, $tagihan, $daysBefore)
{
    $bulan = Carbon::parse($tagihan->bulan)->format('F Y');
    $jatuhTempo = Carbon::parse($tagihan->jatuh_tempo)->format('d F Y');
    $totalTagihan = number_format($tagihan->jumlah_tagihan + $tagihan->denda, 0, ',', '.');
    
    // Ambil email user sebagai username
    $email = $pelanggan->user->email ?? '-';
    
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
- Periode: {$bulan}
- Paket: {$pelanggan->paket_wifi}
- Nominal: Rp {$totalTagihan}
- Jatuh Tempo: {$jatuhTempo}

{$urgency}

👤 *Informasi Login Dashboard:*
- Username: {$email}
- Password: 123456

💳 *Cara Pembayaran:*
Login ke dashboard pelanggan Anda atau hubungi admin untuk informasi pembayaran atau transfer
- Transfer Bank BRI: An. Zikri Rizkian, No. Rekening: 3768 01022083532
- Transfer Bank BCA: An. Zikri Rizkian, No. Rekening: 8930536084
- E-wallet DANA: An. Zikri Rizkian, No. Rekening: 082242350529

Terima kasih atas perhatian Anda! 🙏

_Pesan otomatis dari sistem_
MESSAGE;
}

    /**
     * Kirim pesan via Piwapi API (WhatsApp)
     */
    protected function sendMessage($phoneNumber, $message)
    {
        try {
            $url = $this->apiUrl . '/send/whatsapp';
            
            // Build payload - HANYA field yang diperlukan
            $payload = [
                'secret' => $this->apiSecret,
                'recipient' => $phoneNumber,
                'type' => 'text',
                'message' => $message
            ];
            
            // Tambahkan account HANYA jika ada dan tidak kosong
            if (!empty($this->account)) {
                $payload['account'] = $this->account;
            }

            Log::info("Sending WhatsApp message via Piwapi", [
                'url' => $url,
                'payload' => array_merge($payload, ['message' => substr($message, 0, 100) . '...']), // Log partial message
                'phone' => $phoneNumber
            ]);

            // Gunakan asForm() untuk application/x-www-form-urlencoded
            $response = Http::asForm()
                ->timeout(30)
                ->post($url, $payload);

            $statusCode = $response->status();
            $responseData = $response->json();
            
            Log::info("Piwapi API Response", [
                'status_code' => $statusCode,
                'response' => $responseData
            ]);

            // Cek status code dan response
            if ($statusCode === 200 && isset($responseData['status']) && $responseData['status'] === 200) {
                Log::info("WhatsApp notification sent successfully", [
                    'phone' => $phoneNumber,
                    'response' => $responseData
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Notifikasi berhasil dikirim',
                    'data' => $responseData
                ];
            }

            // Handle error responses
            $errorMessage = $responseData['message'] ?? 'Unknown error';
            
            Log::error("Failed to send WhatsApp notification", [
                'phone' => $phoneNumber,
                'status_code' => $statusCode,
                'error_message' => $errorMessage,
                'full_response' => $responseData
            ]);
            
            return [
                'success' => false,
                'message' => 'Gagal mengirim notifikasi: ' . $errorMessage,
                'data' => $responseData
            ];

        } catch (\Exception $e) {
            Log::error("Exception sending WhatsApp notification", [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
            $testPhone = '6281392246785'; // Ganti dengan nomor test Anda
            $testMessage = 'Test connection dari WiFi Billing System';
            
            $result = $this->sendMessage($testPhone, $testMessage);
            
            return $result;
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}