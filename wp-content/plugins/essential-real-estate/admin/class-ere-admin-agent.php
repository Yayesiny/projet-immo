<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if (!class_exists('ERE_Admin_Agent')) {
    /**
     * Class ERE_Admin_Agent
     */
    class ERE_Admin_Agent
    {
        /**
         * Register custom columns
         * @param $columns
         * @return array
         */
        public function register_custom_column_titles($columns)
        {
            unset($columns['tags']);
            $columns['cb'] = "<input type=\"checkbox\" />";
            $columns['thumb'] = esc_html__('Avatar', 'essential-real-estate');
            $columns['title'] = esc_html__('Name', 'essential-real-estate');
            $columns['email'] =esc_html__('Email', 'essential-real-estate');
            $columns['mobile'] =  esc_html__('Mobile', 'essential-real-estate');
            $columns['agency'] =esc_html__('Agency', 'essential-real-estate');
            $new_columns = array();
            $custom_order = array('cb','thumb', 'title', 'email','mobile','agency','date');
            foreach ($custom_order as $colname){
                $new_columns[$colname] = $columns[$colname];
            }
            return $new_columns;
        }

        /**
         * Display custom column for agents
         * @param $column
         */
        public function display_custom_column($column)
        {
            global $post;
            switch ($column) {
                case 'thumb':
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('thumbnail', array(
                            'class' => '',
                        ));
                    } else {
                        echo '&ndash;';
                    }
                    break;
                case 'email':
                    $email = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'agent_email', true);

                    if (!empty($email)) {
                        echo esc_html($email);
                    } else {
                        echo '&ndash;';
                    }
                    break;
                case 'mobile':
                    $phone = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'agent_mobile_number', true);

                    if (!empty($phone)) {
                        echo esc_html($phone);
                    } else {
                        echo '&ndash;';
                    }
                    break;
                case 'agency':
                    $cate = ere_admin_taxonomy_terms($post->ID, 'agency', 'agent');
                    $allowed_html = array(
                        'a' => array(
                            'href' => array(),
                            'title' => array(),
                            'target' => array()
                        )
                    );
                    if (!empty($cate)) {
                        echo wp_kses($cate, $allowed_html);
                    } else {
                        echo '&ndash;';
                    }
                    break;
            }
        }

        /**
         * @param $actions
         * @param $post
         * @return mixed
         */
        public function modify_list_row_actions( $actions, $post ) {
            // Check for your post type.
            if ( $post->post_type == 'agent' ) {
                if (in_array($post->post_status, array('pending')) && current_user_can('publish_agents', $post->ID)) {
                    $actions['agent-approve']='<a href="'.wp_nonce_url(add_query_arg('approve_agent', $post->ID), 'approve_agent').'">'.esc_html__('Approve', 'essential-real-estate').'</a>';
                }
            }
            return $actions;
        }
        /**
         * Modify agent slug
         * @param $existing_slug
         * @return string
         */
        public function modify_agent_slug($existing_slug)
        {
            $agent_url_slug = ere_get_option('agent_url_slug');
            if ($agent_url_slug) {
                return $agent_url_slug;
            }
            return $existing_slug;
        }

        /**
         * @param $existing_slug
         * @return string
         */
        public function modify_agency_slug($existing_slug)
        {
            $agency_url_slug = ere_get_option('agency_url_slug');
            if ($agency_url_slug) {
                return $agency_url_slug;
            }
            return $existing_slug;
        }

        /**
         * modify_author_slug
         */
        public function modify_author_slug()
        {
            $author_url_slug = ere_get_option('author_url_slug');
            if ($author_url_slug) {
                global $wp_rewrite;
                $wp_rewrite->author_base = $author_url_slug;
            }
        }
        /**
         * Save agent meta
         * @param $post_id
         * @param $post
         */
        public function save_agent_meta($post_id, $post)
        {
            if (!is_object($post) || !isset($post->post_type)) {
                return;
            }
            if ('agent' != $post->post_type) {
                return;
            }
            if (!isset($_POST[ERE_METABOX_PREFIX . 'agent_email'])) {
                return;
            }
            $user_as_agent = ere_get_option('user_as_agent', 1);
            if ($user_as_agent) {
                $allowed_html = array();

                $agent_description = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_description'], $allowed_html);
                $agent_position = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_position'], $allowed_html);
                $agent_email = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_email'], $allowed_html);
                $agent_mobile_number = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_mobile_number'], $allowed_html);
                $agent_fax_number = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_fax_number'], $allowed_html);
                $agent_company = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_company'], $allowed_html);
                $agent_licenses = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_licenses'], $allowed_html);
                $agent_office_number = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_office_number'], $allowed_html);
                $agent_office_address = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_office_address'], $allowed_html);
                $agent_facebook_url = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_facebook_url'], $allowed_html);
                $agent_twitter_url = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_twitter_url'], $allowed_html);
                $agent_googleplus_url = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_googleplus_url'], $allowed_html);
                $agent_linkedin_url = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_linkedin_url'], $allowed_html);
                $agent_pinterest_url = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_pinterest_url'], $allowed_html);
                $agent_instagram_url = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_instagram_url'], $allowed_html);
                $agent_skype = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_skype'], $allowed_html);
                $agent_youtube_url = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_youtube_url'], $allowed_html);
                $agent_vimeo_url = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_vimeo_url'], $allowed_html);
                $agent_website_url = wp_kses($_POST[ERE_METABOX_PREFIX . 'agent_website_url'], $allowed_html);

                $image_id = get_post_thumbnail_id($post_id);
                $full_img = wp_get_attachment_image_src($image_id, 'full');
                $user_id = get_post_meta($post_id, ERE_METABOX_PREFIX . 'agent_user_id', true);
                update_user_meta($user_id, 'aim', '/' . $full_img[0] . '/');
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_position', $agent_position);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_company', $agent_company);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_licenses', $agent_licenses);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_number', $agent_office_number);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_fax_number', $agent_fax_number);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_mobile_number', $agent_mobile_number);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_skype', $agent_skype);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_address', $agent_office_address);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_custom_picture', $full_img[0]);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_facebook_url', $agent_facebook_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_twitter_url', $agent_twitter_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_linkedin_url', $agent_linkedin_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_vimeo_url', $agent_vimeo_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_googleplus_url', $agent_googleplus_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_youtube_url', $agent_youtube_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_pinterest_url', $agent_pinterest_url);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_instagram_url', $agent_instagram_url);

                if (!empty($agent_description)) {
                    $args = array(
                        'ID' => $user_id,
                        'description' => $agent_description
                    );
                    wp_update_user($args);
                }
                if (!empty($agent_website_url)) {
                    $args = array(
                        'ID' => $user_id,
                        'user_url' => $agent_website_url
                    );
                    wp_update_user($args);
                }
                if (!email_exists($agent_email)) {
                    $args = array(
                        'ID' => $user_id,
                        'user_email' => $agent_email
                    );
                    wp_update_user($args);
                }
            }
        }

        /**
         * Approve Agent
         */
        public function approve_agent()
        {
            if (!empty($_GET['approve_agent']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'approve_agent') && current_user_can('publish_post', $_GET['approve_agent'])) {
                $post_id = absint($_GET['approve_agent']);
                $listing_data = array(
                    'ID' => $post_id,
                    'post_status' => 'publish'
                );
                wp_update_post($listing_data);

                $author_id = get_post_field('post_author', $post_id);
                $user = get_user_by('id', $author_id);
                $user_email = $user->user_email;

                $args = array(
                    'agent_name' => get_the_title($post_id),
                    'agent_url' => get_permalink($post_id)
                );
                ere_send_email($user_email, 'mail_approved_agent', $args);
                wp_redirect(remove_query_arg('approve_agent', add_query_arg('approve_agent', $post_id, admin_url('edit.php?post_type=agent'))));
                exit;
            }
        }
        /**
         * filter_restrict_manage_agent
         */
        public function filter_restrict_manage_agent() {
            global $typenow;
            $post_type = 'agent';
            if ($typenow == $post_type) {
                $taxonomy='agency';
                $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
                $info_taxonomy = get_taxonomy($taxonomy);
                wp_dropdown_categories(array(
                    'show_option_all' => __("All {$info_taxonomy->label}"),
                    'taxonomy'        => $taxonomy,
                    'name'            => $taxonomy,
                    'orderby'         => 'name',
                    'selected'        => $selected,
                    'show_count'      => true,
                    'hide_empty'      => false,
                ));
            };
        }

        /**
         * agent_filter
         * @param $query
         */
        public function agent_filter($query) {
            global $pagenow;
            $post_type = 'agent';
            $q_vars    = &$query->query_vars;
            if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type)
            {
                $taxonomy='agency';
                if (isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
                    $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
                    $q_vars[$taxonomy] = $term->slug;
                }
            }
        }

        /***
         * @param $query
         */
        public function post_types_admin_order( $query ) {
            if (is_admin()) {
                $post_type = $query->query['post_type'];
                if ( $post_type == 'agent') {
                    if($query->get('orderby') == ''){
                        $query->set('orderby', array('menu_order' => 'ASC', 'date' => 'DESC'));
                    }
                }
            }
        }
    }
}