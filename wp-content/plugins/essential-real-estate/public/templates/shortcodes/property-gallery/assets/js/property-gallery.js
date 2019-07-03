(function ($) {
	"use strict";
	var checkFilter = true;
	var ERE_property_gallery = {
		vars: {
			filter_id: '',
			property_items: []
		},
		init: function () {
			this.filterCarousel();
			this.filterRow();
			this.resize();
			ERE.select_term();
		},
		filterCarousel: function () {
			var property_owl_filter = $('[data-filter-type="carousel"]');
			property_owl_filter.each(function () {
				var objectClick = $('a', $(this));
				ERE_property_gallery.executeFilter('filterCarousel', objectClick);
			});
		},
		filterRow: function () {
			var property_filter = $('[data-filter-type="filter"]'),
				itemSelector = property_filter.data('item'),
				isRTL = $('body').hasClass('rtl');
			if(typeof itemSelector == 'undefined') itemSelector = '.property-item';
			$('[data-layout="fitRows"]').each(function () {
				var $this = $(this);
				$this.imagesLoaded(function () {
					$this.isotope({
						itemSelector: itemSelector,
						layoutMode: 'fitRows',
						isOriginLeft: !isRTL,
						transitionDuration: '0.8s'
					}).isotope('layout');
				});
			});
			$(document).on('vc-tab-clicked', function (event, $current_tab) {
				$('[data-layout="fitRows"]', $current_tab).each(function () {
					$(this).isotope('layout');
				});
			});
			$(property_filter).each(function () {
				if($(this).data('filter-style') == 'filter-isotope') {
					$('a', $(this)).on('click', function (e) {
						e.preventDefault();
						var filterId = $(this).parent().attr('data-filter_id'),
							$property_content = $('.property-content[data-filter_id="'+filterId+'"]'),
							check = true;
						if ($(this).hasClass('active-filter')) {
							check = false;
						}
						if (checkFilter && check) {
							var filterValue = $(this).attr('data-filter');
							$property_content.isotope({filter: filterValue});
							$(this).parent().children('a').css('cursor', 'pointer');
							$(this).parent().children('a').removeClass('active-filter');
							$(this).addClass('active-filter');
							$(this).css('cursor', 'not-allowed');
							var select_filter = $(this).parent().next().children('select');
							select_filter.removeAttr('disabled');
							select_filter.children('option').removeAttr('selected');
							select_filter.children('option[value="' + filterValue + '"]').attr('selected', 'selected');
							if(select_filter.val() != filterValue) {
								select_filter.selectize()[0].selectize.setValue(filterValue);
							}
						}
					});
				} else {
					var objectClick = $('a', $(this));
					ERE_property_gallery.executeFilter('filterRow', objectClick);
				}
			});
		},
		executeFilter: function ($filter_style, objectClick) {
			objectClick.on('click', function (event) {
				event.preventDefault();
				var thisObject = $(this),
					filterId = thisObject.parent().attr('data-filter_id'),
					$property_content = $('.property-content[data-filter_id="'+filterId+'"]'),
					ere_property = $property_content.parent();
				if (thisObject.hasClass('active-filter')) {
					thisObject.css('cursor', 'not-allowed');
					return false;
				} else {
					thisObject.parent().children('a').css('cursor', 'wait');
					if (checkFilter) {
						checkFilter = false;
						var dataFilter = thisObject.data('filter'),
							select_filter = thisObject.parent().next().children('select');
						ERE_property_gallery.vars.filter_id = thisObject.parent().data('filter_id');
						thisObject.parent().find('.active-filter').removeClass('active-filter');
						thisObject.addClass('active-filter');
						ere_property.css('height',ere_property.outerHeight());
						if (typeof ERE_property_gallery.vars.property_items[dataFilter + '-' + ERE_property_gallery.vars.filter_id] == 'undefined') {
							thisObject.css('width', thisObject.outerWidth());
							var property_type = thisObject.data('filter');
							if('*' === property_type) {
                                property_type = '';
							}
							var $ajax_url = objectClick.closest('.filter-inner').data('admin-url');
							$.ajax({
								url: $ajax_url,
								data: {
									action: 'ere_property_gallery_fillter_ajax',
									is_carousel: thisObject.parent().data('is-carousel'),
									columns_gap: thisObject.parent().data('columns-gap'),
									columns: thisObject.parent().data('columns'),
									property_type: property_type,
									item_amount: thisObject.parent().data('item-amount'),
									image_size: thisObject.parent().data('image-size'),
									color_scheme: thisObject.parent().data('color_scheme')
								},
								success: function (html) {
									var $newElems = $('.property-item', html);
									ERE_property_gallery.vars.property_items[dataFilter + '-' + ERE_property_gallery.vars.filter_id] = html;

									$property_content.css('opacity', 0);
									if($filter_style == 'filterRow') {
										$property_content.isotope('destroy');
									} else {
										$property_content.trigger('destroy.owl.carousel');
									}
									$property_content.html($newElems);
									if($filter_style == 'filterRow') {
										ERE.set_item_effect($newElems, 'hide');
									}
									$property_content.css('opacity', 1);
									$property_content.imagesLoaded(function () {
										if($filter_style == 'filterCarousel') {
											ERE.set_item_effect($newElems, 'hide');
											ERE_Carousel.owlCarousel();
										} else {
											ERE_property_gallery.filterRow();
										}
										$newElems = $('.property-item', $property_content);
										ERE.set_item_effect($newElems, 'show');
										setTimeout(function(){
											ere_property.css('height','auto');
										}, 200);
									});
									setTimeout(function () {
										thisObject.css('width', 'auto');
									},100);
									checkFilter = true;
									select_filter.removeAttr('disabled');
									select_filter.children('option').removeAttr('selected');
									select_filter.children('option[value="' + dataFilter + '"]').attr('selected', 'selected');
									
									thisObject.parent().children('a').css('cursor', 'pointer');
									thisObject.parent().children('.active-filter').css('cursor', 'not-allowed');
								},
								error: function () {
									checkFilter = true;
								}
							});
						} else {
							var old_data = ERE_property_gallery.vars.property_items[dataFilter + '-' + ERE_property_gallery.vars.filter_id];
							var $newElems = $('.property-item', old_data);
							if($filter_style == 'filterRow') {
								$property_content.isotope('destroy');
							}
							$property_content.css('opacity', 0);
							if($filter_style == 'filterCarousel') {
								$property_content.trigger('destroy.owl.carousel');
							}
							$property_content.html($newElems);
							ERE.set_item_effect($newElems, 'hide');
							$property_content.css('opacity', 1);
							if($filter_style == 'filterCarousel') {
								ERE_Carousel.owlCarousel();
							}
							$property_content.imagesLoaded(function () {
								$newElems = $('.property-item', $property_content);
								ERE.set_item_effect($newElems, 'show');
								setTimeout(function(){
									ere_property.css('height','auto');
								}, 200);
								if($filter_style == 'filterRow') {
									ERE_property_gallery.filterRow();
								}
							});
							checkFilter = true;
							select_filter.removeAttr('disabled');
							select_filter.children('option').removeAttr('selected');
							select_filter.children('option[value="' + dataFilter + '"]').attr('selected', 'selected');
							thisObject.parent().children('a').css('cursor', 'pointer');
							thisObject.parent().children('.active-filter').css('cursor', 'not-allowed');
						}
					}
				}
			});
		},
		resize: function () {
			$(window).resize(function () {
				ERE_property_gallery.executeResize();
			});
			$(window).on('orientationchange', function () {
				ERE_property_gallery.executeResize();
			});
		},
		executeResize: function () {
			$('.property-content.owl-carousel').each(function () {
				var container = $(this);
				setTimeout(function () {
					var $items = $('.property-item', container);
					ERE.set_item_effect($items, 'show');
				}, 500);
			});
		}
	};
	$(document).ready(function () {
		ERE_property_gallery.init();
	});
})(jQuery);