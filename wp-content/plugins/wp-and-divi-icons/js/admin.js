/*
WP and Divi Icons by Divi Space, an Aspen Grove Studios company
Licensed under the GNU General Public License v3 (see ../license.txt)

This plugin includes code based on parts of the Divi theme and/or the
Divi Builder by Elegant Themes, licensed GPLv2, used under GPLv3 in this project by special permission (see ../license.txt).
*/

jQuery(document).ready(function($) {
	function getPromo() {
		return ags_divi_icons_credit_promos[Math.floor(Math.random() * ags_divi_icons_credit_promos.length)];
	}
	setInterval(function() {
		 $('.agsdi-picker-credit-promo:empty:visible').fadeOut(300, function() {
			var $promo = $(this).html(getPromo());
			if (!$promo.hasClass('agsdi-picker-credit-promo-tinymce')) {
				$promo.siblings('.agsdi-picker-credit-separator').remove();
				$('<span>')
					.addClass('agsdi-picker-credit-separator')
					.hide()
					.html(' &bull; ')
					.insertBefore($promo);
			}
			$promo.fadeIn(300);
		});
	}, 1000);
	setInterval(function() {
		 $('.agsdi-picker-credit-promo:parent:visible').fadeOut(300, function() {
			var $promo = $(this);
			var oldPromoContentText = $promo.text();
			do {
				var newPromoContent = getPromo();
				var newPromoContentText = $('<div>').html(newPromoContent).text();
			} while (newPromoContentText == oldPromoContentText);
			$promo.html(newPromoContent).fadeIn(300);
		});
	}, 6600);
	
	$('#agsdi-colors-add').click(function() {
		var $button = $(this);
		var $colorPreview = $('<div>').addClass('agsdi-color-preview');
		var $scheme = $('<div>')
						.attr('data-colors-id', 'new')
						.append($colorPreview)
						.append($('<input>').attr({type: 'text', name: 'agsdi_colors[new][]'}).val('#6C75E2'))
						.append($('<input>').attr({type: 'text', name: 'agsdi_colors[new][]'}).val('#FEB480'))
						.append($('<input>').attr({type: 'text', name: 'agsdi_colors[new][]'}).val('#E6E7E8'))
						.append($('<button>').attr({type: 'button'}).addClass('agsdi-colors-remove button-secondary').text('Remove'))
						.insertBefore($button.parent());
						
		createColorPreview($colorPreview);
		$scheme
			.find('input[type=\'text\']')
			.wpColorPicker({
				change:onColorsColorChange
			});
		onColorsColorChange($scheme);
		$button.hide();
		$('#agsdi-color-schemes-none').addClass('hidden');
	});
	
	$('#agsdi-color-schemes > div').each(function() {
		var $scheme = $(this);
		createColorPreview($scheme.find('.agsdi-color-preview'));
		$scheme
			.find('input[type=\'text\']')
			.wpColorPicker({
				change:onColorsColorChange
			});
		onColorsColorChange($scheme);
	});
	
	$('#agsdi-color-schemes').on('click', '.agsdi-colors-remove', function() {
		var $colorScheme = $(this).closest('[data-colors-id]');
		var wasLastColorScheme = !$colorScheme.siblings('[data-colors-id]').length;
		$colorScheme.remove();
		if (wasLastColorScheme) {
			$('#agsdi-color-schemes-none').removeClass('hidden');
		}
		if ($colorScheme.attr('data-colors-id') == 'new') {
			$('#agsdi-colors-add').show();
		}
	});
	
	
	
	function createColorPreview(container) {
		for (var i = 0; i < agsdi_color_preview_icons.length; ++i) {
			$('<div>').attr('data-icon', agsdi_color_preview_icons[i]).appendTo(container);
		}
	}
	
	function onColorsColorChange($scheme) {
		var $scheme = $scheme.length ? $scheme : $(this).closest('[data-colors-id]');
		var $inputs = $scheme.find('input[type=\'text\']');
		var colorsId = $scheme.attr('data-colors-id');
		var $style = $('#agsdi-color-preview-style-' + colorsId);
		if (!$style.length) {
			$style = $('<style>').attr('id', '#agsdi-color-preview-style-' + colorsId).appendTo('head:first');
		}
		$style.html('#agsdi-color-schemes > [data-colors-id=\'' + colorsId + '\'] .agsdi-color-preview div:before{background-image:url(\'' + ajaxurl + '?action=agsdi_colorize_preview&colors[]=' + encodeURIComponent($inputs.eq(0).wpColorPicker('color')) + '&colors[]=' + encodeURIComponent($inputs.eq(1).wpColorPicker('color')) + '&colors[]=' + encodeURIComponent($inputs.eq(2).wpColorPicker('color')) + '\'); }');
	}
});

