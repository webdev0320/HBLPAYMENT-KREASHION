<?php
return [
    'env' => env('HBLPAYENV'), // 'production' for live
    'user_id' => env('HBLUSERID'),
    'password' => env('HBLPASSWORD'),
    'channel' => env('HBLCHANNEL'),
    'client_name'=> env('HBLCLIENT_NAME'),
    'return_url' => env('HBLPAY_RETURN_URL'),
    'return_url' => env('HBLPAY_RETURN_URL'),
    'response_url' => env('HBLPAY_RETURN_URL'),
    'publicKey' => "insert public key here",
    'privateKey' => "insert private key here",
];
