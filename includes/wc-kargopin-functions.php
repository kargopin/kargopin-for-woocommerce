<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use KP\API;

/**
 * Returns available shipping companies of the user.
 *
 * @return array
 */
function wc_kargopin_get_available_shipping_companies() {
    $response = API::get('/api/v1/user_shipping_company_details/availables');

    if( $response['status'] == 200 ) {
        if( array_key_exists( 'data', $response ) ) {
            return $response[ 'data' ];
        }
    }

    return [];
}

/**
 * Will the shipment include cash on delivery or cash on credit card?
 *
 * @param  mixed $order_id|WC_Order $order
 * @return bool
 */
function wc_kargopin_is_cod_order( $order_id ) {
    if( $order_id instanceof WC_Order ) {
        $order = $order_id;
    }elseif( intval($order_id) > 0 ) {
        $order = wc_get_order( $order_id );
    }

    if( !$order ) {
        throw new \Exception( __( 'Order could not found', 'kargopin-for-woocommerce' ) );
    }
        
    $available_cod_payment_method_ids = array( 'cod' );

    if( in_array( $order->get_payment_method(), $available_cod_payment_method_ids ) ) {
        return true;
    }
    
    return false;
}