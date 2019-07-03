/**
 * color field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_ColorClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_ColorClass.prototype = {
		init: function() {
			var data = $.extend(
				{
					change: function () {
						var $this = $(this),
							$field = $this.closest('.gsf-field');
						if (!$this.hasClass('gsf-color-init-done')) {
							$this.addClass('gsf-color-init-done');
						}
						else {
							setTimeout(function() {
								var value = GSFFieldsConfig.fields.getValue($field);
								GSFFieldsConfig.required.checkRequired($field, value);
							}, 50);
						}
					},
					clear: function () {
						var $field = $(this).closest('.gsf-field');

						setTimeout(function() {
							var value = GSFFieldsConfig.fields.getValue($field);
							GSFFieldsConfig.required.checkRequired($field, '');
						}, 50);
					}
				}
			);
			this.$container.find('.gsf-color').wpColorPicker(data);
		}
	};

	/**
	 * Define object field
	 */
	var GSF_ColorObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-color-inner').each(function () {
					var field = new GSF_ColorClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-color').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-color-inner');
				if ($items.length) {
					var field = new GSF_ColorClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_ColorObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_ColorObject);
	});
})(jQuery);