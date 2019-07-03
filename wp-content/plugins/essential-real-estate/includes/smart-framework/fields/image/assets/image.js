/**
 * image field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_ImageClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_ImageClass.prototype = {
		init: function() {
			this.selectMedia();
		},
		selectMedia: function() {
			var self = this,
				$idField = self.$container.find('.gsf-image-id'),
				$urlField = self.$container.find('.gsf-image-url'),
				$chooseImage = self.$container.find('.gsf-image-choose-image'),
				$removeButton = self.$container.find('.gsf-image-remove'),
				$preview = self.$container.find('.gsf-image-preview img'),
				$selectImageDefaultDir = self.$container.find('.gsf-image-choose-image-dir');

			if ($selectImageDefaultDir.length) {
				$.ajax({
					url: gsfMetaData.ajax_url,
					data: {
						action: 'gsf_select_default_image'
					},
					success: function (res) {
						var $popup = $('.gsf-image-default-popup');
						if ($popup.length == 0) {
							$popup = $(res);
							$('body').append($popup);
							self.imageDefaultPopupEvent($popup);
						}
					}
				});
			}
			$selectImageDefaultDir.on('click', function() {
				var $popup = $('.gsf-image-default-popup');
				if (!$popup.length) {
					return;
				}
				$popup.data('urlField', $urlField);
				$popup.data('idField', $idField);
				$popup.data('previewField', $preview);
				$popup.show();
			});

			/**
			 * Init Media
			 */
			var _media = new GSFMedia();
			_media.selectImage($chooseImage, {filter: 'image'}, function(attachment) {
				if (attachment) {
					var thumb_url = '';
					if (attachment.sizes.thumbnail == undefined) {
						thumb_url = attachment.sizes.full.url;
					}
					else {
						thumb_url = attachment.sizes.thumbnail.url;
					}
					$preview.attr('src', thumb_url);
					$preview.show();
					$idField.val(attachment.id);
					$urlField.val(attachment.url);

					self.changeField(self.$container);
				}
			});

			/**
			 * Remove Image
			 */
			$removeButton.on('click', function() {
				$preview.attr('src', '');
				$preview.hide();
				$idField.val('');
				$urlField.val('');

				self.changeField(self.$container);
			});

			$urlField.on('change', function() {
				$.ajax({
					url: gsfMetaData.ajax_url,
					data: {
						action: 'gsf_get_attachment_id',
						url: $urlField.val()
					},
					type: 'GET',
					error: function() {
						$idField.val('0');
					},
					success: function(res) {
						$idField.val(res);
					}
				});
				if ($urlField.val() == '') {
					$preview.attr('src', '');
					$preview.hide();
				}
				else {
					$preview.attr('src', $urlField.val());
					$preview.show();
				}
			});
		},
		imageDefaultPopupEvent: function($popup) {
			var self = this;
			$popup.find('.gsf-image-default-popup-content > h1 > span').on('click', function() {
				$popup.hide();
			});
			$popup.find('.gsf-image-default-popup-item').on('click', function() {
				var $img = $(this).find('img'),
					src = $img.attr('src');

				$popup.data('previewField').attr('src', src);
				$popup.data('previewField').show();
				$popup.data('idField').val('0');
				$popup.data('urlField').val(src);
				$popup.hide();
				self.changeField(self.$container);
			});
		},
		changeField: function($item) {
			var $field = $item.closest('.gsf-field'),
				value = GSFFieldsConfig.fields.getValue($field);
			GSFFieldsConfig.required.checkRequired($field, value);
		}
	};

	/**
	 * Define object field
	 */
	var GSF_ImageObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-image-inner').each(function () {
					var field = new GSF_ImageClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-image').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-image-inner');
				if ($items.length) {
					console.log($items);
					var field = new GSF_ImageClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_ImageObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_ImageObject);
	});
})(jQuery);