<?php
return [
    'env' => env('HBLPAYENV'), // 'production' for live
    'user_id' => env('HBLUSERID'),
    'password' => env('HBLPASSWORD'),
    'channel' => env('HBLCHANNEL'),
    'return_url' => env('HBLPAY_RETURN_URL'),
    'cancel_url' => env('HBLPAY_CANCEL_URL'),
    'rsa' => [
        'public_key_path' => env('HBLPAY_PUBLIC_KEY_PATH'),
        'private_key_path' => env('HBLPAY_PRIVATE_KEY_PATH'),
    ],
];
