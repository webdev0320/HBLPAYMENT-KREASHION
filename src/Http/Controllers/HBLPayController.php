<?php

namespace Kreashion\HBLPay\Http\Controllers;

use Illuminate\Routing\Controller;
use Kreashion\HBLPay\HBLPay;

class HBLPayController extends Controller
{
    public function testCheckout()
    {
        $hbl = new HBLPay();

        $payload = [
            "USER_ID" => config('hblpay.user_id'),
            "PASSWORD" => config('hblpay.password'),
            "CLIENT_NAME" => config('hblpay.client_name'),
            "RETURN_URL" => config('hblpay.return_url'),
            "RESPONSE_URL" => config('hblpay.response_url'),
            "CANCEL_URL" => config('hblpay.cancel_url'),
            "CHANNEL" => config('hblpay.channel'),
            "TYPE_ID" => "0",
            "ORDER" => [
                "DISCOUNT_ON_TOTAL" => "0",
                "SUBTOTAL" => "1000",
                "OrderSummaryDescription" => [
                    [
                        "ITEM_NAME" => "License",
                        "QUANTITY" => "1",
                        "UNIT_PRICE" => "1000",
                        "OLD_PRICE" => "0",
                        "CATEGORY" => "application",
                        "SUB_CATEGORY" => "NA"
                    ]
                ]
            ]
        ];

        $checkoutUrl = $hbl->checkout($payload);

        return "<a href='$checkoutUrl'>Pay Now</a>";
    }
}
