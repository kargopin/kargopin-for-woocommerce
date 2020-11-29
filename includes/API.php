<?php

namespace KP;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use KP\Storage\Credentials;

class API {

    public static $base_url = 'http://kargopin-api.test';
    
    /**
     * Get API Service Base URL
     *
     * @return string
     */
    public static function get_base_url() {
        return self::$base_url;
    }
    
    /**
     * HTTP Post Method
     *
     * @param  string $endpoint_url
     * @param  array $data
     * @return array|WP_Error
     */
    public static function post( $endpoint_url, $data=array() )
    {
        $response = wp_remote_post( self::get_base_url() . $endpoint_url, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(),
            'body'        => $data,
            'cookies'     => array()
            )
        );

        return [
            'data' => json_decode( wp_remote_retrieve_body( $response ) ),
            'status' => wp_remote_retrieve_response_code( $response )
        ];
    }
    
    /**
     * HTTP Get Method
     *
     * @param  string $endpoint_url
     * @return array|WP_Error
     */
    public static function get( $endpoint_url )
    {
        $response =  wp_remote_get( self::get_base_url() . $endpoint_url, 
            [
                'headers' => 
                [
                'Accept'=>'application/json',
                'Content-Type'=>'application/json',
                'Authorization'=>'Bearer ' . ( new Credentials() )->get_access_token()
                ]
            ]
        );

        return [
            'data' => json_decode( wp_remote_retrieve_body( $response ) ),
            'status' => wp_remote_retrieve_response_code( $response )
        ];
    }

}