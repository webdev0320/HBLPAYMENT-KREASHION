<?php

namespace Kreashion\HBLPay\Helpers;

class Encryptor
{
    private $publicPEMKey;

    public function __construct()
    {
        $this->publicPEMKey = config('hblpay.public_key');
    }

    public function rsaEncrypt($plainData, $publicPEMKey = null)
    {
        if (!$publicPEMKey) {
            $publicPEMKey = $this->publicPEMKey;
        }

        $encryptionOk = openssl_public_encrypt($plainData, $encryptedData, $publicPEMKey, OPENSSL_PKCS1_PADDING);

        if ($encryptionOk === false) {
            return false;
        }

        return base64_encode($encryptedData);
    }
}
