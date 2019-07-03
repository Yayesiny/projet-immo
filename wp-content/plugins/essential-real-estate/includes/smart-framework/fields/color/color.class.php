<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Color')) {
	class GSF_Field_Color extends GSF_Field
	{
		public function enqueue()
		{
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script('wp_color_picker_alpha', GSF_PLUGIN_URL . 'assets/vendors/wp-color-picker-alpha.js', array(), '1.2', true);
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'color', GSF_PLUGIN_URL . 'fields/color/assets/color.js', array(), GSF_VER, true);
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$alpha = isset($this->params['alpha']) ? $this->params['alpha'] : false;
			$validate = array(
				'maxlength' => 7,
				'pattern'   => '^(#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3}))$'
			);
			if ($alpha) {
				$validate = array(
					'maxlength' => 22,
					'pattern'   => '^((#(([a-fA-F0-9]{6})|([a-fA-F0-9]{3})))|(rgba\(\d+,\d+,\d+,\d?(\.\d+)*\)))$'
				);
			}
			?>
			<div class="gsf-field-color-inner">
				<input data-field-control=""
				       class="gsf-color" type="text"
				       maxlength="<?php echo esc_attr($validate['maxlength']); ?>"
				       pattern="<?php echo esc_attr($validate['pattern']); ?>"
					<?php echo($alpha ? 'data-alpha="true"' : ''); ?>
				       name="<?php echo esc_attr($this->get_name()) ?>"
				       value="<?php echo esc_attr($field_value); ?>"/>
			</div>
		<?php
		}
	}
}