var agsdi_search_id = 0, agsdi_search_timeout;
function agsdi_search(searchField) {
	if (agsdi_search_timeout) {
		clearTimeout(agsdi_search_timeout);
	}
	
	agsdi_search_timeout = setTimeout(function() {
		var $ = jQuery;
		var $searchField = $(searchField);
		var $searchTarget = $searchField.siblings('ul,.agsdi-icons:first');
		
		if ($searchTarget.children(':not([data-agsdi-keywords])').length) {
			$searchTarget.children().each(function() {
				var $icon = $(this);
				var iconId = $icon.attr('data-icon');
				if (iconId.substr(0, 6) == 'agsdi-') {
					var keywords = iconId.substr(6);
				} else if (iconId.substr(0, 9) == 'agsdix-fa') {
					var keywords = iconId.substr(14);
				} else if (iconId.substr(0, 7) == 'agsdix-') {
					var keywords = iconId.substr(iconId.indexOf('-', 7) + 1);
				} else {
					var keywords = '';
				}
				
				if (keywords) {
					keywords = keywords.split('-').join(' ');
				}
				
				if (agsdi_icon_aliases[iconId]) {
					keywords = (keywords ? keywords + ' ' : '') + agsdi_icon_aliases[iconId];
				}
				
				$icon.attr('data-agsdi-keywords', keywords);
			});
		}
		
		var $searchCss = $('#agsdi-search-css');
		if (!$searchCss.length) {
			$searchCss = $('<style>').attr('id', 'agsdi-search-css').appendTo('head:first');
		}
		
		var query = $searchField.val().trim();
		if (query) {
			var searchId = ++agsdi_search_id;
			$searchTarget.addClass('agsdi-search').attr('data-agsdi-search-id', searchId);
			var queryCss = query.split(' ');
			for (var i = 0; i < queryCss.length; ++i) {
				queryCss[i] = '.agsdi-search[data-agsdi-search-id=\'' + searchId + '\']>:not([data-agsdi-keywords*=\'' + queryCss[i] + '\'])';
			}
			$searchCss.html(queryCss.join() + '{display:none!important;}');
		} else {
			$searchTarget.removeClass('agsdi-search').attr('data-agsdi-search-id', null);
			$searchCss.html('');
		}
	}, 500);
}

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
								/*$iconPicker.after(
									// Credit HTML copied from ds-icon-expansion-pack.php
									'<span class="agsdi-picker-credit">With free icons by <a href="https://divi.space/?utm_source=ds-icon-expansion&amp;utm_medium=plugin-credit-link&amp;utm_content=divi-visual-builder" target="_blank">Divi Space</a><span class="agsdi-picker-credit-promo"></span></span>'
								);*/
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

