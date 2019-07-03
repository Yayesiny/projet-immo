<?php
/**
 * Field Background
 *
 * @package SmartFramework
 * @subpackage Fields
 * @author g5plus
 * @since 1.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Background')) {
	class GSF_Field_Background extends GSF_Field
	{
		function field_map() {
			return 'background_color,background_image_id,background_image_url,background_repeat,background_size,background_position,background_attachment';
		}

		function enqueue()
		{
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script('wp_color_picker_alpha', GSF_PLUGIN_URL . 'assets/vendors/wp-color-picker-alpha.js', array(), '1.2', true);
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'background', GSF_PLUGIN_URL . 'fields/background/assets/background.js', array(), GSF_VER, true);
		}

		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			if (!is_array($field_value)) {
				$field_value = array();
			}

			$default = array(
				'background_color'      => '#fff',
				'background_image_id'      => 0,
				'background_image_url'      => '',
				'background_repeat'     => 'repeat',
				'background_size'       => 'contain',
				'background_position'   => 'center center',
				'background_attachment' => 'scroll',
			);

			$field_default = isset($this->params['default']) ? $this->params['default'] : array();
			if (isset($this->params['default'])) {
				if (is_array($this->params['default'])) {
					if (isset($field_default['background_image_id']) && is_numeric($field_default['background_image_id'])) {
						$field_default = array(
							'background_image_id' => $field_default['background_image_id'],
							'background_image_url' => wp_get_attachment_url($field_default['background_image_id']),
						);
					}
					elseif (isset($field_default['background_image_url']) && !empty($field_default['background_image_url'])) {
						$field_default = array(
							'background_image_id' => gsf_get_attachment_id($field_default['background_image_url']),
							'background_image_url' => $field_default['background_image_url'],
						);
					}
				} else {
					if (is_numeric($field_default)) {
						$field_default = array(
							'background_image_id' => $field_default,
							'background_image_url' => wp_get_attachment_url($field_default),
						);
					}
					else {
						$field_default = array(
							'background_image_id' => gsf_get_attachment_id($field_default),
							'background_image_url' => $field_default,
						);
					}
				}

			}

			$field_default = wp_parse_args($field_default, $default);
			$field_value = wp_parse_args($field_value, $field_default);

			$background_repeat = array(
				'repeat'    => 'Repeat',
				'repeat-x'  => 'Repeat Horizontal',
				'repeat-y'  => 'Repeat Vertical',
				'no-repeat' => 'No Repeat',
				'inherit'   => 'Inherit',
				'initial'   => 'Initial',
			);

			$background_size = array(
				'auto'    => 'Auto',
				'length'  => 'Length',
				'cover'   => 'Cover',
				'contain' => 'Contain',
				'inherit' => 'Inherit',
				'initial' => 'Initial',
			);

			$background_position = array(
				'left top'      => 'Left Top',
				'left center'   => 'Left Center',
				'left bottom'   => 'Left Bottom',
				'center top'    => 'Center Top',
				'center center' => 'Center Center',
				'center bottom' => 'Center Bottom',
				'right top'     => 'Right Top',
				'right center'  => 'Right Center',
				'right bottom'  => 'Right Bottom',
			);

			$background_attachment = array(
				'scroll'  => 'Scroll',
				'fixed'   => 'Fixed',
				'local'   => 'Local',
				'inherit' => 'Inherit',
				'initial' => 'Initial',
			);

			$image_preview_class = '';
			if (empty($field_value)) {
				$image_preview_class = 'no-preview';
			}


			$is_background_color = isset($this->params['background_color']) ? $this->params['background_color'] : true;

			?>
			<div class="gsf-field-background-inner gsf-clearfix" data-url="<?php echo esc_url(admin_url('admin-ajax.php?action=gsf_get_attachment_id')); ?>">
				<input data-field-control="" type="hidden"
				       class="gsf-background-image"
				       name="<?php echo esc_attr($this->get_name()) ?>[background_image_id]"
				       value="<?php echo esc_attr($field_value['background_image_id']); ?>"/>
				<div class="gsf-background-preview <?php echo $image_preview_class; ?>"></div>
				<div class="gsf-background-info">
					<?php if ($is_background_color) : ?>
					<div><input data-field-control="" type="text"  data-alpha="true"
					            class="gsf-background-color" name="<?php echo esc_attr($this->get_name()) ?>[background_color]" value="<?php echo esc_attr($field_value['background_color']); ?>"/></div>
					<?php endif; ?>
					<div>
						<input data-field-control="" type="text"
						       placeholder="<?php esc_html_e('No background image','smart-framework'); ?>" class="gsf-background-url"
						       name="<?php echo esc_attr($this->get_name()) ?>[background_image_url]"
						       value="<?php echo esc_url($field_value['background_image_url']); ?>"/>
						<button type="button" class="button gsf-background-choose-image"><?php esc_html_e('Choose Image','smart-framework'); ?></button>
						<button type="button" class="button gsf-background-remove-image"><?php esc_html_e('Remove','smart-framework'); ?></button>
					</div>
					<div class="gsf-background-attr">
						<div class="gsf-background-attr-title"><?php esc_html_e('Background Image Properties','smart-framework'); ?></div>
						<select data-field-control="" name="<?php echo esc_attr($this->get_name()) ?>[background_repeat]" class="gsf-background-repeat">
							<?php foreach ($background_repeat as $value => $text): ?>
								<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $field_value['background_repeat'], true); ?>><?php echo esc_html($text); ?></option>
							<?php endforeach;?>
						</select>
						<select data-field-control="" name="<?php echo esc_attr($this->get_name()) ?>[background_size]" class="gsf-background-size">
							<?php foreach ($background_size as $value => $text): ?>
								<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $field_value['background_size'], true); ?>><?php echo esc_html($text); ?></option>
							<?php endforeach;?>
						</select>
						<select data-field-control="" name="<?php echo esc_attr($this->get_name()) ?>[background_position]" class="gsf-background-position">
							<?php foreach ($background_position as $value => $text): ?>
								<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $field_value['background_position'], true); ?>><?php echo esc_html($text); ?></option>
							<?php endforeach;?>
						</select>
						<select data-field-control="" name="<?php echo esc_attr($this->get_name()) ?>[background_attachment]" class="gsf-background-attachment">
							<?php foreach ($background_attachment as $value => $text): ?>
								<option value="<?php echo esc_attr($value); ?>" <?php selected($value, $field_value['background_attachment'], true); ?>><?php echo esc_html($text); ?></option>
							<?php endforeach;?>
						</select>
					</div>
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
				'background_color'      => '#fff',
				'background_image_id'      => 0,
				'background_image_url'      => '',
				'background_repeat'     => 'repeat',
				'background_size'       => 'contain',
				'background_position'   => 'center center',
				'background_attachment' => 'scroll',
			);
			$field_default = isset($this->params['default']) ? $this->params['default'] : array();

			if (isset($this->params['default'])) {
				if (is_array($this->params['default'])) {
					if (isset($field_default['background_image_id']) && is_numeric($field_default['background_image_id'])) {
						$field_default = array(
							'background_image_id' => $field_default['background_image_id'],
							'background_image_url' => wp_get_attachment_url($field_default['background_image_id']),
						);
					}
					elseif (isset($field_default['background_image_url']) && !empty($field_default['background_image_url'])) {
						$field_default = array(
							'background_image_id' => gsf_get_attachment_id($field_default['background_image_url']),
							'background_image_url' => $field_default['background_image_url'],
						);
					}
				} else {
					if (is_numeric($field_default)) {
						$field_default = array(
							'background_image_id' => $field_default,
							'background_image_url' => wp_get_attachment_url($field_default),
						);
					}
					else {
						$field_default = array(
							'background_image_id' => gsf_get_attachment_id($field_default),
							'background_image_url' => $field_default,
						);
					}
				}

			}


			$default = wp_parse_args($field_default, $default);
			return $this->is_clone() ? array($default) : $default;
		}
	}
}