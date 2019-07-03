/**
 * select field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_SelectClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_SelectClass.prototype = {
		init: function() {
			this.$container.find('.gsf-select').on('change', function() {
				var $field = $(this).closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				GSFFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_SelectObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-select-inner').each(function () {
					var field = new GSF_SelectClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-select').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-select-inner');
				if ($items.length) {
					var field = new GSF_SelectClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_SelectObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_SelectObject);
	});
})(jQuery);