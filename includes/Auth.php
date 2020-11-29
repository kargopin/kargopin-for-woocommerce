<?php

namespace KP;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use KP\Storage\Credentials;

class Auth {
    
    /**
     * Start OAuth processes.
     *
     * @return void
     */
    public static function start_oauth()
    {
        // start oauth login processes
        if( !session_id() )
            session_start();

        $_SESSION['state'] = $state = wp_generate_password( 40, false );

        $query = http_build_query([
            'state' => $state
        ]);

        return wp_redirect( API::get_base_url() . '/oauth/wp-credentials?'.$query);
    }

    /**
     * Start Authorize process.
     *
     * @return void
     */
    public static function authorize()
    {
        if( !session_id() )
            session_start();

        $_SESSION['state'] = $state = wp_generate_password( 40, false );

        $redirect_uri = add_query_arg( 'kargopin-wc-oauth-callback', true, get_site_url() );
        
        $query = http_build_query([
            'client_id' => ( new Credentials() )->get_client_id(),
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => '',
            'state' => $state
        ]);

        return wp_redirect( API::get_base_url() . '/oauth/authorize?'.$query);
    }

}