<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Spacing')) {
	class GSF_Field_Spacing extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'spacing', GSF_PLUGIN_URL . 'fields/spacing/assets/spacing.js', array(), GSF_VER, true);
		}
		function field_map() {
			$map = array();
			if (!isset($this->params['left']) || $this->params['left']) {
				$map[] = 'left';
			}
			if(!isset($this->params['right']) || $this->params['right']) {
				$map[] = 'right';
			}
			if(!isset($this->params['top']) || $this->params['top']) {
				$map[] = 'top';
			}
			if(!isset($this->params['bottom']) || $this->params['bottom']) {
				$map[] = 'bottom';
			}
			return join(',', $map);
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$is_left = isset($this->params['left']) ? $this->params['left'] : true;
			$is_right = isset($this->params['right']) ? $this->params['right'] : true;
			$is_top = isset($this->params['top']) ? $this->params['top'] : true;
			$is_bottom = isset($this->params['bottom']) ? $this->params['bottom'] : true;
			$default = array(
				'left' => '',
				'right' => '',
				'top' => '',
				'bottom' => '',
			);
			if (!is_array($field_value)) {
				$field_value = array();
			}
			$field_value = wp_parse_args($field_value, $default);
			?>
			<div class="gsf-field-spacing-inner">
				<?php if ($is_left): ?>
					<div class="gsf-spacing-item">
						<div class="dashicons dashicons-arrow-left-alt"></div>
						<input data-field-control="" class="gsf-spacing" type="number" placeholder="<?php esc_html_e('Left','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[left]" value="<?php echo esc_attr($field_value['left']); ?>"/>
					</div>
				<?php endif;?>
				<?php if ($is_right): ?>
					<div class="gsf-spacing-item">
						<div class="dashicons dashicons-arrow-right-alt"></div>
						<input data-field-control="" class="gsf-spacing" type="number" placeholder="<?php esc_html_e('Right','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[right]" value="<?php echo esc_attr($field_value['right']); ?>"/>
					</div>
				<?php endif;?>
				<?php if ($is_top): ?>
					<div class="gsf-spacing-item">
						<div class="dashicons dashicons-arrow-up-alt"></div>
						<input data-field-control="" class="gsf-spacing" type="number" placeholder="<?php esc_html_e('Top','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[top]" value="<?php echo esc_attr($field_value['top']); ?>"/>
					</div>
				<?php endif;?>
				<?php if ($is_bottom): ?>
					<div class="gsf-spacing-item">
						<div class="dashicons dashicons-arrow-down-alt"></div>
						<input data-field-control="" class="gsf-spacing" type="number" placeholder="<?php esc_html_e('Bottom','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[bottom]" value="<?php echo esc_attr($field_value['bottom']); ?>"/>
					</div>
				<?php endif;?>
			</div>

		<?php
		}

		/**
		 * Get default value
		 *
		 * @return array
		 */
		function get_default() {
			$default = array(
				'left' => '',
				'right' => '',
				'top' => '',
				'bottom' => '',
			);
			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			$default = wp_parse_args($field_default, $default);

			return $this->is_clone() ? array($default) : $default;
		}
	}
}