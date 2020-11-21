<?php

namespace KP\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use KP\Storage\Credentials;

class Navigation {

    function __construct()
    {
        add_action( 'admin_menu', array( $this, 'define_menu_pages' ) );
    }
    
    /**
     * Define Admin Menu Items.
     *
     * @return void
     */
    function define_menu_pages()
    {
        add_menu_page(
            'KargoPin',
            'KargoPin',
            'manage_options',
            'wc-kargopin',
            array( $this, 'output_dashboard_callback' )
        );
    }
    
    /**
     * Output function for WP Admin Screen.
     *
     * @param  mixed $view
     * @return void
     */
    function output( $view, $data = array() )
    {
        $view_path = WC_KARGOPIN_PATH . 'includes/Admin/Views/' . $view . '.php';
        ?>
        <div id="wrap">
            <?php require_once $view_path; ?>
        </div>
        <?php
    }
    
    /**
     * Dashboard Screen Output.
     *
     * @return void
     */
    function output_dashboard_callback()
    {
        if( $_POST && isset( $_POST['security'] ) 
            && wp_verify_nonce( $_POST['security'], 'update_kargopin_credentials' )
        ) {
            if( wp_is_uuid( sanitize_key( $_POST['app-key'] ) ) && wp_is_uuid( sanitize_key( $_POST['client-id'] ) ) ){
                // save credentials
                $credentials = new Credentials();
                $credentials->client_id = sanitize_key( $_POST['client-id'] );
                $credentials->app_key = sanitize_key( $_POST['app-key'] );
                $update_status = $credentials->save();
            }else {
                $update_status = false;
            }
        }

        $data = [
            'credentials' => ( new Credentials() ),
            'update_status' => $update_status
        ];

        $this->output( 'Dashboard', $data );
    }
}

new Navigation();