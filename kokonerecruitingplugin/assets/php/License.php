<?php

namespace KRP\SDK;

use ErrorException;

if (!class_exists('KRP\SDK\License')) {

    class License
    {
        private $plugin_name;
        private $api_url;
        private $customer_key;
        private $customer_secret;
        private $valid_status;
        private $product_ids;
        private $stored_license;
        private $valid_object;
        private $ttl;

        public function __construct(
            $plugin_name,
            $server_url,
            $customer_key,
            $customer_secret,
            $product_ids,
            $license_options,
            $valid_object,
            $ttl
        )
        {
            $this->plugin_name = $plugin_name;
            $this->api_url = "{$server_url}/wp-json/lmfwc/v2/";
            $this->customer_key = $customer_key;
            $this->customer_secret = $customer_secret;
            $this->product_ids = is_array($product_ids) ? $product_ids : [$product_ids];

            $this->stored_license = null;
            if (isset($license_options['settings_key'])) {
                $license = get_option($license_options['settings_key']);
                if ($license !== false) {
                    $this->stored_license = $license[$license_options['option_key']];
                }
            } elseif (isset($license_options['option_key'])) {
                $this->stored_license = get_option($license_options['option_key']);
            }

            $this->valid_object = $valid_object;
            $this->ttl = $ttl;
            $this->valid_status = get_option($valid_object, []);
        }

        private function call($endpoint, $method = 'GET', $args = '')
        {
            $url = "{$this->api_url}{$endpoint}?consumer_key={$this->customer_key}&consumer_secret={$this->customer_secret}";

            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json; charset=UTF-8',
            ];

            $wp_args = [
                'headers' => $headers,
                'method' => $method,
                'timeout' => 5
            ];

            if (!empty($args)) {
                $wp_args['body'] = $args;
            }

            $res = wp_remote_request($url, $wp_args);

            if (!is_wp_error($res) && ($res['response']['code'] == 200 || $res['response']['code'] == 201)) {
                return json_decode($res['body'], TRUE);
            } elseif (is_wp_error($res)) {
                throw new ErrorException('Unknown error', 500);
            } else {
                $response = json_decode($res['body'], TRUE);
                throw new ErrorException($response['message'], $response['data']['status']);
            }
        }

        public function activate($license_key)
        {
            $license = null;
            if (!empty($license_key)) {
                $response = $this->call("licenses/activate/{$license_key}");
                if (isset($response['success']) && $response['success'] === true) {
                    $license = $response['data'];
                } else {
                    $this->valid_status['is_valid'] = false;
                    $this->valid_status['error'] = $response['message'];
                    $this->valid_status['nextValidation'] = time();
                    update_option($this->valid_object, $this->valid_status);
                    throw new ErrorException($response['message']);
                }
            }

            return $license;
        }

        public function deactivate($license_key)
        {
            if (!empty($license_key)) {
                $this->call("licenses/deactivate/{$license_key}");
            }
            delete_option($this->valid_object);
        }

        public function validate_status($license_key = '')
        {
            $valid_result = [
                'is_valid' => false,
                'error' => __('The license has not been activated yet', $this->plugin_name),
            ];

            $current_time = time();

            if (empty($license_key) && isset($this->valid_status['nextValidation']) && $this->valid_status['nextValidation'] > $current_time) {
                $valid_result['is_valid'] = $this->valid_status['is_valid'];
                $valid_result['error'] = $this->valid_status['error'];
            } else {
                if (empty($license_key)) {
                    $license_key = $this->stored_license;
                }

                if (empty($license_key)) {
                    $valid_result['error'] = __('A license has not been submitted', $this->plugin_name);
                } else {
                    try {
                        $response = $this->call("licenses/{$license_key}");
                        if (isset($response['success']) && $response['success'] === true) {
                            $this->valid_status['valid_until'] = ($response['data']['expiresAt'] !== null) ? strtotime($response['data']['expiresAt']) : null;

                            if (!empty($this->product_ids) && !in_array($response['data']['productId'], $this->product_ids)) {
                                $valid_result['error'] = __('The license entered does not belong to this plugin', $this->plugin_name);
                            } elseif ($this->valid_status['valid_until'] !== null && $this->valid_status['valid_until'] < time()) {
                                $valid_result['error'] = __('The license entered is expired', $this->plugin_name);
                            } else {
                                $valid_result['is_valid'] = true;
                                $valid_result['error'] = '';
                            }
                        }
                    } catch (ErrorException $exception) {
                        $valid_result['error'] = $exception->getMessage();
                    }
                }
            }

            $this->valid_status['nextValidation'] = strtotime(date('Y-m-d') . "+ {$this->ttl} days");
            $this->valid_status['is_valid'] = $valid_result['is_valid'];
            $this->valid_status['error'] = $valid_result['error'];
            update_option($this->valid_object, $this->valid_status);

            return $valid_result;
        }

        public function valid_until()
        {
            return isset($this->valid_status['valid_until']) ? $this->valid_status['valid_until'] : null;
        }
    }
}
