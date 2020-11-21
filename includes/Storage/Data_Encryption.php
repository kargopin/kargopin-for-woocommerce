<?php

namespace KP\Storage;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Data_Encryption {

    private $key;

    public function __construct()
    {
        $this->set_key();
    }

    /**
     * Get Encryption Key
     *
     * @return void
     */
    private function set_key()
    {
        if ( defined( 'LOGGED_IN_KEY' ) )
            return LOGGED_IN_KEY;

        return 'bu-gizli-anahtar-degil';
    }

    /**
     * Encrypt
     *
     * @param  mixed $plaintext
     * @return void
     */
    function encrypt($plaintext)
    {
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $this->key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary=true);
        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }
    
    /**
     * Decrypt
     *
     * @param  mixed $ciphertext
     * @return void
     */
    function decrypt($ciphertext)
    {
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $this->key, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary=true);
        if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
        {
            return $original_plaintext;
        }

        return false;
    }
}