<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Sortable')) {
	class GSF_Field_Sortable extends GSF_Field
	{
		function field_map() {
			if (!is_array($this->params['options'])) {
				$this->params['options'] = array();
			}
			return join(',', array_keys($this->params['options'])) . ',sort_order';
		}
		/**
		 * Enqueue field resources
		 */
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'sortable', GSF_PLUGIN_URL . 'fields/sortable/assets/sortable.js', array(), GSF_VER, true);
		}

		/**
		 * Render field
		 *
		 * @param string $content_args
		 */
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();

			if (!is_array($field_value)) {
				$field_value = array();
			}

			$sort = array();
			if (isset($field_value['sort_order'])) {
				$sort = explode('|', $field_value['sort_order']);
			}

			if (is_array($this->params['options'])) {
				foreach ($this->params['options'] as $key => $value) {
					if (!in_array($key, $sort)) {
						$sort[] = $key;
					}
				}

				foreach ($sort as $key => $value) {
					if (!isset($this->params['options'][$value])) {
						unset($field_value[$key]);
					}
				}
			}

			?>
			<div class="gsf-field-sortable-inner gsf-clearfix">
				<?php foreach ($sort as $sortValue): ?>
					<div class="gsf-field-sortable-item">
						<i class="dashicons dashicons-menu"></i>
						<label>
							<input class="gsf-field-sortable-checkbox" type="checkbox"
								   data-field-control=""
								   data-uncheck-novalue="true"
								   name="<?php echo esc_attr($this->get_name()) ?>[<?php echo esc_attr($sortValue) ?>]"
								   value="<?php echo esc_attr($sortValue) ?>"
								<?php echo in_array($sortValue, $field_value) ? 'checked="checked"' : ''; ?>/>
							<span><?php echo esc_html($this->params['options'][$sortValue]); ?></span>
						</label>
					</div>
					<input class="gsf-field-sortable-sort" data-field-control="" type="hidden" name="<?php echo esc_attr($this->get_name()) ?>[sort_order]" value="<?php echo join('|', $sort) ?>"/>
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
			$field_default = isset($this->params['default']) ? $this->params['default'] : array();

			return $this->is_clone() ? array($field_default) : $field_default;
		}
	}
}