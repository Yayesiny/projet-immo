/**
 * spacing field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_SpacingClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_SpacingClass.prototype = {
		init: function() {
			this.$container.find('[data-field-control]').on('change', function() {
				var $field = $(this).closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				GSFFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_SpacingObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-spacing-inner').each(function () {
					var field = new GSF_SpacingClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-spacing').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-spacing-inner');
				if ($items.length) {
					var field = new GSF_SpacingClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_SpacingObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_SpacingObject);
	});
})(jQuery);