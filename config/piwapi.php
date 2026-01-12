<?php

return [
    'api_url' => env('PIWAPI_URL', 'https://api.piwapi.com/v1'),
    'api_key' => env('PIWAPI_API_KEY'),
    'sender_id' => env('PIWAPI_SENDER_ID'),
    
    // Pengaturan notifikasi jatuh tempo
    'reminder_days' => [
         7, // H-7 (seminggu sebelum)
    3, // H-3
    1, // H-1
    0, // H-0
    ],
];