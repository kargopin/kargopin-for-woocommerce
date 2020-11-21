<?php

namespace KP\Storage;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Credentials {

    public $client_id;
    public $app_key;
    private $access_token;
    private $refresh_token;
    private $expires_at;

    public function __construct()
    {
        $this->update_defaults();
    }
    
    /**
     * Set default credentials.
     *
     * @return void
     */
    function update_defaults()
    {
        $encrypted_credentials = get_option( 'kargopin_wc_credentials', false );

        if( $encrypted_credentials ) {
            $credentials = unserialize( ( new Data_Encryption() )->decrypt( $encrypted_credentials ) );

            // update properties
            $this->client_id = $credentials['client_id'];
            $this->app_key = $credentials['app_key'];
            $this->access_token = $credentials['access_token'];
            $this->refresh_token = $credentials['refresh_token'];
            $this->expires_at = $credentials['expires_at'];
        }
    }
    
    /**
     * Update credentials
     *
     * @return void
     */
    function save()
    {
        $credentials = ( new Data_Encryption() )->encrypt( 
            serialize([
                'client_id' => $this->client_id,
                'app_key' => $this->app_key,
                'access_token' => $this->app_key,
                'refresh_token' => $this->refresh_token,
                'expires_at' => $this->expires_at
            ])
         );

         return update_option( 'kargopin_wc_credentials', $credentials );
    }
    
        
    /**
     * get_client_id
     *
     * @return string
     */
    public function get_client_id()
    {
        return $this->client_id;
    }
    
    /**
     * get_app_key
     *
     * @return string
     */
    public function get_app_key()
    {
        return $this->app_key;
    }
}