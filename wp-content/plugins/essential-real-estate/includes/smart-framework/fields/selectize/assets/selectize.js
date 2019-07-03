/**
 * selectize field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_SelectizeClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_SelectizeClass.prototype = {
		init: function() {
			var self = this,
				$selectField = self.$container.find('.gsf-selectize'),
				$editField = self.$container.find('.gsf-selectize-edit-link'),
				$btnCreate = self.$container.find('.gsf-selectize-create-link'),
				config = {
					plugins: ['remove_button'],
					onChange: function() {
						var $field = self.$container.closest('.gsf-field'),
							value = GSFFieldsConfig.fields.getValue($field),
							$selectizeControl = $field.find('.selectize-control');
						GSFFieldsConfig.required.checkRequired($field, value);


						if ($editField.length) {
							if ($selectizeControl.find('.gsf-selectize-edit-link').length == 0) {
								$editField.detach().appendTo($selectizeControl);
							}
							if (value == '') {
								$editField.hide();
							} else {
								$editField.show();
								var editLink = $editField.data('link') +  '?post='+ value +'&action=edit';
								$editField.attr('href',editLink);
							}
						}

						if ($btnCreate.length) {
							if ($selectizeControl.find('.gsf-selectize-create-link').length == 0) {
								$btnCreate.detach().appendTo($selectizeControl);
							}
						}
					}
				};
			if (!$selectField.attr('multiple')) {
				if ($selectField.data('allow-clear')) {
					config.allowEmptyOption = true;
					config.onInitialize = this.addRemoveButton;
					config.onItemAdd = this.addRemoveButton;
					config.onItemRemove = this.addRemoveButton;
				}
			}
			if ($selectField.data('tags')) {
				config.create = true;
				config.persist = false;
			}
			if ($selectField.data('drag')) {
				config.plugins[1] = 'drag_drop';
			}

			var $select = $selectField.selectize(config);
			var control = $select[0].selectize;
			var val = $selectField.data('value');
			if (typeof (val) !== "undefined") {
				control.setValue(val);
			}
		},
		addRemoveButton: function () {
			if (this.getValue() != '') {
				if (this.$control.find('.selectize-remove').length == 0) {
					this.$control.append('<span class="selectize-remove dashicons dashicons dashicons-no-alt"></span>');
					var $this = this;
					$('.selectize-remove', this.$control).on('click', function () {
						$this.setValue('');
					})
				}
			}
			else {
				$('.selectize-remove', this.$control).remove();
			}
		}
	};

	/**
	 * Define object field
	 */
	var GSF_SelectizeObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-selectize-inner').each(function () {
					var field = new GSF_SelectizeClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-selectize').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-selectize-inner');
				if ($items.length) {
					var field = new GSF_SelectizeClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_SelectizeObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_SelectizeObject);
	});
})(jQuery);