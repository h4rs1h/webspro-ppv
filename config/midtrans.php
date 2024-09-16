<?php

return [
    'mercant_id' => env('MIDTRANS_MERCHAT_ID',NULL),
    'client_key' => env('MIDTRANS_CLIENT_KEY',NULL),
    'server_key' => env('MIDTRANS_SERVER_KEY',NULL),
    'is_production' => env('MIDTRANS_IS_PRODUCTION',true),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED',true),
    'is_3ds' => env('MIDTRANS_ID_3DS',true),
];
