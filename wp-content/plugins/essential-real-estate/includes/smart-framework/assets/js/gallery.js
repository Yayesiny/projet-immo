/**
 * Define class field
 */
var GSF_GalleryClass = function($container) {
	this.$container = $container;
};
(function ($) {
	"use strict";
	/**
	 * Define class field prototype
	 */
	GSF_GalleryClass.prototype = {
		init: function() {
			this.select();
			this.remove();
			this.sortable();
		},
		select: function () {
			var _media = new GSFMedia(),
				$addButton = this.$container.find('.gsf-gallery-add');
			_media.selectGallery($addButton, {filter: 'image'}, function(attachments) {
				if (attachments.length) {
					var $this = $(_media.clickedButton);
					var $parent = $this.parent();
					var $input = $parent.find('input[type="hidden"]');
					var valInput = $input.val();
					var arrInput = valInput.split('|');
					var imgHtml = '';
					var url_image='';
					attachments.each(function(attachment) {
						attachment = attachment.toJSON();

						if (arrInput.indexOf('' + attachment.id) != -1) {
							return;
						}
						if (valInput != '') {
							valInput += '|' + attachment.id;
						}
						else {
							valInput = '' + attachment.id;
						}
						arrInput.push('' + attachment.id);
						if( attachment.sizes){
							if(   attachment.sizes.thumbnail !== undefined  ) url_image=attachment.sizes.thumbnail.url;
							else if( attachment.sizes.medium !== undefined ) url_image=attachment.sizes.medium.url;
							else url_image=attachment.sizes.full.url;
							imgHtml += '<div class="gsf-image-preview" data-id="' + attachment.id + '">';
							imgHtml +='<div class="centered">';
							imgHtml += '<img src="' + url_image + '" alt=""/>';
							imgHtml += '</div>';
							imgHtml += '<span class="gsf-gallery-remove dashicons dashicons dashicons-no-alt"></span>';
							imgHtml += '</div>';
						}
					});
					$input.val(valInput);
					$this.before(imgHtml);
					$this.trigger('gsf-gallery-selected');
				}
			});
		},
		remove: function() {
			this.$container.on('click', '.gsf-gallery-remove', function() {
				var $this = $(this).parent();
				var $parent = $this.parent();
				var $input = $parent.find('input[type="hidden"]');
				$this.remove();
				var valInput = '';
				$('.gsf-image-preview', $parent).each(function() {
					if (valInput != '') {
						valInput += '|' + $(this).data('id');
					}
					else {
						valInput = '' + $(this).data('id');
					}
				});
				$input.val(valInput);
				$parent.trigger('gsf-gallery-removed');
			});
		},
		sortable: function () {
			this.$container.sortable({
				placeholder: "gsf-gallery-sortable-placeholder",
				items: '.gsf-image-preview',
				update: function( event, ui ) {
					var $wrapper = $(event.target);
					var valInput = '';
					$('.gsf-image-preview', $wrapper).each(function() {
						if (valInput != '') {
							valInput += '|' + $(this).data('id');
						}
						else {
							valInput = '' + $(this).data('id');
						}
					});
					var $input = $wrapper.find('input[type="hidden"]');
					$input.val(valInput);
					$wrapper.trigger('gsf-gallery-sortable-updated');
				}
			});
		}
	};
})(jQuery);
