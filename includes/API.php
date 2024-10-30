<?php


namespace DigitalLab\SMSNet24;


class API
{
    /**
     * Initialize the class
     */
    function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_api' ] );
    }

    /**
     * Register the API
     *
     * @return void
     */
    public function register_api() {
        $otp = new API\OTP();
        $otp->register_routes();

        $sms = new API\SMS();
        $sms->register_routes();
    }
}