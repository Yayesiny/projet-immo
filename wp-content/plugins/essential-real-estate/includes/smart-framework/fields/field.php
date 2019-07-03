<?php
if (!class_exists('GSF_Field')) {
	abstract class GSF_Field
	{
		public $parent_type;
		public $parent_col;
		public $params;
		public $index;
		public $panel_id;
		public $panel_index;
		public $panel_default;

		public function __construct(
			&$params = array()
			, $parent_type = ''
			, $parent_col = 12
			, $panel_id = ''
			, $panel_index = 0)
		{
			$this->params = &$params;
			$this->parent_type = $parent_type;
			$this->parent_col = $parent_col;
			$this->panel_id = $panel_id;
			$this->panel_index = $panel_index;
			$this->index = 0;
			$this->panel_default = array(array());

			/**
			 * Enqueue script & stylesheet
			 */
			add_action('admin_footer', array($this, 'enqueue'));
		}

		public function enqueue()
		{
			// Nothing
		}

		/**
		 * Define map for field.
		 * Example: field contain 2 attribute: width, height --> return field map: 'width,height'
		 * *******************************************************
		 */
		public function field_map() {
			return '';
		}

		/**
		 * Add clone button bellow field content (only clone field)
		 * *******************************************************
		 */
		public function html_clone_button_add()
		{
			$btn_class = 'gsf-clone-button-add button';
			if (isset($this->params['type']) && ($this->params['type'] === 'repeater')) {
				$btn_class .= ' gsf-is-repeater';
			}
			?>
			<button class="<?php echo esc_attr($btn_class); ?>"
			        type="button"><?php echo esc_html__('+ Add more', 'smart-framework'); ?></button>
		<?php
		}

		/**
		 * Add remove clone button in field clone content (only clone field)
		 * *******************************************************
		 */
		public function html_clone_button_remove()
		{
			$btn_class = 'gsf-clone-button-remove';
			if (isset($this->params['type']) && ($this->params['type'] === 'repeater')) {
				$btn_class .= ' gsf-is-repeater';
			}
			?>
			<span class="<?php echo esc_attr($btn_class); ?>"><i class="dashicons dashicons-dismiss"></i></span>
		<?php
		}

		/**
		 * Add sort clone button in field clone content (only clone field)
		 * *******************************************************
		 */
		public function html_clone_button_sort()
		{
			$btn_class = 'gsf-sortable-button';
			if (isset($this->params['type']) && ($this->params['type'] === 'repeater')) {
				$btn_class .= ' gsf-is-repeater';
			}
			?>
			<span class="<?php echo esc_attr($btn_class); ?>"><i class="dashicons dashicons-menu"></i></span>
		<?php
		}

		/**
		 * Render html field label
		 * *******************************************************
		 */
		public function html_label()
		{
			if ($this->parent_type === 'repeater') {
				return;
			}
			?>
			<div class="gsf-label">
				<div class="gsf-title"><?php echo wp_kses_post(isset($this->params['title']) ? $this->params['title'] : ''); ?></div>
				<?php if (!empty($this->params['subtitle'])): ?>
					<div class="gsf-subtitle"><?php echo wp_kses_post($this->params['subtitle']); ?></div>
				<?php endif;?>
			</div>
		<?php
		}

		/**
		 * Render html field description
		 * *******************************************************
		 */
		public function html_desc()
		{
			if ($this->parent_type === 'repeater') {
				return;
			}
			if (!isset($this->params['desc']) || empty($this->params['desc'])) {
				return;
			}
			?>
			<p class="gsf-desc"><?php echo wp_kses_post($this->params['desc']); ?></p>
		<?php
		}

		/**
		 * Render html start field
		 * *******************************************************
		 */
		public function html_start()
		{
			/*
			 * Not Allow clone field in clone ROW/GROUP
			 */
			$field_value = $this->get_value();
			$field_string = is_array($field_value) ? json_encode($field_value) : $field_value;
			?>
			<?php if (($this->parent_type === 'row') || ($this->parent_type === 'repeater')): ?>
				<div id="<?php echo esc_attr($this->get_id()); ?>"
					<?php echo(isset($this->params['type']) ? 'data-field-type="' . esc_attr($this->params['type']) . '"' : ''); ?>
					data-field-value="<?php echo esc_attr($field_string); ?>"
					data-field-map="<?php echo esc_attr($this->field_map()); ?>"
					data-input-name="<?php echo esc_attr($this->get_input_name()); ?>"
					<?php $this->the_required(); ?>
					<?php $this->the_preset(); ?>
					class="gsf-field gsf-col gsf-col-<?php echo esc_attr($this->get_col()); ?> <?php echo esc_attr($this->get_layout() . '-col'); ?> <?php echo(isset($this->params['type']) ? 'gsf-field-' . $this->params['type'] : ''); ?>">
						<?php $this->html_label(); ?>

			<?php else: ?>
					<div id="<?php echo esc_attr($this->get_id()); ?>"
						<?php echo(isset($this->params['type']) ? 'data-field-type="' . esc_attr($this->params['type']) . '"' : ''); ?>
						<?php $this->the_required(); ?>
						<?php $this->the_preset(); ?>
						data-field-value="<?php echo esc_attr($field_string); ?>"
						data-field-map="<?php echo esc_attr($this->field_map()); ?>"
						data-input-name="<?php echo esc_attr($this->get_input_name()); ?>"
						class="gsf-field gsf-row-outer <?php echo(isset($this->params['type']) ? 'gsf-field-' . $this->params['type'] : ''); ?> <?php echo esc_attr($this->get_layout()); ?>">
						<div class="gsf-row">
							<div class="gsf-col gsf-col-<?php echo esc_attr($this->get_col()); ?> <?php echo esc_attr($this->get_layout() . '-col'); ?>">
								<?php $this->html_label(); ?>
			<?php endif; ?>
		<?php
		}

		/**
		 * Render end field
		 * *******************************************************
		 */
		public function html_end()
		{
			?>
			<?php if ($this->parent_type === 'row' || ($this->parent_type === 'repeater')): ?>
				</div><!-- /.gsf-col -->
			<?php else: ?>
					</div><!-- /.gsf-col -->
				</div><!-- /.gsf-row -->
			</div><!-- /.gsf-row-outer -->
			<?php endif; ?>
		<?php
		}

		/**
		 * Render html field content
		 * *******************************************************
		 */
		public function html_content()
		{
			$count = 0;
			if ($this->is_clone()) {
				$count = apply_filters('gsf_'. gsf_get_config_type() . '_get_clone_count', $count, $this);
			}
			$wrapper_class = 'gsf-field-content-wrap';
			if (in_array($this->params['type'], array('row', 'group'))) {
				$wrapper_class .= ' gsf-no-margin-bottom';
			}
			?>
			<div class="<?php echo esc_attr($wrapper_class); ?>">
				<?php if ($this->is_clone()): ?>
					<?php
					$content_wrap_class = 'gsf-field-content-inner gsf-field-content-inner-clone';
					if ($this->is_sort()) {
						$content_wrap_class .= ' gsf-field-sortable';
					}

					$this->index = 0;
					if (!$count) {
						$count = 1;
					}
					?>
					<div class="<?php echo esc_attr($content_wrap_class); ?>">
						<?php for ($i = 0; $i < $count; $i++): ?>
							<div class="gsf-field-content gsf-clone-field">
								<?php if ($this->is_sort()): ?>
									<?php $this->html_clone_button_sort(); ?>
								<?php endif; ?>
								<?php $this->render_content(); ?>
								<?php $this->html_clone_button_remove(); ?>
								<?php $this->index++; ?>
							</div><!-- /.gsf-field-content -->
						<?php endfor; ?>
					</div>
					<?php $this->html_desc(); ?>
					<?php $this->html_clone_button_add(); ?>
				<?php else: ?>
					<div class="gsf-field-content">
						<?php $this->render_content(); ?>
					</div><!-- /.gsf-field-content -->
					<?php $this->html_desc(); ?>
				<?php endif; ?>
			</div><!-- /.gsf-field-content-wrap -->
		<?php
		}

		/**
		 * Render content for field
		 * *******************************************************
		 */
		public function render_content($content_args = '')
		{
			echo '';
		}

		/**
		 * Render Field
		 * *******************************************************
		 */
		public function render()
		{
			$this->html_start();
			$this->html_content();
			$this->html_end();
		}

		/**
		 * Get Meta Value
		 * *******************************************************
		 */
		public function get_value() {
			return apply_filters('gsf_'. gsf_get_config_type() . '_get_value', '', $this);
		}

		/**
		 * Get Default Value
		 *
		 * @return array | string
		 */
		public function get_default() {
			return isset($this->params['default'])
				? $this->params['default']
				: ($this->is_clone() ? array('') : '');
		}

		/**
		 * Get Field ID
		 */
		public function get_id()
		{
			$field_id = isset($this->params['id']) ? $this->params['id'] : '';
			if (!empty($this->panel_id)) {
				$field_id = $this->panel_id . '_' . $field_id;
			}
			return $field_id;
		}

		public function get_input_name() {
			$field_id = isset($this->params['id']) ? $this->params['id'] : '';
			if (!empty($this->panel_id)) {
				$field_id = $this->panel_id . '[' . $field_id . ']';
			}
			return $field_id;
		}

		/**
		 * Get Field Name
		 */
		public function get_name()
		{
			$name = isset($this->params['id']) ? $this->params['id'] : '';
			if (empty($this->panel_id)) {
				if ($this->is_clone() || ($this->parent_type === 'repeater')) {
					return sprintf('%s[%d]', $name, $this->index);
				}
				return $name;
			}
			if ($this->is_clone() || ($this->parent_type === 'repeater')) {
				return sprintf('%s[%d][%s][%d]', $this->panel_id, $this->panel_index, $name, $this->index);
			}

			return sprintf('%s[%d][%s]', $this->panel_id, $this->panel_index, $name);
		}

		/**
		 * Get Field Grid
		 */
		public function get_col()
		{
			if (in_array($this->parent_type, array('row', 'repeater'))) {
				$col = isset($this->params['col']) ? $this->params['col'] : $this->parent_col;
			} else {
				$col = isset($this->params['col']) ? $this->params['col'] : 12;
			}
			if (empty($col)) {
				$col = 12;
			}
			return $col;
		}

		/**
		 * Check Field is Clone
		 */
		public function is_clone()
		{
			return isset($this->params['clone']) && $this->params['clone'];
		}

		/**
		 * Check Field is Sort
		 */
		public function is_sort()
		{
			return isset($this->params['sort']) && $this->params['sort'];
		}

		/**
		 * Echo required field data
		 */
		public function the_required() {
			$required = isset($this->params['required']) ? $this->params['required'] : array();
			$panel_id = !empty($this->panel_id) ? $this->panel_id . '_' : '';
			if (!empty($required)) {
				if (!is_array($required[0])) {
					$required[0] = $panel_id . $required[0];
				}
				else {
					foreach ($required as &$required_child) {
						if (!is_array($required_child[0])) {
							$required_child[0] = $panel_id . $required_child[0];
						}
						else {
							foreach ($required_child as &$required_grand_child) {
								$required_grand_child[0] = $panel_id . $required_grand_child[0];
							}
						}
					}
				}
				printf("data-required='%s'", json_encode($required));
			}
		}

		/**
		 * Echo required field data
		 */
		public function the_preset() {
			$preset_setting = isset($this->params['preset']) ? $this->params['preset'] : array();
			$panel_id = !empty($this->panel_id) ? $this->panel_id . '_' : '';
			if (!empty($preset_setting)) {
				foreach ($preset_setting as &$preset) {
					if (isset($preset['fields'])) {
						foreach ($preset['fields'] as &$field) {
							$field[0] = $panel_id . $field[0];
						}
					}
				}
				printf("data-preset='%s'", json_encode($preset_setting));
			}
		}

		/**
		 * Get config layout: horizontal | vertical
		 * default: vertical
		 * *******************************************************
		 */
		public function get_layout() {
			$layout = isset($this->params['layout']) ? $this->params['layout'] : gsf_get_config_layout();
			if (!empty($layout)) {
				$layout = 'gsf-layout-' . $layout;
			}
			return $layout;
		}


		/**  **/
	}
}