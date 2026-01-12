<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tagihan;
use App\Services\PiwapiService;
use Carbon\Carbon;

class SendPaymentReminders extends Command
{
    protected $signature = 'tagihan:send-reminders';
    protected $description = 'Kirim pengingat pembayaran tagihan via WhatsApp';

    protected $piwapiService;

    public function __construct(PiwapiService $piwapiService)
    {
        parent::__construct();
        $this->piwapiService = $piwapiService;
    }

    public function handle()
    {
        $this->info('Memulai pengiriman reminder pembayaran...');
        
        // Ambil konfigurasi hari reminder (H-3, H-1, H-0)
        $reminderDays = config('piwapi.reminder_days', [3, 1, 0]);
        
        $totalSent = 0;
        $totalFailed = 0;

        foreach ($reminderDays as $days) {
            $targetDate = Carbon::today()->addDays($days);
            
            // Cari tagihan yang jatuh tempo sesuai target date dan belum lunas
            $tagihans = Tagihan::where('status', 'belum_lunas')
                ->whereDate('jatuh_tempo', $targetDate)
                ->with('pelanggan')
                ->get();

            $this->info("Mengirim reminder H-{$days}: {$tagihans->count()} tagihan");

            foreach ($tagihans as $tagihan) {
                try {
                    $result = $this->piwapiService->sendReminderJatuhTempo(
                        $tagihan->pelanggan,
                        $tagihan,
                        $days
                    );

                    if ($result['success']) {
                        $totalSent++;
                        $this->info("✓ Sent to {$tagihan->pelanggan->nama} ({$tagihan->pelanggan->no_hp})");
                    } else {
                        $totalFailed++;
                        $this->error("✗ Failed to {$tagihan->pelanggan->nama}: {$result['message']}");
                    }

                } catch (\Exception $e) {
                    $totalFailed++;
                    $this->error("✗ Error sending to {$tagihan->pelanggan->nama}: {$e->getMessage()}");
                }

                // Delay 1 detik antar pengiriman untuk menghindari rate limit
                sleep(1);
            }
        }

        $this->info("=================================");
        $this->info("Pengiriman selesai!");
        $this->info("Berhasil: {$totalSent}");
        $this->info("Gagal: {$totalFailed}");
        $this->info("=================================");

        return Command::SUCCESS;
    }
}