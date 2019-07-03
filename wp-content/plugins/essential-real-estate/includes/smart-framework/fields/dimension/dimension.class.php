<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Dimension')) {
	class GSF_Field_Dimension extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'dimension', GSF_PLUGIN_URL . 'fields/dimension/assets/dimension.js', array(), GSF_VER, true);
		}
		function field_map() {
			$map = array();
			if (!isset($this->params['width']) || $this->params['width']) {
				$map[] = 'width';
			}
			if(!isset($this->params['height']) || $this->params['height']) {
				$map[] = 'height';
			}
			return join(',', $map);
		}
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$is_width = isset($this->params['width']) ? $this->params['width'] : true;
			$is_height = isset($this->params['height']) ? $this->params['height'] : true;
			$default = array(
				'width' => '',
				'height' => '',
			);
			if (!is_array($field_value)) {
				$field_value = array();
			}
			$field_value = wp_parse_args($field_value, $default);
			?>
			<div class="gsf-field-dimension-inner">
				<?php if ($is_width): ?>
					<div class="gsf-dimension-item">
						<div class="dashicons dashicons-leftright"></div>
						<input data-field-control="" class="gsf-dimension" type="number" placeholder="<?php esc_html_e('Width','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[width]" value="<?php echo esc_attr($field_value['width']); ?>"/>
					</div>
				<?php endif;?>
				<?php if ($is_height): ?>
					<div class="gsf-dimension-item">
						<div class="dashicons dashicons-leftright rotate-90deg" style="margin-right: 1px"></div>
						<input data-field-control="" class="gsf-dimension" type="number" placeholder="<?php esc_html_e('Height','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[height]" value="<?php echo esc_attr($field_value['height']); ?>"/>
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
				'width' => '',
				'height' => '',
			);
			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			$default = wp_parse_args($field_default, $default);

			return $this->is_clone() ? array($default) : $default;
		}
	}
}