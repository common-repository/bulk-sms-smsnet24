<?php

namespace DigitalLab\SMSNet24;

/**
 * Assets handlers class
 */
class Assets {

    /**
     * Class constructor
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {
        return [
            'smsnet-24-script' => [
                'src'     => SMSNET_24_ASSETS . '/js/frontend.js',
                'version' => filemtime( SMSNET_24_PATH . '/assets/js/frontend.js' ),
            ]
        ];
    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {
        return [
            'smsnet-24-style' => [
                'src'     => SMSNET_24_ASSETS . '/css/frontend.css',
                'version' => filemtime( SMSNET_24_PATH . '/assets/css/frontend.css' )
            ],
            'smsnet-24-admin-style' => [
                'src'     => SMSNET_24_ASSETS . '/css/admin.css',
                'version' => filemtime( SMSNET_24_PATH . '/assets/css/admin.css' )
            ]
        ];
    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;

            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }
    }
}