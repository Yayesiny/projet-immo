/**
 * background field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_BackgroundClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_BackgroundClass.prototype = {
		init: function() {

			var self = this,
				$colorField = self.$container.find('.gsf-background-color'),
				$chooseImageButton = self.$container.find('.gsf-background-choose-image'),
				$removeImageButton = self.$container.find('.gsf-background-remove-image'),
				$urlField = self.$container.find('.gsf-background-url'),
				$imageField = self.$container.find('.gsf-background-image'),
				$selectField = self.$container.find('select'),
				ajaxUrl = self.$container.data('url');

			/**
			 * Init Color
			 */
			var data = $.extend(
				{
					change: function () {
						var $this = $(this),
							$field = $this.closest('.gsf-field');
						if (!$this.hasClass('gsf-color-init-done')) {
							$this.addClass('gsf-color-init-done');
							self.changePreview();
						}
						else {
							setTimeout(function() {
								var value = GSFFieldsConfig.fields.getValue($field);
								GSFFieldsConfig.required.checkRequired($field, value);
								self.changePreview();
							}, 50);
						}

					},
					clear: function () {
						var $this = $(this);
						setTimeout(function() {
							self.changeField($this);
						}, 50);
					}
				},
				$colorField.data('options')
			);
			$colorField.wpColorPicker(data);

			/**
			 * Init Media
			 */
			var _media = new GSFMedia();
			_media.selectImage($chooseImageButton, {filter: 'image'}, function(attachment) {
				if (attachment) {
					var $parent = $(_media.clickedButton).parent();
					$urlField.val(attachment.url);
					$imageField.val(attachment.id);

					self.changeField($(_media.clickedButton));
				}
			});

			/**
			 * Remove button
			 */
			$removeImageButton.on('click', function() {
				$urlField.val('');
				$imageField.val('');
				self.changePreview();

				self.changeField($(this));
			});

			/**
			 * Image Url Change
			 */
			$urlField.on('change', function() {
				$.ajax({
					url: ajaxUrl,
					data: {
						url: $urlField.val()
					},
					type: 'GET',
					error: function() {
						$imageField.val('0');
						self.changeField($imageField);
					},
					success: function(res) {
						$imageField.val(res);
						self.changeField($imageField);
					}
				});
			});

			/**
			 * Select Url Change
			 */
			$selectField.on('change', function() {
				self.changeField($(this));
			});
		},

		changeField: function($this) {
			var $field = $this.closest('.gsf-field'),
				value = GSFFieldsConfig.fields.getValue($field);
			GSFFieldsConfig.required.checkRequired($field, value);
			this.changePreview();
		},

		changePreview: function() {
			var self = this,
				$colorField = self.$container.find('.gsf-background-color'),
				$preview = self.$container.find('.gsf-background-preview '),
				bg_url = self.$container.find('.gsf-background-url').val(),
				bg_repeat = self.$container.find('.gsf-background-repeat').val(),
				bg_size = self.$container.find('.gsf-background-size').val(),
				bg_position = self.$container.find('.gsf-background-position').val(),
				bg_attachment = self.$container.find('.gsf-background-attachment').val();
			$preview.css('background-color', $colorField.val());
			if (bg_url != '') {
				$preview.css('background-image', 'url(' + bg_url + ')');
				$preview.css('background-repeat', bg_repeat);
				$preview.css('background-size', bg_size);
				$preview.css('background-position', bg_position);
				$preview.css('background-attachment', bg_attachment);
			}
			else {
				$preview.css('background-image', '');
				$preview.css('background-repeat', '');
				$preview.css('background-size', '');
				$preview.css('background-position', '');
				$preview.css('background-attachment', '');
			}
		}
	};

	/**
	 * Define object field
	 */
	var GSF_BackgroundObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-background-inner').each(function () {
					var field = new GSF_BackgroundClass($(this));
					field.init();
					field.changePreview();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-background').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-background-inner');
				if ($items.length) {
					var field = new GSF_BackgroundClass($items);
					field.init();
					field.changePreview();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_BackgroundObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_BackgroundObject);
	});
})(jQuery);