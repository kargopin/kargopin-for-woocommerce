<h2><?php echo __( 'Account Settings', 'kargopin-for-woocommerce' ); ?></h2>

<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <div class="postbox">
                <h2 class="title">Connect your WooCommerce Store to KargoPin!</h2>

                <div class="inside">
                    <?php
                        if( $data['customer_data']['status'] == 200 ){
                    ?>
                        <h3><?php echo __( 'Hi,', 'kargopin-for-woocommerce' ); ?> <?php printf( "%s %s", $data['customer_data']['data']->data->firstname, $data['customer_data']['data']->data->lastname ); ?>!</h3>
                    <?php
                        }else{
                    ?>
                            <form action="" method="POST">
                                <?php wp_nonce_field( 'kargopin_oauth_login', 'security' ); ?>

                                <?php submit_button(
                                    __( 'Login to your Kargopin Account', 'kargopin-for-woocommerce' ),
                                    'primary',
                                    'submit'
                                ); ?>
                            </form>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>