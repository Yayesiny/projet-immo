/**
 * text field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */
/**
 * Define class field
 */
var GSF_TextClass = function($container) {
	this.$container = $container;
};
(function($) {
	"use strict";



	/**
	 * Define class field prototype
	 */
	GSF_TextClass.prototype = {
		init: function() {
			this.slider();
			this.onChange();
			this.unique_id();
		},
		slider: function() {
			this.$container.find('.gsf-text[type="range"]').each(function() {
				var $this = $(this),
					$parent = $this.closest('.gsf-field-text-inner');
				$parent.append('<span class="gsf-text-range-info">' + $this.val() + '</span>');

				/**
				 * Slide drag
				 */
				this.oninput = function() {
					$(this).next().text($(this).val());
				}
			});
		},

		onChange: function() {
			this.$container.find('.gsf-text[data-field-control]').on('change', function() {
				var $this = $(this),
					type = $this.attr('type');
				var $field = $this.closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				GSFFieldsConfig.required.checkRequired($field, value);
			});
		},
		unique_id : function() {
			this.$container.find('.gsf-text[data-unique_id="true"]').each(function(){
				var $this = $(this),
					prefix = $this.data('unique_id-prefix'),
					$field = $this.closest('.gsf-field'),
					value = GSFFieldsConfig.fields.getValue($field);
				if (value === '') {
					var random =  Math.floor(Math.random() * (999999 - 100000)) + 100000;
					$this.val(prefix + random);
				}
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_TextObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-text-inner').each(function () {
					var field = new GSF_TextClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-text').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-text-inner');
				if ($items.length) {
					var field = new GSF_TextClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_TextObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_TextObject);
	});
})(jQuery);