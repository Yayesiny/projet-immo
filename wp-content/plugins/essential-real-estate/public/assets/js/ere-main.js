var ERE = ERE || {};
(function ($) {
    'use strict';
    var ajax_url = ere_main_vars.ajax_url,
        confirm_yes_text = ere_main_vars.confirm_yes_text,
        confirm_no_text = ere_main_vars.confirm_no_text,
        loading_text = ere_main_vars.loading_text,
        sending_text = ere_main_vars.sending_text,
        decimals= ere_main_vars.decimals,
        dec_point= ere_main_vars.dec_point,
        thousands_sep= ere_main_vars.thousands_sep;
    ERE = {
        init: function () {
            this.register_pattern_validator();
            this.show_wire_transfer_info();
            this.view_gallery();
            this.favorite();
            this.tooltip();
            // Property Shortcode
            this.property_paging();
            this.move_link_to_carousel();

            // Property Carousel
            this.execute_nav();
            // Property Slider
            this.execute_slider_nav();
            var $property_sync_wrap = $('.pagination-image.ere-property-slider');
            this.sync_property_carousel($property_sync_wrap);
            this.light_gallery();
        },
        show_wire_transfer_info: function(){
            $('input[type=radio][name=ere_payment_method]').on('change', function() {
                if ($(this).val()=='wire_transfer')
                {
                    $('.ere-wire-transfer-info').show();
                }
                else{
                    $('.ere-wire-transfer-info').hide();
                }
            });
        },
        register_pattern_validator: function(){
            $.validator.addMethod("pattern", function (value, element, param) {
                if (this.optional(element)) {
                    return true;
                }
                if (typeof param === "string") {
                    param = new RegExp("^(?:" + param + ")$");
                }
                return param.test(value);
            }, "Invalid format.");
        },
        number_format: function(number,decimal) {
            decimal = (typeof decimal !== 'undefined') ?  decimal : decimals;

            // Strip all characters but numerical ones.
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimal) ? 0 : Math.abs(decimal),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        },
        tooltip: function () {
            $('[data-toggle="tooltip"]').tooltip();
        },
        login_modal: function () {
            $("#ere_signin_modal").modal('show');
        },
        get_page_number_from_href:function($href) {
            var $href_default = '',
                pattern = /paged=\d+/ig;
            if (new RegExp(pattern).test($href)) {
                $href_default = new RegExp(pattern).exec($href);
            }else{
                pattern = /page\/\d+/ig;
                $href_default = new RegExp(pattern).test($href) ? new RegExp(pattern).exec($href) : $href_default;
            }
            pattern = /\d+/g;
            return new RegExp(pattern).test($href_default) ? new RegExp(pattern).exec($href_default)[0] : 1;
        },
        view_gallery: function () {
            $(".property-view-gallery").on('click', function (e) {
                e.preventDefault();
                var $this = $(this),
                    property_id = $this.attr('data-property-id');
                var $parent = $this.parent();
                if (!$this.parent().hasClass('on-handle')) {
                    $this.parent().addClass('on-handle');
                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'ere_view_gallery_ajax',
                            'property_id': property_id
                        },
                        beforeSend: function () {
                            $this.children('i').addClass('fa-spinner fa-spin');
                        },
                        success: function (data) {
                            $parent.html(data);
                            $parent.lightGallery({thumbnail:false});
                            $(".btn-view-gallery-" + property_id + "").trigger("click");
                        },
                        error: function () {
                            $this.children('i').removeClass('fa-spinner fa-spin');
                            $this.parent().removeClass('on-handle');
                        }
                    });
                }
            });
        },
        favorite: function () {
            $(".property-favorite").on('click', function (e) {
                e.preventDefault();
                if (!$(this).hasClass('on-handle')) {
                    var $this = $(this).addClass('on-handle'),
                        property_inner = $this.closest('.property-inner').addClass('property-active-hover'),
                        property_id = $this.attr('data-property-id'),
                        title_not_favorite = $this.attr('data-title-not-favorite'),
                        icon_not_favorite = $this.attr('data-icon-not-favorite'),
                        title_favorited = $this.attr('data-title-favorited'),
                        icon_favorited = $this.attr('data-icon-favorited');
                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'ere_favorite_ajax',
                            'property_id': property_id
                        },
                        beforeSend: function () {
                            $this.children('i').addClass('fa-spinner fa-spin');
                        },
                        success: function (data) {
                            if ((typeof(data.added) == 'undefined') ||(data.added==-1)) {
                                ERE.login_modal();
                            }
                            if (data.added==1) {
                                $this.children('i').removeClass(icon_not_favorite).addClass(icon_favorited);
                                $this.attr('title', title_favorited);
                            } else if (data.added==0) {
                                $this.children('i').removeClass(icon_favorited).addClass(icon_not_favorite);
                                $this.attr('title', title_not_favorite);
                            }
                            $this.children('i').removeClass('fa-spinner fa-spin');
                            $this.removeClass('on-handle');
                            property_inner.removeClass('property-active-hover');
                        },
                        error: function () {
                            $this.children('i').removeClass('fa-spinner fa-spin');
                            $this.removeClass('on-handle');
                            property_inner.removeClass('property-active-hover');
                        }
                    });
                }
            });
        },
        light_gallery: function () {
            $("[data-rel='ere_light_gallery']").each(function () {
                var $this = $(this),
                    galleryId = $this.data('gallery-id');
                $this.on('click', function (event) {
                    event.preventDefault();
                    var _data = [];
                    var $index = 0;
                    var $current_src = $(this).attr('href');
                    var $current_thumb_src = $(this).data('thumb-src');

                    if (typeof galleryId != 'undefined') {
                        $('[data-gallery-id="' + galleryId + '"]').each(function (index) {
                            var src = $(this).attr('href'),
                                thumb = $(this).data('thumb-src'),
                                subHtml = $(this).attr('title');
                            if(src==$current_src && thumb==$current_thumb_src){
                                $index = index;
                            }
                            if(typeof(subHtml)=='undefined')
                                subHtml = '';
                            _data.push({
                                'src': src,
                                'downloadUrl': src,
                                'thumb': thumb,
                                'subHtml': subHtml
                            });
                        });
                        $this.lightGallery({
                            hash: false,
                            galleryId: galleryId,
                            dynamic: true,
                            dynamicEl: _data,
                            thumbWidth: 80,
                            index: $index
                        })
                    }
                });
            });
            $('a.ere-view-video').on('click',function (event) {
                event.preventDefault();
                var $src = $(this).attr('data-src');
                $(this).lightGallery({
                    dynamic: true,
                    dynamicEl: [{
                        'src': $src,
                        'thumb': '',
                        'subHtml': ''
                    }]
                });
            });
        },
        show_loading: function ($text) {
            if($text=='undefined' || $text=='' || $text==null) {
                $text=loading_text;
            }
            var template = wp.template('ere-processing-template');
            $('body').append(template({'ico': 'fa fa-spinner fa-spin', 'text': $text}));
        },

        change_loading_status: function ($ico_class, $text) {
            $('i', '.ere-processing').removeClass('fa-spinner fa-spin').addClass($ico_class);
            $('span', '.ere-processing').text($text);
        },

        close_loading: function ($timeout) {
            if (typeof $timeout == 'undefined' || $timeout == null) {
                $timeout = 500;
            }
            if ($timeout == 0) {
                $('.ere-processing').remove();
            } else {
                setTimeout(function () {
                    $('.ere-processing').fadeOut(function () {
                        $('.ere-processing').remove();
                    });
                }, $timeout);
            }
        },
        popup_alert: function ($ico_class, $title, $message) {
            var template = wp.template('ere-dialog-template');
            $('body').append(template({ico: $ico_class, message: $message}));
            $("#ere-dialog-popup").dialog({
                title: $title,
                resizable: false,
                closeOnEscape: true,
                modal: true,
                buttons: {
                    Ok: function() {
                        $(this).dialog( 'close' );
                        $(this).dialog('destroy').remove();
                    }
                }
            });
        },
        confirm_dialog: function ($title, $message, yes_callback, no_callback) {
            var template = wp.template('ere-dialog-template');
            $('body').append(template({ico: 'fa fa-question-circle', message: $message}));
            $("#ere-dialog-popup").dialog({
                title: $title,
                resizable: false,
                closeOnEscape: true,
                modal: true,
                buttons: [
                    {
                        text: confirm_yes_text, click: function () {
                        if (yes_callback)
                            yes_callback();
                        $(this).dialog('destroy').remove();
                    }
                    },
                    {
                        text: confirm_no_text, click: function () {
                        if (no_callback)
                            no_callback();
                        $(this).dialog('close');
                        $(this).dialog('destroy').remove();
                    }
                    }
                ]
            });
        },
        set_item_effect: function ($items, $effect) {
            if ($effect == 'hide') {
                $items.css('transition', 'opacity 1.5s linear, transform 1s');
                $items.css('-webkit-transition', 'opacity 1.5s linear, transform 1s');
                $items.css('-moz-transition', 'opacity 1.5s linear, transform 1s');
                $items.css('-ms-transition', 'opacity 1.5s linear, transform 1s');
                $items.css('-o-transition', 'opacity 1.5s linear, transform 1s');
                $items.css('opacity', 0);
                $items.css('transform', 'scale(0.2)');
                $items.css('-ms-transform', 'scale(0.2)');
                $items.css('-webkit-transform', 'scale(0.2)');
            }
            if ($effect == 'show') {
                for (var $i = 0; $i < $items.length; $i++) {
                    (function ($index) {
                        var $delay = 10 * $i;
                        setTimeout(function () {
                            $($items[$index]).css('opacity', 1);
                            $($items[$index]).css('transform', 'scale(1)');
                            $($items[$index]).css('-ms-transform', 'scale(1)');
                            $($items[$index]).css('-webkit-transform', 'scale(1)');
                        }, $delay);
                    })($i);
                }
            }
        },
        select_term: function () {
            var $elm = $('select.property-filter-mb');
            $elm.off();
            $($elm).on('change', function (event) {
                var $this = $(this);
                $this.attr('disabled', 'disabled');
                event.preventDefault();
                var optionValue = $('option:selected', $this).attr('value'),
                    object = $this.parent().prev().children('[data-filter="' + optionValue + '"]');
                object.click();
            });
        },
        contact_agent_by_email: function() {
            $('.agent-contact-btn', '#contact-agent-form').each(function () {
                $(this).on('click', function (event) {
                    event.preventDefault();
                    var $this = $(this),
                        $form = $(this).parents('form'),
                        name = $('[name="sender_name"]', $form).val(),
                        phone = $('[name="sender_phone"]', $form).val(),
                        sender_email = $('[name="sender_email"]', $form).val(),
                        message = $('[name="sender_msg"]', $form).val(),
                        error = false;
                    if(name == null || name.length === 0) {
                        $('.name-error', $form).removeClass('hidden');
                        error = true;
                    } else if(!$('.name-error', $form).hasClass('hidden')) {
                        $('.name-error', $form).addClass('hidden');
                    }
                    if(phone == null || phone.length === 0) {
                        $('.phone-error', $form).removeClass('hidden');
                        error = true;
                    } else if(!$('.phone-error', $form).hasClass('hidden')) {
                        $('.phone-error', $form).addClass('hidden');
                    }

                    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

                    if(sender_email==null || sender_email.length === 0 || !re.test(sender_email)) {
                        $('.email-error', $form).removeClass('hidden');
                        if(sender_email.trim().length !== 0 && !re.test(sender_email)) {
                            $('.email-error', $form).text($('.email-error', $form).data('not-valid'));
                        } else {
                            $('.email-error', $form).text($('.email-error', $form).data('error'));
                        }
                        error = true;
                    } else if(!$('.email-error', $form).hasClass('hidden')) {
                        $('.email-error', $form).addClass('hidden');
                    }
                    if(message==null || message.length === 0) {
                        $('.message-error', $form).removeClass('hidden');
                        error = true;
                    } else if(!$('.message-error', $form).hasClass('hidden')) {
                        $('.message-error', $form).addClass('hidden');
                    }
                    if(!error) {
                        $.ajax({
                            type: 'post',
                            url: ajax_url,
                            dataType: 'json',
                            data: $form.serialize(),
                            beforeSend: function () {
                                $('.form-messages', $form).html('<span class="success text-success"> ' + sending_text + '</span>');
                            },
                            success: function (response) {
                                if (response.success) {
                                    $('.form-messages', $form).html('<span class="success text-success"><i class="fa fa-check"></i> ' + response.message + '</span>');

                                } else {
                                    if (typeof ere_reset_recaptcha == 'function') {
                                        ere_reset_recaptcha();
                                    }
                                    $('.form-messages', $form).html('<span class="error text-danger"><i class="fa fa-close"></i> ' + response.message + '</span>');
                                }
                            },
                            error: function () {
                            }
                        });
                    }
                });
            });
        },

        property_paging: function() {
            var handle = true;
            $('.paging-navigation', '.property-paging-wrap').each(function () {
                $('a', $(this)).off('click').on('click', function (event) {
                    event.preventDefault();
                    if(handle) {
                        handle = false;
                        var $this = $(this);
                        var href = $this.attr('href'),
                            data_paged = ERE.get_page_number_from_href(href),
                            data_contain = $this.closest('.property-paging-wrap'),
                            property_content = $this.closest('.ere-property').find('.property-content');
                        $.ajax({
                            url: data_contain.data('admin-url'),
                            data: {
                                action: 'ere_property_paging_ajax',
                                layout: data_contain.data('layout'),
                                items_amount: data_contain.data('items-amount'),
                                columns: data_contain.data('columns'),
                                image_size: data_contain.data('image-size'),
                                columns_gap: data_contain.data('columns-gap'),
                                view_all_link: data_contain.data('view-all-link'),
                                paged: data_paged,
                                property_type: data_contain.data('property-type'),
                                property_status: data_contain.data('property-status'),
                                property_feature: data_contain.data('property-feature'),
                                property_city: data_contain.data('property-city'),
                                property_state: data_contain.data('property-state'),
                                property_neighborhood: data_contain.data('property-neighborhood'),
                                property_label: data_contain.data('property-label'),
                                property_featured: data_contain.data('property-featured'),
                                author_id: data_contain.data('author-id'),
                                agent_id: data_contain.data('agent-id')
                            },
                            success: function (html) {
                                var $newElems = $('.property-item', html),
                                    paging = $('.property-paging-wrap', html);

                                property_content.css('opacity', 0);

                                property_content.html($newElems);
                                ERE.set_item_effect($newElems, 'hide');
                                var contentTop = property_content.offset().top - 30;
                                $('html,body').animate({scrollTop: +contentTop + 'px'}, 500);
                                property_content.css('opacity', 1);
                                property_content.imagesLoaded(function () {
                                    $newElems = $('.property-item', property_content);
                                    ERE.set_item_effect($newElems, 'show');
                                    property_content.closest('.ere-property').find('.property-paging-wrap').html(paging.html());
                                    ERE.property_paging();
                                    ERE.property_paging_control();
                                    ERE.favorite();
                                    ERE.tooltip();
                                    ERE_Compare.register_event_compare();
                                });
                                handle = true;
                            },
                            error: function () {
                                handle = true;
                            }
                        });
                    }
                })
            });
        },
        property_paging_control: function() {
            $('.paging-navigation', '.ere-property').each(function () {
                var $this = $(this);
                if($this.find('a.next').length === 0) {
                    $this.addClass('next-disable');
                } else {
                    $this.removeClass('next-disable');
                }
            });
        },
        move_link_to_carousel: function() {
            $('.property-carousel').each(function () {
                var this_elm = $(this);
                $('.owl-carousel', this_elm).on('owlInitialized',function() {
                    if (this_elm.data('view-all-link') != undefined && (this_elm.children('.owl-loaded').hasClass('owl-nav-top-right') ||
                        this_elm.children('.owl-loaded').hasClass('owl-nav-bottom-center'))) {
                        var view_all_link = this_elm.find('.view-all-link');
                        if(view_all_link.length > 0 && !this_elm.find('.owl-nav').hasClass('disabled')) {
                            view_all_link.removeClass('mg-top-60 sm-mg-top-40');
                            this_elm.find('.owl-nav').addClass('has-view-all');
                            this_elm.find('.owl-nav').append(view_all_link[0].outerHTML);
                            view_all_link.remove();
                        }
                    }
                    if(this_elm.hasClass('owl-move-nav-par-with-heading') && this_elm.find('.ere-heading').length > 0
                        && !this_elm.find('.ere-heading').hasClass('heading-contain-owl-nav')) {
                        this_elm.find('.ere-heading').addClass('heading-contain-owl-nav');
                        this_elm.find('.owl-nav').addClass('owl-nav-top-right').insertAfter(this_elm.find('.ere-heading').children('h2'));
                    }
                });
            });
        },

        execute_nav: function() {
            $('.ere-property-carousel').each(function () {
                var this_elm = $(this),
                    navigation_wrap = $('.navigation-wrap', this_elm),
                    carousel_item = $('.owl-carousel .property-item', this_elm);
                ERE.ere_calc_column_padding(navigation_wrap, carousel_item);
                $('.owl-carousel', this_elm).on('owlInitialized',function() {
                    var $this = $(this),
                        nav = $('.owl-nav', $this);
                    if (navigation_wrap.length > 0 && nav.length > 0) {
                        nav.detach().appendTo(navigation_wrap);
                    }
                    ERE.ere_calc_column_padding(navigation_wrap, $('.property-item', $this));
                });
            });
        },
        ere_calc_column_padding: function(navigation_wrap, carousel_item) {
            if(navigation_wrap.height() < carousel_item.height()) {
                var padding = Math.floor((carousel_item.height() - navigation_wrap.height()) / 2);
                navigation_wrap.css({
                    'padding-top': padding + 'px',
                    'padding-bottom': padding + 'px'
                });
            }
        },

        execute_slider_nav: function() {
            $('.ere-property-slider.navigation-middle').each(function () {
                ERE.ere_calc_nav_top($('.owl-carousel', $(this)));
                $('.owl-carousel', $(this)).on('owlInitialized',function() {
                    var this_elm = $(this),
                        nav = $('.owl-nav', this_elm);
                    nav.addClass('container');
                    setTimeout(function(){
                        ERE.ere_calc_nav_top(this_elm);
                    },20);
                });
            });
        },
        ere_calc_nav_top: function(carousel_wrap) {
            var nav = $('.owl-nav', carousel_wrap),
                wrap_height = $('.property-item', carousel_wrap).outerHeight(),
                content_height = $('.block-center-inner', carousel_wrap).outerHeight(),
                top = Math.floor((wrap_height - content_height) / 2);
            nav.css('top', top + 'px');
        },
        sync_property_carousel: function($propertySyncWrap) {
            var isRTL = $('body').hasClass('rtl'),
                $sliderMain = $propertySyncWrap.find('.property-content-slider'),
                $sliderThumb = $propertySyncWrap.find('.property-image-slider');
            $sliderMain.owlCarousel({
                items: 1,
                autoHeight: true,
                nav: false,
                dots: false,
                loop: false,
                smartSpeed: 500,
                rtl: isRTL
            }).on('changed.owl.carousel', syncPosition);

            $sliderThumb.on('initialized.owl.carousel', function () {
                $sliderThumb.find(".owl-item").eq(0).addClass("current");
            }).owlCarousel({
                nav: false,
                dots: false,
                rtl: isRTL,
                margin: 10,
                responsive: {
                    992: {
                        items: 4
                    },
                    768: {
                        items: 3
                    },
                    480: {
                        items: 2
                    },
                    0: {
                        items: 1
                    }
                }
            }).on('changed.owl.carousel', syncPosition2);

            function syncPosition(el) {
                //if you set loop to false, you have to restore this next line
                var current = el.item.index;

                $sliderThumb
                    .find(".owl-item")
                    .removeClass("current")
                    .eq(current)
                    .addClass("current");
                var onscreen = $sliderThumb.find('.owl-item.active').length - 1;
                var start = $sliderThumb.find('.owl-item.active').first().index();
                var end = $sliderThumb.find('.owl-item.active').last().index();

                if (current > end) {
                    $sliderThumb.data('owl.carousel').to(current, 500, true);
                }
                if (current < start) {
                    $sliderThumb.data('owl.carousel').to(current - onscreen, 500, true);
                }
            }

            function syncPosition2(el) {
                var number = el.item.index;
                $sliderMain.data('owl.carousel').to(number, 500, true);
            }

            $sliderThumb.on("click", ".owl-item", function (e) {
                e.preventDefault();
                if ($(this).hasClass('current')) return;
                var number = $(this).index();
                $sliderMain.data('owl.carousel').to(number, 500, true);
            });
        }
    };
    $(document).ready(function () {
        ERE.init();
    });
    $(window).resize(function () {
        setTimeout( ERE.execute_nav, 20);
        setTimeout( function(){
            ERE.execute_slider_nav();
            var $property_sync_wrap = $('.pagination-image.ere-property-slider');
            ERE.sync_property_carousel($property_sync_wrap);
        }, 10);
    });
})(jQuery);
