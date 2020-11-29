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