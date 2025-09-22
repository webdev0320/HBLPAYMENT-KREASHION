<?php

return [
    'api_url' => env('HBLPAY_API_URL', 'https://testpaymentapi.hbl.com/hblpay/api/checkout'),
    'checkout_url' => env('HBLPAY_CHECKOUT_URL', 'https://testpaymentapi.hbl.com/HBLPay/Site/index.html#/checkout?data='),
    'public_key' => env('HBLPAY_PUBLIC_KEY'),
    'user_id' => env('HBLPAY_USER_ID'),
    'password' => env('HBLPAY_PASSWORD'),
    'client_name' => env('HBLPAY_CLIENT_NAME'),
    'return_url' => env('HBLPAY_RETURN_URL'),
    'response_url' => env('HBLPAY_RESPONSE_URL'),
    'cancel_url' => env('HBLPAY_CANCEL_URL'),
    'channel' => env('HBLPAY_CHANNEL', 'HBLPay_Mediatiz_Website'),
];
