<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Checkbox')) {
	class GSF_Field_Checkbox extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'checkbox', GSF_PLUGIN_URL . 'fields/checkbox/assets/checkbox.js', array(), GSF_VER, true);
		}
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			?>
			<div class="gsf-field-checkbox-inner">
				<label>
					<input data-field-control="" class="gsf-checkbox" type="checkbox"<?php echo $field_value ? 'checked="checked"' : ''; ?>
					       name="<?php echo esc_attr($this->get_name()) ?>"
					       value="1"/>
					<span><?php echo wp_kses_post($this->params['desc']) ?></span>
				</label>
			</div>
		<?php
		}
		public function html_desc() {
		}
	}
}