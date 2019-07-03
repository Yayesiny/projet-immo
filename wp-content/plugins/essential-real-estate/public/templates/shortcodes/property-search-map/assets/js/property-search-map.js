var ERE_Property_Map_Search = ERE_Property_Map_Search || {};
(function ($) {
    'use strict';
    var ajax_url = ere_search_map_vars.ajax_url;
    var price_is_slider = ere_search_map_vars.price_is_slider;
    var item_amount = ere_search_map_vars.item_amount;
    var marker_image_size = ere_search_map_vars.marker_image_size;
    var css_class_wrap = '.ere-search-map-properties';
    var handle = true;
    var ere_map;
    var markers = [];
    var is_mobile = false;
    var drgflag = true;
    var infobox;
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        drgflag = false;
        is_mobile = true;
    }
    ERE_Property_Map_Search = {
        init: function () {
            var enable_filter_location=ere_search_map_vars.enable_filter_location;
            if(enable_filter_location=='1')
            {
                $('.ere-property-country-ajax', css_class_wrap).select2();
                $('.ere-property-state-ajax', css_class_wrap).select2();
                $('.ere-property-city-ajax', css_class_wrap).select2();
                $('.ere-property-neighborhood-ajax', css_class_wrap).select2();
            }

            this.full_screen();
            this.get_states_by_country();
            $(".ere-property-country-ajax", css_class_wrap).on('change', function () {
                ERE_Property_Map_Search.get_states_by_country();
            });
            this.get_cities_by_state();
            $(".ere-property-state-ajax", css_class_wrap).on('change', function () {
                ERE_Property_Map_Search.get_cities_by_state();
            });
            this.get_neighborhoods_by_city();
            $(".ere-property-city-ajax", css_class_wrap).on('change', function () {
                ERE_Property_Map_Search.get_neighborhoods_by_city();
            });
            $('.btn-status-filter', css_class_wrap).on('click', function (e) {
                e.preventDefault();
                var status = $(this).data("value");
                $(this).parent().find('input').val(status);
                $(this).parent().find('button').removeClass('active');
                $(this).addClass('active');
                ERE_Property_Map_Search.change_price_on_status_change(status);
            });
            $('select[name="status"]', css_class_wrap).on('change', function (e) {
                e.preventDefault();
                var status = $(this).val();
                ERE_Property_Map_Search.change_price_on_status_change(status);
            });
            this.execute_url_search();
            $(".ere-sliderbar-filter.ere-sliderbar-price", css_class_wrap).on('register.again', function () {
                $(".ere-sliderbar-filter.ere-sliderbar-price", css_class_wrap).each(function () {
                    var slider_filter = $(this);
                    ERE_Property_Map_Search.set_slider_filter(slider_filter);
                });
            });
            this.register_slider_filter();
            this.set_slider_value();
            this.property_map_paging();
            this.search_map('map_only');
            $('.other-features-wrap .btn-other-features', css_class_wrap).on('click', function (event) {
                event.preventDefault();
                $('.other-features-list', css_class_wrap).slideToggle();
                $(this).toggleClass('show');
                if ($(this).hasClass('show') == true) {
                    $('input[name="features-search"]', css_class_wrap).attr('value', '1');
                    $(this).find('i').removeClass('fa-chevron-down');
                    $(this).find('i').addClass('fa-chevron-up');
                }
                else {
                    $('input[name="features-search"]', css_class_wrap).attr('value', '0');
                    $(this).find('i').removeClass('fa-chevron-up');
                    $(this).find('i').addClass('fa-chevron-down');
                }
                ERE_Property_Map_Search.search_map('map_and_content');
            });
            $(".ere-sliderbar-filter.ere-sliderbar-price", css_class_wrap).on('register.again', function () {
                $(".ere-sliderbar-filter.ere-sliderbar-price", css_class_wrap).each(function () {
                    var slider_filter = $(this);
                    ERE_Property_Map_Search.set_slider_filter(slider_filter);
                });
            });

            $('.ere-search-status-tab .btn-status-filter', css_class_wrap).on('click', function () {
                $(this).parent().find('input').val($(this).data("value"));
                $(this).parent().find('button').removeClass('active');
                $(this).addClass('active');
            });

            $('select[name="type"], select[name="bedrooms"],select[name="bathrooms"] , ' +
                'select[name="garage"], select[name="label"],input[name="address"],input[name="title"],input[name="property_identity"], ' +
                'select[name="min-price"], select[name="max-price"],select[name="min-area"], select[name="max-area"],select[name="min-land-area"], select[name="min-land-area"], ' +
                'select[name="city"], select[name="country"], select[name="state"], select[name="neighborhood"]', css_class_wrap).on('change', function () {
                ERE_Property_Map_Search.search_map('map_and_content');
            });

            $('input[name="other_features"]', css_class_wrap).on('change', function () {
                ERE_Property_Map_Search.search_map('map_and_content');
            });
        },
        get_states_by_country: function () {
            var $this = $(".ere-property-country-ajax", css_class_wrap);
            if ($this.length) {
                var selected_country = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'ere_get_states_by_country_ajax',
                        'country': selected_country,
                        'type': 1,
                        'is_slug':'1'
                    },
                    success: function (response) {
                        $(".ere-property-state-ajax", css_class_wrap).html(response);
                        var val_selected = $(".ere-property-state-ajax", css_class_wrap).attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $(".ere-property-state-ajax", css_class_wrap).val(val_selected);
                        }
                    }
                });
            }
        },
        get_cities_by_state: function () {
            var $this = $(".ere-property-state-ajax", css_class_wrap);
            if ($this.length) {
                var selected_state = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'ere_get_cities_by_state_ajax',
                        'state': selected_state,
                        'type': 1,
                        'is_slug':'1'
                    },
                    success: function (response) {
                        $(".ere-property-city-ajax", css_class_wrap).html(response);
                        var val_selected = $(".ere-property-city-ajax", css_class_wrap).attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $(".ere-property-city-ajax", css_class_wrap).val(val_selected);
                        }
                    }
                });
            }
        },
        get_neighborhoods_by_city: function () {
            var $this = $(".ere-property-city-ajax", css_class_wrap);
            if ($this.length) {
                var selected_city = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'ere_get_neighborhoods_by_city_ajax',
                        'city': selected_city,
                        'type': 1,
                        'is_slug':'1'
                    },
                    success: function (response) {
                        $(".ere-property-neighborhood-ajax", css_class_wrap).html(response);
                        var val_selected = $(".ere-property-neighborhood-ajax", css_class_wrap).attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $(".ere-property-neighborhood-ajax", css_class_wrap).val(val_selected);
                        }
                    }
                });
            }
        },
        execute_url_search: function () {
            $('.ere-advanced-search-btn', css_class_wrap).on('click', function (e) {
                e.preventDefault();
                var search_form = $(this).closest('.search-properties-form'),
                    search_url = search_form.data('href'),
                    search_field = [],
                    query_string = '?';
                if (search_url.indexOf('?') !== -1) {
                    query_string = '&';
                }
                $('.search-field', search_form).each(function () {
                    var $this = $(this),
                        field_name = $this.attr('name'),
                        current_value = $this.val(),
                        default_value = $this.data('default-value');
                    if (current_value != default_value) {
                        search_field[field_name] = current_value;
                    }
                });
                $('.ere-sliderbar-filter', search_form).each(function () {
                    var $this = $(this),
                        field_name_min = $this.find('.min-input-request').attr('name'),
                        field_name_max = $this.find('.max-input-request').attr('name'),
                        current_value_min = $this.find('.min-input-request').val(),
                        current_value_max = $this.find('.max-input-request').val(),
                        default_value_min = $this.data('min-default'),
                        default_value_max = $this.data('max-default');
                    if (current_value_min != default_value_min || current_value_max != default_value_max) {
                        search_field[field_name_min] = current_value_min;
                        search_field[field_name_max] = current_value_max;
                    }
                });
                if (typeof(search_field['features-search']) != 'undefined') {
                    var other_features = '';
                    $('[name="other_features"]', search_form).each(function () {
                        var $this = $(this),
                            value = $this.attr('value');
                        if ($this.is(':checked')) {
                            other_features += value + ";";
                        }
                    });
                    if (other_features !== '') {
                        other_features = other_features.substring('0', other_features.length - 1);
                        search_field['other_features'] = other_features;
                    }
                }
                if (search_field !== []) {
                    for (var k in search_field) {
                        if (search_field.hasOwnProperty(k)) {
                            query_string += k + "=" + search_field[k] + "&";
                        }
                    }
                }
                query_string = query_string.substring('0', query_string.length - 1);
                window.location.href = search_url + query_string;
            });
        },
        set_slider_filter: function (elm) {
            var $container = elm,
                min = parseInt($container.attr('data-min-default')),
                max = parseInt($container.attr('data-max-default')),
                min_value = $container.attr('data-min'),
                max_value = $container.attr('data-max'),
                $sidebar_filter = $container.find('.sidebar-filter'),
                x, y;
            $sidebar_filter.slider({
                min: min,
                max: max,
                range: true,
                values: [min_value, max_value],
                slide: function (event, ui) {
                    x = ui.values[0];
                    y = ui.values[1];
                    $container.attr('data-min', x);
                    $container.attr('data-max', y);
                    $container.find('input.min-input-request').attr('value', x);
                    $container.find('input.max-input-request').attr('value', y);
                    if ($container.find('span').hasClass("not-format")) {
                        $container.find('span.min-value').html(x);
                        $container.find('span.max-value').html(y);
                    }
                    else {
                        $container.find('span.min-value:not(.not-format)').html(ERE.number_format(x));
                        $container.find('span.max-value:not(.not-format)').html(ERE.number_format(y));
                    }

                },
                stop: function (event, ui) {
                    ERE_Property_Map_Search.search_map('map_and_content');
                }
            });
        },
        register_slider_filter: function () {
            $(".ere-sliderbar-filter", css_class_wrap).each(function () {
                var slider_filter = $(this);
                ERE_Property_Map_Search.set_slider_filter(slider_filter);
            });
        },
        set_slider_value: function () {
            $('.ere-sliderbar-filter', css_class_wrap).each(function () {
                var $this = $(this),
                    min_default = $this.attr('data-min-default'),
                    max_default = $this.attr('data-max-default'),
                    min_value = $this.attr('data-min'),
                    max_value = $this.attr('data-max'),
                    left = (min_value - min_default) / (max_default - min_default) * 100 + '%',
                    width = (max_value - min_value) / (max_default - min_default) * 100 + '%',
                    left_max = (max_value - min_default) / (max_default - min_default) * 100 + '%';
                $this.find('.ui-slider-range.ui-corner-all.ui-widget-header').css({
                    'left': left,
                    'width': width
                });
                $this.find('.ui-slider-handle.ui-corner-all.ui-state-default').css('left', left);
                $this.find('.ui-slider-handle.ui-corner-all.ui-state-default:last-child').css('left', left_max);
            })
        },
        change_price_on_status_change: function (status) {
            $.ajax({
                type: 'POST',
                url: ajax_url,
                dataType: 'json',
                data: {
                    'action': 'ere_ajax_change_price_on_status_change',
                    'status': status,
                    'price_is_slider': price_is_slider
                },
                success: function (response) {
                    if (response.slide_html) {
                        $('.ere-sliderbar-price-wrap', css_class_wrap).html(response.slide_html);
                        ERE_Property_Map_Search.register_slider_filter();
                        ERE_Property_Map_Search.set_slider_value();
                    }
                    else {
                        if (response.min_price_html) {
                            $('select[name="min-price"]', css_class_wrap).html(response.min_price_html);
                        }
                        if (response.max_price_html) {
                            $('select[name="max-price"]', css_class_wrap).html(response.max_price_html);
                        }
                    }
                    ERE_Property_Map_Search.search_map('map_and_content');
                }
            });
        },
        full_screen: function () {
            if ($('.ere-search-map-properties').length > 0) {
                var $window_height = $(window).outerHeight(),
                    admin_height = $('#wpadminbar').outerHeight();

                if (admin_height == null) {
                    admin_height = 0;
                }
                var header_height = $('header').outerHeight(),
                    footer_height = $('footer').outerHeight(),
                    admin_bar_height = $('.wpadminbar').outerHeight(),
                    map_height = $window_height - admin_height - header_height - footer_height - admin_bar_height;
                $('.ere-search-map-properties .ere-map-search').css('height', map_height);
                $('.ere-search-map-properties .ere-map-search .ere-map-result').css('height', map_height);
                $('.col-scroll-vertical').css({
                    'height': map_height,
                    'overflow-y': 'scroll',
                    'overflow-x': 'hidden'
                });

                var $container = $('.property-vertical-map-listing', '.list-property-result-ajax'),
                    $newElems = $('.property-item', $container);
                $container.css('opacity', 1);
                $container.imagesLoaded(function () {
                    ERE.set_item_effect($newElems, 'hide');
                    $newElems = $('.property-item', $container);
                    ERE.set_item_effect($newElems, 'show');
                });
            }
        },
        property_map_paging: function () {
            handle = true;
            $('.paging-navigation', '.property-search-map-paging-wrap').each(function () {
                $('a', $(this)).off('click').on('click', function (event) {
                    event.preventDefault();
                    var $this = $(this);
                    ERE_Property_Map_Search.search_map('map_and_content', $this);
                });
            });
        },
        property_map_paging_control: function () {
            $('.paging-navigation', '.property-search-map-paging-wrap').each(function () {
                var $this = $(this);
                if ($this.find('a.next').length === 0) {
                    $this.addClass('next-disable');
                } else {
                    $this.removeClass('next-disable');
                }
            });
        },
        search_map: function (search_type, element) {
            var country, city, state, neighborhood, title, area, status, type, bedrooms, bathrooms, min_price, max_price,
                min_area, max_area, address, garage, features, label, min_land_area, max_land_area, property_identity, features_enable;
            var search_form = $(css_class_wrap);
            var map_result = search_form.find('.ere-map-result').attr('id');
            title = search_form.find('input[name="title"]').val();
            address = search_form.find('input[name="address"]').val();
            city = search_form.find('select[name="city"]').val();
            type = search_form.find('select[name="type"]').val();
            status = search_form.find('select[name="status"]').val();
            if (status == undefined) {
                status = search_form.find('input[name="status"]').val();
            }
            bedrooms = search_form.find('select[name="bedrooms"]').val();
            bathrooms = search_form.find('select[name="bathrooms"]').val();
            if ($('.ere-sliderbar-price', search_form).length) {
                min_price = search_form.find('.ere-sliderbar-filter.ere-sliderbar-price').attr('data-min');
                max_price = search_form.find('.ere-sliderbar-filter.ere-sliderbar-price').attr('data-max');
            }
            else {
                min_price = search_form.find('select[name="min-price"]').val();
                max_price = search_form.find('select[name="max-price"]').val();
            }


            if ($('.ere-sliderbar-area', search_form).length) {
                min_area = search_form.find('.ere-sliderbar-filter.ere-sliderbar-area').attr('data-min');
                max_area = search_form.find('.ere-sliderbar-filter.ere-sliderbar-area').attr('data-max');
            }
            else {
                min_area = search_form.find('select[name="min-area"]').val();
                max_area = search_form.find('select[name="max-area"]').val();
            }

            if ($('.ere-sliderbar-land-area', search_form).length) {
                min_land_area = search_form.find('.ere-sliderbar-filter.ere-sliderbar-land-area').attr('data-min');
                max_land_area = search_form.find('.ere-sliderbar-filter.ere-sliderbar-land-area').attr('data-max');
            }
            else {
                min_land_area = search_form.find('select[name="min-land-area"]').val();
                max_land_area = search_form.find('select[name="max-land-area"]').val();
            }

            state = search_form.find('select[name="state"]').val();
            country = search_form.find('select[name="country"]').val();
            neighborhood = search_form.find('select[name="neighborhood"]').val();
            label = search_form.find('select[name="label"]').val();
            garage = search_form.find('select[name="garage"]').val();
            property_identity = search_form.find('input[name="property_identity"]').val();
            features_enable = search_form.find('input[name="features-search"]').val();
            if (features_enable == '1') {
                features = '';
                search_form.find('.other-features-list input[type=checkbox]:checked').each(function () {
                    features += $(this).val() + ';';
                });
                if (features != '') {
                    features = features.substring(0, features.length - 1);
                }
            }
            var ere_security_search_map = $('#ere_security_search_map').val(),
                map_result_content = $('#' + map_result);
            var marker_cluster = null,
                googlemap_default_zoom = ere_search_map_vars.googlemap_default_zoom,
                not_found = ere_search_map_vars.not_found,
                clusterIcon = ere_search_map_vars.clusterIcon,
                google_map_style = ere_search_map_vars.google_map_style,
                pin_cluster_enable = ere_search_map_vars.pin_cluster_enable;

            var ere_search_map_option = {
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scrollwheel: false,
                scroll: {x: $(window).scrollLeft(), y: $(window).scrollTop()},
                zoom: parseInt(googlemap_default_zoom),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                draggable: drgflag,
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                }
            };

            infobox = new InfoBox({
                disableAutoPan: true, //false
                maxWidth: 310,
                alignBottom: true,
                pixelOffset: new google.maps.Size(-140, -55),
                zIndex: null,
                closeBoxMargin: "0 0 -16px -16px",
                infoBoxClearance: new google.maps.Size(1, 1),
                isHidden: false,
                pane: "floatPane",
                enableEventPropagation: false
            });
            var ere_add_markers = function (props, map) {
                $.each(props, function (i, prop) {
                    var latlng = new google.maps.LatLng(prop.lat, prop.lng),
                        marker_url = prop.marker_icon,
                        marker_size = new google.maps.Size(44, 60);

                    var marker_icon = {
                        url: marker_url,
                        size: marker_size,
                        scaledSize: new google.maps.Size(44, 60),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(7, 27)
                    };

                    var marker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                        icon: marker_icon,
                        draggable: false,
                        animation: google.maps.Animation.DROP
                    });

                    var prop_title = prop.data ? prop.data.post_title : prop.title,
                        display_css = '';
                    if (prop.image_url == '' || typeof(prop.image_url) == 'undefined') {
                        display_css = 'style="display: none;"';
                    }

                    var contentString = document.createElement("div");
                    contentString.className = 'marker-content clearfix';
                    contentString.innerHTML = '<div class="marker-content-inner clearfix">' +
                        '<div class = "item-thumb" ' + display_css + '>' +
                        '<a href="' + prop.url + '">' +
                        '<img src="' + prop.image_url + '" alt="' + prop_title + '">' +
                        '</a>' +
                        '</div>' +
                        '<div class="item-body">' +
                        '<a href="' + prop.url + '" class="title-marker">' + prop_title + '</a>' +
                        '<div class="price-marker">' + prop.price + '</div>' +
                        '<div class="address-marker"><i class="fa fa-map-marker"></i>' + prop.address + '</div>' +
                        '</div>' +
                        '</div>';
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            var scale = Math.pow(2, map.getZoom()),
                                offsety = ( (100 / scale) || 0 ),
                                projection = map.getProjection(),
                                markerPosition = marker.getPosition(),
                                markerScreenPosition = projection.fromLatLngToPoint(markerPosition),
                                pointHalfScreenAbove = new google.maps.Point(markerScreenPosition.x, markerScreenPosition.y - offsety),
                                aboveMarkerLatLng = projection.fromPointToLatLng(pointHalfScreenAbove);
                            map.setCenter(aboveMarkerLatLng);
                            setTimeout(function () {
                                infobox.setContent(contentString);
                                infobox.open(map, marker);
                            }, 300)
                        }
                    })(marker, i));
                    markers.push(marker);
                });
            };

            var paged = 1;
            if (element != undefined) {
                var href = element.attr('href');
                paged = ERE.get_page_number_from_href(href);
            }
            $.ajax({
                dataType: 'json',
                url: ajax_url,
                data: {
                    'action': 'ere_property_search_map_ajax',
                    'title': title,
                    'address': address,
                    'country': country,
                    'state': state,
                    'city': city,
                    'neighborhood': neighborhood,
                    'type': type,
                    'status': status,
                    'bedrooms': bedrooms,
                    'bathrooms': bathrooms,
                    'min_price': min_price,
                    'max_price': max_price,
                    'min_area': min_area,
                    'max_area': max_area,
                    'label': label,
                    'garage': garage,
                    'min_land_area': min_land_area,
                    'max_land_area': max_land_area,
                    'property_identity': property_identity,
                    'features': features,
                    'search_type': search_type,
                    'paged': paged,
                    'item_amount': item_amount,
                    'marker_image_size':marker_image_size,
                    'ere_security_search_map': ere_security_search_map
                },
                beforeSend: function () {
                    map_result_content.parents('div.ere-search-map-properties').find('#ere-map-loading').fadeIn();
                },
                success: function (data) {
                    if (search_type == 'map_and_content') {
                        var $property_content = $('.property-vertical-map-listing'),
                            $wrap = $('.list-property-result-ajax');
                        if (data.success === false) {
                            $wrap.find('.title-result h2 .number-result').hide();
                            $wrap.find('.title-result h2 .text-no-result').show();
                            $wrap.find('.title-result h2 .text-result').hide();
                            $wrap.find('.property-vertical-map-listing').hide();
                            $wrap.find('.property-search-map-paging-wrap').hide();

                        } else {
                            var $newElems = $('.property-item', data.property_html),
                                $paging = $('.property-search-map-paging-wrap', data.property_html);
                            $property_content.css('opacity', 0);
                            $property_content.html($newElems);
                            ERE.set_item_effect($newElems, 'hide');
                            var contentTop = $property_content.offset().top - 30;
                            $('html,body').animate({scrollTop: +contentTop + 'px'}, 500);
                            $property_content.css('opacity', 1);
                            $property_content.imagesLoaded(function () {
                                $newElems = $('.property-item', $property_content);
                                ERE.set_item_effect($newElems, 'show');
                                $property_content.closest('div.list-property-result-ajax').find('.property-search-map-paging-wrap').html($paging.html());
                                ERE_Property_Map_Search.property_map_paging();
                                ERE_Property_Map_Search.property_map_paging_control();
                                ERE.favorite();
                                ERE.tooltip();
                                ERE_Compare.register_event_compare();
                            });
                            if ($newElems.length != '0') {
                                $wrap.find('.title-result h2 .number-result').html(data.total_post);
                                $wrap.find('.title-result h2 .number-result').show();
                                $wrap.find('.title-result h2 .text-no-result').hide();
                                $wrap.find('.title-result h2 .text-result').show();
                                $wrap.find('.property-vertical-map-listing').show();
                                $wrap.find('.property-search-map-paging-wrap').show();
                            }
                        }
                    }
                    handle = true;
                    ere_map = new google.maps.Map(document.getElementById(map_result), ere_search_map_option);
                    ere_map.set('scrollwheel', false);
                    google.maps.event.trigger(ere_map, 'resize');
                    if (data.success === true) {
                        if (data.properties) {
                            var count_properties = data.properties.length;
                        }
                    }
                    if (count_properties == 1) {
                        var boundsListener = google.maps.event.addListener((ere_map), 'bounds_changed', function (event) {
                            this.setZoom(parseInt(googlemap_default_zoom));
                            google.maps.event.removeListener(boundsListener);
                        });
                    }
                    if (google_map_style !== '') {
                        var styles = JSON.parse(google_map_style);
                        ere_map.setOptions({styles: styles});
                    }
                    var mapPosition = new google.maps.LatLng('', '');
                    ere_map.setCenter(mapPosition);
                    ere_map.setZoom(parseInt(googlemap_default_zoom));
                    google.maps.event.addListener(ere_map, 'tilesloaded', function () {
                        $('#ere-map-loading').fadeOut();
                    });
                    if (data.success === true) {
                        for (var i = 0; i < markers.length; i++) {
                            markers[i].setMap(null);
                        }
                        markers = [];
                        ere_add_markers(data.properties, ere_map);
                        ere_map.fitBounds(markers.reduce(function (bounds, marker) {
                            return bounds.extend(marker.getPosition());
                        }, new google.maps.LatLngBounds()));

                        google.maps.event.trigger(ere_map, 'resize');
                        if (pin_cluster_enable == '1') {
                            marker_cluster = new MarkerClusterer(ere_map, markers, {
                                gridSize: 60,
                                styles: [
                                    {
                                        url: clusterIcon,
                                        width: 48,
                                        height: 48,
                                        textColor: "#fff"
                                    }
                                ]
                            });
                        }
                        if(!is_mobile)
                        {
                            ere_infobox_trigger();
                        }
                    } else {
                        map_result_content.empty().html('<div class="map-notfound">' + not_found + '</div>');
                    }
                    map_result_content.closest('div.ere-search-map-properties').find('#ere-map-loading').fadeOut('slow');
                },
                error: function () {
                    map_result_content.closest('div.ere-search-map-properties').find('#ere-map-loading').fadeOut('slow');
                    handle = true;
                }
            });
        }
    };
    var ere_infobox_trigger = function() {
        $('.property-item',css_class_wrap).each(function(i) {
            $(this).on('mouseenter', function() {
                if(ere_map) {
                    google.maps.event.trigger(markers[i], 'click');
                }
            });
            $(this).on('mouseleave', function() {
                infobox.open(null,null);
            });
        });
        return false;
    };
    $(document).ready(function () {
        ERE_Property_Map_Search.init();
    });
    $(window).resize(function () {
        ERE_Property_Map_Search.full_screen();
    });
    $(window).on('orientationchange', function () {
        ERE_Property_Map_Search.full_screen();
    });
    $("body").keydown(function (e) {
        if (e.which == 35 || e.which == 34) {
            $('.col-scroll-vertical').animate({
                scrollTop: $('.col-scroll-vertical-inner').height()
            }, 'slow');
            /* [end] key hit */
            return false;
        }
        else if (e.which == 36 || e.which == 33) {
            $('.col-scroll-vertical').animate({
                scrollTop: 0
            }, 'slow');
            /* [home] key hit */
            return false;
        }
    });
})(jQuery);