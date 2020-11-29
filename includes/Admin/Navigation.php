<?php

namespace KP\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use KP\API;
use KP\Storage\Credentials;
use KP\Auth;

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
        // OAuth login button form submitted.
        if( $_POST && isset( $_POST['security'] ) 
            && wp_verify_nonce( $_POST['security'], 'kargopin_oauth_login' )
        ) {
            // start oauth redirect processes.
            Auth::start_oauth();
        }

        // get customer data from API service
        $customer_data = API::get( '/api/v1/customer' );
        $customer_stats = API::get('/api/v1/customer/stats');
    
        // print output
        $this->output( 'Dashboard', [
            'credentials' => ( new Credentials() ),
            'customer_data' => $customer_data,
            'customer_stats' => $customer_stats
        ] );
    }
}

new Navigation();