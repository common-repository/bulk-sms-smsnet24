<?php
namespace DigitalLab\SMSNet24\Admin;

/**
 * Contact Handler class
 */
class Guideline {

    /**
     * Plugin page handler
     *
     * @return void
     */
    public function plugin_page() {
        $template = __DIR__ . '/views/guideline/guideline.php';
        if ( file_exists( $template ) ) {
            include $template;
        }
    }

}