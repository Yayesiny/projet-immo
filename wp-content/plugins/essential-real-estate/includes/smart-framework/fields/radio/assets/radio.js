/**
 * radio field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_RadioClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_RadioClass.prototype = {
		init: function() {
			this.$container.find('input.gsf-radio').on('change', function() {
				console.log(this);
				var $field = $(this).closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				GSFFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_RadioObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-radio-inner').each(function () {
					var field = new GSF_RadioClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-radio').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-radio-inner');
				if ($items.length) {
					var field = new GSF_RadioClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_RadioObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_RadioObject);
	});
})(jQuery);