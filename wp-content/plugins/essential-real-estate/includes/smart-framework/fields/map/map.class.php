<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Map')) {
	class GSF_Field_Map extends GSF_Field
	{
		public function enqueue()
		{
			$api_key = isset($this->params['api_key']) ? $this->params['api_key'] : 'AIzaSyBqmFdSPp4-iY_BG14j_eUeLwOn9Oj4a4Q';
			$google_map_url = apply_filters('gsf_google_map_api_url', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key);

			wp_enqueue_script('google-map', esc_url_raw($google_map_url), array(), '', true);
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'map', GSF_PLUGIN_URL . 'fields/map/assets/map.js', array(), GSF_VER, true);
			wp_enqueue_style(GSF_PLUGIN_RESOURCE_PREFIX . 'map', GSF_PLUGIN_URL . 'fields/map/assets/map.css', array(), GSF_VER);
		}

		function field_map() {
			return 'location,address';
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			if (!is_array($field_value)) {
				$field_value = array();
			}
			$value_default = array(
				'location' => isset($this->params['default']) ? $this->params['default'] : '-33.868419, 151.193245',
				'address' => ''
			);
			$field_value = wp_parse_args($field_value, $value_default);
			$js_options = isset($this->params['js_options']) ? $this->params['js_options'] : array();
			if (isset($js_options['styles'])) {
				$js_options['styles'] = json_decode($js_options['styles']);
			}
			$placeholder = isset($this->params['placeholder']) ? $this->params['placeholder'] : esc_html__('Enter an address...','smart-framework');
		    ?>
			<div class="gsf-field-map-inner">
				<input data-field-control="" type="hidden" class="gsf-map-location-field" name="<?php echo esc_attr($this->get_name()) ?>[location]" value="<?php echo esc_attr($field_value['location']); ?>"/>
				<?php if (!isset($this->params['show_address']) || $this->params['show_address']): ?>
					<div class="gsf-map-address">
						<div class="gsf-map-address-text">
							<input data-field-control="" type="text" placeholder="<?php echo esc_attr($placeholder); ?>" name="<?php echo esc_attr($this->get_name()) ?>[address]" value="<?php echo esc_attr($field_value['address']); ?>"/>
						</div>
						<button type="button" class="button"><?php echo esc_html__('Find Address','smart-framework'); ?></button>
						<div class="gsf-map-suggest"></div>
					</div>
				<?php endif;?>
				<div class="gsf-map-canvas" data-options="<?php echo esc_attr(wp_json_encode($js_options)); ?>"></div>
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
				'location' => isset($this->params['default']) ? $this->params['default'] : '-33.868419, 151.193245',
				'address' => ''
			);

			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			$default = wp_parse_args($field_default, $default);

			return $this->is_clone() ? array($default) : $default;
		}
	}
}