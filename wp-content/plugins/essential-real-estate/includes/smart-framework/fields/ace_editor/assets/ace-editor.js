/**
 * ace-editor field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_AceEditorClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_AceEditorClass.prototype = {
		init: function() {
			this.$fieldText = this.$container.find('textarea');
			this.$editorField = this.$container.find('.gsf-ace-editor');
			var params = this.$fieldText.data('options'),
				mode = this.$fieldText.data('mode'),
				theme = this.$fieldText.data('theme');
			this.editor = ace.edit(this.$editorField.attr('id'));
			this.$editorField.attr('id', '');
			if (mode != '') {
				this.editor.session.setMode('ace/mode/' + mode);
			}
			if (theme != '') {
				this.editor.setTheme('ace/theme/' + theme);
			}

			this.editor.setAutoScrollEditorIntoView(true);
			this.editor.setOptions(params);
			var self = this;
			this.editor.on('change', function (event) {
				self.$fieldText.val(self.editor.getSession().getValue());

				var $field = self.$container.closest('.gsf-field');
				$field.trigger('gsf_field_change');
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_AceEditorObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-ace-editor-inner').each(function () {
					var field = new GSF_AceEditorClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-ace_editor').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-ace-editor-inner');
				if ($items.length) {
					var field = new GSF_AceEditorClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_AceEditorObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_AceEditorObject);
	});
})(jQuery);