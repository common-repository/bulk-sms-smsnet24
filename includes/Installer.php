<?php

namespace DigitalLab\SMSNet24;

/**
 * Installer class
 */
class Installer {

    /**
     * Run the installer
     *
     * @return void
     */
    public function run() {
        $this->add_version();
        $this->create_tables();
        $this->store_setting_table();
    }

    /**
     * Add time and version on DB
     */
    public function add_version() {
        $installed = get_option( 'smsnet_24_installed' );

        if ( ! $installed ) {
            update_option( 'smsnet_24_installed', time() );
        }

        update_option( 'smsnet_24_version', SMSNET_24_VERSION );
    }

    /**
     * Create necessary database tables
     *
     * @return void
     */
    public function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $settings_table_schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}smsnet_settings` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `setting_key` varchar(100) NOT NULL,
          `setting_value` varchar(255) NOT NULL,
          `created_by` bigint(20) unsigned NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate";

        $otps_table_schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}smsnet_otps` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `otp_code` varchar(100) NOT NULL,
          `phone` varchar(25) NOT NULL,
          `is_used` TINYINT(1) NOT NULL DEFAULT '0',
          `status` varchar(50) NOT NULL,
          `expiry_at` datetime NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $settings_table_schema );
        dbDelta( $otps_table_schema );
    }


    /**
     * Insert setting table necessary data
     *
     * @return void
     */
    public function store_setting_table(){
        global $wpdb;

        //truncate
        $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}smsnet_settings");

        //insert
        $data_to_be_inserted = [
            [
                'setting_key' => 'consumer_key',
                'setting_value' => 'ck_239d0ca3054a0009f39e006ce232261793',
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'consumer_secret',
                'setting_value' => 'cs_94868b0b36ea2e07002709cd',
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'sms_url',
                'setting_value' => 'http://smsnet24.com/',
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'sms_user_id',
                'setting_value' => 'admin',
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'sms_user_password',
                'setting_value' => 'admin',
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'otp_length',
                'setting_value' => '4',
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'otp_expiry_type',
                'setting_value' => 'minutes',
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'otp_expiry_value',
                'setting_value' => '5',
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
        ];

        $values = $place_holders = array();
        foreach($data_to_be_inserted as $data) {
            array_push( $values, $data['setting_key'], $data['setting_value'], $data['created_by'], $data['created_at'] );
            $place_holders[] = "( %s, %s, %d, %s )";
        }

        $query  = "INSERT INTO {$wpdb->prefix}smsnet_settings (`setting_key`, `setting_value`, `created_by`, `created_at`) VALUES ";
        $query .= implode( ', ', $place_holders );
        $sql    = $wpdb->prepare( "$query ", $values );
        $wpdb->query( $sql );
    }
}