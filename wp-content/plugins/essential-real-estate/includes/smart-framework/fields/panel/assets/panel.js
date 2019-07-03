/**
 * panel field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_PanelClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_PanelClass.prototype = {
		init: function() {
			var self = this;
			self.toggleElement();
			self.panelTitleElement();
		},

		panelTitleElement: function() {
			var $panelTitle = this.$container.find('[data-panel-title="true"]:first');
			$panelTitle.on('change', function() {
				var $this = $(this),
					value = $this.val(),
					$title = $this.closest('.gsf-clone-field-panel').find('.gsf-panel-title'),
					label = $title.data('label');
				if (value == '') {
					$title.text(label);
				}
				else {
					$title.text(label + ': ' + value);
				}
			});
			$panelTitle.trigger('change');
		},

		toggleElement: function($element) {
			var $toggle = this.$container.find('> h4'),
				$inner = this.$container.find('.gsf-clone-field-panel-inner');
			$toggle.on('click', function(event) {
				if ($(event.target).closest('.gsf-clone-button-remove').length == 0) {
					$toggle.find('.gsf-panel-toggle').toggleClass('dashicons-arrow-up').toggleClass('dashicons-arrow-down');
					$inner.slideToggle();
				}
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_PanelObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-clone-field-panel').each(function () {
					var field = new GSF_PanelClass($(this));
					field.init();
				});
				GSF_PanelObject.sortableFieldPanel();
				GSF_PanelObject.addCloneButton();
			});
		},
		addCloneButton: function() {
			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-panel').on('gsf_add_clone_field', function(event){
				var $items = $(event.target);
				if ($items.length) {
					var field = new GSF_PanelClass($items);
					field.init();

					GSFFieldsConfig.cloneField.makeCloneTemplateElement($items);
					$items.find('.gsf-field').each(function() {
						var $field = $(this),
							fieldType = $field.data('field-type');
						if (typeof (fieldType) != 'undefined') {
							var $container = $field.find('.gsf-field-' + fieldType + '-inner');
							try {
								var field = eval("new " + GSF_PanelObject.getFieldClass(fieldType) + "($container)");
								field.init();
							}
							catch (ex) {}
						}
					});
					$items.find('.gsf-field').each(function() {
						var $field = $(this);
						$field.on('gsf_check_required', GSFFieldsConfig.required.onChangeEvent);
						$field.trigger('gsf_check_required');
						$field.trigger('gsf_check_preset');
					});
				}
			});
		},
		getFieldClass: function(fieldType) {
			var arr = fieldType.split('_');
			for (var i = 0; i < arr.length; i++) {
				arr[i] = this.ucwords(arr[i]);
			}
			return 'GSF_' + arr.join('') + 'Class';
		},
		ucwords: function(str) {
			return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
				return $1.toUpperCase();
			})
		},
		sortableFieldPanel: function() {
			var self = this;
			$('.gsf-field-panel-sortable').sortable({
				placeholder: "gsf-field-panel-sortable-placeholder",
				handle: '.gsf-field-panel-title',
				items: '.gsf-clone-field-panel',
				update: function(event) {
					var $wrapper = $(event.target),
						$field = $wrapper.closest('.gsf-field');
					GSFFieldsConfig.cloneField.reIndexFieldName($wrapper.parent(), false);
					$field.trigger('gsf_field_change');
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_PanelObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_PanelObject);
	});
})(jQuery);