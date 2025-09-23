# HBLPay Laravel Package

A Laravel package for integrating **HBLPay Payment Gateway**.  
Built with ❤️ by [Kreashion Software House](https://kreashionsoftwarehouse.com).

---

## 📦 Installation

Install the package via Composer:

composer require kreashion/hblpay

If Laravel does not auto-discover, add the provider manually in config/app.php:

'providers' => [
    Kreashion\HBLPay\HBLPayServiceProvider::class,
],

Publish Configuration

Publish the package config file:

php artisan vendor:publish --tag=config

This will create:

config/hblpay.php


Configuration

Add the following to your .env file:

HBLPAYENV=sandbox   # use "production" for live
HBLUSERID=your_user_id
HBLPASSWORD=your_password
HBLCHANNEL=HBLPay_MyWebsite
HBLCLIENT_NAME=your_client_name
HBLPAY_RETURN_URL=https://yourdomain.com/payment/success
HBLPAY_RESPONSE_URL=https://yourdomain.com/payment/response
HBLPAY_CANCEL_URL=https://yourdomain.com/payment/cancel

Then update config/hblpay.php with your RSA keys:

'publicKey'  => "-----BEGIN PUBLIC KEY-----...-----END PUBLIC KEY-----",
'privateKey' => "-----BEGIN PRIVATE KEY-----...-----END PRIVATE KEY-----",

1. Create Checkout

use Kreashion\HBLPay\HBLPay;

public function checkout()
{
    $hbl = new HBLPay();

    $payload = [
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
            "SUBTOTAL" => "1000",
            "OrderSummaryDescription" => [
                [
                    "ITEM_NAME"   => "License",
                    "QUANTITY"    => "1",
                    "UNIT_PRICE"  => "1000",
                    "OLD_PRICE"   => "0",
                    "CATEGORY"    => "application",
                    "SUB_CATEGORY"=> "NA",
                ]
            ]
        ],
        "SHIPPING_DETAIL" => [
            "NAME"           => "NA",
            "DELIEVERY_DAYS" => 2,
            "SHIPPING_COST"  => 0,
        ],
        "ADDITIONAL_DATA" => [
            "REFERENCE_NUMBER" => "ORDER12345",
            "CUSTOMER_ID"      => "CUSTOMER001",
            "CURRENCY"         => "PKR",
            "BILL_TO_SURNAME"  => "Test",
            "BILL_TO_FORENAME" => "User",
            "BILL_TO_EMAIL"    => "test@example.com",
            "BILL_TO_PHONE"    => "03001234567",
            "BILL_TO_ADDRESS_LINE" => "Some Address",
            "BILL_TO_ADDRESS_CITY" => "Karachi",
            "BILL_TO_ADDRESS_COUNTRY" => "PK",
            "BILL_TO_ADDRESS_POSTAL_CODE" => "74200",
        ],
    ];

    $checkoutUrl = $hbl->checkout($payload);

    return redirect($checkoutUrl);
}


2. Handle Response

After payment, HBLPay redirects to your configured RETURN_URL or RESPONSE_URL.

use Kreashion\HBLPay\HBLPay;

public function paymentResponse()
{
    $hbl = new HBLPay();
    $response = $hbl->getResponse();

    return $response;
}


Example Response:

{
  "splitToArray": [...],
  "responseCode": "000",
  "responseMsg": "Transaction Successful",
  "orderRefNumber": "24101723544183",
  "paymentType": "CARD"
}


License

This package is open-source, released under the MIT License.

Author

Kreashion Software House
🔗 www.kreashionsoftwarehouse.com

📧 info@kreashionsoftwarehouse.com