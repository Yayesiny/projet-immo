<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Slider')) {
	class GSF_Field_Slider extends GSF_Field
	{
		/**
		 * Enqueue resources for field
		 */
		function enqueue() {
			wp_enqueue_style('nouislider', GSF_PLUGIN_URL . 'assets/vendors/noUiSlider/nouislider.min.css', array(), '9.0.0');
			wp_enqueue_script('nouislider', GSF_PLUGIN_URL . 'assets/vendors/noUiSlider/nouislider.min.js', array(), '9.0.0', true);
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'slider', GSF_PLUGIN_URL . 'fields/slider/assets/slider.js', array(), GSF_VER, true);
		}

		/**
		 * Define field map using get value in javascript
		 *
		 * @return string
		 */
		function field_map() {
			if (isset($this->params['range']) && $this->params['range']) {
				return 'from,to';
			}
			return '';
		}

		/**
		 * Render field
		 *
		 * @param string $content_args
		 */
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$is_range = isset($this->params['range']) && $this->params['range'];
			if ($is_range) {
				$default = array(
					'from' => '',
					'to' => '',
				);
				if (!is_array($field_value)) {
					$field_value = array();
				}
				$field_value = wp_parse_args($field_value, $default);
			}
			$opt_default = array(
				'min' => 0,
				'max' => 100,
				'step' => 1
			);

			$option = isset($this->params['js_options']) ? $this->params['js_options'] : array();
			$option = wp_parse_args($option, $opt_default);
			?>
			<div class="gsf-field-slider-inner">
				<?php if ($is_range): ?>
					<input data-field-control="" class="gsf-slider-from" type="text" pattern="(-)?[0-9]*"
					       name="<?php echo esc_attr($this->get_name()) ?>[from]" value="<?php echo esc_attr($field_value['from']); ?>"/>
					<div class="gsf-slider-place" data-options='<?php echo json_encode($option); ?>'></div>
					<input data-field-control="" class="gsf-slider-to" type="text" pattern="(-)?[0-9]*"
					       name="<?php echo esc_attr($this->get_name()) ?>[to]" value="<?php echo esc_attr($field_value['to']); ?>"/>
				<?php else: ?>
					<input data-field-control="" class="gsf-slider" type="text" pattern="(-)?[0-9]*"
					       name="<?php echo esc_attr($this->get_name()) ?>" value="<?php echo esc_attr($field_value); ?>"/>
					<div class="gsf-slider-place" data-options='<?php echo json_encode($option); ?>'></div>
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
			if (isset($this->params['range']) && $this->params['range']) {
				$default = array(
					'from' => '',
					'to' => '',
				);
				$field_default = isset($this->params['default']) ? $this->params['default'] : array();
				$default = wp_parse_args($field_default, $default);

				return $this->is_clone() ? array($default) : $default;
			}

			return isset($this->params['default'])
				? $this->params['default']
				: ($this->is_clone() ? array('') : '');
		}
	}
}