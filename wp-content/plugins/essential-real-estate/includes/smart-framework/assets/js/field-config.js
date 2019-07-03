var GSFFieldsConfig = GSFFieldsConfig || {};
(function($) {
	"use strict";
	GSFFieldsConfig.fieldInstance = [];

	/**
	 * Process required field
	 */
	GSFFieldsConfig.required = {
		applyField: [],
		init: function() {
			this.processApplyField();
			this.onChange();
		},
		processApplyField: function() {
			$('.gsf-field[data-required]').each(function() {
				var $this = $(this),
					required = $this.data('required'),
					fieldId = $this.attr('id'),
					i, j, requiredChild, requiredGrandChild,
					_name, _op, _value;
				if ($.isArray(required[0])) {
					for (i = 0; i < required.length; i++) {
						requiredChild = required[i];
						if ($.isArray(requiredChild[0])) {
							for (j = 0; j < requiredChild.length; j++) {
								requiredGrandChild = requiredChild[j];
								_name = requiredGrandChild[0];
								_op = requiredGrandChild[1];
								_value = requiredGrandChild[2];

								if (_name.indexOf('[') != -1) {
									_name = _name.replace(/\[.*/i,'');
								}

								if (typeof (GSFFieldsConfig.required.applyField[_name]) === "undefined") {
									GSFFieldsConfig.required.applyField[_name] = [];
								}
								if (GSFFieldsConfig.required.applyField[_name].indexOf(fieldId) === -1) {
									GSFFieldsConfig.required.applyField[_name].push(fieldId);
								}

								if (_op[0] === '&') {
									if (typeof (GSFFieldsConfig.required.applyField[_value]) === "undefined") {
										GSFFieldsConfig.required.applyField[_value] = [];
									}
									if (GSFFieldsConfig.required.applyField[_value].indexOf(fieldId) === -1) {
										GSFFieldsConfig.required.applyField[_value].push(fieldId);
									}
								}
							}
						}
						else {
							_name = requiredChild[0];
							_op = requiredChild[1];
							_value = requiredChild[2];

							if (_name.indexOf('[') != -1) {
								_name = _name.replace(/\[.*/i,'');
							}

							if (typeof (GSFFieldsConfig.required.applyField[_name]) === "undefined") {
								GSFFieldsConfig.required.applyField[_name] = [];
							}
							if (GSFFieldsConfig.required.applyField[_name].indexOf(fieldId) === -1) {
								GSFFieldsConfig.required.applyField[_name].push(fieldId);
							}
							if (_op[0] === '&') {
								if (typeof (GSFFieldsConfig.required.applyField[_value]) === "undefined") {
									GSFFieldsConfig.required.applyField[_value] = [];
								}
								if (GSFFieldsConfig.required.applyField[_value].indexOf(fieldId) === -1) {
									GSFFieldsConfig.required.applyField[_value].push(fieldId);
								}
							}
						}
					}
				}
				else {
					_name = required[0];
					_op = required[1];
					_value = required[2];

					if (_name.indexOf('[') != -1) {
						_name = _name.replace(/\[.*/i,'');
					}

					if (typeof (GSFFieldsConfig.required.applyField[_name]) === "undefined") {
						GSFFieldsConfig.required.applyField[_name] = [];
					}
					if (GSFFieldsConfig.required.applyField[_name].indexOf(fieldId) === -1) {
						GSFFieldsConfig.required.applyField[_name].push(fieldId);
					}
					if (_op[0] === '&') {
						if (typeof (GSFFieldsConfig.required.applyField[_value]) === "undefined") {
							GSFFieldsConfig.required.applyField[_value] = [];
						}
						if (GSFFieldsConfig.required.applyField[_value].indexOf(fieldId) === -1) {
							GSFFieldsConfig.required.applyField[_value].push(fieldId);
						}
					}
				}
			});
		},
		onChange: function() {
			$('.gsf-field').on('gsf_check_required', GSFFieldsConfig.required.onChangeEvent);
		},
		onChangeEvent: function (event) {
			if (this != event.target) {
				return;
			}
			var $this = $(this),
				fieldId = $this.attr('id');
			if (typeof ($this.data('field-value')) == "undefined") {
				return;
			}
			if (typeof (GSFFieldsConfig.required.applyField[fieldId]) === "undefined") {
				return;
			}
			var i,
				$cloneField = $this.closest('.gsf-clone-field-panel');
			if ($cloneField.length) {
				for (i = 0; i < GSFFieldsConfig.required.applyField[fieldId].length; i++) {
					GSFFieldsConfig.required.toggleField($('#' + GSFFieldsConfig.required.applyField[fieldId][i], $cloneField), $cloneField);
				}
			}
			else {
				for (i = 0; i < GSFFieldsConfig.required.applyField[fieldId].length; i++) {
					GSFFieldsConfig.required.toggleField($('#' + GSFFieldsConfig.required.applyField[fieldId][i]), $cloneField);
				}
			}
		},
		toggleField: function($field, $cloneField) {
			var required = $field.data('required'),
				isVisible = true;
			if (!$.isArray(required[0])) {
				isVisible = GSFFieldsConfig.required.processField(required, $cloneField);
			}
			else {
				isVisible = GSFFieldsConfig.required.andCondition(required, $cloneField);
			}
			if (isVisible) {
				$field.slideDown();
			}
			else {
				$field.slideUp();
			}
		},
		andCondition: function(required, $cloneField) {
			var requiredChild, i;
			for (i = 0; i < required.length; i++) {
				requiredChild = required[i];
				if (!$.isArray(requiredChild[0])) {
					if (!GSFFieldsConfig.required.processField(requiredChild, $cloneField))
					{
						return false;
					}
				}
				else {
					if (!GSFFieldsConfig.required.orCondition(requiredChild, $cloneField)) {
						return false;
					}
				}
			}
			return true;
		},
		orCondition: function(required, $cloneField) {
			var requiredChild, i;
			for (i = 0; i < required.length; i++) {
				requiredChild = required[i];
				if (GSFFieldsConfig.required.processField(requiredChild, $cloneField)) {
					return true;
				}
			}
			return false;
		},
		processField: function(required, $cloneField) {
			var _field = required[0],
				_op = required[1],
				_val = required[2],
				fieldVal,
				_field_key = '';
			if (_field.indexOf('[') != -1) {
				var _field_temp = _field.replace(/\[.*/i,'');
				_field_key = _field.substring(_field_temp.length);
				_field_key = _field_key.substr(1, _field_key.length - 2);
				_field = _field_temp;
			}

			if ($cloneField.length) {
				fieldVal = $('#' + _field, $cloneField).data('field-value');
			}
			else {
				fieldVal = $('#' + _field).data('field-value');
			}
			if ((_field_key !== '') && (typeof (fieldVal[_field_key]) !== "undefined")) {
				fieldVal = fieldVal[_field_key];
			}

			if (_op.substr(0, 1) === '&') {
				if ($cloneField.length) {
					_val = $('#' + _val, $cloneField).data('field-value');
				}
				else {
					_val = $('#' + _val).data('field-value');
				}
			}

			// _op: =, !=, in, not in, contain, not contain
			// _op start with "&": reference to field (_val)
			switch (_op) {
				case '=':
				case '&=':
					return _val == fieldVal;
				case  '!=':
				case  '&!=':
					return _val != fieldVal;
				case  'in':
				case  '&in':
					return (_val == fieldVal) || $.isArray(_val) && (_val.indexOf(fieldVal) != -1);
				case  'not in':
				case  '&not in':
					return (!$.isArray(_val) && (_val != fieldVal)) || (_val.indexOf(fieldVal) == -1);
				case  'contain':
				case  '&contain':
					return (_val == fieldVal) || ($.isArray(fieldVal) && (fieldVal.indexOf(_val) != -1)) || ((typeof(fieldVal) === "object" ) && (_val in fieldVal));
				case  'not contain':
				case  '&not contain':
					return (!$.isArray(fieldVal) && (fieldVal != _val)) || (fieldVal.indexOf(_val) == -1);
			}
			return false;
		},
		checkRequired: function($field, value) {
			$field.data('field-value', value);
			$field.trigger('gsf_check_required');
			$field.trigger('gsf_check_preset');
			$field.trigger('gsf_field_change');
		}
	};

	GSFFieldsConfig.preset = {
		init: function() {
			this.onChange();
		},
		onChange: function() {
			$('.gsf-fields-wrapper').on('gsf_check_preset', '.gsf-field', function(event) {
				if (this != event.target) {
					return;
				}
				var $this = $(this),
					$panel = $this.closest('.gsf-clone-field-panel');

				if ($panel.length === 0) {
					$panel = $('.gsf-fields-wrapper');
				}

				if (typeof ($this.data('field-value')) == "undefined") {
					return;
				}
				var dataPreset = $this.data('preset');
				if (typeof (dataPreset) == "undefined") {
					return;
				}
				var fieldValue = $this.data('field-value'),
					i, j, _op, _value, _fields;
				for (i = 0; i < dataPreset.length; i++) {
					_op = dataPreset[i]['op'];
					_value = dataPreset[i]['value'];
					_fields = dataPreset[i]['fields'];
					if (((_op === '=') && (_value == fieldValue)) || ((_op === '!=') && (_value != fieldValue))) {
						for (j = 0; j < _fields.length; j++) {
							var $field =  $panel.find('#' + _fields[j][0]);
							$field.find('[data-field-control]').val(_fields[j][1]);
							$field.find('[data-field-control]').trigger('gsf_preset_change', _fields[j][1]);
							$field.find('[data-field-control]').trigger('change');
						}
						break;
					}
				}

			});
		}
	};

	/**
	 * Clone Field
	 */
	GSFFieldsConfig.cloneField = {
		cloneTemplate: [],
		init: function() {
			this.makeCloneTemplate();
			this.addButton();
			this.removeButton();
			this.sortableField();
		},
		makeCloneTemplate: function(item) {
			var cloneIndex = 0;
			$('.gsf-field-content-inner-clone').each(function() {
				var fieldClone = $('> .gsf-clone-field:last', this);
				if (fieldClone.length > 0) {
					GSFFieldsConfig.cloneField.cloneTemplate[cloneIndex] = fieldClone[0].outerHTML;
					$(this).attr('data-clone-template', cloneIndex);
					cloneIndex++;
				}
			});

			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.trigger('gsf_make_template_done');
		},
		makeCloneTemplateElement: function ($item) {
			var cloneIndex = GSFFieldsConfig.cloneField.cloneTemplate.length + 1;
			$item.each(function() {
				var fieldClone = $('> .gsf-clone-field:last', this);
				if (fieldClone.length > 0) {
					GSFFieldsConfig.cloneField.cloneTemplate[cloneIndex] = fieldClone[0].outerHTML;
					$(this).attr('data-clone-template', cloneIndex);
					cloneIndex++;
				}
			});
			$item.trigger('gsf_make_template_item_done');
		},

		reIndexFieldName: function($wrapper, isRepeater) {
			var cloneIndex = 0,
				isCloneInner = false,
				isClonePanel = false;
			var $field = $wrapper.closest('.gsf-field');
			if ($field.hasClass('gsf-panel')) {
				isClonePanel = true;
			}
			else {
				if ($field.closest('.gsf-panel').length) {
					isCloneInner = true;
				}
			}
			if (!isClonePanel) {
				$('.gsf-clone-field', $wrapper).each(function() {
					$(':input[name$="]"]', this).each(function() {
						if (isCloneInner) {
							var $this = $(this),
								fullName = $this.attr('name'),
								after = fullName.replace(/\w+\[\d+\]\[\w+\]/i,''),
								name = fullName.substring(0, fullName.length - after.length),
								affix = after.replace(/^\[\d+\]/i,'');
							$this.attr('name', name + '[' + cloneIndex + ']' + affix);

							var panelIndex = $(this).closest('.gsf-clone-field').parent().closest('.gsf-clone-field').data('panel-index');
							name = $this.attr('name').replace(/\[.*/i,'');
							affix = $this.attr('name').replace(/[^\]]*\]/i,'');
							$this.attr('name', name + '[' + panelIndex + ']' + affix);
						}
						else {
							var $this = $(this);
							var name = $this.attr('name').replace(/\[.*/i,'');
							var affix = $this.attr('name').replace(/[^\]]*\]/i,'');
							$this.attr('name', name + '[' + cloneIndex + ']' + affix);
						}
					});
					cloneIndex++;
				});
			}
			else {
				cloneIndex = 0;
				$('> .gsf-clone-field', $wrapper).each(function() {
					$(this).data('panel-index', cloneIndex);
					$(':input[name$="]"]', this).each(function() {
						var $this = $(this);
						var name = $this.attr('name').replace(/\[.*/i,'');
						var affix = $this.attr('name').replace(/[^\]]*\]/i,'');
						$this.attr('name', name + '[' + cloneIndex + ']' + affix);
					});
					cloneIndex++;
				});
			}


			if (isRepeater) {
				$('input[type="hidden"]', $wrapper).val(cloneIndex);
			}


		},
		removeButton: function($element) {
			if (typeof ($element) === "undefined") {
				$element = $('.gsf-fields-wrapper');
			}

			$element.find('.gsf-clone-button-remove').on('click', function() {
				var $parent = $(this).parent();
				var $wrapper = $parent.parent();
				$parent.remove();
				GSFFieldsConfig.cloneField.reIndexFieldName($wrapper, $(this).hasClass('gsf-is-repeater'));
			});
		},
		addButton: function() {
			$('.gsf-clone-button-add').on('click', function() {
				var $parent = $(this).parent().find('> .gsf-field-content-inner');
				if (typeof ($parent.attr('data-clone-template')) == "undefined") {
					return;
				}
				var cloneIndex = parseInt($parent.attr('data-clone-template'), 10);
				var $lastCloneField = $('> .gsf-clone-field:last', $parent);
				var $element = $(GSFFieldsConfig.cloneField.cloneTemplate[cloneIndex]);

				if ($lastCloneField.length == 0) {
					$parent.prepend($element);
				}
				else {
					$lastCloneField.after($element);
				}
				GSFFieldsConfig.cloneField.removeButton($element);
				GSFFieldsConfig.cloneField.emptyElementValue($element);

				GSFFieldsConfig.cloneField.reIndexFieldName($parent, $(this).hasClass('gsf-is-repeater'));
				$element.trigger('gsf_add_clone_field');
			});
		},
		emptyElementValue: function($element) {
			$('[data-field-control]', $element).each(function() {
				var $field = $(this).closest('.gsf-field');
				$field.data('field-value', '');
				if ($field.hasClass('gsf-field-text')) {
					$(this).val('');
				}
				else if ($field.hasClass('gsf-field-select')) {
					$(this).prop('selectedIndex', 0);
				}
			});
		},
		sortableField: function() {
			$( '.gsf-field-content-inner-clone' ).sortable({
				placeholder: "gsf-field-clone-sortable-placeholder",
				handle: '.gsf-sortable-button',
				update: function( event, ui ) {
					var $wrapper = $(event.target);
					GSFFieldsConfig.cloneField.reIndexFieldName($wrapper, false);
				}
			});
		}
	};

	/**
	 * Group Field Process
	 */
	GSFFieldsConfig.group = {
		init: function() {
			this.toggle();
		},
		toggle: function() {
			$('.gsf-group-toggle').closest('h4').on('click', function(){
				var $this = $(this),
					$toggleIcon = $this.find('.gsf-group-toggle'),
					$inner = $this.next('.gsf-group-inner');
				$toggleIcon.toggleClass('dashicons-arrow-up').toggleClass('dashicons-arrow-down');
				$inner.slideToggle();
			});
		}
	};

	/**
	 * Other Fields
	 */
	GSFFieldsConfig.fields = {
		/**
		 * Get value of field
		 * @param $field
		 * @param input
		 * @returns {string}
		 */
		getValue: function($field) {
			var input = '[data-field-control]',
				value = '',
				$firstField = $(input + ':first', $field),
				fieldType = $firstField.attr('type'),
				fieldName = $firstField.attr('name'),
				fieldMap = $field.data('field-map'),
				isMultiple = fieldName.match(/\[\]$/i);
			if (fieldMap != '') {
				fieldMap = fieldMap.split(',');
			}

			if (typeof (fieldType) === "undefined") {
				fieldType = '';
			}
			fieldType = fieldType.toLowerCase();
			var isOptionInput = ((fieldType == 'radio') || (fieldType == 'checkbox') ? true : false);

			if ($('.gsf-clone-field', $field).length) {
				value = [];
				$('.gsf-clone-field', $field).each(function () {
					if (isMultiple) {
						var valueChild = [];
						$(input, this).each(function() {
							if (isOptionInput) {
								// Only checkbox
								if ($(this).prop('checked')) {
									valueChild.push($(this).val());
								}
								else {
									valueChild.push('');
								}
							}
							else {
								valueChild.push($(this).val());
							}
						});
						value.push(valueChild);
					}
					else {
						if (fieldMap.length) {
							valueChild = [];
							for (var mapIndex = 0; mapIndex < fieldMap.length; mapIndex++) {
								var $thisControl = $(input + '[name$="[' + fieldMap[mapIndex] + ']"]', this);

								fieldType = $thisControl.attr('type');
								if (typeof (fieldType) === "undefined") {
									fieldType = '';
								}
								fieldType = fieldType.toLowerCase();
								isOptionInput = ((fieldType == 'radio') || (fieldType == 'checkbox') ? true : false);

								if (isOptionInput) {
									if ($thisControl.prop('checked')) {
										valueChild[fieldMap[mapIndex]] = $thisControl.val();
									}
									else {
										if (!$thisControl.data('uncheck-novalue')) {
											value[fieldMap[mapIndex]] = '';
										}
										valueChild[fieldMap[mapIndex]] = '';
									}
								}
								else {
									valueChild[fieldMap[mapIndex]] = $thisControl.val();
								}
							}
							value.push(valueChild);
						}
						else {
							if (isOptionInput) {
								if (fieldType === 'radio') {
									var _noVal = true;
									$(input, this).each(function() {
										if ($(this).prop('checked')) {
											value.push($(this).val());
											_noVal = false;
										}
									});
									if (_noVal) {
										value.push('');
									}
								}
								else {
									if ($(input, this).prop('checked')) {
										value.push($(input, this).val());
									}
									else {
										value.push('');
									}
								}
							}
							else {
								value.push($(input, this).val());
							}
						}
					}
				});
			}
			else {
				if (isMultiple) {
					value = [];
					if (isOptionInput) {
						// Only checkbox
						$(input, $field).each(function() {
							if ($(this).prop('checked')) {
								value.push($(this).val());
							}
							else {
								value.push('');
							}
						});
					}
					else {
						$(input, $field).each(function() {
							value.push($(this).val());
						});
					}
				}
				else {
					if (fieldMap.length) {
						value = [];
						for (var mapIndex = 0; mapIndex < fieldMap.length; mapIndex++) {
							var $thisControl = $(input + '[name$="[' + fieldMap[mapIndex] + ']"]', $field);
							fieldType = $thisControl.attr('type');
							if (typeof (fieldType) === "undefined") {
								fieldType = '';
							}
							fieldType = fieldType.toLowerCase();
							isOptionInput = ((fieldType == 'radio') || (fieldType == 'checkbox') ? true : false);

							if (isOptionInput) {
								if ($thisControl.prop('checked')) {
									value[fieldMap[mapIndex]] = $thisControl.val();
								}
								else {
									if (!$thisControl.data('uncheck-novalue')) {
										value[fieldMap[mapIndex]] = '';
									}

								}
							}
							else {
								value[fieldMap[mapIndex]] = $thisControl.val();
							}
						}
					}
					else {
						if (isOptionInput) {
							if (fieldType === 'radio') {
								$(input, $field).each(function() {
									if ($(this).prop('checked')) {
										value = $(this).val();
									}
								});
							}
							else {
								if ($(input, $field).prop('checked')) {
									value = $(input, $field).val();
								}
							}
						}
						else {
							value = $(input, $field).val();
						}
					}
				}
			}
			return value;
		}
	};

	/**
	 * Tabs for metabox
	 * @type {{init: Function}}
	 */
	GSFFieldsConfig.tabs = {
		init: function() {
			this.toggle();
			setTimeout(function() {
				GSFFieldsConfig.tabs.changeWidthContent();
			}, 100);

		},
		toggle: function() {
			$('.gsf-tab a').on('click', function(event) {
				var idCurrent = $('.gsf-fields-wrapper > div.gsf-section-container:visible').attr('id');
				event.preventDefault();
				if (typeof (event.currentTarget.hash) != "undefined") {
					$('#' + idCurrent).hide();
					$(event.currentTarget.hash).fadeIn();
				}
				$('.gsf-tab li').removeClass('active');
				$(this).parent().addClass('active');
				$(this).trigger('gsf-tab-clicked');
			});
		},
		changeWidthContent: function () {
			var $tab = $('.gsf-tab');
			if ($tab.length > 0) {
				var $wrap = $('.gsf-meta-box-wrap'),
					$fields = $('.gsf-fields'),
					tabWidth = $tab.outerWidth(),
					wrapWidth = $wrap.width();
				$fields.css({
					'float': 'left',
					'width': (wrapWidth - tabWidth) + 'px',
					'overflow': 'visible'
				});
			}
		}
	};

	GSFFieldsConfig.onReady = {
		init: function() {
			GSFFieldsConfig.cloneField.init();
			GSFFieldsConfig.group.init();
			GSFFieldsConfig.required.init();
			GSFFieldsConfig.preset.init();
			GSFFieldsConfig.tabs.init();
			$('.gsf-field').trigger('gsf_check_required');
		}
	};
	GSFFieldsConfig.onResize = {
		init: function() {
			GSFFieldsConfig.tabs.changeWidthContent();
		}
	};
	$(document).ready(GSFFieldsConfig.onReady.init);
	$(window).resize(GSFFieldsConfig.onResize.init);
})(jQuery);