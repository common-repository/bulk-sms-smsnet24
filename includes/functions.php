<?php
/*
   |--------------------------------------------------------------------------
   | common
   |--------------------------------------------------------------------------
*/

/**
 * generate OTP code
 *
 * @param  int $length
 *
 * @return string
 */
function smsnet_generate_numeric_OTP( $length ) {
    $generator = "01357902468";
    $result = "";
    for ($i = 1; $i <= $length; $i++) {
        $result .= substr($generator, (rand()%(strlen($generator))), 1);
    }
    return $result;
}

/*
   |--------------------------------------------------------------------------
   | setting
   |--------------------------------------------------------------------------
*/

/**
 * Fetch a single contact from the DB
 *
 * @return array
 */
function smsnet_get_settings() {
    global $wpdb;

    $sql = $wpdb->prepare( "SELECT setting_key,setting_value FROM {$wpdb->prefix}smsnet_settings");

    $setting_list = $wpdb->get_results( $sql );

    $settings = [];

    foreach ( $setting_list as $setting ) {
        $settings[$setting->setting_key] = $setting->setting_value;
    }

    return $settings;
}

/**
 * Fetch a single contact from the DB
 *
 * @return array|WP_Error
 */
function get_sms_settings( $args = [] ) {
    if ( empty( $args['consumer_key'] ) ) {
        return new \WP_Error( 'no-consumer-key', __( 'You must provide a consumer key', 'smsnet-24' ) );
    }

    if ( empty( $args['consumer_secret'] ) ) {
        return new \WP_Error( 'no-consumer-secret', __( 'You must provide a consumer secret', 'smsnet-24' ) );
    }

    $settings = smsnet_get_settings();

    if ( $args['consumer_key'] != $settings['consumer_key'] ) {
        return new \WP_Error( 'no-match-consumer-key', __( 'You must provide a valid consumer key', 'smsnet-24' ) );
    }

    if ( $args['consumer_secret'] != $settings['consumer_secret'] ) {
        return new \WP_Error( 'no-match-consumer-secret', __( 'You must provide a valid consumer secret key', 'smsnet-24' ) );
    }

    $sms_settings['sms_url'] = $settings['sms_url'];
    $sms_settings['sms_user_id'] = $settings['sms_user_id'];
    $sms_settings['sms_user_password'] = $settings['sms_user_password'];

    return $sms_settings;
}

/*
   |--------------------------------------------------------------------------
   | OTP
   |--------------------------------------------------------------------------
*/

/**
 * Insert a new address
 *
 * @param  array  $args
 *
 * @return int|WP_Error
 */
function smsnet_create_otp( $args = [] ) {
    global $wpdb;

    if ( empty( $args['consumer_key'] ) ) {
        return new \WP_Error( 'no-consumer-key', __( 'You must provide a consumer key', 'smsnet-24' ) );
    }

    if ( empty( $args['consumer_secret'] ) ) {
        return new \WP_Error( 'no-consumer-secret', __( 'You must provide a consumer secret', 'smsnet-24' ) );
    }

    if ( empty( $args['phone'] ) ) {
        return new \WP_Error( 'no-phone', __( 'You must provide a phone number', 'smsnet-24' ) );
    }

    if ( strlen($args['phone']) <= 5 ) {
        return new \WP_Error( 'invalid-phone-length', __( 'You must provide a valid length phone number', 'smsnet-24' ) );
    }

    $settings = smsnet_get_settings();

    if ( $args['consumer_key'] != $settings['consumer_key'] ) {
        return new \WP_Error( 'no-match-consumer-key', __( 'You must provide a valid consumer key', 'smsnet-24' ) );
    }

    if ( $args['consumer_secret'] != $settings['consumer_secret'] ) {
        return new \WP_Error( 'no-match-consumer-secret', __( 'You must provide a valid consumer secret key', 'smsnet-24' ) );
    }

    //update
    $wpdb->update(
        $wpdb->prefix . 'smsnet_otps',
        ['status'     => 'invalid',],
        [ 'phone' => $args['phone'] ],
        [ '%s' ],
        [ '%s' ]
    );

    //insert
    $otp_expiry_type  = $settings['otp_expiry_type'];
    $otp_expiry_value = $settings['otp_expiry_value'];

    $data = [
        'otp_code'   => smsnet_generate_numeric_OTP($settings['otp_length']),
        'phone'      => $args['phone'],
        'is_used'     => 0,
        'status'     => 'valid',
        'expiry_at'  => date( 'Y-m-d H:i:s', strtotime('+'.$otp_expiry_value.' '.$otp_expiry_type)),
        'created_at' => date( 'Y-m-d H:i:s'),
    ];

    $inserted = $wpdb->insert(
        $wpdb->prefix . 'smsnet_otps',
        $data,
        [
            '%s',
            '%s',
            '%d',
            '%s',
            '%s',
            '%s'
        ]
    );

    if ( ! $inserted ) {
        return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'smsnet-24' ) );
    }

    return $wpdb->insert_id;
}

/**
 * Fetch a single contact from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function smsnet_get_otp( $id ) {
    global $wpdb;

    return $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}smsnet_otps WHERE id = %d", $id )
    );
}

/**
 * Fetch a single contact from the DB
 *
 * @param  int $id
 *
 * @return object
 */
function otp_validation( $args = [] ) {
    global $wpdb;

    if ( empty( $args['consumer_key'] ) ) {
        return new \WP_Error( 'no-consumer-key', __( 'You must provide a consumer key', 'smsnet-24' ) );
    }

    if ( empty( $args['consumer_secret'] ) ) {
        return new \WP_Error( 'no-consumer-secret', __( 'You must provide a consumer secret', 'smsnet-24' ) );
    }

    if ( empty( $args['phone'] ) ) {
        return new \WP_Error( 'no-phone', __( 'You must provide a phone number', 'smsnet-24' ) );
    }

    if ( empty( $args['otp_code'] ) ) {
        return new \WP_Error( 'no-otp-code', __( 'You must provide a otp code', 'smsnet-24' ) );
    }
    
    $current_datetime = date( 'Y-m-d H:i:s');

    return $wpdb->get_row(
        $wpdb->prepare( "SELECT otp_code,phone,status,expiry_at,created_at FROM {$wpdb->prefix}smsnet_otps WHERE status = %s AND phone = %s AND otp_code = %s AND created_at <= %s AND expiry_at >= %s ORDER BY id DESC", 'valid', $args['phone'], $args['otp_code'], $current_datetime, $current_datetime )
    );
}

