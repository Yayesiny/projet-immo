/**
 * checkbox field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_CheckboxClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_CheckboxClass.prototype = {
		init: function() {
			this.$container.find('input.gsf-checkbox').on('change', function() {
				var $field = $(this).closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				GSFFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_CheckboxObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-checkbox-inner').each(function () {
					var field = new GSF_CheckboxClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-checkbox').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-checkbox-inner');
				if ($items.length) {
					var field = new GSF_CheckboxClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_CheckboxObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_CheckboxObject);
	});
})(jQuery);