// This must be the last line - do not modify (automatically updated)
// Includes data copied from the Divi theme (core/admin/fonts/modules.svg)
var agsdi_icon_aliases={"agsdi-message":"paper airplane","!":"arrow up","\"":"arrow down","#":"arrow left","$":"arrow right","%":"arrow left up","&":"arrow right up","'":"arrow right down","(":"arrow left down",")":"arrow up down","*":"arrow up down alt","+":"arrow left right alt",",":"arrow left right","-":"arrow expand alt2",".":"arrow expand alt","\/":"arrow condense","1":"arrow move","2":"arrow carrot up","3":"arrow carrot down","4":"arrow carrot left","5":"arrow carrot right","6":"arrow carrot 2up","7":"arrow carrot 2down","8":"arrow carrot 2left","9":"arrow carrot 2right",":":"arrow carrot up alt2",";":"arrow carrot down alt2","<":"arrow carrot left alt2","=":"arrow carrot right alt2",">":"arrow carrot 2up alt2","?":"arrow carrot 2down alt2","@":"arrow carrot 2left alt2","A":"arrow carrot 2right alt2","B":"arrow triangle up","C":"arrow triangle down","D":"arrow triangle left","E":"arrow triangle right","F":"arrow triangle up alt2","G":"arrow triangle down alt2","H":"arrow triangle left alt2","I":"arrow triangle right alt2","J":"arrow back","K":"minus 06","L":"plus","M":"close","N":"check","O":"minus alt2","P":"plus alt2","Q":"close alt2","R":"check alt2","S":"zoom out alt","T":"zoom in alt","U":"search","V":"box empty","W":"box selected","X":"minus box","Y":"plus box","Z":"box checked","[":"circle empty","\\":"circle slelected","]":"stop alt2","^":"stop","_":"pause alt2","`":"pause","a":"menu","b":"menu square alt2","c":"menu circle alt2","d":"ul","e":"ol","f":"adjust horiz","g":"adjust vert","h":"document alt","i":"documents alt","j":"pencil","k":"pencil edit alt","l":"pencil edit","m":"folder alt","n":"folder open alt","o":"folder add alt","p":"info alt","q":"error oct alt","r":"error circle alt","s":"error triangle alt","t":"question alt2","u":"question","v":"comment alt","w":"chat alt","x":"vol mute alt","y":"volume low alt","z":"volume high alt","{":"quotations","|":"quotations alt2","}":"clock alt","~":"lock alt","\ue000":"lock open alt","\ue001":"key alt","\ue002":"cloud alt","\ue003":"cloud upload alt","\ue004":"cloud download alt","\ue005":"image","\ue006":"images","\ue007":"lightbulb alt","\ue008":"gift alt","\ue009":"house alt","\ue00a":"genius","\ue00b":"mobile","\ue00c":"tablet","\ue00d":"laptop","\ue00e":"desktop","\ue00f":"camera alt","\ue010":"mail alt","\ue011":"cone alt","\ue012":"ribbon alt","\ue013":"bag alt","\ue014":"creditcard","\ue015":"cart alt","\ue016":"paperclip","\ue017":"tag alt","\ue018":"tags alt","\ue019":"trash alt","\ue01a":"cursor alt","\ue01b":"mic alt","\ue01c":"compass alt","\ue01d":"pin alt","\ue01e":"pushpin alt","\ue01f":"map alt","\ue020":"drawer alt","\ue021":"toolbox alt","\ue022":"book alt","\ue023":"calendar","\ue024":"film","\ue025":"table","\ue026":"contacts alt","\ue027":"headphones","\ue028":"lifesaver","\ue029":"piechart","\ue02a":"refresh","\ue02b":"link alt","\ue02c":"link","\ue02d":"loading","\ue02e":"blocked","\ue02f":"archive alt","\ue030":"heart alt","\ue031":"star alt","\ue032":"star half alt","\ue033":"star","\ue034":"star half","\ue035":"tools","\ue036":"tool","\ue037":"cog","\ue038":"cogs","\ue039":"arrow up alt","\ue03a":"arrow down alt","\ue03b":"arrow left alt","\ue03c":"arrow right alt","\ue03d":"arrow left up alt","\ue03e":"arrow right up alt","\ue03f":"arrow right down alt","\ue040":"arrow left down alt","\ue041":"arrow condense alt","\ue042":"arrow expand alt3","\ue043":"arrow carrot up alt","\ue044":"arrow carrot down alt","\ue045":"arrow carrot left alt","\ue046":"arrow carrot right alt","\ue047":"arrow carrot 2up alt","\ue048":"arrow carrot 2dwnn alt","\ue049":"arrow carrot 2left alt","\ue04a":"arrow carrot 2right alt","\ue04b":"arrow triangle up alt","\ue04c":"arrow triangle down alt","\ue04d":"arrow triangle left alt","\ue04e":"arrow triangle right alt","\ue04f":"minus alt","\ue050":"plus alt","\ue051":"close alt","\ue052":"check alt","\ue053":"zoom out","\ue054":"zoom in","\ue055":"stop alt","\ue056":"menu square alt","\ue057":"menu circle alt","\ue058":"document","\ue059":"documents","\ue05a":"pencil alt","\ue05b":"folder","\ue05c":"folder open","\ue05d":"folder add","\ue05e":"folder upload","\ue05f":"folder download","\ue060":"info","\ue061":"error circle","\ue062":"error oct","\ue063":"error triangle","\ue064":"question alt","\ue065":"comment","\ue066":"chat","\ue067":"vol mute","\ue068":"volume low","\ue069":"volume high","\ue06a":"quotations alt","\ue06b":"clock","\ue06c":"lock","\ue06d":"lock open","\ue06e":"key","\ue06f":"cloud","\ue070":"cloud upload","\ue071":"cloud download","\ue072":"lightbulb","\ue073":"gift","\ue074":"house","\ue075":"camera","\ue076":"mail","\ue077":"cone","\ue078":"ribbon","\ue079":"bag","\ue07a":"cart","\ue07b":"tag","\ue07c":"tags","\ue07d":"trash","\ue07e":"cursor","\ue07f":"mic","\ue080":"compass","\ue081":"pin","\ue082":"pushpin","\ue083":"map","\ue084":"drawer","\ue085":"toolbox","\ue086":"book","\ue087":"contacts","\ue088":"archive","\ue089":"heart","\ue08a":"profile","\ue08b":"group","\ue08c":"grid 2x2","\ue08d":"grid 3x3","\ue08e":"music","\ue08f":"pause alt","\ue090":"phone","\ue091":"upload","\ue092":"download","\ue093":"social facebook","\ue094":"social twitter","\ue095":"social pinterest","\ue096":"social googleplus","\ue097":"social tumblr","\ue098":"social tumbleupon","\ue099":"social wordpress","\ue09a":"social instagram","\ue09b":"social dribbble","\ue09c":"social vimeo","\ue09d":"social linkedin","\ue09e":"social rss","\ue09f":"social deviantart","\ue0a0":"social share","\ue0a1":"social myspace","\ue0a2":"social skype","\ue0a3":"social youtube","\ue0a4":"social picassa","\ue0a5":"social googledrive","\ue0a6":"social flickr","\ue0a7":"social blogger","\ue0a8":"social spotify","\ue0a9":"social delicious","\ue0aa":"social facebook circle","\ue0ab":"social twitter circle","\ue0ac":"social pinterest circle","\ue0ad":"social googleplus circle","\ue0ae":"social tumblr circle","\ue0af":"social stumbleupon circle","\ue0b0":"social wordpress circle","\ue0b1":"social instagram circle","\ue0b2":"social dribbble circle","\ue0b3":"social vimeo circle","\ue0b4":"social linkedin circle","\ue0b5":"social rss circle","\ue0b6":"social deviantart circle","\ue0b7":"social share circle","\ue0b8":"social myspace circle","\ue0b9":"social skype circle","\ue0ba":"social youtube circle","\ue0bb":"social picassa circle","\ue0bc":"social googledrive alt2","\ue0bd":"social flickr circle","\ue0be":"social blogger circle","\ue0bf":"social spotify circle","\ue0c0":"social delicious circle","\ue0c1":"social facebook square","\ue0c2":"social twitter square","\ue0c3":"social pinterest square","\ue0c4":"social googleplus square","\ue0c5":"social tumblr square","\ue0c6":"social stumbleupon square","\ue0c7":"social wordpress square","\ue0c8":"social instagram square","\ue0c9":"social dribbble square","\ue0ca":"social vimeo square","\ue0cb":"social linkedin square","\ue0cc":"social rss square","\ue0cd":"social deviantart square","\ue0ce":"social share square","\ue0cf":"social myspace square","\ue0d0":"social skype square","\ue0d1":"social youtube square","\ue0d2":"social picassa square","\ue0d3":"social googledrive square","\ue0d4":"social flickr square","\ue0d5":"social blogger square","\ue0d6":"social spotify square","\ue0d7":"social delicious square","\ue0d8":"wallet alt","\ue0d9":"shield alt","\ue0da":"percent alt","\ue0db":"pens alt","\ue0dc":"mug alt","\ue0dd":"like alt","\ue0de":"globe alt","\ue0df":"flowchart alt","\ue0e0":"id alt","\ue0e1":"hourglass","\ue0e2":"globe","\ue0e3":"globe 2","\ue0e4":"floppy alt","\ue0e5":"drive alt","\ue0e6":"clipboard","\ue0e7":"calculator alt","\ue0e8":"floppy","\ue0e9":"easel","\ue0ea":"drive","\ue0eb":"dislike","\ue0ec":"datareport","\ue0ed":"currency","\ue0ee":"calulator","\ue0ef":"building","\ue0f0":"easel alt","\ue0f1":"dislike alt","\ue0f2":"datareport alt","\ue0f3":"currency alt","\ue0f4":"briefcase alt","\ue0f5":"target","\ue0f6":"shield","\ue0f7":"search alt","\ue0f8":"rook","\ue0f9":"puzzle alt","\ue0fa":"printer alt","\ue0fb":"percent","\ue0fc":"id 2 alt","\ue0fd":"building alt","\ue0fe":"briefcase","\ue0ff":"balance","\ue100":"wallet","\ue101":"search","\ue102":"puzzle","\ue103":"printer","\ue104":"pens","\ue105":"mug","\ue106":"like","\ue107":"id","\ue108":"id 2","\ue109":"flowchart","\ue600":"toggle","\ue601":"tabs","\ue602":"subscribe","\ue603":"slider","\ue604":"sidebar","\ue605":"share","\ue606":"pricing table","\ue607":"portfolio","\ue608":"number counter","\ue609":"header","\ue60a":"filtered portfolio","\ue60b":"divider","\ue60c":"cta","\ue60d":"countdown","\ue60e":"circle counter","\ue60f":"blurb","\ue610":"bar counters","\ue611":"audio","\ue612":"accordion","\ue613":"text","\ue614":"testimonial","\ue615":"shop","\ue616":"person","\ue617":"menu","\ue618":"map","\ue619":"login","\ue61a":"image","\ue61b":"gallery","\ue61c":"follow","\ue61d":"contact","\ue61e":"blog","\ue61f":"reset","\ue620":"code","\ue621":"underline","\ue622":"bold","\ue623":"italic","\ue624":"uppercase","\ue625":"divi","\ue626":"D","\ue900":"import export","\ue901":"double underline","\ue902":"smallcaps","\ue903":"strikethrough","\ue904":"menu expand","\ue905":"strikethrough","\ue906":"external link"};