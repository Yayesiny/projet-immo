<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Button_Set')) {
	class GSF_Field_Button_Set extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'button_set', GSF_PLUGIN_URL . 'fields/button_set/assets/button-set.js', array(), GSF_VER, true);
		}
		function render_content($content_args = '')
		{
			if (!isset($this->params['options']) || !is_array($this->params['options'])) {
				return;
			}
			$field_value = $this->get_value();
			if (isset($this->params['multiple']) && $this->params['multiple'] && !is_array($field_value)) {
				$field_value = (array)$field_value;
			}
			$allowClear = (!isset($this->params['multiple']) || !$this->params['multiple'])
						&& (isset($this->params['allow_clear']) && $this->params['allow_clear']);
			?>
			<div class="gsf-field-button_set-inner">
				<?php foreach ($this->params['options'] as $key => $value): ?>
					<label>
						<?php if (isset($this->params['multiple']) && $this->params['multiple']): ?>
							<input data-field-control="" type="checkbox" name="<?php echo esc_attr($this->get_name()) ?>[]" value="<?php echo esc_attr($key); ?>" <?php echo (in_array($key, $field_value) ? ' checked="checked"' :''); ?>/>
						<?php else: ?>
							<input data-field-control="" type="radio" name="<?php echo esc_attr($this->get_name()) ?>" value="<?php echo esc_attr($key); ?>" <?php echo ($key == $field_value ? ' checked="checked"' :''); ?>/>
						<?php endif;?>
						<span class="<?php echo ($allowClear ? 'gsf-allow-clear' : ''); ?>"><?php echo esc_html($value); ?></span>
					</label>
				<?php endforeach;?>
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