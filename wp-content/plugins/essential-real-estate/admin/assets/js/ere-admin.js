(function ($) {
    'use strict';
    $(document).ready(function () {
        $('.tips, .help_tip').tipTip({
            'attribute': 'data-tip',
            'fadeIn': 50,
            'fadeOut': 50,
            'delay': 200
        });

        var css_class_wrap = '.ere-property-select-meta-box-wrap';
        var ajax_url = ere_admin_vars.ajax_url;
        var enable_filter_location=ere_admin_vars.enable_filter_location;
        if(enable_filter_location=='1')
        {
            $('.ere-property-country-ajax', css_class_wrap).select2();
            $('.ere-property-state-ajax', css_class_wrap).select2();
            $('.ere-property-city-ajax', css_class_wrap).select2();
            $('.ere-property-neighborhood-ajax', css_class_wrap).select2();
        }

        var ere_get_states_by_country = function () {
            var $this = $(".ere-property-country-ajax", css_class_wrap);
            var $property_state = $(".ere-property-state-ajax", css_class_wrap);
            var $is_slug = $property_state.attr('data-slug');
            if (typeof($is_slug) === 'undefined') {
                $is_slug='1';
            }
            if ($this.length && $property_state.length) {
                var selected_country = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'ere_get_states_by_country_ajax',
                        'country': selected_country,
                        'type': 0,
                        'is_slug':$is_slug
                    },
                    beforeSend: function () {
                        $this.parent().children('.ere-loading').remove();
                        $this.parent().append('<span class="ere-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                    },
                    success: function (response) {
                        $property_state.html(response);
                        var val_selected =$property_state.attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $property_state.val(val_selected);
                        }
                        $this.parent().children('.ere-loading').remove();
                    },
                    error: function () {
                        $this.parent().children('.ere-loading').remove();
                    },
                    complete: function () {
                        $this.parent().children('.ere-loading').remove();
                    }
                });
            }
        };
        ere_get_states_by_country();

        $(".ere-property-country-ajax", css_class_wrap).on('change', function () {
            ere_get_states_by_country();
        });

        var ere_get_cities_by_state = function () {
            var $this = $(".ere-property-state-ajax", css_class_wrap);
            var $property_city = $(".ere-property-city-ajax", css_class_wrap);
            var $is_slug = $property_city.attr('data-slug');
            if (typeof($is_slug) === 'undefined') {
                $is_slug='1';
            }
            if ($this.length && $property_city.length) {
                var selected_state = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'ere_get_cities_by_state_ajax',
                        'state': selected_state,
                        'type': 0,
                        'is_slug':$is_slug
                    },
                    beforeSend: function () {
                        $this.parent().children('.ere-loading').remove();
                        $this.parent().append('<span class="ere-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                    },
                    success: function (response) {
                        $property_city.html(response);
                        var val_selected = $property_city.attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $property_city.val(val_selected);
                        }
                        $this.parent().children('.ere-loading').remove();
                    },
                    error: function () {
                        $this.parent().children('.ere-loading').remove();
                    },
                    complete: function () {
                        $this.parent().children('.ere-loading').remove();
                    }
                });
            }
        };
        ere_get_cities_by_state();

        $(".ere-property-state-ajax", css_class_wrap).on('change', function () {
            ere_get_cities_by_state();
        });

        var ere_get_neighborhoods_by_city = function () {
            var $this = $(".ere-property-city-ajax", css_class_wrap);
            var $property_neighborhood = $(".ere-property-neighborhood-ajax", css_class_wrap);
            var $is_slug = $property_neighborhood.attr('data-slug');
            if (typeof($is_slug) === 'undefined') {
                $is_slug='1';
            }
            if ($this.length && $property_neighborhood.length) {
                var selected_city = $this.val();
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        'action': 'ere_get_neighborhoods_by_city_ajax',
                        'city': selected_city,
                        'type': 0,
                        'is_slug':$is_slug
                    },
                    beforeSend: function () {
                        $this.parent().children('.ere-loading').remove();
                        $this.parent().append('<span class="ere-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                    },
                    success: function (response) {
                        $property_neighborhood.html(response);
                        var val_selected = $property_neighborhood.attr('data-selected');
                        if (typeof val_selected !== 'undefined') {
                            $property_neighborhood.val(val_selected);
                        }
                        $this.parent().children('.ere-loading').remove();
                    },
                    error: function () {
                        $this.parent().children('.ere-loading').remove();
                    },
                    complete: function () {
                        $this.parent().children('.ere-loading').remove();
                    }
                });
            }
        };
        ere_get_neighborhoods_by_city();

        $(".ere-property-city-ajax", css_class_wrap).on('change', function () {
            ere_get_neighborhoods_by_city();
        });
    });
})(jQuery);