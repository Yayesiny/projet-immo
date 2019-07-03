<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Font')) {
	class GSF_Field_Font extends GSF_Field
	{
		function field_map() {
			$map = array();
			if (!isset($this->params['font_size']) || $this->params['font_size']) {
				$map[] = 'font_size';
			}
			if (!isset($this->params['font_weight']) || $this->params['font_weight']) {
				$map[] = 'font_weight';
			}
			if(!isset($this->params['font_subsets']) || $this->params['font_subsets']) {
				$map[] = 'font_subsets';
			}
			return join(',', $map);
		}
		function enqueue()
		{
			wp_enqueue_style('selectize', GSF_PLUGIN_URL . 'assets/vendors/selectize/css/selectize.css', array(), '0.12.3');
			wp_enqueue_style('selectize_default', GSF_PLUGIN_URL . 'assets/vendors/selectize/css/selectize.default.css', array(), '0.12.3');
			wp_enqueue_script('selectize', GSF_PLUGIN_URL . 'assets/vendors/selectize/js/selectize.js', array(), '0.12.3', true);
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'font', GSF_PLUGIN_URL . 'fields/font/assets/font.js', array(), GSF_VER, true);
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			if (!is_array($field_value)) {
				$field_value = array();
			}

			$default = array(
				'font_kind' => 'google',
				'font_family' => "'Open Sans'",
				'font_size' => '14',
				'font_weight' => '400',
				'font_style' => '',
				'font_subsets' => ''
			);

			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			$field_default = wp_parse_args($field_default, $default);
			$field_value = wp_parse_args($field_value, $field_default);
			$font_size = $field_value['font_size'];
			$font_size_unit = preg_replace('/[0-9]*/', '', $font_size);
			$font_size_value = preg_replace('/em|px|\%/', '', $font_size);
			$step = 1;
			if ($font_size_unit === 'em') {
				$step = 0.01;
			}
			?>
			<div class="gsf-field-font-inner gsf-clearfix">
				<input data-field-control="" type="hidden" class="gsf-font-size-kind"
				       name="<?php echo esc_attr($this->get_name()); ?>[font_kind]"
				       value="<?php echo esc_attr($field_value['font_kind']); ?>"/>
				<div class="gsf-font-family">
					<div class="gsf-font-label"><?php esc_html_e('Font Family','smart-framework'); ?></div>
					<select data-field-control="" placeholder="<?php esc_attr_e('Select Font Family','smart-framework'); ?>"
					        name="<?php echo esc_attr($this->get_name()); ?>[font_family]"
					        data-value="<?php echo esc_attr($field_value['font_family']); ?>"></select>
				</div>
				<?php if (isset($this->params['font_size']) && $this->params['font_size']): ?>
					<div class="gsf-font-size">
						<div class="gsf-font-label"><?php esc_html_e('Font Size','smart-framework'); ?></div>
						<input data-field-control="" type="hidden" class="gsf-font-size-full"
						       name="<?php echo esc_attr($this->get_name()); ?>[font_size]"
						       value="<?php echo esc_attr($field_value['font_size']); ?>"/>
						<input type="number" placeholder="<?php esc_attr_e('Font size','smart-framework'); ?>" step="<?php echo esc_attr($step); ?>"
						       class="gsf-font-size-value" value="<?php echo esc_attr($font_size_value); ?>"/>
						<select class="gsf-font-size-unit">
							<option value="px" <?php selected('px', $font_size_unit); ?>>px</option>
							<option value="em" <?php selected('em', $font_size_unit); ?>>em</option>
							<option value="%" <?php selected('%', $font_size_unit); ?>>%</option>
						</select>
					</div>
				<?php endif;?>
				<?php if (isset($this->params['font_weight']) && $this->params['font_weight']): ?>
					<div class="gsf-font-weight-style">
						<input data-field-control="" type="hidden" class="gsf-font-weight"
						       name="<?php echo esc_attr($this->get_name()); ?>[font_weight]"
						       value="<?php echo esc_attr($field_value['font_weight']); ?>"/>
						<input data-field-control="" type="hidden" class="gsf-font-style"
						       name="<?php echo esc_attr($this->get_name()); ?>[font_style]"
						       value="<?php echo esc_attr($field_value['font_style']); ?>"/>
						<div class="gsf-font-label"><?php esc_html_e('Font Weight & Style','smart-framework'); ?></div>
						<select data-value="<?php echo esc_attr($field_value['font_weight'].$field_value['font_style']); ?>">
							<option value="400">Normal</option>
						</select>
					</div>
				<?php endif;?>
				<?php if (isset($this->params['font_subsets']) && $this->params['font_subsets']): ?>
					<div class="gsf-font-subsets">
						<div class="gsf-font-label"><?php esc_html_e('Font Subsets','smart-framework'); ?></div>
						<select data-field-control="" name="<?php echo esc_attr($this->get_name()); ?>[font_subsets]" data-value="<?php echo esc_attr($field_value['font_subsets']); ?>"></select>
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
				'font_kind' => 'google',
				'font_family' => "'Open Sans'",
				'font_size' => '14',
				'font_weight' => '400',
				'font_style' => '',
				'font_subsets' => ''
			);

			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			$default = wp_parse_args($field_default, $default);

			return $this->is_clone() ? array($default) : $default;
		}
	}
}