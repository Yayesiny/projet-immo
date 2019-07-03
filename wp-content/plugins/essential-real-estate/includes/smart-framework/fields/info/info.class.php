<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_Field_Info')) {
	class GSF_Field_Info extends GSF_Field
	{
		function render()
		{
			$desc = isset($this->params['desc']) ? $this->params['desc']: '';
			$title = isset($this->params['title']) ? $this->params['title']: '';
			$class_inner = array('gsf-info-inner');
			if (isset($this->params['style'])) {
				$class_inner[] = 'gsf-info-style-' . $this->params['style'];
			}

			$icon = isset($this->params['icon']) ? $this->params['icon'] : '';
			if ($icon === true) {
				if (isset($this->params['style'])) {
					switch ($this->params['style']) {
						case 'info':
							$icon = 'dashicons-info';
							break;
						case 'warning':
							$icon = 'dashicons-shield-alt';
							break;
						case 'success':
							$icon = 'dashicons-yes';
							break;
						case 'error':
							$icon = 'dashicons-dismiss';
							break;
					}
				}
				else {
					$icon = 'dashicons-wordpress';
				}
			}

			if (isset($this->params['icon'])) {
				$class_inner[] = 'gsf-info-has-icon gsf-clearfix';
			}

			?>
			<div id="<?php echo esc_attr($this->get_id()); ?>" class="gsf-field gsf-field-info" <?php $this->the_required(); ?>>
				<div class="<?php echo join(' ', $class_inner) ?>">
					<div class="gsf-info-content">
						<?php if (isset($this->params['icon'])): ?>
							<span class="gsf-info-content-icon dashicons <?php echo esc_attr($icon); ?>"></span>
						<?php endif;?>
						<?php if (!empty($title)): ?>
							<div class="gsf-info-content-title">
								<?php echo wp_kses_post($title); ?>
							</div>
						<?php endif;?>
						<div class="gsf-info-content-desc">
							<?php echo wp_kses_post($desc); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}