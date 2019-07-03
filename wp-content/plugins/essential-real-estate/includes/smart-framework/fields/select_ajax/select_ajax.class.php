<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Select_Ajax')) {
	class GSF_Field_Select_Ajax extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_style('selectize', GSF_PLUGIN_URL . 'assets/vendors/selectize/css/selectize.css', array(), '0.12.3');
			wp_enqueue_style('selectize_default', GSF_PLUGIN_URL . 'assets/vendors/selectize/css/selectize.default.css', array(), '0.12.3');
			wp_enqueue_script('selectize', GSF_PLUGIN_URL . 'assets/vendors/selectize/js/selectize.js', array(), '0.12.3', true);
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'select_ajax', GSF_PLUGIN_URL . 'fields/select_ajax/assets/select-ajax.js', array(), GSF_VER, true);
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$input_type = isset($this->params['input_type']) ? $this->params['input_type'] : 'text';
			$place_holder = isset($this->params['placeholder']) ? $this->params['placeholder'] : '';
			$multiple = isset($this->params['multiple']) ? $this->params['multiple'] : false;
			$post_type = isset($this->params['data']) ? $this->params['data'] : 'post';
			$options = array();
			$args = array(
				'post__in' => (array)$field_value,
				'post_type' => $post_type
			);
			$posts = get_posts($args);
			foreach ($posts as $post) {
				$options[$post->ID] = $post->post_title;
			}
			if ($multiple && is_array($field_value)) {
				// Sort options array by $field_value
				$options_temp = array();
				foreach ($field_value as $post_id) {
					if (isset($options[$post_id])) {
						$options_temp[$post_id] = $options[$post_id];
					}
				}
				$options = $options_temp;
			}
			?>
			<div class="gsf-field-select_ajax-inner">
				<select data-field-control="" class="gsf-select-ajax repositories" type="<?php echo esc_attr($input_type); ?>"
				        name="<?php echo esc_attr($this->get_name()) ?><?php echo $multiple ? '[]' : ''; ?>"
				        data-value="<?php echo esc_attr(is_array($field_value) ? json_encode($field_value) : $field_value) ?>"
				        placeholder="<?php echo esc_attr($place_holder); ?>"
				        data-url="<?php echo esc_url(admin_url('admin-ajax.php?action=gsf_get_posts')); ?>"
				        data-source="<?php echo esc_attr($post_type); ?>"
					<?php echo esc_attr($multiple ? 'multiple' : '' ); ?>>
					<?php foreach ($options as $opt_key => $opt_val): ?>
						<option value="<?php echo esc_attr($opt_key); ?>"><?php echo esc_html($opt_val); ?></option>
					<?php endforeach;?>
				</select>
			</div>
			<?php
		}

		/**
		 * Get default value
		 *
		 * @return array | string
		 */
		function get_default() {
			$default = '';
			if (isset($this->params['multiple']) && $this->params['multiple']) {
				$default = array();
			}
			$field_default = isset($this->params['default']) ? $this->params['default'] : $default;
			return $this->is_clone() ? array($field_default) : $field_default;
		}
	}
}