<?php

namespace DigitalLab\SMSNet24\Admin;

/**
 * The Menu handler class
 */
class Menu {

    public $settings;
    public $contact;
    public $guideline;

    /**
     * Initialize the class
     */
    function __construct( $settings, $contact,$guideline ) {
        $this->settings = $settings;
        $this->contact = $contact;
        $this->guideline = $guideline;
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */
    public function admin_menu() {
        $parent_slug = 'smsnet-setting';
        $capability = 'manage_options';

        add_menu_page( __( 'SMSNet 24', 'smsnet-24' ), __( 'SMSNet24', 'smsnet-24' ), $capability, $parent_slug, [ $this->settings, 'plugin_page' ], 'dashicons-format-chat' );
        add_submenu_page( $parent_slug, __( 'Setting', 'smsnet-24' ), __( 'Setting', 'smsnet-24' ), $capability, $parent_slug, [ $this->settings, 'plugin_page' ] );
        add_submenu_page( $parent_slug, __( 'Contact', 'smsnet-24' ), __( 'Contact', 'smsnet-24' ), $capability, 'smsnet-contact', [ $this->contact, 'plugin_page' ] );
        add_submenu_page( $parent_slug, __( 'Guideline', 'smsnet-24' ), __( 'Guideline', 'smsnet-24' ), $capability, 'smsnet-guideline', [ $this->guideline, 'plugin_page' ] );
    }
}