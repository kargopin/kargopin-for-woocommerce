<?php

namespace KP;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use KP\Storage\Credentials;

class Auth_Callback {
    
    function __construct()
    {
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'init', array( $this, 'callback_save_credentials' ) );
    }

    /**
     * Callback for Save OAuth Client Codes.
     *
     * @return void
     */
    function callback_save_credentials()
    {
        if( ! isset( $_GET['kargopin-wc-oauth-1'] ) )
            return;

        if( !session_id() )
            session_start();

        if( !isset( $_GET['state'] ) || !( strlen( $_SESSION['state'] ) > 0 ) || $_SESSION['state'] !== $_GET['state'] )
            die('invalid state');

        if( isset( $_GET['error-code'] ) )
        {
            echo $_GET['error-code'];
            exit;
        }

        $code = base64_decode( $_GET['code'] );

        // get client secret and client id by code.
        $data = [ 'code' => $code ];
        $response = API::post('/api/v1/get-wp-client-codes', $data);

        if( $response['status'] == '200' )
        {
            // save credentials
            $credentials = new Credentials();
            $credentials->client_id = $response['data']->code->client_id;
            $credentials->client_secret = $response['data']->code->client_secret;
            $credentials->app_key = $response['data']->code->app_key;
            $credentials->save();

            // start authorize process.
            Auth::authorize();
        }

        exit;
    }
    
    /**
     * OAuth Login Callback.
     *
     * @return void
     */
    function init()
    {
        if( ! isset( $_GET['kargopin-wc-oauth-callback'] ) )
            return;

        $redirect_uri = add_query_arg( 'kargopin-wc-oauth-callback', true, get_site_url() );

        if( !session_id() )
            session_start();

        if( !isset( $_GET['state'] ) || !( strlen( $_SESSION['state'] ) > 0 ) || $_SESSION['state'] !== $_GET['state'] )
            die('invalid state');
        
        // get access token
        $credentials = new Credentials();

        $data = [
            'grant_type' => 'authorization_code',
            'client_id' => $credentials->get_client_id(),
            'client_secret' => $credentials->get_client_secret(),
            'redirect_uri' => $redirect_uri,
            'code' => $_GET['code'],
        ];
        
        $get_token = API::post( '/oauth/token', $data );

        if( $get_token['status'] != 200 ){
            wp_redirect( get_admin_url() . 'admin.php?page=wc-kargopin' );
            exit;
        }

        $data = $get_token['data'];
        
        // save access and refresh token.
        $credentials->set_access_token( $data->access_token );
        $credentials->set_refresh_token( $data->refresh_token );
        $credentials->set_expires_at( ( current_time( 'timestamp' ) + $data->expires_in ) );
        $credentials->save();

        // redirect to dashboard again.
        wp_redirect( get_admin_url() . 'admin.php?page=wc-kargopin' );

        exit;
    }

}

new Auth_Callback();