<?php
/**
 * Register Taxonomy For Post Type
 *
 * @package SmartFramework
 * @subpackage Taxonomy
 * @author g5plus
 * @since 1.0
 */
if (!function_exists('gsf_register_taxonomy')) {
	function gsf_register_taxonomy()
	{
		$GLOBALS['gsf_taxonomy'] = array();
		$custom_tax = apply_filters('gsf_register_taxonomy', array());
		foreach ($custom_tax as $tax => $args) {
			if (!is_array($args)) {
				return;
			}
			if (!isset($args['post_type'])) {
				return;
			}

			$post_type = array_unique((array)$args['post_type']);
			$label = isset($args['label']) ? $args['label'] : $tax;
			$singular_name = isset($args['singular_name']) ? $args['singular_name'] : $label;

			foreach ($post_type as $value) {
				if (!empty($value)) {
					$GLOBALS['gsf_taxonomy'][$value] = $tax;
				}
			}

			$default = array(
				'hierarchical' => true,
				'label'        => $label,
				'query_var'    => true,
				'rewrite'      => array(
					'slug'       => $tax, // This controls the base slug that will display before each term
					'with_front' => false // Don't display the category base before
				),
				'labels'       => array(
					'singular_name'              => $singular_name,
					'search_items'               => sprintf(esc_html__('Search %s', 'smart-framework'), $label),
					'popular_items'              => sprintf(esc_html__('Popular %s', 'smart-framework'), $label),
					'all_items'                  => sprintf(esc_html__('All %s', 'smart-framework'), $label),
					'parent_item'                => sprintf(esc_html__('Parent %s', 'smart-framework'), $singular_name),
					'parent_item_colon'          => sprintf(esc_html__('Parent %s:', 'smart-framework'), $singular_name),
					'edit_item'                  => sprintf(esc_html__('Edit %s', 'smart-framework'), $singular_name),
					'view_item'                  => sprintf(esc_html__('View %s', 'smart-framework'), $singular_name),
					'update_item'                => sprintf(esc_html__('Update %s', 'smart-framework'), $singular_name),
					'add_new_item'               => sprintf(esc_html__('Add New %s', 'smart-framework'), $singular_name),
					'new_item_name'              => sprintf(esc_html__('New %s New', 'smart-framework'), $singular_name),
					'separate_items_with_commas' => sprintf(esc_html__('Separate %s with commas', 'smart-framework'), strtolower($label)),
					'add_or_remove_items'        => sprintf(esc_html__('Add or remove %s', 'smart-framework'), strtolower($label)),
					'choose_from_most_used'      => sprintf(esc_html__('Choose from the most used %s', 'smart-framework'), strtolower($label)),
					'not_found'                  => sprintf(esc_html__('No %s found.', 'smart-framework'), strtolower($label)),
					'no_terms'                   => sprintf(esc_html__('No %s', 'smart-framework'), strtolower($label)),
					'items_list_navigation'      => sprintf(esc_html__('%s list navigation', 'smart-framework'), $label),
					'items_list'                 => sprintf(esc_html__('%s list', 'smart-framework'), $label),
				)
			);

			$args = wp_parse_args($args, $default);
			$args['labels'] = wp_parse_args($args['labels'], $default['labels']);
			register_taxonomy(
				$tax,       //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
				$post_type, //post type name
				$args
			);

		}
	}

	add_action('init', 'gsf_register_taxonomy', 0);
}