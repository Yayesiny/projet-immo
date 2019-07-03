<?php
/**
 * Updater plugin
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (!class_exists('ERE_Updater')) {
	/**
	 * Class ERE_Updater
	 */
	class ERE_Updater
	{
		public static function updater()
		{
			if ( version_compare( get_option( 'ere_version', ERE_PLUGIN_VER ), '1.2.9', '<' ) ) {
				$args = array(
					'post_type' => 'property',
					'posts_per_page' => -1
				);
				$properties = new WP_Query($args);
				if ($properties->have_posts()) :
					while ($properties->have_posts()): $properties->the_post();
						$post_id=get_the_ID();
						$property_price_short = get_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_short', true);
						if (empty($property_price_short)) {
							$property_price = get_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price', true);
							update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_short', $property_price);
							update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_price_unit', 1);
						}
					endwhile;
				endif;
				wp_reset_postdata();
				update_option('ere_version', ERE_PLUGIN_VER);
			}
			global $wpdb;
			// Update taxonomy and meta_key
			if ( version_compare( get_option( 'ere_version', ERE_PLUGIN_VER ), '1.3.2', '<' ) ) {
				$wpdb->query( "UPDATE {$wpdb->term_taxonomy} t SET t.taxonomy ='agency' WHERE t.taxonomy ='agencies';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_des' WHERE tm.meta_key ='agencies_des';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_logo' WHERE tm.meta_key ='agencies_logo';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_licenses' WHERE tm.meta_key ='agencies_licenses';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_address' WHERE tm.meta_key ='agencies_address';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_map_address' WHERE tm.meta_key ='agencies_map_address';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_email' WHERE tm.meta_key ='agencies_email';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_mobile_number' WHERE tm.meta_key ='agencies_mobile_number';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_fax_number' WHERE tm.meta_key ='agencies_fax_number';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_office_number' WHERE tm.meta_key ='agencies_office_number';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_website_url' WHERE tm.meta_key ='agencies_website_url';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_vimeo_url' WHERE tm.meta_key ='agencies_vimeo_url';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_facebook_url' WHERE tm.meta_key ='agencies_facebook_url';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_twitter_url' WHERE tm.meta_key ='agencies_twitter_url';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_googleplus_url' WHERE tm.meta_key ='agencies_googleplus_url';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_linkedin_url' WHERE tm.meta_key ='agencies_linkedin_url';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_pinterest_url' WHERE tm.meta_key ='agencies_pinterest_url';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_instagram_url' WHERE tm.meta_key ='agencies_instagram_url';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_skype' WHERE tm.meta_key ='agencies_skype';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='agency_youtube_url' WHERE tm.meta_key ='agencies_youtube_url';" );

				$wpdb->query( "UPDATE {$wpdb->term_taxonomy} t SET t.taxonomy ='property-label' WHERE t.taxonomy ='property-labels';" );
				$wpdb->query( "UPDATE {$wpdb->termmeta} tm SET tm.meta_key ='property_label_color' WHERE tm.meta_key ='property_labels_color';" );
				update_option('ere_version', ERE_PLUGIN_VER);
			}
			if ( version_compare( get_option( 'ere_version', ERE_PLUGIN_VER ), '1.4.0', '<' ) ) {
				$args = array(
					'post_type' => 'property',
					'posts_per_page' => -1
				);
				$properties = new WP_Query($args);
				if ($properties->have_posts()) :
					while ($properties->have_posts()): $properties->the_post();
						$post_id=get_the_ID();
						$property_identity = get_post_meta($post_id, ERE_METABOX_PREFIX . 'property_identity', true);
						if (empty($property_identity)) {
							update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_identity', $post_id);
						}
					endwhile;
				endif;
				wp_reset_postdata();
				update_option('ere_version', ERE_PLUGIN_VER);
			}
			if ( version_compare( get_option( 'ere_version', ERE_PLUGIN_VER ), '1.5.3', '<' ) ) {
				$args = array(
					'post_type' => 'property',
					'posts_per_page' => -1
				);
				$properties = new WP_Query($args);
				if ($properties->have_posts()) :
					while ($properties->have_posts()): $properties->the_post();
						$post_id=get_the_ID();
						$property_featured = get_post_meta($post_id, ERE_METABOX_PREFIX . 'property_featured', true);
						if (empty($property_featured)) {
							update_post_meta($post_id, ERE_METABOX_PREFIX . 'property_featured', 0);
						}
					endwhile;
				endif;
				wp_reset_postdata();
				update_option('ere_version', ERE_PLUGIN_VER);
			}

			if ( version_compare( get_option( 'ere_version', ERE_PLUGIN_VER ), '1.5.9', '<' ) ) {
				$terms_city = get_categories(
					array(
						'taxonomy' => 'property-city',
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false,
						'parent' => 0
					)
				);

				foreach ($terms_city as $term):
					$term_id=$term->term_id;
					$property_city_state_tax_id  = get_term_meta( $term_id, 'property_city_state', true );
					$property_city_country  = get_term_meta( $property_city_state_tax_id, 'property_state_country', true );
					add_term_meta( $term_id, 'property_city_country', strtoupper($property_city_country), true );
				endforeach;

				$terms_neighborhood = get_categories(
					array(
						'taxonomy' => 'property-neighborhood',
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false,
						'parent' => 0
					)
				);

				foreach ($terms_neighborhood as $term):
					$term_id=$term->term_id;
					$property_neighborhood_city_tax_id  = get_term_meta( $term_id, 'property_neighborhood_city', true );
					$property_neighborhood_state_tax_id  = get_term_meta( $property_neighborhood_city_tax_id, 'property_city_state', true );
					$property_neighborhood_country  = get_term_meta( $property_neighborhood_state_tax_id, 'property_state_country', true );
					add_term_meta( $term_id, 'property_neighborhood_state', $property_neighborhood_state_tax_id, true );
					add_term_meta( $term_id, 'property_neighborhood_country', strtoupper($property_neighborhood_country), true );
				endforeach;
				update_option('ere_version', ERE_PLUGIN_VER);
			}
		}
	}
}