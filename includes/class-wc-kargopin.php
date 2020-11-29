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
        include_once WC_KARGOPIN_PATH . 'includes/Admin/Navigation.php';
        include_once WC_KARGOPIN_PATH . 'includes/Storage/Credentials.php';
        include_once WC_KARGOPIN_PATH . 'includes/Auth.php';
        include_once WC_KARGOPIN_PATH . 'includes/Auth_Callback.php';
        include_once WC_KARGOPIN_PATH . 'includes/API.php';
    }
}

new WC_KargoPin();