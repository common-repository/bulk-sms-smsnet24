<div class="wrap">

    <h1 class="wp-heading-inline">
        <?php _e( 'General Settings', 'smsnet-24' ); ?>
    </h1>

    <?php if ( isset( $_GET['updated'] ) && $_GET['updated'] ) { ?>
        <div class="notice notice-success">
            <p><?php _e( 'Setting has been updated successfully!', 'smsnet-24' ); ?></p>
        </div>
    <?php } elseif ( isset( $_GET['updated'] ) && !$_GET['updated'] ) { ?>
        <div class="notice notice-danger">
            <p><?php _e( 'Setting has not been updated', 'smsnet-24' ); ?></p>
        </div>
    <?php } ?>

    <form action="" method="post">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row" colspan="2">
                    <h3 class="wp-heading-inline">
                        <?php _e( 'API Keys', 'smsnet-24' ); ?>
                    </h3>
                </th>
            </tr>

            <tr>
                <th scope="row"><label for="consumer_key"><?php _e( 'CONSUMER KEY', 'smsnet-24' ); ?></label></th>
                <td>
                    <input name="consumer_key" type="text" id="consumer_key" value="<?php echo esc_attr( $settings['consumer_key'] ); ?>" class="regular-text">
                    <?php if ( $this->has_error( 'consumer_key' ) ) { ?>
                        <p class="description error"><?php echo $this->get_error( 'consumer_key' ); ?></p>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="consumer_secret"><?php _e( 'CONSUMER SECRET', 'smsnet-24' ); ?></label></th>
                <td>
                    <input name="consumer_secret" type="text" id="consumer_secret" value="<?php echo esc_attr( $settings['consumer_secret'] ); ?>" class="regular-text">
                    <?php if ( $this->has_error( 'consumer_secret' ) ) { ?>
                        <p class="description error"><?php echo $this->get_error( 'consumer_secret' ); ?></p>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <th scope="row" colspan="2">
                    <h3 class="wp-heading-inline">
                        <?php _e( 'SMS Setting', 'smsnet-24' ); ?>
                    </h3>
                </th>
            </tr>

            <tr>
                <th scope="row"><label for="sms_url"><?php _e( 'SMS URL', 'smsnet-24' ); ?></label></th>
                <td>
                    <input name="sms_url" type="text" id="sms_url" value="<?php echo esc_attr( $settings['sms_url'] ); ?>" class="regular-text">
                    <?php if ( $this->has_error( 'sms_url' ) ) { ?>
                        <p class="description error"><?php echo $this->get_error( 'sms_url' ); ?></p>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="sms_user_id"><?php _e( 'USER ID', 'smsnet-24' ); ?></label></th>
                <td>
                    <input name="sms_user_id" type="text" id="sms_user_id" value="<?php echo esc_attr( $settings['sms_user_id'] ); ?>" class="regular-text">
                    <?php if ( $this->has_error( 'sms_user_id' ) ) { ?>
                        <p class="description error"><?php echo $this->get_error( 'sms_user_id' ); ?></p>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="sms_user_password"><?php _e( 'USER PASSWORD', 'smsnet-24' ); ?></label></th>
                <td>
                    <input name="sms_user_password" type="text" id="sms_user_password" value="<?php echo esc_attr( $settings['sms_user_password'] ); ?>" class="regular-text">
                    <?php if ( $this->has_error( 'sms_user_password' ) ) { ?>
                        <p class="description error"><?php echo $this->get_error( 'sms_user_password' ); ?></p>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <th scope="row" colspan="2">
                    <h3 class="wp-heading-inline">
                        <?php _e( 'OTP Settings', 'smsnet-24' ); ?>
                    </h3>
                </th>
            </tr>

            <tr>
                <th scope="row"><label for="otp_length"><?php _e( 'OTP Length', 'smsnet-24' ); ?></label></th>
                <td>
                    <select name="otp_length" id="otp_length">
                        <option value="4" <?php echo esc_attr( $settings['otp_length'] ) == 4 ? 'selected':'' ?>>4 digit</option>
                        <option value="5" <?php echo esc_attr( $settings['otp_length'] ) == 5 ? 'selected':'' ?>>5 digit</option>
                        <option value="6" <?php echo esc_attr( $settings['otp_length'] ) == 6 ? 'selected':'' ?>>6 digit</option>
                        <option value="7" <?php echo esc_attr( $settings['otp_length'] ) == 7 ? 'selected':'' ?>>7 digit</option>
                        <option value="8" <?php echo esc_attr( $settings['otp_length'] ) == 8 ? 'selected':'' ?>>8 digit</option>
                        <option value="9" <?php echo esc_attr( $settings['otp_length'] ) == 9 ? 'selected':'' ?>>9 digit</option>
                        <option value="10" <?php echo esc_attr( $settings['otp_length'] ) == 10 ? 'selected':'' ?>>10 digit</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="otp_expiry_type"><?php _e( 'OTP Expiry Type', 'smsnet-24' ); ?></label></th>
                <td>
                    <select name="otp_expiry_type" id="otp_expiry_type">
                        <option value="minutes" <?php echo esc_attr( $settings['otp_expiry_type'] ) == 'minutes' ? 'selected':'' ?>>Minute</option>
                        <option value="hours" <?php echo esc_attr( $settings['otp_expiry_type'] ) == 'hours' ? 'selected':'' ?>>Hour</option>
                        <option value="days" <?php echo esc_attr( $settings['otp_expiry_type'] ) == 'days' ? 'selected':'' ?>>Day</option>
                        <option value="months" <?php echo esc_attr( $settings['otp_expiry_type'] ) == 'months' ? 'selected':'' ?>>Month</option>
                        <option value="years" <?php echo esc_attr( $settings['otp_expiry_type'] ) == 'years' ? 'selected':'' ?>>Year</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="otp_expiry_value"><?php _e( 'OTP Expiry Value', 'smsnet-24' ); ?></label></th>
                <td>
                    <input name="otp_expiry_value" type="number" id="otp_expiry_value" value="<?php echo esc_attr( $settings['otp_expiry_value'] ); ?>" class="regular-text">
                    <?php if ( $this->has_error( 'otp_expiry_value' ) ) { ?>
                        <p class="description error"><?php echo $this->get_error( 'otp_expiry_value' ); ?></p>
                    <?php } ?>
                </td>
            </tr>
            </tbody>
        </table>

        <?php wp_nonce_field( 'smsnet-settings-update' ); ?>

        <?php submit_button( __( 'Save Changes', 'smsnet-24' ), 'primary', 'submit_settings' ); ?>
    </form>
</div>