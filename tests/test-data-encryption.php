<?php
/**
 * Class DataEncryption
 *
 * @package Kargopin_For_Woocommerce
 */

 use KP\Storage\Data_Encryption;

class DataEncryption extends WP_UnitTestCase {

	public function test_encrypt()
	{
		$data_encryption = new Data_Encryption();

		// override the encryption key private property.
		$rc = new ReflectionClass( $data_encryption );
		$key_property = $rc->getProperty('key');
		$key_property->setAccessible(true);
		$key_property->setValue( $data_encryption, 'test-key' );
		$encrypted_text = $data_encryption->encrypt('test-string');

		// decode the data
		$c = base64_decode($encrypted_text);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, 'test-key', $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, 'test-key', $as_binary=true);
		
		$this->assertEquals( 'test-string', $original_plaintext );
	}

}
