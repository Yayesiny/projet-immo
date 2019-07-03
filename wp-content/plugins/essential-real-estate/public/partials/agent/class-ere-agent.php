<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Agent')) {
    /**
     * Class ERE_Agent
     */
    class ERE_Agent
    {
        /**
         * submit review
         */
        public function submit_review_ajax()
        {
            check_ajax_referer('ere_submit_review_ajax_nonce', 'ere_security_submit_review');
            global $wpdb, $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $user = get_user_by('id', $user_id);
            $agent_id = $_POST['agent_id'];
            $rating_value = $_POST['rating'];
            $my_review = $wpdb->get_row("SELECT * FROM $wpdb->comments as comment INNER JOIN $wpdb->commentmeta AS meta WHERE comment.comment_post_ID = $agent_id AND comment.user_id = $user_id  AND meta.meta_key = 'agent_rating' AND meta.comment_id = comment.comment_ID ORDER BY comment.comment_ID DESC");
            $comment_approved = 1;
            $auto_publish_review_agent = ere_get_option( 'review_agent_approved_by_admin',0 );
            if ($auto_publish_review_agent == 1) {
                $comment_approved = 0;
            }
            if ( sizeof( $my_review ) == 0 ) {
                $data = Array();
                $user = $user->data;
                $data['comment_post_ID'] = $agent_id;
                $data['comment_content'] = $_POST['message'];
                $data['comment_date'] = current_time('mysql');
                $data['comment_approved'] = $comment_approved;
                $data['comment_author'] = $user->user_login;
                $data['comment_author_email'] = $user->user_email;
                $data['comment_author_url'] = $user->user_url;
                $data['user_id'] = $user_id;
                $comment_id = wp_insert_comment($data);

                add_comment_meta($comment_id, 'agent_rating', $rating_value);
                if ($comment_approved == 1) {
                    apply_filters('ere_agent_rating_meta', $agent_id, $rating_value);
                }
            } else {
                $data = Array();
                $data['comment_ID'] = $my_review->comment_ID;
                $data['comment_post_ID'] = $agent_id;
                $data['comment_content'] = $_POST['message'];
                $data['comment_date'] = current_time('mysql');
                $data['comment_approved'] = $comment_approved;

                wp_update_comment($data);
                update_comment_meta($my_review->comment_ID, 'agent_rating', $rating_value, $my_review->meta_value);
                if ($comment_approved == 1) {
                    apply_filters('ere_agent_rating_meta', $agent_id, $rating_value, false, $my_review->meta_value);
                }
            }
            wp_die();
        }

        /**
         * @param $agent_id
         * @param $rating_value
         * @param bool|true $comment_exist
         * @param int $old_rating_value
         */
        public function rating_meta_filter($agent_id, $rating_value, $comment_exist = true, $old_rating_value = 0)
        {
            $agent_rating = get_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_rating', true);
            if ($comment_exist == true) {
                echo $old_rating_value;
                if (!empty($agent_rating)) {
                    $agent_rating[$rating_value]++;
                } else {
                    $agent_rating = Array();
                    $agent_rating[1] = 0;
                    $agent_rating[2] = 0;
                    $agent_rating[3] = 0;
                    $agent_rating[4] = 0;
                    $agent_rating[5] = 0;
                    $agent_rating[$rating_value]++;
                }
            } else {
                $agent_rating[$old_rating_value]--;
                $agent_rating[$rating_value]++;
            }
            update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_rating', $agent_rating);
        }

        /**
         * delete review
         * @param $comment_id
         */
        public function delete_review($comment_id)
        {
            global $wpdb;
            $rating_value = get_comment_meta($comment_id, 'agent_rating', true);
            if (!empty($rating_value)) {
                $comments = $wpdb->get_row("SELECT comment_post_ID as agent_ID FROM $wpdb->comments WHERE comment_ID = $comment_id");
                $agent_id = $comments->agent_ID;
                $agent_rating = get_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_rating', true);
                $agent_rating[$rating_value]--;
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_rating', $agent_rating);
            }
        }

        /**
         * approve review
         * @param $new_status
         * @param $old_status
         * @param $comment
         */
        public function approve_review($new_status, $old_status, $comment)
        {
            if ($old_status != $new_status) {
                $rating_value = get_comment_meta($comment->comment_ID, 'agent_rating', true);
                $agent_rating = get_post_meta($comment->comment_post_ID, ERE_METABOX_PREFIX . 'agent_rating', true);
                if ($new_status == 'approved') {
                    $agent_rating[$rating_value]++;

                } else {
                    $agent_rating[$rating_value]--;
                }
                update_post_meta($comment->comment_post_ID, ERE_METABOX_PREFIX . 'agent_rating', $agent_rating);
            }
        }
    }
}