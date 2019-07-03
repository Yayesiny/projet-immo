<div class="gsf-theme-options-wrapper wrap" style="display: none">
	<h2 class="screen-reader-text"><?php echo esc_html($page_title) ?></h2>
	<?php do_action("gsf/{$option_name}-theme-option-form/before") ?>
	<form action="#" method="post" enctype="multipart/form-data">
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( $option_name); ?>" />
		<input type="hidden" id="_current_page" name="_current_page" value="<?php echo esc_attr($page); ?>"/>
		<div class="gsf-theme-options-title gsf-clearfix">
			<h1><?php echo esc_html($page_title) ?></h1>

			<div class="gsf-theme-options-action">
				<button class="button gsf-theme-options-import" type="button"><?php esc_html_e('Import/Export','smart-framework'); ?></button>
				<button class="button gsf-theme-options-reset-section" type="button"><?php esc_html_e('Reset Section','smart-framework'); ?></button>
				<button class="button gsf-theme-options-reset-options" type="button"><?php esc_html_e('Reset Options','smart-framework'); ?></button>
				<button class="button button-primary gsf-theme-options-save-options" type="submit" name="gsf_save_option"><?php esc_html_e('Save Options','smart-framework'); ?></button>
			</div>
		</div>
		<div class="gsf-meta-box-wrap">
			<?php gsf_get_template('templates/theme-option-section', array('list_section' => $list_section)) ?>
			<div class="gsf-fields">
				<div class="gsf-fields-wrapper">