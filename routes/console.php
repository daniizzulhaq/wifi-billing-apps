<?php

use Illuminate\Support\Facades\Schedule;

// Ubah dari dailyAt ke everyMinute untuk testing
Schedule::command('tagihan:send-reminders')
    ->everyMinute()  // ← Ubah ini untuk testing
    ->withoutOverlapping()
    ->onOneServer();