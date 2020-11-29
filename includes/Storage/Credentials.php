<?php

namespace KP\Storage;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Credentials {

    public $client_id;
    public $client_secret;
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
            $this->client_secret = $credentials['client_secret'];
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
                'client_secret' => $this->client_secret,
                'app_key' => $this->app_key,
                'access_token' => $this->access_token,
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
    
    /**
     * get_client_secret
     *
     * @return void
     */
    public function get_client_secret()
    {
        return $this->client_secret;
    }
    
    /**
     * set_access_token
     *
     * @param  mixed $access_token
     * @return void
     */
    public function set_access_token( $access_token )
    {
        $this->access_token = $access_token;
    }
    
    /**
     * set_refresh_token
     *
     * @param  mixed $refresh_token
     * @return void
     */
    public function set_refresh_token( $refresh_token )
    {
        $this->refresh_token = $refresh_token;
    }
    
    /**
     * set_expires_at
     *
     * @param  mixed $expires_at
     * @return void
     */
    public function set_expires_at( $expires_at )
    {
        $this->expires_at = $expires_at;
    }
    
    /**
     * get_access_token
     *
     * @return void
     */
    public function get_access_token()
    {
        return $this->access_token;
    }
}