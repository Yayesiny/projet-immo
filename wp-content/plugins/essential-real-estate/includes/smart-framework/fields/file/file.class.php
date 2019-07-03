<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_File')) {
	class GSF_Field_File extends GSF_Field
	{
		function enqueue() {
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'file', GSF_PLUGIN_URL . 'fields/file/assets/file.js', array(), GSF_VER, true);
			wp_localize_script(GSF_PLUGIN_RESOURCE_PREFIX . 'file', 'sfFileFieldMeta', array(
				'title'   => esc_html__('Select File', 'smart-framework'),
				'button'  => esc_html__('Use these files', 'smart-framework')
			));
		}

		/*
		 * Render field content
		 */
		function render_content($content_args = '')
		{
			$field_value = $this->get_value();
			$field_value_arr = explode('|', $field_value);
			$remove_text = esc_html__('Remove','smart-framework');
			$lib_filter = '';
			if (isset($this->params['lib_filter']) && !empty($this->params['lib_filter'])) {
				$lib_filter = sprintf('data-lib-filter="%s"', esc_attr($this->params['lib_filter']));
			}
			?>
			<div class="gsf-field-file-inner" data-remove-text="<?php echo esc_attr($remove_text); ?>" <?php echo ($lib_filter); ?>>
				<input data-field-control="" type="hidden" name="<?php echo esc_attr($this->get_name()) ?>" value="<?php echo esc_attr($field_value); ?>"/>
				<?php foreach ($field_value_arr as $file_id): ?>
					<?php
					if (empty($file_id)) {
						continue;
					}
					$file_meta = get_post($file_id);
					if ($file_meta == null) {
						continue;
					}
					?>
					<div class="gsf-file-item" data-file-id="<?php echo esc_attr($file_id); ?>">
						<span class="dashicons dashicons-media-document"></span>
						<div class="gsf-file-info">
							<a class="gsf-file-title" href="<?php echo esc_url(get_edit_post_link($file_id)); ?>" target="_blank"><?php echo esc_html($file_meta->post_title); ?></a>
							<div class="gsf-file-name"><?php echo esc_html(wp_basename($file_meta->guid)); ?></div>
							<div class="gsf-file-action">
								<span class="gsf-file-remove"><span class="dashicons dashicons-no-alt"></span>  <?php echo esc_html($remove_text) ?></span>
							</div>
						</div>
					</div>
				<?php endforeach;?>
				<div class="gsf-file-add">
					<button class="button" type="button"><?php esc_html_e('+ Add File','smart-framework'); ?></button>
				</div>
			</div>
			<?php
		}
	}
}