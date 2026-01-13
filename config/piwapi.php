<?php

return [
    'api_url'     => env('PIWAPI_URL', 'https://piwapi.com/api'),
    'api_secret' => env('PIWAPI_API_KEY'),
    'account'    => env('PIWAPI_ACCOUNT', null),


    
    // Pengaturan notifikasi jatuh tempo
    'reminder_days' => [
        7, // H-7 (seminggu sebelum)
        3, // H-3
        2,
        1, // H-1
        0, // H-0
    ],
];