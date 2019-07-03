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
var GSF_SorterClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_SorterClass.prototype = {
		init: function() {
			var $items = this.$container.find('.gsf-field-sorter-group');
			$items.sortable({
				placeholder: 'gsf-sorter-sortable-placeholder',
				items: '.gsf-field-sorter-item',
				connectWith: $('.gsf-field-sorter-group', this.$container),
				update: function (event, ui) {
					var $wrapper = $(event.target),
						groupName = $wrapper.data('group');

					/**
					 * Update input name
					 */
					$('.gsf-field-sorter-item input', $wrapper).each(function () {
						var $this = $(this),
							itemName = $this.data('item-name');
						$this.prop('name', groupName + '[' + itemName + ']')
					});

					var $field = $wrapper.closest('.gsf-field'),
						value = GSFFieldsConfig.fields.getValue($field);
					GSFFieldsConfig.required.checkRequired($field, value);
				}
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_SorterObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-sorter-inner').each(function () {
					var field = new GSF_SorterClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-sorter').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-sorter-inner');
				if ($items.length) {
					var field = new GSF_SorterClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_SorterObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_SorterObject);
	});
})(jQuery);