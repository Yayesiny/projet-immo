/**
 * image_set field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_ImageSetClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_ImageSetClass.prototype = {
		init: function() {
			var self = this;

			self.allowClearChecked = false;

			self.$container.find('[data-field-control]').on('change', function() {
				var $field = $(this).closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				GSFFieldsConfig.required.checkRequired($field, value);
			});

			self.$container.find('.gsf-allow-clear').on('click mousedown', function(event) {
				var $input = $(this).closest('label').find('input[type="radio"]');

				if ($input.length > 0) {
					if (event.type == 'click') {
						setTimeout(function() {
							if (self.allowClearChecked) {
								$input[0].checked = false;
							}
						}, 10);
					}
					else {
						self.allowClearChecked = $input[0].checked;
					}
				}
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_ImageSetObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-image_set-inner').each(function () {
					var field = new GSF_ImageSetClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-image_set').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-image_set-inner');
				if ($items.length) {
					var field = new GSF_ImageSetClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_ImageSetObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_ImageSetObject);
	});
})(jQuery);