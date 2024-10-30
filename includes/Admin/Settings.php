<?php

namespace DigitalLab\SMSNet24\Admin;

use DigitalLab\SMSNet24\Traits\Form_Error;

/**
 * Settings Handler class
 */
class Settings {

    use Form_Error;

    /**
     * Plugin page handler
     *
     * @return void
     */
    public function plugin_page() {
        $settings = smsnet_get_settings();
        $template = __DIR__ . '/views/settings/settings.php';

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    /**
     * Handle the form
     *
     * @return void
     */
    public function form_handler() {
        if ( ! isset( $_POST['submit_settings'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'smsnet-settings-update' ) ) {
            wp_die( 'Are you cheating?' );
        }

        /*if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Are you cheating?' );
        }*/

        $consumer_key      = isset( $_POST['consumer_key'] ) ? sanitize_text_field( $_POST['consumer_key'] )              : '';
        $consumer_secret   = isset( $_POST['consumer_secret'] ) ? sanitize_textarea_field( $_POST['consumer_secret'] )    : '';
        $sms_url           = isset( $_POST['sms_url'] ) ? sanitize_textarea_field( $_POST['sms_url'] )                    : '';
        $sms_user_id       = isset( $_POST['sms_user_id'] ) ? sanitize_textarea_field( $_POST['sms_user_id'] )            : '';
        $sms_user_password = isset( $_POST['sms_user_password'] ) ? sanitize_textarea_field( $_POST['sms_user_password'] ): '';
        $otp_length        = isset( $_POST['otp_length'] ) ? sanitize_text_field( $_POST['otp_length'] )                  : '';
        $otp_expiry_type   = isset( $_POST['otp_expiry_type'] ) ? sanitize_text_field( $_POST['otp_expiry_type'] )        : '';
        $otp_expiry_value  = isset( $_POST['otp_expiry_value'] ) ? sanitize_text_field( $_POST['otp_expiry_value'] )      : '';

        if ( empty( $consumer_key ) ) {
            $this->errors['consumer_key'] = __( 'Please provide a consumer key', 'smsnet-24' );
        }

        if ( empty( $consumer_secret ) ) {
            $this->errors['consumer_secret'] = __( 'Please provide a consumer secret', 'smsnet-24' );
        }

        if ( empty( $sms_url ) ) {
            $this->errors['sms_url'] = __( 'Please provide a SMS24 url', 'smsnet-24' );
        }

        if ( empty( $sms_user_id ) ) {
            $this->errors['sms_user_id'] = __( 'Please provide a SMS24 login id', 'smsnet-24' );
        }

        if ( empty( $sms_user_password ) ) {
            $this->errors['sms_user_password'] = __( 'Please provide a SMS24 password', 'smsnet-24' );
        }

        if ( empty( $otp_length ) ) {
            $this->errors['otp_length'] = __( 'Please provide a otp length', 'smsnet-24' );
        }

        if ( empty( $otp_expiry_type ) ) {
            $this->errors['otp_expiry_type'] = __( 'Please provide a otp expiry type', 'smsnet-24' );
        }

        if ( empty( $otp_expiry_value ) ) {
            $this->errors['otp_expiry_value'] = __( 'Please provide a otp expiry value', 'smsnet-24' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        $args = [
            'consumer_key'     => $consumer_key,
            'consumer_secret'  => $consumer_secret,
            'sms_url'          => $sms_url,
            'sms_user_id'      => $sms_user_id,
            'sms_user_password'=> $sms_user_password,
            'otp_length'       => $otp_length,
            'otp_expiry_type'  => $otp_expiry_type,
            'otp_expiry_value' => $otp_expiry_value,
        ];

        if ( $this->smsnet_update_setting( $args ) ) {
            $redirected_to = admin_url( 'admin.php?page=smsnet-setting&updated=true' );
        }else{
            $redirected_to = admin_url( 'admin.php?page=smsnet-setting&updated=false' );
        }

        wp_redirect( $redirected_to );

        exit;
    }


    /**
     * Handle the form
     *
     * @return boolean
     */
    public function smsnet_update_setting( $args = [] ) {
        global $wpdb;

        //truncate
        $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}smsnet_settings");

        //insert
        $data_to_be_inserted = [
            [
                'setting_key' => 'consumer_key',
                'setting_value' => $args['consumer_key'],
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'consumer_secret',
                'setting_value' => $args['consumer_secret'],
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'sms_url',
                'setting_value' => $args['sms_url'],
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'sms_user_id',
                'setting_value' => $args['sms_user_id'],
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'sms_user_password',
                'setting_value' => $args['sms_user_password'],
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'otp_length',
                'setting_value' => $args['otp_length'],
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'otp_expiry_type',
                'setting_value' => $args['otp_expiry_type'],
                'created_by' => get_current_user_id(),
                'created_at' => current_time( 'mysql' ),
            ],
            [
                'setting_key' => 'otp_expiry_value',
                'setting_value' => $args['otp_expiry_value'],
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

        if ( $wpdb->query( $sql ) ) {
            return true;
        }
        else{
            return false;
        }
    }
}