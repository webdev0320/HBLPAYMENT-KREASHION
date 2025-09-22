<?php

namespace Kreashion\HBLPay;

use Kreashion\HBLPay\Helpers\Encryptor;

class HBLPay
{
    protected $encryptor;

    public function __construct()
    {
        $this->encryptor = new Encryptor();
    }

    protected function encryptParams(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->encryptParams($value);
            } else {
                if ($key !== "USER_ID") {
                    $data[$key] = $this->encryptor->rsaEncrypt($value);
                }
            }
        }
        return $data;
    }

    public function checkout(array $payload)
    {
        $payload = $this->encryptParams($payload);
        $jsonData = json_encode($payload);

        $ch = curl_init(config('hblpay.api_url'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($result['IsSuccess'] && $result['ResponseCode'] == 0) {
            $sessionId = base64_encode($result['Data']['SESSION_ID']);
            return config('hblpay.checkout_url') . $sessionId;
        }

        return $result;
    }
}
