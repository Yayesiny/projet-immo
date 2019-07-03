<?php
/**
 * Field Ace Editor
 *
 * @package SmartFramework
 * @subpackage Fields
 * @author g5plus
 * @version 1.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Border')) {
	class GSF_Field_Border extends GSF_Field
	{
		function enqueue()
		{
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script('wp_color_picker_alpha', GSF_PLUGIN_URL . 'assets/vendors/wp-color-picker-alpha.js', array(), '1.2', true);
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'border', GSF_PLUGIN_URL . 'fields/border/assets/border.js', array(), GSF_VER, true);
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			if (!is_array($field_value)) {
				$field_value = array();
			}

			$default = array(
				'border_color' => '#fff',
				'border_width' => '',
				'border_top_width' => '',
				'border_right_width' => '',
				'border_bottom_width' => '',
				'border_left_width' => '',
				'border_style' => '',
			);

			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			$field_default = wp_parse_args($field_default, $default);
			$field_value = wp_parse_args($field_value, $field_default);

			$border_style = array(
				'none'    => 'None',
				'hidden'  => 'Hidden',
				'dotted'  => 'Dotted',
				'dashed'  => 'Dashed',
				'solid'   => 'Solid',
				'double'  => 'Double',
				'groove'  => 'Groove',
				'ridge'   => 'Ridge',
				'inset'   => 'Inset',
				'outset'  => 'Outset',
				'initial' => 'Initial',
				'inherit' => 'Inherit',
			);

			$border_top = isset($this->params['top']) && $this->params['top'];
			$border_right = isset($this->params['right']) && $this->params['right'];
			$border_bottom = isset($this->params['bottom']) && $this->params['bottom'];
			$border_left = isset($this->params['left']) && $this->params['left'];

			?>
			<div class="gsf-field-border-inner">
				<?php if (!$border_top && !$border_right && !$border_bottom && !$border_left): ?>
					<div class="gsf-border-width-info">
						<span class="dashicons dashicons-move"></span>
						<input data-field-control="" type="number" class="gsf-border-width"
						       min="0" placeholder="<?php esc_html_e('All','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[border_width]"
						       value="<?php echo esc_attr($field_value['border_width']); ?>" />
					</div>
				<?php endif;?>
				<?php if ($border_top): ?>
					<div class="gsf-border-width-info">
						<span class="dashicons dashicons-arrow-up-alt"></span>
						<input data-field-control="" type="number" class="gsf-border-top-width"
						       min="0" placeholder="<?php esc_html_e('Top','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[border_top_width]"
						       value="<?php echo esc_attr($field_value['border_top_width']); ?>" />
					</div>
				<?php endif;?>
				<?php if ($border_right): ?>
					<div class="gsf-border-width-info">
						<span class="dashicons dashicons-arrow-right-alt"></span>
						<input data-field-control="" type="number" class="gsf-border-top-width" min="0"
						       placeholder="<?php esc_html_e('Right','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[border_right_width]"
						       value="<?php echo esc_attr($field_value['border_right_width']); ?>" />
					</div>
				<?php endif;?>
				<?php if ($border_bottom): ?>
					<div class="gsf-border-width-info">
						<span class="dashicons dashicons-arrow-down-alt"></span>
						<input data-field-control="" type="number" class="gsf-border-top-width" min="0"
						       placeholder="<?php esc_html_e('Bottom','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[border_bottom_width]"
						       value="<?php echo esc_attr($field_value['border_bottom_width']); ?>" />
					</div>
				<?php endif;?>
				<?php if ($border_left): ?>
					<div class="gsf-border-width-info">
						<span class="dashicons dashicons-arrow-left-alt"></span>
						<input data-field-control="" type="number" class="gsf-border-top-width" min="0"
						       placeholder="<?php esc_html_e('Left','smart-framework'); ?>"
						       name="<?php echo esc_attr($this->get_name()) ?>[border_left_width]"
						       value="<?php echo esc_attr($field_value['border_left_width']); ?>"/>
					</div>
				<?php endif;?>
				<select data-field-control=""
				        name="<?php echo esc_attr($this->get_name()) ?>[border_style]"
				        class="gsf-border-style">
					<?php foreach ($border_style as $value => $text): ?>
						<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $field_value['border_style'], true); ?>><?php echo esc_html($text); ?></option>
					<?php endforeach;?>
				</select>
				<div><input data-field-control="" type="text" data-alpha="true"
				            class="gsf-border-color" name="<?php echo esc_attr($this->get_name()) ?>[border_color]" value="<?php echo esc_attr($field_value['border_color']); ?>"/></div>
			</div>
		<?php
		}

		/**
		 * Get default value
		 * @since   1.0
		 * @return  array
		 */
		function get_default() {
			$default = array(
				'border_color' => '#fff',
				'border_width' => '',
				'border_top_width' => '',
				'border_right_width' => '',
				'border_bottom_width' => '',
				'border_left_width' => '',
				'border_style' => '',
			);
			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			$default = wp_parse_args($field_default, $default);
			return $this->is_clone() ? array($default) : $default;
		}
	}
}