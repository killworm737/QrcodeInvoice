<?php

namespace killworm737\qrcode;

class Qrcode
{
    private $spec_key = "Dt8lyToo17X/XkXaQvihuA==";
    public function __construct()
    {

    }

    public function aes128_cbc_encrypt($aesKey, $invoice_random)
    {
        $key = hex2bin($aesKey);
        $iv = base64_decode($this->spec_key);
        $data = self::pkcs5_pad($invoice_random,16);
        return base64_encode(
                    openssl_encrypt(
                      $data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv
                    )
                );
    }

    public function aes128_cbc_encrypt_mcrypt($aesKey, $invoice_random)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $key = hex2bin($aesKey);
        $iv = base64_decode($this->spec_key);
        $data = self::pkcs5_pad($invoice_random,$iv_size);

        return base64_encode(
                    mcrypt_encrypt(
                        MCRYPT_RIJNDAEL_128,
                        $key,
                        $data,
                        MCRYPT_MODE_CBC,
                        $iv
                    )
                );
    }

    private function pkcs5_pad ($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }


}
