<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Sorter')) {
	class GSF_Field_Sorter extends GSF_Field
	{
		/**
		 * Enqueue field resources
		 */
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'sorter', GSF_PLUGIN_URL . 'fields/sorter/assets/sorter.js', array(), GSF_VER, true);
		}

		/**
		 * Define field map using get value in javascript
		 *
		 * @return string
		 */
		function field_map() {
			$default = array(
				'enable' => array(),
				'disable' => array()
			);
			if (isset($this->params['default']) && is_array($this->params['default'])) {
				$default = wp_parse_args($this->params['default'], $default);
			}
			$map_keys = array();
			foreach ($default as $key => $value) {
				$map_keys []= $key;
			}
			return join(',', $map_keys);

		}

		/**
		 * Render field
		 *
		 * @param string $content_args
		 */
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$default = array(
				'enable' => array(),
				'disable' => array()
			);
			if (empty($field_value)) {
				$field_value = $default;
			}
			if (isset($this->params['default']) && is_array($this->params['default'])) {
				foreach ($this->params['default'] as $key => $value) {
					if (!isset($field_value[$key])) {
						$field_value[$key] = array();
					}
				}
			}
			?>
			<div class="gsf-field-sorter-inner gsf-clearfix">
				<?php foreach ($field_value as $group_key => $group): ?>
					<div class="gsf-field-sorter-group" data-group="<?php echo esc_attr($this->get_name()) ?>[<?php echo esc_attr($group_key); ?>]">
						<div class="gsf-field-sorter-title"><?php echo esc_html($group_key); ?></div>
						<div class="gsf-field-sorter-items">
							<?php foreach ($group as $item_key => $item_value): ?>
								<div class="gsf-field-sorter-item" data-id="<?php echo esc_attr($item_key); ?>">
									<input data-field-control="" type="hidden"
									       data-item-name="<?php echo esc_attr($item_key); ?>"
									       name="<?php echo esc_attr($this->get_name()) ?>[<?php echo esc_attr($group_key); ?>][<?php echo esc_attr($item_key); ?>]"
									       value="<?php echo esc_attr($item_value); ?>"/>
									<?php echo esc_html($item_value); ?>
								</div>
							<?php endforeach;?>
						</div>
					</div>
				<?php endforeach;?>
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
				'enable' => array(),
				'disable' => array()
			);
			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			if (empty($field_default)) {
				$field_default = $default;
			}

			return $this->is_clone() ? array($field_default) : $field_default;
		}
	}
}