/**
 * sorter field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_SortableClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_SortableClass.prototype = {
		init: function() {
			this.$container.sortable({
				placeholder: 'gsf-sortable-sortable-placeholder',
				items: '.gsf-field-sortable-item',
				handle: '.dashicons-menu',
				update: function (event, ui) {
					var $wrapper = $(event.target);

					var sortValue = '';
					$wrapper.find('input[type="checkbox"]').each(function() {
						var $this = $(this);
						if (sortValue === '') {
							sortValue += $this.val();
						}
						else {
							sortValue += '|' + $this.val();
						}
					});

					$wrapper.find('.gsf-field-sortable-sort').val(sortValue);


					var $field = $wrapper.closest('.gsf-field'),
						value = GSFFieldsConfig.fields.getValue($field);
					GSFFieldsConfig.required.checkRequired($field, value);
				}
			});

			$('.gsf-field-sortable-inner .gsf-field-sortable-checkbox').change(function() {
				var $field = $(this).closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				GSFFieldsConfig.required.checkRequired($field, value);
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_SortableObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-sortable-inner').each(function () {
					var field = new GSF_SortableClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-sortable').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-sortable-inner');
				if ($items.length) {
					var field = new GSF_SortableClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_SortableObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_SortableObject);
	});
})(jQuery);