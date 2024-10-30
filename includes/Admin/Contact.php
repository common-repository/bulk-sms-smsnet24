<?php
namespace DigitalLab\SMSNet24\Admin;

/**
 * Contact Handler class
 */
class Contact {

    /**
     * Plugin page handler
     *
     * @return void
     */
    public function plugin_page() {
        $template = __DIR__ . '/views/contact/contact.php';
        if ( file_exists( $template ) ) {
            include $template;
        }
    }

}