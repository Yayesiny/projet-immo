/**
 * gallery field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */


(function($) {
	"use strict";

	/**
	 * Define object field
	 */
	var GSF_GalleryObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-gallery-inner','.gsf-field.gsf-field-gallery').each(function () {
					var field = new GSF_GalleryClass($(this));
					field.init();
				});
			});

			$('.gsf-field.gsf-field-gallery').on('gsf-gallery-selected gsf-gallery-removed gsf-gallery-sortable-updated ',function(event){
				var $field = $(event.target).closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				GSFFieldsConfig.required.checkRequired($field, value);
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-gallery').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-gallery-inner');
				if ($items.length) {
					var field = new GSF_GalleryClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_GalleryObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_GalleryObject);
	});
})(jQuery);