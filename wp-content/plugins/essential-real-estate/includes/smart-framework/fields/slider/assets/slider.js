/**
 * slider field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_SliderClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_SliderClass.prototype = {
		init: function() {
			var self = this,
				$slider = self.$container.find('.gsf-slider-place'),
				slider = $slider[0],
				$input = self.$container.find('input'),
				options = $slider.data('options'),
				config = {
					step: options['step'],
					range: {
						'min': options['min'],
						'max': options['max']
					}
				};


			if ($input.length == 1) {
				config.start = $input.val();
				config.connect = [true, false];
			}
			else {
				config.start = [$input[0].value, $input[1].value];
				config.connect = [false, true, false];
			}
			noUiSlider.create(slider, config);

			slider.noUiSlider.on('update', function( values, handle ) {
				if ($($input[handle]).hasClass('gsf-slider-init-done')) {
					$input[handle].value = self.getValue(values[handle], parseFloat(options['step']));

					var $field = $input.closest('.gsf-field'),
						value = GSFFieldsConfig.fields.getValue($field);
					GSFFieldsConfig.required.checkRequired($field, value);
				}
				else {
					$($input[handle]).addClass('gsf-slider-init-done');
				}
			});
			$input.on('change', function () {
				if ($input.length == 1) {
					slider.noUiSlider.set(this.value);
				}
				else {
					slider.noUiSlider.set([$input[0].value, $input[1].value]);
				}

			});
		},
		getValue: function(value, step) {
			if (Math.round(step) == step) {
				return Math.round(value);
			}
			if (Math.round(step*10) == step * 10) {
				return Math.round(value*10)/10;
			}
			return value;

		}
	};

	/**
	 * Define object field
	 */
	var GSF_SliderObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-slider-inner').each(function () {
					var field = new GSF_SliderClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-slider').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-slider-inner');
				if ($items.length) {
					var field = new GSF_SliderClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_SliderObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_SliderObject);
	});
})(jQuery);