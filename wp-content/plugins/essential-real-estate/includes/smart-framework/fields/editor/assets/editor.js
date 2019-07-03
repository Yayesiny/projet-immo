/**
 * editor field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_EditorClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_EditorClass.prototype = {
		init: function() {
			this.initEditor();
			this.onChangeWPEditorArea();
		},
		initEditor: function() {
			/**
			 * Replace Id, name, data-wp-editor-id
			 */
			var $editorTextArea = this.$container.find('.wp-editor-area'),
				textarea_name = $editorTextArea.attr('name'),
				id = textarea_name.replace(/[\[\]]/g, '__') + '__editor',
				oldId = $editorTextArea.attr('id');

			/**
			 * Wrapper div and media buttons
			 */
			this.$container.find('.wp-editor-wrap').attr('id', 'wp-' + id + '-wrap')
				.removeClass('html-active').addClass('tmce-active') // Active the visual mode by default
				.find('.wp-editor-tools').attr('id', 'wp-' + id + '-editor-tools')
				.find('.wp-media-buttons').attr('id', 'wp-' + id + '-media-buttons')
				.find('.insert-media').data('editor', id);

			/**
			 * Editor tabs
			 */
			this.$container.find('.switch-tmce')
				.attr('id', id + '-tmce')
				.data('wp-editor-id', id).attr('data-wp-editor-id', id).end()
				.find('.switch-html')
				.attr('id', id + '-html')
				.data('wp-editor-id', id).attr('data-wp-editor-id', id);

			/**
			 * Quick tags
			 */
			this.$container.find('.wp-editor-container').attr('id', 'wp-' + id + '-editor-container')
				.find('.quicktags-toolbar').attr('id', 'qt_' + id + '_toolbar').html('');

			/**
			 * Text area
			 */
			this.$container.find('.wp-editor-container').find('.wp-editor-area')
				.attr('id', id)
				.val('');


			//init tinymce
			if (oldId in tinyMCEPreInit.mceInit) {
				var newMceInit = JSON.parse(JSON.stringify(tinyMCEPreInit.mceInit[oldId]));
				newMceInit['body_class'] = newMceInit['body_class'].replace(oldId, id);
				newMceInit['selector'] = newMceInit['selector'].replace(oldId, id);
				tinymce.execCommand('mceRemoveEditor', false, id);
				tinymce.init(newMceInit);
			}
			if (oldId in tinyMCEPreInit.qtInit) {
				var newQtInit = JSON.parse(JSON.stringify(tinyMCEPreInit.qtInit[oldId]));
				quicktags({id: id, buttons: newQtInit['buttons']});
				QTags._buttonsInit();
			}
		},
		onChangeWPEditorArea: function() {
			this.$container.find('.wp-editor-area').on('change', function() {
				var $field = $(this).closest('.gsf-field');
				$field.trigger('gsf_field_change');
			});
		}
	};

	/**
	 * Define object field
	 */
	var GSF_EditorObject = {
		init: function() {
			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-editor').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-editor-inner');
				if ($items.length) {
					var field = new GSF_EditorClass($items);
					field.init();
				}
			});

			/**
			 * Change editor has been init
			 */
			setTimeout(function () {
				if (typeof(tinymce) !== "undefined") {
					for (var i = 0; i < tinymce.editors.length; i++) {
						GSF_EditorObject.editorChange(i);
					}
				}
			}, 1000);
		},
		editorChange: function (i) {
			tinymce.editors[i].on('change', function (e) {
				var $field = $(e.target.contentAreaContainer).closest('.gsf-field');
				$field.trigger('gsf_field_change');
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_EditorObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_EditorObject);
	});
})(jQuery);