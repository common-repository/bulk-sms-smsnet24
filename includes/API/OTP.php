<?php


namespace DigitalLab\SMSNet24\API;

use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;


class OTP extends WP_REST_Controller
{

    /**
     * Initialize the class
     */
    function __construct()
    {
        $this->namespace = 'smsnet/v1';
        $this->rest_base = 'otp';
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
            '/' . $this->rest_base . '/create',
            [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'create_otp'],
                    'permission_callback' => [$this, 'create_otp_permissions_check'],
                    'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
                ],
                'schema' => [$this, 'get_item_create_schema'],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/check-otp',
            [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'otp_validation'],
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
    public function otp_validation($request)
    {
        $data = $this->prepare_item_for_validation_otp($request);

        if (is_wp_error($data)) {
            return $data;
        }

        $otp = otp_validation($data);

        if (is_wp_error($otp)) {
            $otp->add_data(['status' => 400]);
            return $otp;
        }

        if (!$otp) {
            return new WP_Error(
                'rest_otp_invalid_code',
                __('Invalid OTP Code'),
                ['status' => 404]
            );
        }

        $response = $this->prepare_item_for_response($otp, $request);
        return rest_ensure_response($response);
    }

    /**
     * Prepares one item for create or update operation.
     *
     * @param \WP_REST_Request $request
     *
     * @return array
     */
    protected function prepare_item_for_validation_otp($request)
    {
        $prepared = [];

        if (isset($request['consumer_key'])) {
            $prepared['consumer_key'] = $request['consumer_key'];
        }

        if (isset($request['consumer_secret'])) {
            $prepared['consumer_secret'] = $request['consumer_secret'];
        }

        if (isset($request['phone'])) {
            $prepared['phone'] = $request['phone'];
        }

        if (isset($request['otp_code'])) {
            $prepared['otp_code'] = $request['otp_code'];
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

        $data['otp_code'] = $item->otp_code;
        $data['phone'] = $item->phone;
        $data['status'] = $item->status;
        $data['expiry_date'] = mysql_to_rfc3339($item->expiry_at);
        $data['created_date'] = mysql_to_rfc3339($item->created_at);

        $context = !empty($request['context']) ? $request['context'] : 'view';
        $data = $this->filter_response_by_context($data, $context);

        return rest_ensure_response($data);
    }

    /**
     * Creates one item from the collection.
     *
     * @param \WP_REST_Request $request
     *
     * @return array|int|WP_Error
     */
    public function create_otp($request)
    {
        //print_r($request->get_header('consumer_key'));
        $otp = $this->prepare_item_for_create_otp($request);

        if (is_wp_error($otp)) {
            return $otp;
        }

        $otp_id = smsnet_create_otp($request);

        if (is_wp_error($otp_id)) {
            $otp_id->add_data(['status' => 400]);

            return $otp_id;
        }

        $otp = $this->get_otp($otp_id);
        $response = $this->prepare_item_for_response($otp, $request);
        return rest_ensure_response($response);
    }

    /**
     * Prepares one item for create or update operation.
     *
     * @param \WP_REST_Request $request
     *
     * @return array
     */
    protected function prepare_item_for_create_otp($request)
    {
        $prepared = [];

        if (isset($request['consumer_key'])) {
            $prepared['consumer_key'] = $request['consumer_key'];
        }

        if (isset($request['consumer_secret'])) {
            $prepared['consumer_secret'] = $request['consumer_secret'];
        }

        if (isset($request['phone'])) {
            $prepared['phone'] = $request['phone'];
        }

        return $prepared;
    }

    /**
     * Get the address, if the ID is valid.
     *
     * @param int $id Supplied ID.
     *
     * @return Object|\WP_Error
     */
    protected function get_otp($id)
    {
        $otp = smsnet_get_otp($id);
        if (!$otp) {
            return new WP_Error(
                'rest_otp_invalid_id',
                __('Invalid OTP ID'),
                ['status' => 404]
            );
        }
        return $otp;
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

    /**
     * Retrieves the contact schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_create_schema()
    {
        if ($this->schema) {
            return $this->add_additional_fields_schema($this->schema);
        }

        $schema = [
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'OTP',
            'type' => 'object',
            'properties' => [
                'id' => [
                    'description' => __('Unique identifier for the object.'),
                    'type' => 'integer',
                    'context' => ['view'],
                    'readonly' => true,
                ],
                'otp_code' => [
                    'description' => __('Code of the OTP.'),
                    'type' => 'string',
                    'context' => ['view'],
                    'readonly' => true,
                ],
                'phone' => [
                    'description' => __('Phone number of the OTP.'),
                    'type' => 'string',
                    'context' => ['view'],
                    'required' => true,
                    'arg_options' => [
                        'sanitize_callback' => 'sanitize_textarea_field',
                    ],
                ],
                'status' => [
                    'description' => __('Status of the OTP.'),
                    'type' => 'string',
                    'context' => ['view'],
                    'readonly' => true,
                ],
                'expiry_at' => [
                    'description' => __("Expiry date of the OTP"),
                    'type' => 'string',
                    'format' => 'date-time',
                    'context' => ['view'],
                    'readonly' => true,
                ],
                'created_at' => [
                    'description' => __("Created date of the OTP"),
                    'type' => 'string',
                    'format' => 'date-time',
                    'context' => ['view'],
                    'readonly' => true,
                ],
            ]
        ];

        $this->schema = $schema;

        return $this->add_additional_fields_schema($this->schema);
    }
}