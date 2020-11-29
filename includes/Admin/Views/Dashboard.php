<h2><?php echo __( 'Account Settings', 'kargopin-for-woocommerce' ); ?></h2>
<div class="card">
    <h2 class="title">Connect your WooCommerce Store to KargoPin!</h2>
	    <p>
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
        </p>
</div>

<?php if( $data['customer_data']['status'] == 200 ){ ?>
    <h2><?php echo __( 'Shipment Tracking Usage Stats', 'kargopin-for-woocommerce' ); ?></h2>
    <div class="card">
        <?php
            if( $data['customer_stats']['status'] == 200 ){
        ?>
        <?php
            $usage_stats = $data['customer_stats']['data']->data->{"tracking-usages"}->totals;
        ?>

        <table style="width:100%">
            <tr>
                <td>
                    <b><?php echo __( 'Total Limit', 'kargopin-for-woocommerce' ); ?></b>
                    <p><?php echo $usage_stats->total_value; ?></p>
                </td>
                <td>
                    <b><?php echo __( 'Usage', 'kargopin-for-woocommerce' ); ?></b>
                    <p><?php echo $usage_stats->usage; ?></p>
                </td>
                <td>
                    <b><?php echo __( 'Remain', 'kargopin-for-woocommerce' ); ?></b>
                    <p><?php echo $usage_stats->remains; ?></p>
                </td>
            </tr>
        </table>
        
        <?php 
            }else{
        ?>
            <?php echo __( 'Usage data could not be retrieved.', 'kargopin-for-woocommerce' ); ?>
        <?php } ?>
    </div>
<?php } ?>