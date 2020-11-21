<?php

namespace KP\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
    function output( $view )
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
        $this->output( 'Dashboard' );
    }
}

new Navigation();