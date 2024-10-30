<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php _e( 'Guideline', 'smsnet-24' ); ?>
    </h1>

    <h2><code>POST</code> Get SMS Settings</h2>
    <code>
        https:://your-project-name/wp-json/smsnet/v1/otp/get-sms-settings
    </code>

    <table style="margin-top: 15px" border="1" width="70%">
        <thead>
        <tr>
            <th colspan="2">
                <?php _e( 'Form Data', 'smsnet-24' ); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php _e( 'consumer_key', 'smsnet-24' ); ?>
            </td>
            <td>ck_239d0ca3054a0009f39e006ce232261793</td>
        </tr>
        <tr>
            <td>
                <?php _e( 'consumer_secret', 'smsnet-24' ); ?>
            </td>
            <td>cs_94868b0b36ea2e07002709cd</td>
        </tr>
        </tbody>
    </table>

    <h2><code>POST</code> Create OTP</h2>
    <code>
        https:://your-project-name/wp-json/smsnet/v1/otp/create
    </code>

    <table style="margin-top: 15px" border="1" width="70%">
        <thead>
        <tr>
            <th colspan="2">
                <?php _e( 'Form Data', 'smsnet-24' ); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php _e( 'consumer_key', 'smsnet-24' ); ?>
            </td>
            <td>ck_239d0ca3054a0009f39e006ce232261793</td>
        </tr>
        <tr>
            <td>
                <?php _e( 'consumer_secret', 'smsnet-24' ); ?>
            </td>
            <td>cs_94868b0b36ea2e07002709cd</td>
        </tr>
        <tr>
            <td>
                <?php _e( 'phone', 'smsnet-24' ); ?>
            </td>
            <td>01837210137</td>
        </tr>
        </tbody>
    </table>

    <h2><code>POST</code> Check OTP</h2>
    <code>
        https:://your-project-name/wp-json/smsnet/v1/otp/check-otp
    </code>

    <table style="margin-top: 15px" border="1" width="70%">
        <thead>
        <tr>
            <th colspan="2">
                <?php _e( 'Form Data', 'smsnet-24' ); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php _e( 'consumer_key', 'smsnet-24' ); ?>
            </td>
            <td>ck_239d0ca3054a0009f39e006ce232261793</td>
        </tr>
        <tr>
            <td>
                <?php _e( 'consumer_secret', 'smsnet-24' ); ?>
            </td>
            <td>cs_94868b0b36ea2e07002709cd</td>
        </tr>
        <tr>
            <td>
                <?php _e( 'phone', 'smsnet-24' ); ?>
            </td>
            <td>01837210137</td>
        </tr>
        <tr>
            <td>
                <?php _e( 'otp_code', 'smsnet-24' ); ?>
            </td>
            <td>9057</td>
        </tr>
        </tbody>
    </table>
</div>