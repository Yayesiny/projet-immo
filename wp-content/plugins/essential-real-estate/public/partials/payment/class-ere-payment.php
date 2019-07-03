<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Payment')) {
    /**
     * Class ERE_Payment
     */
    class ERE_Payment
    {
        protected $ere_invoice;
        protected $ere_package;
        protected $ere_trans_log;

        /**
         * Construct
         */
        public function __construct()
        {
            $this->ere_package = new ERE_Package();
            $this->ere_invoice = new ERE_Invoice();
            $this->ere_trans_log = new ERE_Trans_Log();
        }

        /**
         * Payment package by stripe
         * @param $package_id
         */
        public function stripe_payment_per_package($package_id)
        {
            require_once(ERE_PLUGIN_DIR . 'public/partials/payment/stripe-php/init.php');
            $stripe_secret_key = ere_get_option('stripe_secret_key');
            $stripe_publishable_key = ere_get_option('stripe_publishable_key');

            $current_user = wp_get_current_user();

            $user_id = $current_user->ID;
            $user_email = get_the_author_meta('user_email', $user_id);

            $stripe = array(
                "secret_key" => $stripe_secret_key,
                "publishable_key" => $stripe_publishable_key
            );

            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            $package_price = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_price', true);
            $package_name = get_the_title($package_id);

            $currency_code = ere_get_option('currency_code', 'USD');
            $package_price = $package_price * 100;
            $payment_completed_link = ere_get_permalink('payment_completed');
            $stripe_processor_link = add_query_arg(array('payment_method' => 2), $payment_completed_link);
            print '<form action="' . $stripe_processor_link . '" method="post" id="ere_stripe_per_package">
            <div class="ere_stripe_per_package ere_payment_stripe" id="' . sanitize_title($package_name) . '">
                <script src="https://checkout.stripe.com/checkout.js" id="stripe_script"
                class="stripe-button"
                data-key="' . $stripe_publishable_key . '"
                data-amount="' . $package_price . '"
                data-email="' . $user_email . '"
                data-currency="' . $currency_code . '"
                data-zip-code="true"
                data-billing-address="true"
                data-label="' . __('Pay with Credit Card', 'essential-real-estate') . '"
                data-description="' . $package_name . ' ' . __('Package Payment', 'essential-real-estate') . '">
                </script>
            </div>
            <input type="hidden" id="package_id" name="package_id" value="' . $package_id . '">
            <input type="hidden" name="user_id" value="' . $user_id . '">
            <input type="hidden" id="payment_money" name="payment_money" value="' . $package_price . '">
            </form>';
        }

        /**
         * Payment upgrade listing by stripe
         * @param $property_id
         * @param $price_featured_submission
         */
        public function stripe_payment_upgrade_listing($property_id, $price_featured_submission)
        {
            require_once(ERE_PLUGIN_DIR . 'public/partials/payment/stripe-php/init.php');
            $stripe_secret_key = ere_get_option('stripe_secret_key');
            $stripe_publishable_key = ere_get_option('stripe_publishable_key');

            $stripe = array(
                "secret_key" => $stripe_secret_key,
                "publishable_key" => $stripe_publishable_key
            );
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            $currency_code = ere_get_option('currency_code', 'USD');
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $user_email = $current_user->user_email;
            $price_submission = $price_featured_submission * 100;
            print '<div class="ere_stripe_upgrade_listing ere_payment_stripe">
                <script src="https://checkout.stripe.com/checkout.js"
                class="stripe-button"
                data-key="' . $stripe_publishable_key . '"
                data-amount="' . $price_submission . '"
                data-email="' . $user_email . '"
                data-zip-code="true"
                data-currency="' . $currency_code . '"
                data-label="' . esc_html__('Upgrade to Featured', 'essential-real-estate') . '"
                data-description="' . esc_html__('Upgrade to Featured', 'essential-real-estate') . '">
                </script>
            </div>
            <input type="hidden" id="property_id" name="property_id" value="' . $property_id . '">
            <input type="hidden" id="payment_for" name="payment_for" value="3">
            <input type="hidden" name="user_id" value="' . $user_id . '">
            <input type="hidden" id="payment_money" name="payment_money" value="' . $price_submission . '">';
        }

        /**
         * Payment per listing by stripe
         * @param $property_id
         * @param $price_submission
         */
        public function stripe_payment_per_listing($property_id, $price_submission)
        {
            require_once(ERE_PLUGIN_DIR . 'public/partials/payment/stripe-php/init.php');
            $stripe_secret_key = ere_get_option('stripe_secret_key');
            $stripe_publishable_key = ere_get_option('stripe_publishable_key');

            $stripe = array(
                "secret_key" => $stripe_secret_key,
                "publishable_key" => $stripe_publishable_key
            );
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            $currency_code = ere_get_option('currency_code', 'USD');
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $user_email = $current_user->user_email;
            $price_submission = $price_submission * 100;

            $payment_completed_link = ere_get_permalink('payment_completed');
            $stripe_processor_link = add_query_arg(array('payment_method' => 2), $payment_completed_link);
            print '<form action="' . $stripe_processor_link . '" method="post" id="ere_stripe_per_listing">
            <div class="ere_stripe_per_listing ere_payment_stripe">
                <script src="https://checkout.stripe.com/checkout.js"
                class="stripe-button"
                data-key="' . $stripe_publishable_key . '"
                data-amount="' . $price_submission . '"
                data-email="' . $user_email . '"
                data-zip-code="true"
                data-currency="' . $currency_code . '"
                data-label="' . esc_html__('Submission Property', 'essential-real-estate') . '"
                data-description="' . esc_html__('Submission Property', 'essential-real-estate') . '">
                </script>
            </div>
            <input type="hidden" id="property_id" name="property_id" value="' . $property_id . '">
            <input type="hidden" id="payment_for" name="payment_for" value="1">
            <input type="hidden" name="user_id" value="' . $user_id . '">
            <input type="hidden" id="payment_money" name="payment_money" value="' . $price_submission . '">
             </form>';
        }

        /**
         * Get paypal access token
         * @param $url
         * @param $postArgs
         * @return mixed
         */
        private function get_paypal_access_token($url, $postArgs)
        {
            $client_id = ere_get_option('paypal_client_id');
            $secret_key = ere_get_option('paypal_client_secret_key');

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_USERPWD, $client_id . ":" . $secret_key);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);
            $response = curl_exec($curl);
            if (empty($response)) {
                die(curl_error($curl));
                curl_close($curl);
            } else {
                $info = curl_getinfo($curl);
                curl_close($curl);
                if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                    echo "Received error: " . $info['http_code'] . "\n";
                    echo "Raw response:" . $response . "\n";
                    die();
                }
            }
            $response = json_decode($response);
            return $response->access_token;
        }

        /**
         * Execute paypal request
         * @param $url
         * @param $jsonData
         * @param $access_token
         * @return array|mixed|object
         */
        private function execute_paypal_request($url, $jsonData, $access_token)
        {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $access_token,
                'Accept: application/json',
                'Content-Type: application/json'
            ));

            curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
            $response = curl_exec($curl);
            if (empty($response)) {
                die(curl_error($curl));
                curl_close($curl);
            } else {
                $info = curl_getinfo($curl);
                curl_close($curl);
                if ($info['http_code'] != 200 && $info['http_code'] != 201) {
                    echo "Received error: " . $info['http_code'] . "\n";
                    echo "Raw response:" . $response . "\n";
                    die();
                }
            }
            $jsonResponse = json_decode($response, TRUE);
            return $jsonResponse;
        }

        /**
         * Payment per listing by Paypal
         */
        public function paypal_payment_per_listing_ajax()
        {
            check_ajax_referer('ere_payment_ajax_nonce', 'ere_security_payment');
            global $current_user;
            $property_id = $_POST['property_id'];
            $property_id = intval($property_id);

            $payment_for = $_POST['payment_for'];
            $payment_for = intval($payment_for);
            $price_per_submission = ere_get_option('price_per_listing', '0');
            $price_featured_submission = ere_get_option('price_featured_listing', '0');
            $currency = ere_get_option('currency_code', 'USD');

            $blogInfo = esc_url(home_url());

            wp_get_current_user();
            $user_id = $current_user->ID;
            $post = get_post($property_id);

            if ($post->post_author != $user_id) {
                wp_die('No Permission');
            }

            $is_paypal_live = ere_get_option('paypal_api');
            $host = 'https://api.sandbox.paypal.com';
            $price_per_submission = floatval($price_per_submission);
            $price_featured_submission = floatval($price_featured_submission);
            $submission_curency = esc_html($currency);
            $payment_description = esc_html__('Listing payment on ', 'essential-real-estate') . $blogInfo;
            $total_price = $price_per_submission;
            if ($payment_for == 1) {
                $total_price = number_format($price_per_submission, 2, '.', '');
            } elseif ($payment_for == 2) {
                $total_price = $price_per_submission + $price_featured_submission;
                $total_price = number_format($total_price, 2, '.', '');
            } elseif ($payment_for == 3) {
                $total_price = number_format($price_featured_submission, 2, '.', '');
                $payment_description = esc_html__('Upgrade to featured listing on ', 'essential-real-estate') . $blogInfo;
            }
            if ($is_paypal_live == 'live') {
                $host = 'https://api.paypal.com';
            }

            $url = $host . '/v1/oauth2/token';
            $postArgs = 'grant_type=client_credentials';

            $access_token = $this->get_paypal_access_token($url, $postArgs);
            $url = $host . '/v1/payments/payment';
            $cancel_link = ere_get_permalink('my_properties');
            $payment_completed_link = ere_get_permalink('payment_completed');
            $return_link = add_query_arg(array('payment_method' => 1), $payment_completed_link);
            $payment = array(
                'intent' => 'sale',
                "redirect_urls" => array(
                    "return_url" => $return_link,
                    "cancel_url" => $cancel_link
                ),
                'payer' => array("payment_method" => "paypal"),
            );

            $payment['transactions'][0] = array(
                'amount' => array(
                    'total' => $total_price,
                    'currency' => $submission_curency,
                    'details' => array(
                        'subtotal' => $total_price,
                        'tax' => '0.00',
                        'shipping' => '0.00'
                    )
                ),
                'description' => $payment_description
            );
            if ($payment_for == 3) {
                $payment['transactions'][0]['item_list']['items'][] = array(
                    'quantity' => '1',
                    'name' => esc_html__('Upgrade to Featured Listing', 'essential-real-estate'),
                    'price' => $total_price,
                    'currency' => $submission_curency,
                    'sku' => 'Upgrade Listing',
                );
            } elseif ($payment_for == 2) {
                $payment['transactions'][0]['item_list']['items'][] = array(
                    'quantity' => '1',
                    'name' => esc_html__('Listing with Featured Payment', 'essential-real-estate'),
                    'price' => $total_price,
                    'currency' => $submission_curency,
                    'sku' => 'Paid Listing with Featured',
                );
            } else {
                $payment['transactions'][0]['item_list']['items'][] = array(
                    'quantity' => '1',
                    'name' => esc_html__('Listing Payment', 'essential-real-estate'),
                    'price' => $total_price,
                    'currency' => $submission_curency,
                    'sku' => 'Paid Listing',
                );
            }
            $jsonEncode = json_encode($payment);
            $json_response = $this->execute_paypal_request($url, $jsonEncode, $access_token);
            $payment_approval_url = $payment_execute_url = '';
            foreach ($json_response['links'] as $link) {
                if ($link['rel'] == 'execute') {
                    $payment_execute_url = $link['href'];
                } else if ($link['rel'] == 'approval_url') {
                    $payment_approval_url = $link['href'];
                }
            }
            $output['payment_execute_url'] = $payment_execute_url;
            $output['access_token'] = $access_token;
            $output['property_id'] = $property_id;
            $output['payment_for'] = $payment_for;
            update_user_meta($user_id, ERE_METABOX_PREFIX . 'paypal_transfer', $output);
            print $payment_approval_url;
            wp_die();
        }

        /**
         * Payment per package by Paypal
         */
        public function paypal_payment_per_package_ajax()
        {
            check_ajax_referer('ere_payment_ajax_nonce', 'ere_security_payment');
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;

            $blogInfo = esc_url(home_url());

            $package_id = $_POST['package_id'];
            $package_id = intval($package_id);
            $package_price = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_price', true);
            $package_name = get_the_title($package_id);

            if (empty($package_price) && empty($package_id)) {
                exit();
            }
            $currency = ere_get_option('currency_code', 'USD');
            $payment_description = $package_name . ' ' . esc_html__('Membership payment on ', 'essential-real-estate') . $blogInfo;
            $is_paypal_live = ere_get_option('paypal_api');
            $host = 'https://api.sandbox.paypal.com';
            if ($is_paypal_live == 'live') {
                $host = 'https://api.paypal.com';
            }
            $url = $host . '/v1/oauth2/token';
            $postArgs = 'grant_type=client_credentials';
            $access_token = $this->get_paypal_access_token($url, $postArgs);
            $url = $host . '/v1/payments/payment';
            $payment_completed_link = ere_get_permalink('payment_completed');
            $return_url = add_query_arg(array('payment_method' => 1), $payment_completed_link);
            $dash_profile_link = ere_get_permalink('my_properties');

            $payment = array(
                'intent' => 'sale',
                "redirect_urls" => array(
                    "return_url" => $return_url,
                    "cancel_url" => $dash_profile_link
                ),
                'payer' => array("payment_method" => "paypal"),
            );


            $payment['transactions'][0] = array(
                'amount' => array(
                    'total' => $package_price,
                    'currency' => $currency,
                    'details' => array(
                        'subtotal' => $package_price,
                        'tax' => '0.00',
                        'shipping' => '0.00'
                    )
                ),
                'description' => $payment_description
            );

            $payment['transactions'][0]['item_list']['items'][] = array(
                'quantity' => '1',
                'name' => esc_html__('Payment Package', 'essential-real-estate'),
                'price' => $package_price,
                'currency' => $currency,
                'sku' => $package_name . ' ' . esc_html__('Payment Package', 'essential-real-estate'),
            );

            $jsonEncode = json_encode($payment);
            $json_response = $this->execute_paypal_request($url, $jsonEncode, $access_token);
            $payment_approval_url = $payment_execute_url = '';
            foreach ($json_response['links'] as $link) {
                if ($link['rel'] == 'execute') {
                    $payment_execute_url = $link['href'];
                } else if ($link['rel'] == 'approval_url') {
                    $payment_approval_url = $link['href'];
                }
            }
            $output['payment_execute_url'] = $payment_execute_url;
            $output['access_token'] = $access_token;
            $output['package_id'] = $package_id;
            update_user_meta($user_id, ERE_METABOX_PREFIX . 'paypal_transfer', $output);

            print $payment_approval_url;
            wp_die();
        }

        /**
         * Payment per package by wire transfer
         */
        public function wire_transfer_per_package_ajax()
        {
            check_ajax_referer('ere_payment_ajax_nonce', 'ere_security_payment');
            global $current_user;
            $current_user = wp_get_current_user();

            if (!is_user_logged_in()) {
                exit('No Login');
            }
            $user_id = $current_user->ID;
            $user_email = $current_user->user_email;
            $admin_email = get_bloginfo('admin_email');
            $package_id = $_POST['package_id'];
            $package_id = intval($package_id);
            $total_price = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_price', true);
            $total_price = ere_get_format_money($total_price);
            $payment_method = 'Wire_Transfer';
            // insert invoice
            $invoice_id = $this->ere_invoice->insert_invoice('Package', $package_id, $user_id, 0, $payment_method, 0);
            $args = array(
                'invoice_no' => $invoice_id,
                'total_price' => $total_price
            );
            /*
             * Send email
             * */
            ere_send_email($user_email, 'mail_new_wire_transfer', $args);
            ere_send_email($admin_email, 'admin_mail_new_wire_transfer', $args);
            $payment_completed_link = ere_get_permalink('payment_completed');
            $return_link = add_query_arg(array('payment_method' => 3, 'order_id' => $invoice_id), $payment_completed_link);
            print $return_link;
            wp_die();
        }

        /**
         * Payment per listing by wire transfer
         */
        public function wire_transfer_per_listing_ajax()
        {
            check_ajax_referer('ere_payment_ajax_nonce', 'ere_security_payment');
            $current_user = wp_get_current_user();
            if (!is_user_logged_in()) {
                exit('No Login');
            }
            $user_id = $current_user->ID;
            $user_email = $current_user->user_email;
            $admin_email = get_bloginfo('admin_email');
            $property_id = $_POST['property_id'];
            $property_id = intval($property_id);

            $payment_for = $_POST['payment_for'];
            $payment_for = intval($payment_for);
            $payment_method = 'Wire_Transfer';
            if ($payment_for == 3) {
                $invoice_id = $this->ere_invoice->insert_invoice('Upgrade_To_Featured', $property_id, $user_id, 3, $payment_method, 0);
                $args = array(
                    'listing_title' => get_the_title($property_id),
                    'listing_id' => $property_id,
                    'invoice_no' => $invoice_id,
                );
                ere_send_email($user_email, 'mail_featured_submission_listing', $args);
                ere_send_email($admin_email, 'admin_mail_featured_submission_listing', $args);
            } else {
                if ($payment_for == 2) {
                    $invoice_id = $this->ere_invoice->insert_invoice('Listing_With_Featured', $property_id, $user_id, 2, $payment_method, 0);
                } else {
                    $invoice_id = $this->ere_invoice->insert_invoice('Listing', $property_id, $user_id, 1, $payment_method, 0);
                }
                $args = array(
                    'listing_title' => get_the_title($property_id),
                    'listing_id' => $property_id,
                    'invoice_no' => $invoice_id,
                );
                //xem l?i mail
                ere_send_email($user_email, 'mail_paid_submission_listing', $args);
                ere_send_email($admin_email, 'admin_mail_paid_submission_listing', $args);
            }
            $payment_completed_link = ere_get_permalink('payment_completed');
            $return_link = add_query_arg(array('payment_method' => 3, 'order_id' => $invoice_id), $payment_completed_link);
            print $return_link;
            wp_die();
        }

        /**
         * Free package
         */
        public function free_package_ajax()
        {
            check_ajax_referer('ere_payment_ajax_nonce', 'ere_security_payment');
            global $current_user;
            $current_user = wp_get_current_user();
            if (!is_user_logged_in()) {
                exit('No Login');
            }
            $user_id = $current_user->ID;
            $package_id = $_POST['package_id'];
            $package_id = intval($package_id);
            $payment_method = 'Free_Package';
            // insert invoice
            $invoice_id = $this->ere_invoice->insert_invoice('Package', $package_id, $user_id, 0, $payment_method, 1);

            $this->ere_package->insert_user_package($user_id, $package_id);
            update_user_meta($user_id, ERE_METABOX_PREFIX . 'free_package', 'yes');
            $payment_completed_link = ere_get_permalink('payment_completed');
            $return_link = add_query_arg(array('payment_method' => 3, 'free_package' => $invoice_id), $payment_completed_link);
            print $return_link;
            wp_die();
        }

        /**
         * stripe_payment_completed
         */
        public function stripe_payment_completed()
        {
            require_once(ERE_PLUGIN_DIR . 'public/partials/payment/stripe-php/init.php');
            $allowed_html = array();
            $paid_submission_type = ere_get_option('paid_submission_type');
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $user_email = $current_user->user_email;
            $admin_email = get_bloginfo('admin_email');
            $currency_code = ere_get_option('currency_code', 'USD');
            $payment_method = 'Stripe';
            $stripe_secret_key = ere_get_option('stripe_secret_key');
            $stripe_publishable_key = ere_get_option('stripe_publishable_key');
            $stripe = array(
                "secret_key" => $stripe_secret_key,
                "publishable_key" => $stripe_publishable_key
            );
            \Stripe\Stripe::setApiKey($stripe['secret_key']);
            $stripeEmail = '';
            if (is_email($_POST['stripeEmail'])) {
                $stripeEmail = wp_kses(esc_html($_POST['stripeEmail']), $allowed_html);
            } else {
                wp_die('None Mail');
            }

            if (isset($_POST['user_id']) && !is_numeric($_POST['user_id'])) {
                die();
            }

            if (isset($_POST['property_id']) && !is_numeric($_POST['property_id'])) {
                die();
            }

            if (isset($_POST['package_id']) && !is_numeric($_POST['package_id'])) {
                die();
            }

            if (isset($_POST['payment_money']) && !is_numeric($_POST['payment_money'])) {
                die();
            }

            if (isset($_POST['payment_for']) && !is_numeric($_POST['payment_for'])) {
                die();
            }
            $payment_for = 0;
            $paymentId = 0;
            if (isset($_POST['payment_for'])) {
                $payment_for = $_POST['payment_for'];
                $payment_for = intval($payment_for);
            }
            try {
                $token = wp_kses($_POST['stripeToken'], $allowed_html);
                $payment_money = $_POST['payment_money'];
                $payment_money = intval($payment_money);
                $customer = \Stripe\Customer::create(array(
                    "email" => $stripeEmail,
                    "source" => $token
                ));
                $charge = \Stripe\Charge::create(array(
                    "amount" => $payment_money,
                    'customer' => $customer->id,
                    "currency" => $currency_code,
                ));
                $payerId = $customer->id;
                if (isset($charge->id) && (!empty($charge->id))) {
                    $paymentId = $charge->id;
                }
                $payment_Status = '';
                if (isset($charge->status) && (!empty($charge->status))) {
                    $payment_Status = $charge->status;
                }

                if ($payment_Status == "succeeded") {
                    if ($paid_submission_type == 'per_listing') {
                        $price_per_listing = ere_get_option('price_per_listing', '0');
                        $price_featured_listing = ere_get_option('price_featured_listing', '0');
                        $price_per_listing_with_featured = intval($price_per_listing) + intval($price_featured_listing);
                        //Payment Stripe listing
                        $property_id = $_POST['property_id'];
                        $property_id = intval($property_id);
                        if ($payment_for == 3) {
                            if ($payment_money != intval($price_featured_listing) * 100) {
                                wp_die('No joke');
                                return;
                            }
                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 1);
                            $invoice_id = $this->ere_invoice->insert_invoice('Upgrade_To_Featured', $property_id, $user_id, 3, $payment_method, 1, $paymentId, $payerId);
                            $args = array(
                                'listing_title' => get_the_title($property_id),
                                'listing_id' => $property_id,
                                'invoice_no' => $invoice_id,
                            );
                            ere_send_email($user_email, 'mail_featured_submission_listing', $args);
                            ere_send_email($admin_email, 'admin_mail_featured_submission_listing', $args);

                        } else {

                            if ($payment_for == 2) {
                                if ($payment_money != intval($price_per_listing_with_featured) * 100) {
                                    wp_die('No joke');
                                    return;
                                }
                                update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 1);
                                $invoice_id = $this->ere_invoice->insert_invoice('Listing_With_Featured', $property_id, $user_id, 2, $payment_method, 1, $paymentId, $payerId);
                            } else {
                                if ($payment_money != intval($price_per_listing) * 100) {
                                    wp_die('No joke');
                                    return;
                                }
                                $invoice_id = $this->ere_invoice->insert_invoice('Listing', $property_id, $user_id, 1, $payment_method, 1, $paymentId, $payerId);
                            }

                            update_post_meta($property_id, ERE_METABOX_PREFIX . 'payment_status', 'paid');
                            $paid_submission_status = ere_get_option('paid_submission_type', 'no');
                            $auto_publish = ere_get_option('auto_publish', 1);

                            if ($auto_publish == 1 && $paid_submission_status == 'per_listing') {
                                $post = array(
                                    'ID' => $property_id,
                                    'post_status' => 'publish',
                                    'post_date' => current_time('mysql'),
                                    'post_date_gmt' => current_time('mysql'),
                                );
                                wp_update_post($post);
                            } else {
                                $post = array(
                                    'ID' => $property_id,
                                    'post_status' => 'pending',
                                    'post_date' => current_time('mysql'),
                                    'post_date_gmt' => current_time('mysql'),
                                );
                                wp_update_post($post);
                            }

                            $args = array(
                                'listing_title' => get_the_title($property_id),
                                'listing_id' => $property_id,
                                'invoice_no' => $invoice_id,
                            );
                            ere_send_email($user_email, 'mail_paid_submission_listing', $args);
                            ere_send_email($admin_email, 'admin_mail_paid_submission_listing', $args);
                        }
                    } else if ($paid_submission_type == 'per_package') {
                        //Payment Stripe package
                        $package_id = $_POST['package_id'];
                        $package_id = intval($package_id);
                        $package_price = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_price', true);
                        if ($payment_money != intval($package_price) * 100) {
                            wp_die('No joke');
                            return;
                        }
                        $this->ere_package->insert_user_package($user_id, $package_id);
                        $this->ere_invoice->insert_invoice('Package', $package_id, $user_id, 0, $payment_method, 1, $paymentId, $payerId);
                        $args = array();
                        ere_send_email($user_email, 'mail_activated_package', $args);
                    }
                } else {
                    $message = esc_html__('Transaction failed', 'essential-real-estate');
                    if ($paid_submission_type == 'per_listing') {
                        //Payment Stripe listing
                        $property_id = $_POST['property_id'];
                        $property_id = intval($property_id);

                        if ($payment_for == 3) {
                            $this->ere_trans_log->insert_trans_log('Upgrade_To_Featured', $property_id, $user_id, 3, $payment_method, 0, $paymentId, $payerId,0, $message);
                        } else {
                            if ($payment_for == 2) {
                                $this->ere_trans_log->insert_trans_log('Listing_With_Featured', $property_id, $user_id, 2, $payment_method, 0, $paymentId, $payerId,0, $message);
                            } else {
                                $this->ere_trans_log->insert_trans_log('Listing', $property_id, $user_id, 1, $payment_method, 0, $paymentId, $payerId,0, $message);
                            }
                        }
                    } else if ($paid_submission_type == 'per_package') {
                        //Payment Stripe package
                        $package_id = $_POST['package_id'];
                        $package_id = intval($package_id);
                        $this->ere_trans_log->insert_trans_log('Package', $package_id, $user_id, 0, $payment_method, 0, $paymentId, $payerId,0, $message);
                    }

                    $error = '<div class="alert alert-danger" role="alert">' . sprintf(__('<strong>Error!</strong> Transaction failed', 'essential-real-estate')) . '</div>';
                    print $error;
                }
            } catch (Exception $e) {
                $error = '<div class="alert alert-danger" role="alert"><strong>Error!</strong> ' . $e->getMessage() . '</div>';
                print $error;
            }
        }

        /**
         * paypal_payment_completed
         */
        public function paypal_payment_completed()
        {
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $user_email = $current_user->user_email;
            $admin_email = get_bloginfo('admin_email');
            $allowed_html = array();
            $payment_method = 'Paypal';
            $paid_submission_type = ere_get_option('paid_submission_type', 'no');
            try {
                if (isset($_GET['token']) && isset($_GET['PayerID'])) {
                    $payerId = wp_kses($_GET['PayerID'], $allowed_html);
                    $paymentId = wp_kses($_GET['paymentId'], $allowed_html);
                    $transfered_data = get_user_meta($user_id, ERE_METABOX_PREFIX . 'paypal_transfer', true);
                    if(empty($transfered_data))
                    {
                        return;
                    }
                    $payment_execute_url = $transfered_data['payment_execute_url'];
                    $token = $transfered_data['access_token'];

                    $payment_execute = array(
                        'payer_id' => $payerId
                    );
                    $json = json_encode($payment_execute);
                    $json_response = $this->execute_paypal_request($payment_execute_url, $json, $token);
                    delete_user_meta($user_id, ERE_METABOX_PREFIX . 'paypal_transfer');
                    if ($json_response['state'] == 'approved') {
                        if ($paid_submission_type == 'per_listing') {
                            $payment_for = $transfered_data['payment_for'];
                            $property_id = $transfered_data['property_id'];
                            if ($payment_for == 3) {
                                $invoice_id = $this->ere_invoice->insert_invoice('Upgrade_To_Featured', $property_id, $user_id, 3, $payment_method, 1, $paymentId, $payerId);
                                update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 1);
                                $args = array(
                                    'listing_title' => get_the_title($property_id),
                                    'listing_id' => $property_id,
                                    'invoice_no' => $invoice_id,
                                );

                                ere_send_email($user_email, 'mail_featured_submission_listing', $args);
                                ere_send_email($admin_email, 'admin_mail_featured_submission_listing', $args);

                            } else {
                                if ($payment_for == 2) {
                                    update_post_meta($property_id, ERE_METABOX_PREFIX . 'property_featured', 1);
                                    $invoice_id = $this->ere_invoice->insert_invoice('Listing_With_Featured', $property_id, $user_id, 2, $payment_method, 1, $paymentId, $payerId);
                                } else {
                                    $invoice_id = $this->ere_invoice->insert_invoice('Listing', $property_id, $user_id, 1, $payment_method, 1, $paymentId, $payerId);
                                }

                                update_post_meta($property_id, ERE_METABOX_PREFIX . 'payment_status', 'paid');
                                $paid_submission_status = ere_get_option('paid_submission_type', 'no');
                                $auto_publish = ere_get_option('auto_publish', 1);
                                if ($auto_publish == 1 && $paid_submission_status == 'per_listing') {
                                    $post = array(
                                        'ID' => $property_id,
                                        'post_status' => 'publish',
                                        'post_date' => current_time('mysql'),
                                        'post_date_gmt' => current_time('mysql'),
                                    );
                                    wp_update_post($post);
                                } else {
                                    $post = array(
                                        'ID' => $property_id,
                                        'post_status' => 'pending',
                                        'post_date' => current_time('mysql'),
                                        'post_date_gmt' => current_time('mysql'),
                                    );
                                    wp_update_post($post);
                                }
                                $args = array(
                                    'listing_title' => get_the_title($property_id),
                                    'listing_id' => $property_id,
                                    'invoice_no' => $invoice_id,
                                );

                                ere_send_email($user_email, 'mail_paid_submission_listing', $args);
                                ere_send_email($admin_email, 'admin_mail_paid_submission_listing', $args);
                            }
                        } else if ($paid_submission_type == 'per_package') {
                            $package_id = $transfered_data['package_id'];
                            $this->ere_package->insert_user_package($user_id, $package_id);
                            $this->ere_invoice->insert_invoice('Package', $package_id, $user_id, 0, $payment_method, 1, $paymentId, $payerId);
                            $args = array();
                            ere_send_email($user_email, 'mail_activated_package', $args);
                        }
                    } else {
                        $message = esc_html__('Transaction failed', 'essential-real-estate');
                        if ($paid_submission_type == 'per_listing') {
                            $payment_for = $transfered_data['payment_for'];
                            $property_id = $transfered_data['property_id'];
                            if ($payment_for == 3) {
                                $this->ere_trans_log->insert_trans_log('Upgrade_To_Featured', $property_id, $user_id, 3, $payment_method, 0, $paymentId, $payerId,0, $message);
                            } else {
                                if ($payment_for == 2) {
                                    $this->ere_trans_log->insert_trans_log('Listing_With_Featured', $property_id, $user_id, 2, $payment_method, 0, $paymentId, $payerId,0, $message);
                                } else {
                                    $this->ere_trans_log->insert_trans_log('Listing', $property_id, $user_id, 1, $payment_method, 0, $paymentId, $payerId,0, $message);
                                }
                            }
                        } else if ($paid_submission_type == 'per_package') {
                            $package_id = $transfered_data['package_id'];
                            $this->ere_trans_log->insert_trans_log('Package', $package_id, $user_id, 0, $payment_method, 0, $paymentId, $payerId,0, $message);
                        }
                        $error = '<div class="alert alert-danger" role="alert">' . sprintf(__('<strong>Error!</strong> Transaction failed', 'essential-real-estate')) . '</div>';
                        print $error;
                    }
                }
            } catch (Exception $e) {
                $error = '<div class="alert alert-danger" role="alert"><strong>Error!</strong> ' . $e->getMessage() . '</div>';
                print $error;
            }
        }
    }
}