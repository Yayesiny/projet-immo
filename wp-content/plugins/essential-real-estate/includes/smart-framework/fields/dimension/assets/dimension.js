/**
 * dimension field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_DimensionClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_DimensionClass.prototype = {
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
	var GSF_DimensionObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-dimension-inner').each(function () {
					var field = new GSF_DimensionClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-dimension').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-dimension-inner');
				if ($items.length) {
					var field = new GSF_DimensionClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_DimensionObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_DimensionObject);
	});
})(jQuery);