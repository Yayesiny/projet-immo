<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Icon')) {
	class GSF_Field_Icon extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'icon', GSF_PLUGIN_URL . 'fields/icon/assets/icon.js', array(), GSF_VER, true);
		}
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			?>
			<div class="gsf-field-icon-inner">
				<input data-field-control="" type="hidden"
				       name="<?php echo esc_attr($this->get_name()) ?>"
				       value="<?php echo esc_attr($field_value); ?>"/>
				<div class="gsf-icon"
				     data-icon-title="<?php esc_html_e('Select icon','smart-framework'); ?>"
				     data-icon-remove="<?php esc_html_e('Remove icon','smart-framework'); ?>"
				     data-icon-search="<?php esc_html_e('Search icon...','smart-framework'); ?>">
					<div class="gsf-icon-info">
						<span class="<?php echo esc_attr($field_value); ?>"></span>
						<div class="gsf-icon-label"><?php esc_html_e('Set Icon','smart-framework'); ?></div>
					</div>
				</div>
			</div>
		<?php
		}
	}
}