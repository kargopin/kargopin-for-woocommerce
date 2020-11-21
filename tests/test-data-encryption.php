<?php
/**
 * Class DataEncryption
 *
 * @package Kargopin_For_Woocommerce
 */

 use KP\Storage\Data_Encryption;

class DataEncryption extends WP_UnitTestCase {
	
	/**
	 * Test: is get_key function result equal to LOGGED_IN_KEY define variable?
	 *
	 * @return void
	 */
	public function test_get_key()
	{
		$key = ( new Data_Encryption )->get_key(); 
		$this->assertEquals( $key, LOGGED_IN_KEY );
	}
}
