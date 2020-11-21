<?php

defined( 'ABSPATH' ) || exit;

final class WC_KargoPin {

    public function __construct()
    {
        $this->includes();
    }

    public function includes()
    {
        include_once WC_KARGOPIN_PATH . 'includes/Storage/Data_Encryption.php';
    }
}

new WC_KargoPin();