<?php
/**
 * Plugin Name: Bulk SMS - SMSNET24
 * Description: SMSNET24.Com is a BULK SMS Service of DigitalLab. Bulk SMS is widely used in Bank, School,College, Universiy, Govt., Non Govt organization world wide. Our SMS gateway is specially designed for Bangladesh Mask SMS including world wide SMS service.Http API, Rest API is Free!! Our web panel is very easy to use and support responsive design for mobile/Tab/Laptop PC browser. Support file to SMS, Phonebook to SMS, Copy to SMS, Android Apps, Iphone(IOS) Apps.
 * Plugin URI: http://smsnet24.com
 * Author: Digital Lab
 * Author URI: http://www.digitallabbd.com
 * Version: 1.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class SMSNet24 {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
     * Class constructor
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \SMSNet24
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'SMSNET_24_VERSION', self::version );
        define( 'SMSNET_24_FILE', __FILE__ );
        define( 'SMSNET_24_PATH', __DIR__ );
        define( 'SMSNET_24_URL', plugins_url( '', SMSNET_24_FILE ) );
        define( 'SMSNET_24_ASSETS', SMSNET_24_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {

        //new DigitalLab\SMSNet24\Assets();
        if ( is_admin() ) {
            new DigitalLab\SMSNet24\Admin();
        }

        new DigitalLab\SMSNet24\API();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installer = new DigitalLab\SMSNet24\Installer();
        $installer->run();
    }
}

function smsnet_24() {
    return SMSNet24::init();
}

// kick-off the plugin
smsnet_24();