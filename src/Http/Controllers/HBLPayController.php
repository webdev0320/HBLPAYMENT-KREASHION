<?php

namespace Kreashion\HBLPay\Http\Controllers;

use Illuminate\Routing\Controller;
use Kreashion\HBLPay\HBLPay;

class HBLPayController extends Controller
{
    public function testCheckout()
    {
        $hbl = new HBLPay();

        $arrayData = [  
                "USER_ID"     => config('hblpay.user_id'),
                "PASSWORD"    => config('hblpay.password'),
                "CLIENT_NAME" => config('hblpay.client_name'),
                "RETURN_URL"  => config('hblpay.return_url'),
                "RESPONSE_URL"=> config('hblpay.response_url'),
                "CANCEL_URL"  => config('hblpay.cancel_url'),
                "CHANNEL"     => config('hblpay.channel'),
                "TYPE_ID"     => "0",
                "ORDER"       => [
                    "DISCOUNT_ON_TOTAL" => "0",
                    "SUBTOTAL"          => "0",
                    "OrderSummaryDescription" => [
                        [
                            "ITEM_NAME"   => 'TEST PRODUCT',
                            "QUANTITY"    => "1",
                            "UNIT_PRICE"  => "10",
                            "OLD_PRICE"   => "0",
                            "CATEGORY"    => "application",
                            "SUB_CATEGORY"=> "NA",
                        ]
                    ],
                ],
                "SHIPPING_DETAIL" => [
                    "NAME"           => "NA",
                    "DELIEVERY_DAYS" => 0,
                    "SHIPPING_COST"  => 0,
                ],
                "ADDITIONAL_DATA" => [
                    "REFERENCE_NUMBER"          => '123213TRF',
                    "CUSTOMER_ID"               => "1234",
                    "CURRENCY"                  => "PKR",
                    "BILL_TO_SURNAME"           => "Muhammad",
                    "BILL_TO_FORENAME"          => "Arbaz",
                    "BILL_TO_EMAIL"             => "mohammad.arbaz001@gmail.com",
                    "BILL_TO_PHONE"             => "+923205038329",
                    "BILL_TO_ADDRESS_LINE"      => "Islamabad",
                    "BILL_TO_ADDRESS_CITY"      => "Islamabad",
                    "BILL_TO_ADDRESS_STATE"     => "Islamabad",
                    "BILL_TO_ADDRESS_COUNTRY"   => "Pakistan",
                    "BILL_TO_ADDRESS_POSTAL_CODE"=> "46000",
                    "SHIP_TO_SURNAME"           => "Muhammad",
                    "SHIP_TO_FORENAME"          => "Arbaz",
                    "SHIP_TO_EMAIL"             => "mohammad.arbaz001@gmail.com",
                    "SHIP_TO_PHONE"             => "+923205038329",
                    "SHIP_TO_ADDRESS_LINE"      => "Islamabad",
                    "SHIP_TO_ADDRESS_CITY"      => "Islamabad",
                    "SHIP_TO_ADDRESS_STATE"     => "Islamabad",
                    "SHIP_TO_ADDRESS_COUNTRY"   => "Pakistan",
                    "SHIP_TO_ADDRESS_POSTAL_CODE"=> "46000",
                    "MerchantFields" => [
                        "MDD1"  => "anyData",
                        "MDD2"  => "anyData",
                        "MDD3"  => "anyData",
                        "MDD4"  => "anyData",
                        "MDD5"  => "anyData",
                        "MDD6"  => "Standard",
                        "MDD7"  => "1",
                        "MDD8"  => "PK",
                        "MDD20" => "NO",
                    ],
                ],
            ];
            $checkoutUrl = $hbl->checkout($arrayData);

        return "<a href='$checkoutUrl'>Pay Now</a>";
    }
}
