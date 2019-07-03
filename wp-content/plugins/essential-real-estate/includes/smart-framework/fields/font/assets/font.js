/**
 * font field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_FontClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_FontClass.prototype = {
		init: function() {
			this.initFont();
		},
		initFont: function() {
			var self = this,
				$fontKind = self.$container.find('.gsf-font-size-kind'),
				$fontSize = self.$container.find('.gsf-font-size-full'),
				$fontSizeValue = self.$container.find('.gsf-font-size-value'),
				$fontSizeUnit = self.$container.find('.gsf-font-size-unit'),
				$fontFamily = self.$container.find('.gsf-font-family > select'),
				$fontWeightStyle = self.$container.find('.gsf-font-weight-style > select'),
				$fontWeight = self.$container.find('.gsf-font-weight'),
				$fontStyle = self.$container.find('.gsf-font-style'),
				$fontSubsetsWrapper = self.$container.find('.gsf-font-subsets'),
				$fontSubsets = self.$container.find('.gsf-font-subsets > select');

			var html = '',
				group, item, i;
			for (var groupKey in GSF_FontObject.googleFonts) {
				group = GSF_FontObject.googleFonts[groupKey];
				html += '<optgroup label="' + group['label'] + '">';
				for (i = 0; i < group['items'].length; i++) {
					item = group['items'][i];
					html += '<option value="' + item['family'] + '">' + item['family_label'] + '</option>';
				}
				html += '</optgroup>';
			}
			$fontFamily.html(html);
			var config = {
					allowEmptyOption: true,
					onChange: function () {
						var font = GSF_FontObject.findFont($fontFamily.val());
						if (font != null) {
							$fontKind.val(font.kind);

							/**
							 * Binder Subset
							 */
							if (font.subsets.length > 0) {
								$fontSubsetsWrapper.show();
								self.binderSubsets(font.subsets);
							}
							else {
								$fontSubsetsWrapper.hide();
								$fontSubsets.val('');
							}

							/**
							 * Binder variants
							 */

							self.binderFontVariants(font.variants);
							$fontWeightStyle.trigger('change');
						}
						if (self.$container.hasClass('gsf-font-init-done')) {
							self.changeField();
						}
						else {
							self.$container.addClass('gsf-font-init-done')
						}

						/**
						 * Set Init Value
						 */
						if (!self.$container.data('init-done')) {
							$fontWeightStyle.val($fontWeightStyle.data('value'));
							$fontSubsets.val($fontSubsets.data('value'));
							self.$container.data('init-done', true);
						}
					}
				},
				select = $fontFamily.selectize(config),
				currentValue = $fontFamily.data('value');
			select[0].selectize.setValue(currentValue);

			// Change Font Size
			$fontSizeValue.on('change', function() {
				if ($fontSizeValue.val() == '') {
					$fontSize.val('');
				}
				else {
					$fontSize.val($fontSizeValue.val() + $fontSizeUnit.val());
				}

				self.changeField();
			});
			$fontSizeUnit.on('change', function () {
				if ($fontSizeUnit.val() === 'em') {
					$fontSizeValue.attr('step', 0.01);
				}
				else {
					$fontSizeValue.attr('step', 1);
				}
				if ($fontSizeValue.val() == '') {
					$fontSize.val('');
				}
				else {
					$fontSize.val($fontSizeValue.val() + $fontSizeUnit.val());
				}
				self.changeField();
			});

			/**
			 * Change Font Weight & Style
			 */
			$fontWeightStyle.on('change', function () {
				var fontWeightValue = $fontWeightStyle.val(),
					fontWeight = fontWeightValue.replace('italic', ''),
					fontStyle = fontWeightValue.substring(fontWeight.length);
				$fontWeight.val(fontWeight);
				$fontStyle.val(fontStyle);
				self.changeField();
			});
		},
		binderSubsets: function(arr) {
			var html = '',
				i;
			for (i = 0; i< arr.length; i++) {
				html += '<option value="' + arr[i] + '">' + arr[i] + '</option>';
			}
			this.$container.find('.gsf-font-subsets > select').html(html);
		},
		binderFontVariants: function (arr) {
			var html = '',
				i,
				fontWeightValue,
				fontWeight,
				fontStyle;
			for (i = 0; i < arr.length; i++) {
				fontWeightValue = arr[i];
				fontWeight = fontWeightValue.replace('italic', '');
				fontStyle = fontWeightValue.substring(fontWeight.length);
				html += '<option value="' + (fontWeight + fontStyle) + '">' + fontWeight + (fontStyle == '' ? '' : ' ' + fontStyle) + '</option>';
			}
			this.$container.find('.gsf-font-weight-style > select').html(html);
		},

		/**
		 * Change Field
		 */
		changeField: function() {
			var $field = this.$container.closest('.gsf-field'),
				value = GSFFieldsConfig.fields.getValue($field);
			GSFFieldsConfig.required.checkRequired($field, value);
		}
	};

	/**
	 * Define object field
	 */
	var GSF_FontObject = {
		googleFonts: null,
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$.ajax({
					url: gsfMetaData.ajax_url,
					data: {
						action: 'gsf_get_fonts'
					},
					success: function (res) {
						GSF_FontObject.googleFonts = JSON.parse(res);
						$('.gsf-field-font-inner').each(function () {
							var field = new GSF_FontClass($(this));
							field.init();
						});
					}
				});

			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-font').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-font-inner');
				if ($items.length) {
					var field = new GSF_FontClass($items);
					field.init();
				}
			});
		},

		findFont: function(value) {
			var groupKey,
				i,
				font = null;
			for (groupKey in GSF_FontObject.googleFonts) {
				for (i = 0; i < GSF_FontObject.googleFonts[groupKey]['items'].length; i++) {
					font = GSF_FontObject.googleFonts[groupKey]['items'][i];
					if (font['family'] == value) {
						return font;
					}
				}
			}
			return null;

		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_FontObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_FontObject);
	});
})(jQuery);