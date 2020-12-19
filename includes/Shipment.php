<?php

namespace KP;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use KP\Storage\Credentials;

class Shipment {

    protected $order;

    protected $errors = array();

    protected $data = array(
        'app_key' => '',
        'payment_type' => '',
        'shipping_company_id' => '',
        'consignee_name' => '',
        'consignee_address' => '',
        'consignee_city' => '',
        'consignee_district' => '',
        'consignee_phone' => '',
        'box_count' => '',
        'is_cod' => '',
        'cod_type' => '',
        'cod_amount' => '',
        'cod_amount_currency' => '',
        'customer_waybill_number' => '',
        'customer_invoice_number' => '',
        'customer_order_number' => ''
    );

    public function __construct( $order_id ) {
        // set the order
        $this->order = wc_get_order( $order_id );

        // set fields
        $this->set_app_key();
        $this->set_consignee_name();
        $this->set_consignee_phone();
        $this->set_consignee_address();
        $this->set_consignee_city();
        $this->set_consignee_district();
        $this->set_box_count();
        $this->set_cod_fields();
        $this->set_customer_order_number();
    }

    /**
     * Set props
     */
    public function __set( $name, $value ) {
        if( $name == 'cod_type' && ! wc_kargopin_is_cod_order( $this->order )  ) {
            return false;
        }

        $this->data[ $name ] = $value;
    }

    /**
     * Get props
     */
    public function __get( $name ) {
        return $this->data[ $name ];
    }
    
    /**
     * Push a new error to errors stack.
     *
     * @param  mixed $error_msg
     */
    private function push_error( $error_msg ) {
        $this->errors[] = $error_msg;
    }
    
    /**
     * Get errors.
     */
    public function get_errors() {
        return $this->errors;
    }

    /**
     * Validate the fields
     */
    private function validate() {
        if( !in_array( $this->payment_type, [ 'Consignee', 'Shipper' ] ) ) {
            $this->push_error( __( 'The payment type is required.', 'kargopin-for-woocommerce' ) );
        }

        if( ! ( $this->shipping_company_id > 0 ) ) {
            $this->push_error( __( 'The shipping company is required.', 'kargopin-for-woocommerce' ) );
        }
    }
    
    /**
     * Set App Key
     */
    private function set_app_key() {
        $this->app_key = ( new Credentials() )->app_key;
    }
    
    /**
     * Set Consignee Phone
     */
    private function set_consignee_phone() {
        $this->consignee_phone = $this->order->get_billing_phone();
    }
    
    /**
     * Set Consignee Name
     */
    private function set_consignee_name() {
        if( $this->order->get_shipping_company() ) {
            $this->consignee_name = sprintf( '%s %s / %s', $this->order->get_shipping_first_name(), $this->order->get_shipping_last_name(), $this->order->get_shipping_company() );
        }else {
            $this->consignee_name = sprintf( '%s %s', $this->order->get_shipping_first_name(), $this->order->get_shipping_last_name() );
        }
    }
    
    /**
     * Set Consignee Address
     */
    private function set_consignee_address() {
        $this->consignee_address  = sprintf( '%s %s', $this->order->get_shipping_address_1(), $this->order->get_shipping_address_2() );
    }
    
    /**
     * Set Consignee City
     */
    private function set_consignee_city() {
        $country_states = ( new \WC_Countries() )->get_states();
        $this->consignee_city = $country_states['TR'][ $this->order->get_shipping_state() ];
    }
    
    /**
     * Set Consignee District
     */
    private function set_consignee_district() {
        $this->consignee_district = $this->order->get_shipping_city();
    }
    
    /**
     * Set Box Count
     */
    private function set_box_count() {
        $this->box_count = 1;
    }

    /**
     * Set COD Fields
     */
    private function set_cod_fields( $cod_type='' ) {
        if( wc_kargopin_is_cod_order( $this->order ) ) {
            $this->is_cod = 1;
            $this->cod_amount = $this->order->get_total();
            $this->cod_amount_currency = 'TRY';
        }else {
            $this->is_cod = 0;
            $this->cod_type = '';
            $this->cod_amount = '';
            $this->cod_amount_currency = '';
        }
    }
    
    /**
     * Set Customer Order Number
     */
    private function set_customer_order_number() {
        $this->customer_order_number = $this->order->get_order_number();
    }
        
    /**
     * Create a new Shipment
     *
     * @return integer
     */
    public function save() {
        // validate the shipment details
        $this->validate();

        // if there are errors, return.
        if( $this->get_errors() ) {
            return 0;
        }

        // make a new API request.
        $response = API::post( '/api/v1/shipment', json_encode( $this->data ) );

        if( $response['status'] == 200 ) {
            if( isset($response['data']->error_code) ) {
                switch( $response['data']->error_code ) {
                    case 0:
                        $shipment_id = $response['data']->shipment_id;

                        // save shipment id to meta
                        update_post_meta( $this->order->get_id(), '_kargopin_shipment_id',  $shipment_id );
                        
                        return $shipment_id;
                    break;

                    /** TODO: Catch other error codes... */
                }
            }
        }else {
            foreach( $response['data']->errors as $error_key => $error_messages ) {
                foreach( $error_messages as $error_message ) {
                    $this->push_error( $error_message );
                }
            }
            return 0;
        }
    }
}