<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Radio')) {
	class GSF_Field_Radio extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'radio', GSF_PLUGIN_URL . 'fields/radio/assets/radio.js', array(), GSF_VER, true);
		}
		function render_content($content_args = '')
		{
			if (isset($this->params['data'])) {
				switch ($this->params['data']) {
					case 'sidebar':
						$this->params['options'] = gsf_get_sidebars();
						break;
					case 'menu':
						$this->params['options'] = gsf_get_menus();
						break;
					case 'taxonomy':
						$this->params['options'] = gsf_get_taxonomies(isset($this->params['data_args']) ? $this->params['data_args'] : array());
						break;
					default:
						if (isset($this->params['data_args']) && !isset($this->params['data_args']['post_type'])) {
							$this->params['data_args']['post_type'] = $this->params['data'];
						}
						$this->params['options'] = gsf_get_posts(isset($this->params['data_args']) ? $this->params['data_args'] : array('post_type' => $this->params['data']));
						break;
				}
			}

			if (!isset($this->params['options']) || !is_array($this->params['options'])) {
				return;
			}
			$field_value = $this->get_value();
			$value_inline = isset($this->params['value_inline']) ? $this->params['value_inline'] : true;
			?>
			<div class="gsf-field-radio-inner <?php echo ($value_inline ? 'value-inline' : ''); ?>">
				<?php foreach ($this->params['options'] as $key => $value): ?>
					<label>
						<input data-field-control="" type="radio" <?php echo ($key === $field_value ? ' checked="checked"' :''); ?>
							   class="gsf-radio"
						       name="<?php echo esc_attr($this->get_name()) ?>"
						       value="<?php echo esc_attr($key); ?>" />
						<span><?php echo esc_html($value); ?></span>
					</label>
				<?php endforeach;?>
			</div>
		<?php
		}
	}
}