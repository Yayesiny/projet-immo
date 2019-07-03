<?php
/**
 * Register Custom Post Type
 *
 * @package SmartFramework
 * @subpackage PostType
 * @author g5plus
 * @since 1.0
 */
if (!function_exists('gsf_register_post_type')) {
	function gsf_register_post_type()
	{
		$ctp_args = apply_filters('gsf_register_post_type', array());

		foreach ($ctp_args as $post_type => $args) {
			$post_type_name = !is_array($args)
				? $args
				: (isset($args['labels']) && isset($args['labels']['name'])
					? $args['labels']['name']
					: (isset($args['label']) ? $args['label'] : $post_type));

			$singular_name = $post_type_name;

			if (!is_array($args)) {
				$args = array();
				$args['labels'] = array();
			} else {
				if (!isset($args['labels'])) {
					$args['labels'] = array();
				}
				if (isset($args['label'])) {
					$args['labels']['name'] = $args['label'];
				}
				if (isset($args['singular_name'])) {
					$singular_name = $args['singular_name'];
				}
			}

			$defaults = array(
				'label'              => $post_type_name,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array('slug' => $post_type),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
				'labels'             => array(
					'name'                  => $post_type_name,
					'singular_name'         => $singular_name,
					'add_new_item'          => sprintf(__('Add New %s', 'smart-framework'), $singular_name),
					'edit_item'             => sprintf(__('Edit %s', 'smart-framework'), $singular_name),
					'new_item'              => sprintf(__('New %s', 'smart-framework'), $singular_name),
					'view_item'             => sprintf(__('View %s', 'smart-framework'), $singular_name),
					'search_items'          => sprintf(__('Search %s', 'smart-framework'), $post_type_name),
					'not_found'             => sprintf(__('No %s found.', 'smart-framework'), strtolower($post_type_name)),
					'not_found_in_trash'    => sprintf(__('No %s found in Trash.', 'smart-framework'), strtolower($post_type_name)),
					'all_items'             => sprintf(__('All %s', 'smart-framework'), $post_type_name),
					'archives'              => sprintf(__('%s Archives', 'smart-framework'), $post_type_name),
					'insert_into_item'      => sprintf(__('Insert into %s', 'smart-framework'), strtolower($singular_name)),
					'uploaded_to_this_item' => sprintf(__('Uploaded to this %s', 'smart-framework'), strtolower($singular_name)),
					'filter_items_list'     => sprintf(__('Filter %s list', 'smart-framework'), strtolower($post_type_name)),
					'items_list_navigation' => sprintf(__('%s list navigation', 'smart-framework'), $post_type_name),
					'items_list'            => sprintf(__('%s list', 'smart-framework'), $post_type_name),
				)
			);
			$args = wp_parse_args($args, $defaults);
			$args['labels'] = wp_parse_args($args['labels'], $defaults['labels']);

			register_post_type($post_type, $args);
		}
	}

	add_action('init', 'gsf_register_post_type', 0);
}
