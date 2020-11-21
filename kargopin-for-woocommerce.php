<?php

/**
* Plugin Name: Kargopin for WooCommerce
* Description: Kargo entegrasyonunun kolay yolu!
* Version: 0.0.0
* Author: Kargopin
* Author URI: https://www.kargopin.com/
* Text Domain: kargopin-for-woocommerce
* Domain Path: /languages
* WC requires at least: 4.0.0
* WC tested up to: 4.7.0
 */

 if ( ! defined( 'WC_KARGOPIN_PLUGIN_FILE' ) ) {
     define( 'WC_KARGOPIN_PLUGIN_FILE', __FILE__ );
 }

 if ( ! class_exists( 'WC_KargoPin' ) ) {
     include_once dirname( WC_KARGOPIN_PLUGIN_FILE ) . '/includes/class-wc-kargopin.php';
 }