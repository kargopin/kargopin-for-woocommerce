<?php

use KP\Shipment;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

abstract class Kargopin_OrderShipment_Meta_Box {    
    /**
     * Add New Meta Box
     *
     * @return void
     */
    public static function add() {
        add_meta_box(
            'kargopin_create_shipment',
            'KargoPin',
            [ self::class, 'html' ],
            'shop_order',
            'normal',
            'high'
        );        
    }
    
    /**
     * Show Meta Box
     *
     */
    public static function html() {
        global $post;
        $shipment_id = get_post_meta( $post->ID, '_kargopin_shipment_id', true );

        if( $shipment_id ) {
            self::html_show_shipment_details( $shipment_id );
        }else {
            self::html_create_shipment_form();
        }
    }
    
    /**
     * Show Shipment Details
     *
     * @param  mixed $shipment_id
     */
    private static function html_show_shipment_details( $shipment_id ) {
        ?>
            
        <?php
    }
    
    /**
     * Show Create Shipment Form
     *
     */
    private static function html_create_shipment_form() {
        global $post;
        $is_cod_order = wc_kargopin_is_cod_order( $post->ID );
        ?>
        <div id="kargopin-create-shipment-form">
            <table width="100%">
                <tr>
                    <td width="50%">
                        <img style="height:25px" src="<?php echo WC_KARGOPIN_URL; ?>assets/img/logo.png" />
                    </td>
                    <td style="text-align:right" width="50%">
                        <button class="button-primary kargopin-btn-create-shipment" type="button">Gönderi Oluştur</button>
                    </td>
                </tr>
            </table>

            <div class="kargopin-hide-element" id="kargopin-spinner">
                <div class="sk-chase">
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                    <div class="sk-chase-dot"></div>
                </div>
            </div>

            <table style="border:none" id="kargopin-create-shipment-form-table" class="widefat">
                <thead>
                    <th>Ödeme:</th>
                    <th>Kargo:</th>
                    <th>Fatura No:</th>
                    <th>İrsaliye No:</th>
                    <?php if( $is_cod_order ){ ?>
                        <th>Kapıda Ödeme Tipi:</th>
                    <?php } ?>
                </thead>
                <tbody>
                    <td>
                        <select id="payment-type">
                            <option value="">--Seçiniz--</option>
                            <option value="Shipper">Gönderici Ödemeli</option>
                            <option value="Consignee">Alıcı Ödemeli</option>
                        </select>
                    </td>
                    <td>
                        <select id="shipping-company">
                            <option value="">--Seçiniz--</option>
                            <?php foreach( wc_kargopin_get_available_shipping_companies() as $company ){  ?>
                                <option value="<?php echo $company->id; ?>"><?php echo $company->shipping_company->name; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" id="invoice-number" />
                    </td>
                    <td>
                        <input type="text" id="waybill-number" />
                    </td>
                    <?php if( $is_cod_order ){ ?>
                        <td>
                            <select id="cod-type">
                                <option value="">--Seçiniz--</option>
                                <option value="cash">Nakit</option>
                                <option value="credit-card">Kredi Kartı</option>
                            </select>
                        </td>
                    <?php } ?>
                </tbody>
            </table>

            <ul class="kargopin-create-shipment-errors">

            </ul>
        </div>

        <script>
            jQuery(document).ready(function($){
                $("#kargopin-create-shipment-form").find('button').click(function( event ){

                    var data = {
                        'action': 'kargopin_create_shipment',
                        'order_id': $('#post_ID').val(),
                        'cod_type': $('#cod-type').val(),
                        'invoice_number': $('#invoice-number').val(),
                        'waybill_number': $('#waybill-number').val(),
                        'shipping_company': $('#shipping-company').val(),
                        'payment_type': $('#payment-type').val(),
                        'security': '<?php echo wp_create_nonce('kargopin-create-shipment'); ?>'
                    };

                    $.post(ajaxurl, data, function(response) {
                        var data = JSON.parse(response);
                        
                        if(('errors' in data) && data.errors.length>0){
                            $('.kargopin-create-shipment-errors').html("");
                            $.each( data.errors, function( index, value ) {
                                $('.kargopin-create-shipment-errors').append('<li>'+value+'</li>');
                            });
                        }else{
                            $('.kargopin-create-shipment-errors').html("");

                            $("#kargopin-create-shipment-form").find('table').hide();
                            $("#kargopin-spinner").removeClass('kargopin-hide-element');
                        }
                    });
                });
            });
        </script>

        <style>
            #kargopin-create-shipment-form-table {
                margin-top:15px !important
            }

            .kargopin-btn-create-shipment {
                background: green !important;
                border: 1px solid green !important;
                padding: 0px 10px !important
            }

            .kargopin-hide-element {
                display:none !important
            }

            #kargopin-spinner {
                margin:60px 0;
            }

            #kargopin-spinner > div {
                margin:0 auto
            }

            .sk-chase {
                width: 40px;
                height: 40px;
                position: relative;
                animation: sk-chase 2.5s infinite linear both;
            }

            .sk-chase-dot {
                width: 100%;
                height: 100%;
                position: absolute;
                left: 0;
                top: 0; 
                animation: sk-chase-dot 2.0s infinite ease-in-out both; 
            }

            .sk-chase-dot:before {
                content: '';
                display: block;
                width: 25%;
                height: 25%;
                background-color: #023d89;
                border-radius: 100%;
                animation: sk-chase-dot-before 2.0s infinite ease-in-out both; 
            }

            .sk-chase-dot:nth-child(1) { animation-delay: -1.1s; }
            .sk-chase-dot:nth-child(2) { animation-delay: -1.0s; }
            .sk-chase-dot:nth-child(3) { animation-delay: -0.9s; }
            .sk-chase-dot:nth-child(4) { animation-delay: -0.8s; }
            .sk-chase-dot:nth-child(5) { animation-delay: -0.7s; }
            .sk-chase-dot:nth-child(6) { animation-delay: -0.6s; }
            .sk-chase-dot:nth-child(1):before { animation-delay: -1.1s; }
            .sk-chase-dot:nth-child(2):before { animation-delay: -1.0s; }
            .sk-chase-dot:nth-child(3):before { animation-delay: -0.9s; }
            .sk-chase-dot:nth-child(4):before { animation-delay: -0.8s; }
            .sk-chase-dot:nth-child(5):before { animation-delay: -0.7s; }
            .sk-chase-dot:nth-child(6):before { animation-delay: -0.6s; }

            @keyframes sk-chase {
                100% { transform: rotate(360deg); } 
            }

            @keyframes sk-chase-dot {
                80%, 100% { transform: rotate(360deg); } 
            }

            @keyframes sk-chase-dot-before {
                50% {
                    transform: scale(0.4); 
                } 100%, 0% {
                    transform: scale(1.0); 
                } 
            }

            .kargopin-create-shipment-errors {
                color:red
            }
        </style>
        <?php
    }
}

add_action( 'add_meta_boxes', [ 'Kargopin_OrderShipment_Meta_Box', 'add' ] );