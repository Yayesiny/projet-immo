<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Image')) {
	class GSF_Field_Image extends GSF_Field
	{
		function field_map() {
			return 'id,url';
		}
		function enqueue()
		{
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'image', GSF_PLUGIN_URL . 'fields/image/assets/image.js', array(), GSF_VER, true);
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			if (!is_array($field_value)) {
				$field_value = array();
			}
			$default = array(
				'id'  => 0,
				'url' => ''
			);

			if (isset($this->params['default'])) {
				if (is_numeric($this->params['default'])) {
					$default['id'] = $this->params['default'];
					$default['url'] = wp_get_attachment_url($default['id']);
				}
				else {
					$default['url'] = $this->params['default'];
					$default['id'] = gsf_get_attachment_id($default['url']);
				}
			}
			$field_value = wp_parse_args($field_value, $default);

			$thumb_url = $field_value['url'];
			$image_attributes = wp_get_attachment_image_src($field_value['id']);
			if (!empty($image_attributes) && is_array($image_attributes)) {
				$thumb_url = $image_attributes[0];
			}
			?>
			<div class="gsf-field-image-inner gsf-clearfix">
				<input data-field-control="" type="hidden"
				       class="gsf-image-id"
				       name="<?php echo esc_attr($this->get_name()) ?>[id]"
				       value="<?php echo esc_attr($field_value['id']); ?>"/>
				<div class="gsf-image-preview">
					<div class="centered">
						<img src="<?php echo esc_url($thumb_url); ?>" alt="" style="<?php echo esc_attr(empty($thumb_url) ? 'display:none' : '') ?>"/>
					</div>
				</div>
				<div class="gsf-image-info">
					<input data-field-control="" type="text"
					       class="gsf-image-url" placeholder="<?php esc_html_e('No image', 'smart-framework'); ?>"
					       name="<?php echo esc_attr($this->get_name()) ?>[url]"
					       value="<?php echo esc_url($field_value['url']); ?>"/>
					<button type="button" class="button gsf-image-choose-image"><?php esc_html_e('Choose Image', 'smart-framework'); ?></button>
					<button type="button"
					        class="button gsf-image-remove"><?php esc_html_e('Remove', 'smart-framework'); ?></button>
					<?php if (isset($this->params['images_select_text']) && !empty($this->params['images_select_text'])): ?>
						<button type="button" class="button gsf-image-choose-image-dir"><?php echo esc_html($this->params['images_select_text']); ?></button>
					<?php endif;?>
				</div>
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
				'id'  => 0,
				'url' => ''
			);

			if (isset($this->params['default'])) {
				if (is_numeric($this->params['default'])) {
					$default['id'] = $this->params['default'];
					$default['url'] = wp_get_attachment_url($default['id']);
				}
				else {
					$default['url'] = $this->params['default'];
					$default['id'] = gsf_get_attachment_id($default['url']);
				}
			}

			return $this->is_clone() ? array($default) : $default;
		}
	}
}