<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Group')) {
	class GSF_Field_Group extends GSF_Field
	{
		function html_start()
		{
			$field_id = $this->get_id();
			$this->params['clone'] = false;
			$isToggle = isset($this->params['toggle']) ? $this->params['toggle'] : true;
			$attr_style = '';
			if ($isToggle && !(isset($this->params['toggle_default']) ? $this->params['toggle_default'] : true)) {
				$attr_style = 'style="display:none"';
			}
			?>
			<div <?php echo (!empty($field_id) ? 'id="' . esc_attr($field_id) . '"' : ''); ?> class="gsf-group gsf-field" <?php $this->the_required(); ?>>
				<h4>
					<?php echo esc_html($this->params['title']); ?>
					<?php if ($isToggle): ?>
						<?php if (empty($attr_style)): ?>
							<span class="gsf-group-toggle dashicons dashicons-arrow-down"></span>
						<?php else: ?>
							<span class="gsf-group-toggle dashicons dashicons-arrow-up"></span>
						<?php endif;?>
					<?php endif;?>
				</h4>
				<div class="gsf-group-inner" <?php echo ($attr_style) ; ?>>
			<?php
		}
		function html_end() {
			?>
				</div><!-- /.gsf-group-inner -->
			</div><!-- /.gsf-group -->
			<?php
		}
		function render_content($content_args = '')
		{
			if (!isset($this->params['fields']) || !is_array($this->params['fields'])) {
				return;
			}
			$col = isset($this->params['col']) ? $this->params['col'] : 12;
			foreach ($this->params['fields'] as $field) {
				if (!isset($field['type'])) {
					continue;
				}
				if (!empty($this->panel_id) && ($field['type'] === 'panel')) {
					continue;
				}
				$field_cls = gsf_get_field_class_name($field['type']);
				$meta = new $field_cls($field, 'group', $col, $this->panel_id, $this->panel_index);
				$meta->panel_default = $this->panel_default;
				$meta->render();
			}
		}
	}
}