<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Panel')) {
	class GSF_Field_Panel extends GSF_Field
	{
		function enqueue()
		{
			wp_enqueue_script(GSF_PLUGIN_RESOURCE_PREFIX . 'panel', GSF_PLUGIN_URL . 'fields/panel/assets/panel.js', array(), GSF_VER, true);
		}

		function is_clone() {
			return true;
		}
		function html_start()
		{
			$field_id = $this->get_id();
			?>
			<div id="<?php echo ($field_id); ?>" class="gsf-field-panel gsf-field" <?php $this->the_required(); ?>>
				<div class="gsf-field-panel-inner">
			<?php
		}
		function html_end() {
			?>
				</div><!-- /.gsf-field-panel-inner -->
			</div><!-- /.gsf-field-panel -->
			<?php
		}

		function html_content()
		{
			$count = 0;
			if ($this->is_clone()) {
				$count = $count = apply_filters('gsf_'. gsf_get_config_type() . '_get_panel_count', $count, $this);
			}
			$isToggle = isset($this->params['toggle']) ? $this->params['toggle'] : true;
			$attr_style = '';
			if ($isToggle && !(isset($this->params['toggle_default']) ? $this->params['toggle_default'] : true)) {
				$attr_style = 'style="display:none"';
			}
			?>
			<div class="gsf-field-content-wrap <?php echo ($this->is_sort() ? 'gsf-field-panel-sortable' : ''); ?>">
				<?php
				$content_wrap_class = 'gsf-field-content-inner gsf-field-content-inner-clone';
				if ($this->is_sort()) {
					$content_wrap_class .= ' gsf-field-sortable';
				}

				if (!$count) {
					$count = 1;
				}
				?>
				<div class="<?php echo esc_attr($content_wrap_class); ?>">
					<?php for ($i = 0; $i < $count; $i++): ?>
						<div class="gsf-field-content gsf-clone-field gsf-clone-field-panel"
						     data-panel-index="<?php echo esc_attr($i); ?>" data-panel-id="<?php echo esc_attr($this->get_id()); ?>">
							<?php if (!empty($this->params['title'])): ?>
								<h4 class="gsf-field-panel-title">
									<span class="gsf-panel-title" data-label="<?php echo esc_attr($this->params['title']); ?>"><?php echo esc_html($this->params['title']); ?></span>
									<?php if ($isToggle): ?>
										<?php if (empty($attr_style)): ?>
											<span class="gsf-group-toggle dashicons dashicons-arrow-down"></span>
										<?php else: ?>
											<span class="gsf-group-toggle dashicons dashicons-arrow-up"></span>
										<?php endif;?>
									<?php endif;?>
								</h4>
							<?php endif;?>
							<div class="gsf-clone-field-panel-inner" <?php echo ($attr_style) ; ?>>
								<?php $this->render_content($i); ?>
							</div>
							<?php $this->html_clone_button_remove(); ?>
						</div><!-- /.gsf-field-content -->
					<?php endfor; ?>
				</div>
				<?php $this->html_desc(); ?>
				<?php $this->html_clone_button_add(); ?>
			</div><!-- /.gsf-field-content-wrap -->
		<?php
		}

		/**
		 * Render content for panel field
		 * *******************************************************
		 */
		function render_content($index = 0)
		{
			if (!isset($this->params['fields']) || !is_array($this->params['fields'])) {
				return;
			}
			$col = isset($this->params['col']) ? $this->params['col'] : 12;
			foreach ($this->params['fields'] as $field) {
				if (!isset($field['type']) || ($field['type'] === 'panel')) {
					continue;
				}
				$field_cls = gsf_get_field_class_name($field['type']);
				$meta = new $field_cls($field, 'panel', $col, $this->get_id());
				$meta->panel_index = $index;
				$meta->panel_default = $this->get_default();
				$meta->render();
			}
		}

		/**
		 * Get default value
		 *
		 * @return array
		 */
		function get_default() {
			$default  = array(array());
			$field_default = isset($this->params['default']) ? $this->params['default'] : $default;
			return $field_default;
		}
	}
}