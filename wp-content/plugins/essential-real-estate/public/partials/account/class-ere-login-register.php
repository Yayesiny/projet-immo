<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('ERE_Login_Register')) {
    /**
     * Class ERE_Login_Register
     */
    class ERE_Login_Register
    {
        /**
         * Login
         */
        public function login_ajax() {
            check_ajax_referer( 'ere_login_ajax_nonce', 'ere_security_login' );
            $allowed_html = array('strong' => array());
            $user_login = wp_kses( $_POST['user_login'], $allowed_html );
            $user_password = wp_kses( $_POST['user_password'], $allowed_html );

            if( isset( $_POST['remember'] ) ) {
                $remember = wp_kses( $_POST['remember'], $allowed_html );
            } else {
                $remember = '';
            }

            if( empty( $user_login ) ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('The username field is empty.', 'essential-real-estate') ) );
                wp_die();
            }
            if( empty( $user_password ) ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('The password field is empty.', 'essential-real-estate') ) );
                wp_die();
            }
            if( !username_exists( $user_login ) ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('Invalid username', 'essential-real-estate') ) );
                wp_die();
            }
            //recaptcha
            if (ere_enable_captcha('login')) {do_action('ere_verify_recaptcha');}
            wp_clear_auth_cookie();

            $remember = ($remember == 'on') ? true : false;

            $credentials = array();
            $credentials['user_login'] = $user_login;
            $credentials['user_password'] = $user_password;
            $credentials['remember'] = $remember;
            $user = wp_signon( $credentials, false );

            if ( is_wp_error( $user ) ) {
                echo json_encode( array(
                    'success' => false,
                    'message' => esc_html__('Incorrect password.', 'essential-real-estate') )
                );

                wp_die();
            } else {
                wp_set_current_user($user->ID);
                do_action('set_current_user');
                global $current_user;
                $current_user = wp_get_current_user();

                echo json_encode( array( 'success' => true, 'message' => esc_html__('Login successful, redirecting...', 'essential-real-estate') ) );

            }
            wp_die();
        }

        /**
         * Register account
         */
        public function register_ajax() {

            check_ajax_referer('ere_register_ajax_nonce', 'ere_register_security');

            $allowed_html = array();
            $user_login          = trim($_POST['user_login']);
            $user_pass='';
            $email             = trim( $_POST['user_email']);
            $term_condition    = wp_kses( $_POST['term_condition'], $allowed_html );
            $enable_password = ere_get_option('enable_password',0);

            $term_condition = ( $term_condition == 'on') ? true : false;

            if( !$term_condition ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('You need to agree with terms & conditions.', 'essential-real-estate') ) );
                wp_die();
            }
            $user_login = sanitize_user( $user_login );
            $email= sanitize_email($email);
            if( empty( $user_login ) ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('The username field is empty.', 'essential-real-estate') ) );
                wp_die();
            }
            if( strlen( $user_login ) < 3 ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('Minimum 3 characters required', 'essential-real-estate') ) );
                wp_die();
            }
            if ( ! validate_username( $user_login ) ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('Please enter a valid account username.', 'essential-real-estate') ) );
                wp_die();
            }
            if( username_exists( $user_login ) ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('This username is already registered.', 'essential-real-estate') ) );
                wp_die();
            }
            if( empty( $email ) ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('The email field is empty.', 'essential-real-estate') ) );
                wp_die();
            }
             if( !is_email( $email ) ) {
                 echo json_encode( array( 'success' => false, 'message' => esc_html__('Invalid email address.', 'essential-real-estate') ) );
                 wp_die();
             }
            if( email_exists( $email ) ) {
                echo json_encode( array( 'success' => false, 'message' => esc_html__('This email address is already registered.', 'essential-real-estate') ) );
                wp_die();
            }
            //recaptcha
            if (ere_enable_captcha('register')) {do_action('ere_verify_recaptcha');}
            if( $enable_password==1){
                $user_pass         = trim( sanitize_text_field(wp_kses( $_POST['user_password'] ,$allowed_html) ) );
                $user_pass_retype  = trim( sanitize_text_field(wp_kses( $_POST['user_password_retype'] ,$allowed_html) ) );

                if ($user_pass == '' || $user_pass_retype == '' ) {
                    echo json_encode( array( 'success' => false, 'message' => esc_html__('The password field is empty!', 'essential-real-estate') ) );
                    wp_die();
                }

                if ($user_pass !== $user_pass_retype ){
                    echo json_encode( array( 'success' => false, 'message' => esc_html__('Passwords don\'t match', 'essential-real-estate') ) );
                    wp_die();
                }
            }

            if($enable_password) {
                $user_password = $user_pass;
            } else {
                $user_password = wp_generate_password( 12, false,false);
            }
            $user_id = wp_create_user( $user_login, $user_password, $email );

            if ( is_wp_error($user_id) ) {
                echo json_encode( array( 'success' => false, 'message' => $user_id ) );
                wp_die();
            } else {
                if( $enable_password) {
                    echo json_encode( array( 'success' => true, 'message' => esc_html__('Your account was created, you can login now!', 'essential-real-estate') ) );
                } else {
                    echo json_encode( array( 'success' => true, 'message' => esc_html__('A generated password was sent to your email, please check email!', 'essential-real-estate') ) );
                }
                $this-> wp_new_user_notification( $user_id, $user_password );
            }
            wp_die();
        }
        /**
         * user notification
         * @param $user_id
         * @param string $randonpassword
         */
        public function wp_new_user_notification( $user_id, $randonpassword = '' ) {

            $user = new WP_User( $user_id );
            $user_login = stripslashes( $user->user_login );
            $user_email = stripslashes( $user->user_email );
            $args = array(
                'user_login_register' => $user_login,
                'user_email_register' => $user_email
            );
            $admin_email = get_bloginfo('admin_email');
            ere_send_email( $admin_email, 'admin_mail_register_user', $args );

            if ( empty( $randonpassword ) ) {
                return;
            }
            $args = array(
                'user_login_register'  =>  $user_login,
                'user_email_register'  =>  $user_email,
                'user_pass_register'   => $randonpassword
            );
            ere_send_email( $user_email, 'mail_register_user', $args );
        }

        /**
         * Reset password
         * @return bool
         */
        public function reset_password_ajax() {
            check_ajax_referer('ere_reset_password_ajax_nonce', 'ere_security_reset_password');
            $allowed_html = array();
            $user_login = wp_kses( $_POST['user_login'], $allowed_html );

            if ( empty( $user_login ) ) {
                echo json_encode(array( 'success' => false, 'message' => esc_html__('Enter a username or email address.', 'essential-real-estate') ) );
                wp_die();
            }
            //recaptcha
            if (ere_enable_captcha('reset_password')) {do_action('ere_verify_recaptcha');}
            if ( strpos( $user_login, '@' ) ) {
                $user_data = get_user_by( 'email', trim( $user_login ) );
                if ( empty( $user_data ) ) {
                    echo json_encode(array('success' => false, 'message' => esc_html__('There is no user registered with that email address.', 'essential-real-estate')));
                    wp_die();
                }
            } else {
                $login = trim( $user_login );
                $user_data = get_user_by('login', $login);

                if ( !$user_data ) {
                    echo json_encode(array( 'success' => false, 'message' => esc_html__('Invalid username', 'essential-real-estate') ) );
                    wp_die();
                }
            }
            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;
            $key = get_password_reset_key( $user_data );

            if ( is_wp_error( $key ) ) {
                echo json_encode(array( 'success' => false, 'message' => $key ) );
                wp_die();
            }

            $message = esc_html__('Someone has requested a password reset for the following account:', 'essential-real-estate' ) . "\r\n\r\n";
            $message .= network_home_url( '/' ) . "\r\n\r\n";
            $message .= sprintf(esc_html__('Username: %s', 'essential-real-estate'), $user_login) . "\r\n\r\n";
            $message .= esc_html__('If this was a mistake, just ignore this email and nothing will happen.', 'essential-real-estate') . "\r\n\r\n";
            $message .= esc_html__('To reset your password, visit the following address:', 'essential-real-estate') . "\r\n\r\n";
            $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";

            if ( is_multisite() )
                $blogname = $GLOBALS['current_site']->site_name;
            else
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

            $title = sprintf( esc_html__('[%s] Password Reset', 'essential-real-estate'), $blogname );
            $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );
            $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );
            if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
                echo json_encode(array('success' => false, 'message' => esc_html__('The email could not be sent.', 'essential-real-estate') . "<br />\n" . esc_html__('Possible reason: your host may have disabled the mail() function.', 'essential-real-estate')));
                wp_die();
            } else {
                echo json_encode(array('success' => true, 'message' => esc_html__('Please, Check your email', 'essential-real-estate') ));
                wp_die();
            }
        }

        /**
         * Check is user restricted
         * @return bool
         */
        private function is_user_restricted() {
            $user = wp_get_current_user();
            if ( in_array( 'ere_customer', (array) $user->roles ) ) {
                return true;
            }
            if ( in_array( 'subscriber', (array) $user->roles ) ) {
                return true;
            }
            return false;
        }

        /**
         * restrict_admin_access
         */
        public function restrict_admin_access() {

            if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            } else if ( isset( $_GET[ 'action' ] ) && ( $_GET[ 'action' ] == 'delete' ) ) {

            } else {
                if ($this-> is_user_restricted() ) {
                    wp_redirect( esc_url_raw( home_url( '/' ) ) );
                    exit;
                }
            }
        }

        /**
         * Hide admin bar
         */
        public function hide_admin_bar() {
            if ( is_user_logged_in() ) {
                if ( $this->is_user_restricted() ) {
                    add_filter( 'show_admin_bar', '__return_false' );
                }
            }
        }

        /**
         * Modal login/register
         */
        public function login_register_modal() {
            echo ere_get_template_html('global/dashboard-login-modal.php');
        }
    }
}
new ERE_Login_Register();