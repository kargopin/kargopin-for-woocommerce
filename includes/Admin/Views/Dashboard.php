<h2><?php echo __( 'Account Settings', 'kargopin-for-woocommerce' ); ?></h2>

<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <div class="postbox">
                <h2 class="title">Connect your WooCommerce Store to KargoPin!</h2>

                <?php
                    if( $data['customer_data']['status'] == 200 ){
                ?>
                    <h3><?php echo __( 'Hi,', 'kargopin-for-woocommerce' ); ?> <?php printf( "%s %s", $data['customer_data']['data']->data->firstname, $data['customer_data']['data']->data->lastname ); ?>!</h3>
                <?php
                    }
                ?>

                <div class="inside">
                    <?php if( $data['update_status'] ) { ?>
                        <div class="notice notice-success inline">
                            <p><?php echo __( 'Credentials has been updated successfully.' ); ?></p>
                        </div>
                    <?php }elseif( !$data['update_status'] && !is_null($data['update_status'])) { ?>
                        <div class="notice notice-error inline">
                            <p><?php echo __( 'Credentials could not updated! Please check the data.'); ?></p>
                        </div>
                    <?php } ?>

                    <form action="" method="POST">
                        <?php wp_nonce_field( 'update_kargopin_credentials', 'security' ); ?>

                        <table class="widefat">
                            <tr>
                                <td><?php echo __( 'Client ID' ); ?></td>
                                <td><input name="client-id" type="text" value="<?php echo $data['credentials']->get_client_id(); ?>" class="large-text" /></td>
                            </tr>
                            <tr>
                                <td><?php echo __( 'Client Secret' ); ?></td>
                                <td><input name="client-secret" type="text" value="<?php echo $data['credentials']->get_client_secret(); ?>" class="large-text" /></td>
                            </tr>
                            <tr>
                                <td><?php echo __( 'App Key' ); ?></td>
                                <td><input name="app-key" type="text" value="<?php echo $data['credentials']->get_app_key(); ?>" class="large-text" /></td>
                            </tr>
                        </table>

                        <br/>
                        <?php submit_button(
                            __( 'Save changes', 'kargopin-for-woocmmerce' ), 'primary', 'submit', false
                        ); ?>
                    </form>
                </div>
            </div>
        </div>

        <div id="postbox-container-1" class="postbox-container">
            <div class="postbox">
                <h2 class="title"><?php echo __('Login Account', 'kargopin-for-woocommerce'); ?></h2>

                <div class="inside">
                    <?php echo __( 'Please login to your Kargopin Account', 'kargopin-for-woocommerce' ); ?>

                    <form action="" method="POST">
                        <?php wp_nonce_field( 'kargopin_oauth_login', 'security' ); ?>

                        <?php submit_button(
                            __( 'Login', 'kargopin-for-woocommerce' ),
                            'primary',
                            'submit'
                        ); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>