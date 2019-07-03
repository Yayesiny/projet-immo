				</div> <!-- /.gsf-fields-wrapper -->
			</div> <!-- /.gsf-fields -->
		</div> <!-- /.gsf-m -->
		<div class="gsf-theme-options-footer">
			<button class="button gsf-theme-options-import" type="button"><?php esc_html_e('Import/Export','smart-framework'); ?></button>
			<button class="button gsf-theme-options-reset-section" type="button"><?php esc_html_e('Reset Section','smart-framework'); ?></button>
			<button class="button gsf-theme-options-reset-options" type="button"><?php esc_html_e('Reset Options','smart-framework'); ?></button>
			<button class="button button-primary gsf-theme-options-save-options" type="submit" name="gsf_save_option"><?php esc_html_e('Save Options','smart-framework'); ?></button>
		</div><!-- /.gsf-theme-options-footer -->
	</form>
	<div class="gsf-theme-options-backup-popup-wrapper">
		<div class="gsf-theme-options-backup-popup">
			<div class="gsf-theme-options-backup-header gsf-clearfix">
				<h4><?php esc_html_e('Import/Export Options','smart-framework'); ?></h4>
				<span class="dashicons dashicons-no-alt"></span>
			</div>
			<div class="gsf-theme-options-backup-content">
				<section>
					<h5><?php esc_html_e('Import Options','smart-framework'); ?></h5>
					<div class="gsf-theme-options-backup-import">
						<textarea></textarea>
						<button type="button" class="button"><?php esc_html_e('Import','smart-framework'); ?></button>
						<span class=""><?php esc_html_e('WARNING! This will overwrite all existing option values, please proceed with caution!','smart-framework'); ?></span>
					</div>
				</section>
				<section>
					<h5><?php esc_html_e('Export Options','smart-framework'); ?></h5>
					<div class="gsf-theme-options-backup-export">
						<textarea readonly><?php echo base64_encode(json_encode(get_option($option_name))); ?></textarea>
						<button type="button" class="button"><?php esc_html_e('Download Data File','smart-framework'); ?></button>
					</div>
				</section>
			</div>
		</div>
	</div>
</div><!-- /.gsf-theme-options-wrapper -->