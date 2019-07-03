/**
 * icon field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_IconClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_IconClass.prototype = {
		init: function() {
			var self = this,
				$iconWrap = self.$container.find('> .gsf-icon'),
				$iconInfo = self.$container.find('.gsf-icon-info');

			/**
			 * Show icon popup when click icon info
			 */
			$iconInfo.on('click', function() {
				var $this = $(this);
				GSF_IconObject.$item = self.$container;
				$('.gsf-icon-section-content > span', GSF_IconObject.$iconPopup).css('display', '');
				GSF_IconObject.$iconPopup.detach();
				$iconWrap.append(GSF_IconObject.$iconPopup);
				var $search = GSF_IconObject.$iconPopup.find('.gsf-icon-popup-header > input');
				$search.val('');
				GSF_IconObject.$iconPopup.fadeIn(function() {
					$search.focus();
				});
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_IconObject = {
		$iconPopup: null,
		$item: null,
		init: function() {
			GSF_IconObject.$iconPopup = null;
			GSF_IconObject.$item = null;
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$.ajax({
					url: gsfMetaData.ajax_url,
					data: {
						action: 'gsf_get_font_icons'
					},
					success: function (res) {
						GSF_IconObject.makeIconPopup(JSON.parse(res));
						$('.gsf-field-icon-inner').each(function () {
							var field = new GSF_IconClass($(this));
							field.init();
						});
					}
				});


			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-icon').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-icon-inner');
				if ($items.length) {
					var field = new GSF_IconClass($items);
					field.init();
				}
			});
		},
		makeIconPopup: function(icons) {
			var html = '',
				fontName, icon, i;
			html += '<div class="gsf-icon-popup">';
			html += '<div class="gsf-icon-popup-header gsf-clearfix"><input type="text" placeholder="Type to search..."/><button class="button" type="button">Remove Icon</button><span class="dashicons dashicons-no-alt"></span></div>';
			html += '<div class="gsf-icon-popup-content">';
			for (fontName in icons) {
				html += '<section>';
				html += '<h4>' + icons[fontName]['label'] + '</h4>';
				html += '<div class="gsf-icon-section-content gsf-clearfix">';
				for (i = 0; i < icons[fontName]['icons'].length; i++) {
					html += '<span class="' + icons[fontName]['icons'][i] + '"></span>';
				}
				html += '</div>';
				html += '</section>';
			}
			html += '</div>';
			html += '</div>';

			var $popupIcon = $('.gsf-icon-popup');
			if ($popupIcon.length > 0) {
				$popupIcon.remove();
			}
			$('body').append(html);
			GSF_IconObject.$iconPopup = $('.gsf-icon-popup');

			/**
			 * Close popup icon
			 */
			$('.gsf-icon-popup-header > span', GSF_IconObject.$iconPopup).on('click', function () {
				GSF_IconObject.$iconPopup.fadeOut(function() {
					GSF_IconObject.$iconPopup.detach();
				});
			});

			/**
			 * Select icon
			 */
			$('.gsf-icon-section-content > span', GSF_IconObject.$iconPopup).on('click', function () {
				var iconValue = $(this).attr('class');
				GSF_IconObject.$item.find('.gsf-icon-info > span').attr('class', iconValue);
				GSF_IconObject.$item.find('> input[type="hidden"]').val(iconValue)
				GSF_IconObject.$iconPopup.fadeOut(function() {
					GSF_IconObject.$iconPopup.detach();
				});
				GSF_IconObject.changeField($(this));
			});

			/**
			 * Remove Icon
			 */
			$('.gsf-icon-popup-header > button', GSF_IconObject.$iconPopup).on('click', function () {
				GSF_IconObject.$item.find('.gsf-icon-info > span').attr('class', '');
				GSF_IconObject.$item.find('> input[type="hidden"]').val('');
				GSF_IconObject.$iconPopup.fadeOut(function() {
					GSF_IconObject.$iconPopup.detach();
				});
				GSF_IconObject.changeField($(this));
			});

			/**
			 * Search Icon
			 */
			$('.gsf-icon-popup-header > input', GSF_IconObject.$iconPopup).on('keyup', function () {
				var filter = $(this).val();
				$('.gsf-icon-section-content > span', GSF_IconObject.$iconPopup).each(function(){
					if ($(this).attr('class').search(new RegExp(filter, "i")) < 0) {
						$(this).hide();
					}
					else {
						$(this).show();
					}
				});
			});
		},
		changeField: function($item) {
			var $field = $item.closest('.gsf-field'),
				value = GSFFieldsConfig.fields.getValue($field);
			GSFFieldsConfig.required.checkRequired($field, value);
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_IconObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_IconObject);

		/**
		 * Auto close popup when click outside
		 */
		$(document).on('click', function(event) {
			if ($(event.target).closest('.gsf-icon-popup,.gsf-icon').length == 0) {
				if (GSF_IconObject.$iconPopup != null) {
					GSF_IconObject.$iconPopup.fadeOut(function() {
						GSF_IconObject.$iconPopup.detach();
					});
				}
			}
		});
	});
})(jQuery);