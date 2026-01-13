<?php

use Illuminate\Support\Facades\Schedule;

// Schedule untuk kirim reminder pembayaran
// Kirim jam 7 pagi
Schedule::command('tagihan:send-reminders')->dailyAt('07:00');

// Kirim 2x sehari (pagi & sore)
Schedule::command('tagihan:send-reminders')->twiceDaily(8, 16);

// Kirim setiap jam
Schedule::command('tagihan:send-reminders')->hourly();