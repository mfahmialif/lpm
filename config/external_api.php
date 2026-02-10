<?php

return [

    /*
    |--------------------------------------------------------------------------
    | External API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk mengakses API dari website external
    | (Buku, Jurnal, Prosiding)
    |
    */

    'base_url' => env('EXTERNAL_API_URL', 'https://karyadosen.uiidalwa.ac.id/api/'),
    'api_key' => env('EXTERNAL_API_KEY', ''),
    'timeout' => env('EXTERNAL_API_TIMEOUT', 30),

];
