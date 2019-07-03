/**
 * select_ajax field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_SelectAjaxClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_SelectAjaxClass.prototype = {
		init: function() {
			var self = this,
				$selectField = self.$container.find('.gsf-select-ajax'),
				ajaxUrl = $selectField.data('url'),
				postType = $selectField.data('source');
			var config = {
				plugins: ['remove_button'],
				valueField: 'value',
				labelField: 'label',
				searchField: 'label',
				sortField: 'label',
				options: [],
				create: false,
				onChange: function() {
					var $field = self.$container.closest('.gsf-field'),
						value = GSFFieldsConfig.fields.getValue($field);
					GSFFieldsConfig.required.checkRequired($field, value);
				},
				load: function(query, callback) {
					if (!query.length) return callback();
					$.ajax({
						url: ajaxUrl,
						data: {
							keyword: query,
							post_type: postType
						},
						type: 'GET',
						error: function() {
							callback();
						},
						success: function(res) {
							callback($.parseJSON(res));
						}
					});
				}
			};
			if ($selectField.attr('multiple')) {
				if ($selectField.data('drag')) {
					config.plugins[1] = 'drag_drop';
				}
			}

			var $select = $selectField.selectize(config);
			var control = $select[0].selectize;
			var val = $selectField.data('value');
			if (typeof (val) !== "undefined") {
				control.setValue(val);
			}
		}
	};

	/**
	 * Define object field
	 */
	var GSF_SelectAjaxObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-select_ajax-inner').each(function () {
					var field = new GSF_SelectAjaxClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-select_ajax').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-select_ajax-inner');
				if ($items.length) {
					var field = new GSF_SelectAjaxClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_SelectAjaxObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_SelectAjaxObject);
	});
})(jQuery);