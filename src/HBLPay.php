<?php

namespace Kreashion\HBLPay;

use Kreashion\HBLPay\Helpers\Encryptor;

class HBLPay
{
    protected $encryptor;

    public $publicPEMKey;
    public $privateKey;

    public function __construct()
    {
        $this->encryptor   = new Encryptor();
        $this->publicPEMKey = config('hblpay.publicKey');
        $this->privateKey   = config('hblpay.privateKey');
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
        $stringData = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $arrJson    = json_decode($stringData, true);

        $arrJson = json_encode($this->recParamsEncryption($arrJson));
        $env     = config('hblpay.env');

        if ($env === null || $env === 'sandbox') {
            $url = "https://testpaymentapi.hbl.com/hblpay/api/checkout";
        } else {
            $url = "https://digitalbankingportal.hbl.com/hostedcheckout/api/checkout";
        }

        $jsonCyberSourceResult = json_decode($this->callAPI("POST", $url, $arrJson), true);

        if ($jsonCyberSourceResult["IsSuccess"] && $jsonCyberSourceResult["ResponseCode"] == 0) {
            $sessionId = base64_encode($jsonCyberSourceResult["Data"]["SESSION_ID"]);

            if ($env === null || $env === 'sandbox') {
                return "https://testpaymentapi.hbl.com/HBLPay/Site/index.html#/checkout?data={$sessionId}";
            }

            return "https://digitalbankingportal.hbl.com/hostedcheckout/site/index.html#/checkout?data={$sessionId}";
        }
    }

    private function rsaEncryptCyb($plainData, $publicPEMKey = null)
    {
        $publicPEMKey = $this->publicPEMKey;

        $encryptionOk = openssl_public_encrypt($plainData, $encryptedData, $publicPEMKey, OPENSSL_PKCS1_PADDING);

        if ($encryptionOk === false) {
            return false;
        }

        return base64_encode($encryptedData);
    }

    private function rsaDecryptCyb($data, $publicPEMKey = null)
    {
        // TODO: implement
    }

    private function recParamsEncryption($arrJson)
    {
        foreach ($arrJson as $jsonIndex => $jsonValue) {
            if (!is_array($jsonValue)) {
                if ($jsonIndex !== "USER_ID") {
                    $arrJson[$jsonIndex] = $this->rsaEncryptCyb($jsonValue);
                } else {
                    $arrJson[$jsonIndex] = $jsonValue;
                }
            } else {
                $arrJson[$jsonIndex] = $this->recParamsEncryption($jsonValue);
            }
        }

        return $arrJson;
    }

    public function callAPI($method, $url, $data)
    {
        $is_live   = 'no';
        $use_proxy = 'no';
        $curl      = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        if ($use_proxy === 'yes') {
            // $proxy = 'your proxy';
            // curl_setopt($curl, CURLOPT_PROXY, $proxy);
        }

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }

        if (isset($error_msg)) {
            echo "Web Exception Raised::::::::::::::::" . $error_msg;
        }

        curl_close($curl);

        return $result;
    }

    public function debug($mixParam, $bolToStop = false)
    {
        if (defined("DEBUG_MODE") && DEBUG_MODE == "1") {
            if (!empty($mixParam)) {
                // print_r($mixParam);
            }

            if ($bolToStop) {
                exit;
            }
        }
    }

    public function getResponse()
    {
        try {
            $encryptedData = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
            $encryptedData = str_replace("data=", "", $encryptedData);

            $url_params = $this->decryptData($encryptedData, $this->privateKey);
            $data       = [];

            $data['splitToArray']   = explode("&", $url_params);
            $data['responseCode']   = str_replace("RESPONSE_CODE=", "", $data['splitToArray'][0]);
            $data['responseMsg']    = str_replace("RESPONSE_MESSAGE=", "", $data['splitToArray'][1]);
            $data['orderRefNumber'] = str_replace("ORDER_REF_NUMBER=", "", $data['splitToArray'][2]);
            $data['paymentType']    = str_replace("PAYMENT_TYPE=", "", $data['splitToArray'][3]);

            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function decryptData($data, $privatePEMKey)
    {
        $DECRYPT_BLOCK_SIZE = 512;
        $decrypted          = '';

        $data = str_split(base64_decode($data), $DECRYPT_BLOCK_SIZE);

        foreach ($data as $chunk) {
            $partial      = '';
            $decryptionOK = openssl_private_decrypt($chunk, $partial, $privatePEMKey, OPENSSL_PKCS1_PADDING);

            if ($decryptionOK === false) {
                return '';
            }

            $decrypted .= $partial;
        }

        return utf8_decode($decrypted);
    }
}
