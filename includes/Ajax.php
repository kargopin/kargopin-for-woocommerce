<?php

namespace KP;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use KP\Storage\Credentials;
use KP\API;
use KP\Shipment;

class Ajax {    
    /**
     * Ajax Constructor
     *
     * @return void
     */
    public function __construct(){
        add_action( 'wp_ajax_kargopin_create_shipment', array( $this, 'create_shipment' ) );
    }
    
    /**
     * Create New Shipment
     *
     * @return void
     */
    public function create_shipment() {
        check_ajax_referer( 'kargopin-create-shipment', 'security' );

        $order_id = isset( $_POST['order_id'] ) ? intval( $_POST['order_id'] ) : 0;

        if( ! $order_id || ! $order = wc_get_order( $order_id ) ) {
            echo wp_json_encode( array( 'shipment_id' => 0, 'errors' => array( __( 'Order ID is invalid', 'kargopin-for-woocommerce' ) ) ) );
            wp_die();
        }

        $invoice_number = isset( $_POST['invoice_number'] ) ? sanitize_title( $_POST['invoice_number'] ) : '';
        $waybill_number = isset( $_POST['waybill_number'] ) ? sanitize_title( $_POST['waybill_number'] ) : '';
        $shipping_company = isset( $_POST['waybill_number'] ) ? sanitize_title( $_POST['shipping_company'] ) : 0;
        $payment_type = isset( $_POST['payment_type'] ) ? sanitize_text_field( $_POST['payment_type'] ) : 0;
        $cod_type = isset( $_POST['cod_type'] ) ? sanitize_text_field( $_POST['cod_type'] ) : '';
        
        $shipment = new Shipment( $order_id );
        $shipment->customer_invoice_number = $invoice_number;
        $shipment->customer_waybill_number = $waybill_number;
        $shipment->shipping_company_id = $shipping_company;
        $shipment->payment_type = $payment_type;
        $shipment->cod_type = $cod_type;
        $shipment_id = $shipment->save();

        echo wp_json_encode( array( 'shipment_id' => $shipment_id, 'errors' => $shipment->get_errors() ) );
        wp_die();
    } 
}

new Ajax();