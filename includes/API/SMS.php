<?php


namespace DigitalLab\SMSNet24\API;

use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;


class SMS extends WP_REST_Controller
{

    /**
     * Initialize the class
     */
    function __construct()
    {
        $this->namespace = 'smsnet/v1';
        $this->rest_base = 'sms';
    }


    /**
     * Registers the routes for the objects of the controller.
     *
     * @return void
     */
    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/get-sms-settings',
            [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_sms_settings'],
                    'permission_callback' => [$this, 'create_otp_permissions_check'],
                    'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
                ]
            ]
        );
    }

    /**
     * Get the address, if the ID is valid.
     *
     * @param int $id Supplied ID.
     *
     * @return array|WP_Error|\WP_HTTP_Response|WP_REST_Response
     */
    public function get_sms_settings($request)
    {
        $data = $this->prepare_item_for_sms_settings($request);

        if (is_wp_error($data)) {
            return $data;
        }

        $settings = get_sms_settings($data);

        if ( is_wp_error( $settings ) ) {
            $settings->add_data(['status' => 400]);
            return $settings;
        }

        if ( !$settings ) {
            return new WP_Error(
                'rest_no_data_found',
                __('No data found'),
                ['status' => 404]
            );
        }

        $response = $this->prepare_item_for_response($settings, $request);
        return rest_ensure_response($response);
    }

    /**
     * Prepares one item for create or update operation.
     *
     * @param \WP_REST_Request $request
     *
     * @return array
     */
    protected function prepare_item_for_sms_settings($request)
    {
        $prepared = [];

        if (isset($request['consumer_key'])) {
            $prepared['consumer_key'] = $request['consumer_key'];
        }

        if (isset($request['consumer_secret'])) {
            $prepared['consumer_secret'] = $request['consumer_secret'];
        }

        return $prepared;
    }

    /**
     * Prepares the item for the REST response.
     *
     * @param mixed $item WordPress representation of the item.
     * @param \WP_REST_Request $request Request object.
     *
     * @return \WP_Error|WP_REST_Response
     */
    public function prepare_item_for_response($item, $request)
    {
        $data = [];

        $data['sms_url']           = $item['sms_url'];
        $data['sms_user_id']       = $item['sms_user_id'];
        $data['sms_user_password'] = $item['sms_user_password'];

        $context = !empty($request['context']) ? $request['context'] : 'view';
        $data = $this->filter_response_by_context($data, $context);
        return rest_ensure_response($data);
    }


    /**
     * Checks if a given request has access to create items.
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|bool
     */
    public function create_otp_permissions_check($request)
    {
        return true;

        /*if ( current_user_can( 'manage_options' ) ) {
            return true;
        }
        return false;*/
    }
}