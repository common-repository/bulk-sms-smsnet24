<?php

namespace DigitalLab\SMSNet24;

/**
 * The admin class
 */
class Admin {

    /**
     * Initialize the class
     */
    function __construct() {
        $settings = new Admin\Settings();
        $contact  = new Admin\Contact();
        $guideline  = new Admin\Guideline();

        $this->dispatch_actions( $settings );
        new Admin\Menu( $settings, $contact, $guideline );
    }

    /**
     * Dispatch and bind actions
     *
     * @return void
     */
    public function dispatch_actions( $settings ) {
        add_action( 'admin_init', [ $settings, 'form_handler' ] );
    }
}