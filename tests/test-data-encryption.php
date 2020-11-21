<?php
/**
 * Class DataEncryption
 *
 * @package Kargopin_For_Woocommerce
 */

 use KP\Storage\Data_Encryption;

class DataEncryption extends WP_UnitTestCase {
	
	/**
	 * Override the encryption key private property. 
	 *
	 * @param  mixed $data_encryption
	 * @return void
	 */
	private function override_key( Data_Encryption $data_encryption )
	{
		$rc = new ReflectionClass( $data_encryption );
		$key_property = $rc->getProperty('key');
		$key_property->setAccessible(true);
		$key_property->setValue( $data_encryption, 'test-key' );
	}

	public function test_encrypt()
	{
		$data_encryption = new Data_Encryption();

		$this->override_key($data_encryption);

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
