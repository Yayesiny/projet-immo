(function($) {
	"use strict";

	var GSF_THEME_OPTION = {
		init: function() {
			this.backupListener();
			this.sectionSelect();
			this.resetSection();
			this.resetOptions();
			this.checkFieldChange();
			this.saveOptions();
		},
		backupListener: function() {
			/**
			 * Show backup popup
			 */
			$('.gsf-theme-options-import').on('click', function() {
				$('.gsf-theme-options-backup-popup-wrapper').fadeIn();
			});

			/**
			 * Close Backup popup
			 */
			$('.gsf-theme-options-backup-header > span').on('click', function() {
				$('.gsf-theme-options-backup-popup-wrapper').fadeOut();
			});

			/**
			 * Import
			 */
			$('.gsf-theme-options-backup-import > button').on('click', function() {
				var backupData = $('.gsf-theme-options-backup-import > textarea').val();
				if (backupData == '') {
					return;
				}
				if (!confirm(gsfMetaData.confirm_import_msg)) {
					return;
				}
				var $this = $(this),
					$wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val();
				$.ajax({
					url: gsfMetaData.ajax_url,
					data: {
						wpnonce: wpnonce,
						_current_page: _current_page,
						action: 'gsf_import_theme_options',
						backup_data: backupData
					},
					type: 'post',
					success: function(res) {
						if (res == 1) {
							alert(gsfMetaData.import_done);
							window.location.reload();
						}
						else {
							alert(gsfMetaData.import_error);
						}
					}
				});
			});

			/**
			 * Export text area content focus
			 */
			$('.gsf-theme-options-backup-export > textarea').on('focus', function() {
				$(this).select();
			});

			/**
			 * Download backup data file
			 */
			$('.gsf-theme-options-backup-export > button').on('click', function() {
				var $wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val();
				window.open(gsfMetaData.ajax_url + '?action=gsf_export_theme_options&wpnonce=' + wpnonce+'&_current_page='+_current_page,'_blank');
			});
		},

		/**
		 * Active current section when init
		 */
		sectionSelect: function () {
			const CURRENT_SECTION = 'gsf_theme_options_current_section';
			var _current_page = $('#_current_page').val(),
				currentSection = localStorage.getItem(CURRENT_SECTION + '_' + _current_page);
			if (currentSection === null) {
				var sectionActive = $('.gsf-tab li:first').data('id');
				currentSection = sectionActive;
				if (typeof (sectionActive) != 'undefined') {
					localStorage.setItem(CURRENT_SECTION + '_' + _current_page, sectionActive);
				}
				else {
					/**
					 * Off reset section if not exist section
					 */
					$('.gsf-theme-options-reset-section').remove();
				}
			}
			$('.gsf-tab li').removeClass('active');
			$('.gsf-fields-wrapper > .gsf-section-container').hide();
			$('.gsf-tab li[data-id="' + currentSection + '"]').addClass('active');
			$('.gsf-fields-wrapper > .gsf-section-container[id="' + currentSection + '"]').show();

			/**
			 * Store currentSection when section clicked
			 */
			$('.gsf-tab li').on('click', function () {
				localStorage.setItem(CURRENT_SECTION + '_' + _current_page, $(this).data('id'));
			});
		},

		checkFieldChange: function () {
			$('.gsf-field').on('gsf_field_change', function() {
				var $message = $('.gsf-fields-wrapper-message');
				if (!$message.is(":visible")) {
					$message.slideDown();
				}
				window.onbeforeunload = GSF_THEME_OPTION.confirmWhenPageExit;
			});
		},
		confirmWhenPageExit: function(event) {
			if(!event) event = window.event;
			event.cancelBubble = true;
			event.returnValue = '';

			if (event.stopPropagation) {
				event.stopPropagation();
				event.preventDefault();
			}
		},

		/**
		 * Reset theme options in section
		 *
		 * Done: reload page
		 * Error: message error
		 */
		resetSection: function () {
			$('.gsf-theme-options-reset-section').on('click', function() {
				if (!confirm(gsfMetaData.confirm_reset_section_option_msg)) {
					return;
				}
				const CURRENT_SECTION = 'gsf_theme_options_current_section';
				var $this = $(this),
					$wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val(),
					currentSection = localStorage.getItem(CURRENT_SECTION + '_' + _current_page);

				$.ajax({
					url: gsfMetaData.ajax_url,
					data: {
						wpnonce: wpnonce,
						_current_page: _current_page,
						action: 'gsf_reset_section_options',
						section: currentSection
					},
					type: 'post',
					success: function(res) {
						if (res == 1) {
							alert(gsfMetaData.reset_section_option_done);
							window.location.reload();
						}
						else {
							alert(gsfMetaData.reset_section_option_error);
						}
					}
				});
			});
		},

		/**
		 * Reset theme options
		 *
		 * Done: reload page
		 * Error: message error
		 */
		resetOptions: function () {
			$('.gsf-theme-options-reset-options').on('click', function() {
				if (!confirm(gsfMetaData.confirm_reset_theme_option_msg)) {
					return;
				}
				var $this = $(this),
					$wpnonce = $('#_wpnonce'),
					wpnonce = $wpnonce.val(),
					_current_page = $('#_current_page').val();
				$.ajax({
					url: gsfMetaData.ajax_url,
					data: {
						wpnonce: wpnonce,
						_current_page: _current_page,
						action: 'gsf_reset_theme_options'
					},
					type: 'post',
					success: function(res) {
						if (res == 1) {
							alert(gsfMetaData.reset_theme_option_done);
							window.location.reload();
						}
						else {
							alert(gsfMetaData.reset_theme_option_error);
						}
					}
				});
			});
		},
		saveOptions: function () {
			$('.gsf-theme-options-save-options').on('click', function() {
				window.onbeforeunload = null;
			});
		}

	}
	$(document).ready(function() {
		GSF_THEME_OPTION.init();
	});
})(jQuery);