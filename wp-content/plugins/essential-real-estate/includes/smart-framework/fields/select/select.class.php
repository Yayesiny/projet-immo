<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Select')) {
	class GSF_Field_Select extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'select', GSF_PLUGIN_URL . 'fields/select/assets/select.js', array(), GSF_VER, true);
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
			$multiple = isset($this->params['multiple']) ? $this->params['multiple'] : false;
			?>
			<div class="gsf-field-select-inner">
				<select data-field-control="" class="gsf-select"
				        name="<?php echo esc_attr($this->get_name()) ?><?php echo $multiple ? '[]' : ''; ?>" <?php echo ($multiple ? ' multiple="multiple"' : '' ); ?>>
					<?php foreach ($this->params['options'] as $key => $value): ?>
						<?php if (is_array($value)): ?>
							<optgroup label="<?php echo esc_attr($key); ?>">
								<?php foreach ($value as $opt_key => $opt_value): ?>
									<option <?php gsf_the_selected($opt_key, $field_value) ?>
										value="<?php echo esc_attr($opt_key); ?>"><?php echo esc_html($opt_value); ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php else:; ?>
							<option value="<?php echo esc_attr($key); ?>" <?php gsf_the_selected($key, $field_value) ?>><?php echo esc_html($value); ?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				</select>
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