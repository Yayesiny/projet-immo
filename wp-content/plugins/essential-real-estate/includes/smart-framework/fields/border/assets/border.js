/**
 * border field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_BorderClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_BorderClass.prototype = {
		init: function() {
			var self = this,
				$colorField = self.$container.find('.gsf-border-color');
			/**
			 * Init Color
			 */
			var data = $.extend(
				{
					change: function () {
						var $this = $(this),
							$field = $this.closest('.gsf-field');
						if (!$this.hasClass('gsf-color-init-done')) {
							$this.addClass('gsf-color-init-done');
						}
						else {
							setTimeout(function() {
								self.changeField($this);
							}, 50);
						}
					},
					clear: function () {
						var $this = $(this);
						setTimeout(function() {
							self.changeField($this);
						}, 50);
					}
				},
				$colorField.data('options')
			);
			$colorField.wpColorPicker(data);

			$('[data-field-control]', self.$container).change(function() {
				self.changeField($(this));
			});
		},
		changeField: function($this) {
			var $field = $this.closest('.gsf-field'),
				value = GSFFieldsConfig.fields.getValue($field);
			GSFFieldsConfig.required.checkRequired($field, value);
		}
	};

	/**
	 * Define object field
	 */
	var GSF_BorderObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-border-inner').each(function () {
					var field = new GSF_BorderClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-border').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-border-inner');
				if ($items.length) {
					var field = new GSF_BorderClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_BorderObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_BorderObject);
	});
})(jQuery);