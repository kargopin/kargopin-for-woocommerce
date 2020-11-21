<h2><?php echo __( 'Account Settings', 'kargopin-for-woocommerce' ); ?></h2>


<div class="card">
    <h2 class="title">Connect your WooCommerce Store to KargoPin!</h2>

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
                <td><input name="client-id" type="text" value="<?php echo $data['credentials']->get_client_id(); ?>" /></td>
            </tr>
            <tr>
                <td><?php echo __( 'App Key' ); ?></td>
                <td><input name="app-key" type="text" value="<?php echo $data['credentials']->get_app_key(); ?>" /></td>
            </tr>
        </table>

        <br/>
        <?php submit_button(
            __( 'Save changes', 'kargopin-for-woocmmerce' ), 'primary', 'submit', false
        ); ?>
    </form>
</div>