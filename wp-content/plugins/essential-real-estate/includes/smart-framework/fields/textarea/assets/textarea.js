/**
 * your_field field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_YourFieldClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_YourFieldClass.prototype = {
		init: function() {
			this.$container.find('.gsf-textarea').on('change', function() {
				var $field = $(this).closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				GSFFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_YourFieldObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-your_field-inner').each(function () {
					var field = new GSF_YourFieldClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-your_field').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-your_field-inner');
				if ($items.length) {
					var field = new GSF_YourFieldClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_YourFieldObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_YourFieldObject);
	});
})(jQuery);