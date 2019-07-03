/*
WP and Divi Icons by Divi Space, an Aspen Grove Studios company
Licensed under the GNU General Public License v3 (see ../license.txt)

This plugin includes code based on parts of the Divi theme and/or the
Divi Builder by Elegant Themes, licensed GPLv2, used under GPLv3 in this project by special permission (see ../license.txt).
*/

jQuery(document).ready(function($) {
	var fbBody = $('body.et-fb');
	if (fbBody.length) {
		var MO = window.MutationObserver ? window.MutationObserver : window.WebkitMutationObserver;
		if (MO) {
			(new MO(function(events) {
				$.each(events, function(i, event) {
					if (event.target && (event.type != 'characterData' || event.target.parentElement)) {
						var $element = $(event.type == 'characterData' ? event.target.parentElement : event.target);
						if ($element.hasClass('et-pb-icon') && $element.closest('.et_pb_main_blurb_image').length
							&& $element.closest('.et_pb_module.et_pb_blurb')) {
								if ($element.hasClass('agsdi-updating')) {
									$element.removeClass('agsdi-updating');
								} else {
									var iconContent = $element.html();
									if (iconContent.substr(0, 6) == 'agsdi-' || iconContent.substr(0, 7) == 'agsdix-') {
										$element.attr('data-icon', iconContent)
											.addClass('agsdi-updating')
											.html('');
									} else {
										var dataIconValue = $element.attr('data-icon');
										if (dataIconValue.substr(0, 6) == 'agsdi-' || dataIconValue.substr(0, 7) == 'agsdix-') {
											$element.attr('data-icon', null);
										}
									}
								}
						} else if (event.addedNodes && event.addedNodes.length) {
							$.each(event.addedNodes, function(i, node) {
								$(node).find('.et-pb-icon').each(function() {
									var $iconChild = $(this);
									if ($iconChild.closest('.et_pb_module.et_pb_blurb .et_pb_main_blurb_image').length) {
										var iconContent = $iconChild.html();
										if (iconContent.substr(0, 6) == 'agsdi-' || iconContent.substr(0, 7) == 'agsdix-') {
											$iconChild.attr('data-icon', iconContent)
												.addClass('agsdi-updating')
												.html('');
										}
									}
								});
								var $iconPicker = $(node).find('.et-fb-font-icon-list');
								$('<input>').attr({
									type: 'search',
									placeholder: 'Search icons...',
									oninput: 'agsdi_search(this);',
								}).addClass('agsdi-picker-search-divi-fb et-fb-settings-option-input').insertBefore($iconPicker);
								$iconPicker.after(
									// Credit HTML copied from ds-icon-expansion-pack.php
									'<span class="agsdi-picker-credit">With free icons by <a href="https://divi.space/?utm_source=ds-icon-expansion&amp;utm_medium=plugin-credit-link&amp;utm_content=divi-visual-builder" target="_blank">Divi Space</a><span class="agsdi-picker-credit-promo"></span></span>'
								);
							});
						}
					}
				});
			})).observe(fbBody[0], {characterData: true, childList: true, subtree: true});
		}
	}
	var bfbBody = $('body.et-bfb');
	if (bfbBody.length) {
		var MO2 = window.MutationObserver ? window.MutationObserver : window.WebkitMutationObserver;
		if (MO2) {
			(new MO2(function(events) {
				$.each(events, function(i, event) {
					if (event.target && (event.type != 'characterData' || event.target.parentElement)) {
						var $element = $(event.type == 'characterData' ? event.target.parentElement : event.target);
						if (event.addedNodes && event.addedNodes.length) {
							$.each(event.addedNodes, function(i, node) {
								$(node).find('.et-pb-icon').each(function() {
									var $iconChild = $(this);
									if ($iconChild.closest('.et_pb_module.et_pb_blurb .et_pb_main_blurb_image').length) {
										var iconContent = $iconChild.html();
										if (iconContent.substr(0, 6) == 'agsdi-' || iconContent.substr(0, 7) == 'agsdix-') {
											$iconChild.attr('data-icon', iconContent)
												.addClass('agsdi-updating')
												.html('');
										}
									}
								});
								var $iconPicker = $(node).find('.et-fb-font-icon-list');
								$('<input>').attr({
									type: 'search',
									placeholder: 'Search icons...',
									oninput: 'agsdi_search(this);',
								}).addClass('agsdi-picker-search-divi-fb et-fb-settings-option-input').insertBefore($iconPicker);
							});
						}
					}
				});
			})).observe(bfbBody[0], {characterData: true, childList: true, subtree: true});
		}
	}
});