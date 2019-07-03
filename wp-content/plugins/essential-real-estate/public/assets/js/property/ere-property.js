(function ($) {
    'use strict';
    $(document).ready(function () {
        if (typeof ere_property_vars !== "undefined") {
            var dtGlobals = {}; // Global storage
            dtGlobals.isMobile	= (/(Android|BlackBerry|iPhone|iPad|Palm|Symbian|Opera Mini|IEMobile|webOS)/.test(navigator.userAgent));
            dtGlobals.isAndroid	= (/(Android)/.test(navigator.userAgent));
            dtGlobals.isiOS		= (/(iPhone|iPod|iPad)/.test(navigator.userAgent));
            dtGlobals.isiPhone	= (/(iPhone|iPod)/.test(navigator.userAgent));
            dtGlobals.isiPad	= (/(iPad|iPod)/.test(navigator.userAgent));
            var ajax_url = ere_property_vars.ajax_url,
                css_class_wrap = '.property-manager-form',
                ere_metabox_prefix = ere_property_vars.ere_metabox_prefix,
                googlemap_zoom_level = ere_property_vars.googlemap_zoom_level,
                google_map_style = ere_property_vars.google_map_style,
                googlemap_marker_icon = ere_property_vars.googlemap_marker_icon,
                googlemap_default_country = ere_property_vars.googlemap_default_country,
                upload_nonce = ere_property_vars.upload_nonce,
                file_type_title = ere_property_vars.file_type_title,
                max_property_images = ere_property_vars.max_property_images,
                image_max_file_size = ere_property_vars.image_max_file_size,
                max_property_attachments = ere_property_vars.max_property_attachments,
                attachment_max_file_size = ere_property_vars.attachment_max_file_size,
                attachment_file_type = ere_property_vars.attachment_file_type;

            var floor_name_text = ere_property_vars.floor_name_text,
                floor_size_text = ere_property_vars.floor_size_text,
                floor_size_postfix_text = ere_property_vars.floor_size_postfix_text,
                floor_bedrooms_text = ere_property_vars.floor_bedrooms_text,
                floor_bathrooms_text = ere_property_vars.floor_bathrooms_text,
                floor_price_text = ere_property_vars.floor_price_text,
                floor_price_postfix_text = ere_property_vars.floor_price_postfix_text,
                floor_image_text = ere_property_vars.floor_image_text,
                floor_description_text = ere_property_vars.floor_description_text,
                floor_upload_text = ere_property_vars.floor_upload_text;
            var map; var location; var geocomplete = $("#geocomplete");
            var ere_geocomplete_map = function () {
                var property_form = $('input[name="property_form"]').val();
                var styles = [];
                if (google_map_style !== '') {
                    styles = JSON.parse(google_map_style);
                }
                geocomplete.geocomplete({
                    map: ".map_canvas",
                    details: "form",
                    country: googlemap_default_country,
                    geocodeAfterResult: true,
                    types: ["geocode", "establishment"],
                    mapOptions: {
                        zoom: parseInt(googlemap_zoom_level),
                        styles: styles
                    },
                    markerOptions: {
                        draggable: true,
                        icon: googlemap_marker_icon
                    }
                }).one("geocode:result", function (event, result) {
                    map = geocomplete.geocomplete("map");
                    google.maps.event.addListenerOnce(map, 'idle', function() {
                        google.maps.event.trigger(map, 'resize');
                        location=result.geometry.location;
                        map.setCenter(result.geometry.location);
                    });
                });
                geocomplete.bind("geocode:dragged", function (event, latLng) {
                    $("input[name=lat]").val(latLng.lat());
                    $("input[name=lng]").val(latLng.lng());
                    $("#reset").show();
                });
                geocomplete.on('focus',function(){
                    google.maps.event.trigger(map, 'resize');
                });
                $("#reset").on('click', function () {
                    geocomplete.geocomplete("resetMarker");
                    $("#reset").hide();
                    return false;
                });
                $("#find").on('click', function (e) {
                    e.preventDefault();
                    geocomplete.trigger("geocode");
                });
                $(window).load(function () {
                    geocomplete.trigger("geocode");
                });
            };
            ere_geocomplete_map();

            $('input[name="agent_display_option"]', css_class_wrap).on('change', function () {
                $('select[name="property_agent"]').hide();
                if ($(this).val() == 'other_info') {
                    $("#property_other_contact").slideDown('slow');
                }
                else {
                    $("#property_other_contact").slideUp('slow');
                }
            });
            var ere_property_price_on_call_change = function () {
                if ($('input[name="property_price_on_call"]').is(':checked')) {
                    $('input[name="property_price_short"]').attr('disabled', 'disabled');
                    $('select[name="property_price_unit"]').attr('disabled', 'disabled');
                    $('input[name="property_price_prefix"]').attr('disabled', 'disabled');
                    $('input[name="property_price_postfix"]').attr('disabled', 'disabled');
                }
                else {
                    $('input[name="property_price_short"]').removeAttr('disabled');
                    $('select[name="property_price_unit"]').removeAttr('disabled');
                    $('input[name="property_price_prefix"]').removeAttr('disabled');
                    $('input[name="property_price_postfix"]').removeAttr('disabled');
                }
            };
            ere_property_price_on_call_change();
            $('input[name="property_price_on_call"]', css_class_wrap).on('change', function () {
                ere_property_price_on_call_change();
            });

            /* ------------------------------------------------------------------------ */
            /*	Property additional Features
             /* ------------------------------------------------------------------------ */
            var ere_execute_additional_order = function () {
                var $i = 0;
                $('tr', '#ere_additional_details').each(function () {
                    var input_title = $('input[name*="additional_feature_title"]', $(this)),
                        input_value = $('input[name*="additional_feature_value"]', $(this));
                    input_title.attr('name', 'additional_feature_title[' + $i + ']');
                    input_title.attr('id', 'additional_feature_title_' + $i);
                    input_value.attr('name', 'additional_feature_value[' + $i + ']');
                    input_value.attr('id', 'additional_feature_value_' + $i);
                    $i++;
                });
            };
            $('#ere_additional_details').sortable({
                revert: 100,
                placeholder: "detail-placeholder",
                handle: ".sort-additional-row",
                cursor: "move",
                stop: function (event, ui) {
                    ere_execute_additional_order();
                }
            });

            $('.add-additional-feature', css_class_wrap).on('click', function (e) {
                e.preventDefault();
                var row_num = $(this).data("increment") + 1;
                $(this).data('increment', row_num);
                $(this).attr({
                    "data-increment": row_num
                });

                var new_feature = '<tr>' +
                    '<td class="action-field">' +
                    '<span class="sort-additional-row"><i class="fa fa-navicon"></i></span>' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control" type="text" name="additional_feature_title[' + row_num + ']" id="additional_feature_title_' + row_num + '" value="">' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control" type="text" name="additional_feature_value[' + row_num + ']" id="additional_feature_value_' + row_num + '" value="">' +
                    '</td>' +
                    '<td>' +
                    '<span data-remove="' + row_num + '" class="remove-additional-feature"><i class="fa fa-remove"></i></span>' +
                    '</td>' +
                    '</tr>';
                $('#ere_additional_details').append(new_feature);
                ere_remove_additional_feature();
            });

            var ere_remove_additional_feature = function () {
                $('.remove-additional-feature', css_class_wrap).on('click', function (event) {
                    event.preventDefault();
                    var $this = $(this),
                        parent = $this.closest('.additional-block'),
                        button_add = parent.find('.add-additional-feature'),
                        increment = parseInt(button_add.data('increment')) - 1;

                    $this.closest('tr').remove();
                    button_add.data('increment', increment);
                    button_add.attr('data-increment', increment);
                    ere_execute_additional_order();
                });
            };
            ere_remove_additional_feature();

            /* ------------------------------------------------------------------------ */
            /*	Floors
             /* ------------------------------------------------------------------------ */
            var ere_execute_floor_order = function () {
                var $i = 0;
                $('tr', '#ere_floors').each(function () {
                    var label_name = $('label[for*="' + ere_metabox_prefix + 'floor_name_"]', $(this)),
                        input_name = $('input[name*="' + ere_metabox_prefix + 'floor_name"]', $(this)),
                        label_price = $('label[for*="' + ere_metabox_prefix + 'floor_price_"]', $(this)),
                        input_price = $('input[name*="' + ere_metabox_prefix + 'floor_price"]', $(this)),
                        label_price_postfix = $('label[for*="' + ere_metabox_prefix + 'floor_price_postfix_"]', $(this)),
                        input_price_postfix = $('input[name*="' + ere_metabox_prefix + 'floor_price_postfix"]', $(this)),
                        label_size = $('label[for*="' + ere_metabox_prefix + 'floor_size_"]', $(this)),
                        input_size = $('input[name*="' + ere_metabox_prefix + 'floor_size"]', $(this)),
                        label_size_postfix = $('label[for*="' + ere_metabox_prefix + 'floor_size_postfix_"]', $(this)),
                        input_size_postfix = $('input[name*="' + ere_metabox_prefix + 'floor_size_postfix"]', $(this)),
                        label_bedrooms = $('label[for*="' + ere_metabox_prefix + 'floor_bedrooms_"]', $(this)),
                        input_bedrooms = $('input[name*="' + ere_metabox_prefix + 'floor_bedrooms"]', $(this)),
                        label_bathrooms = $('label[for*="' + ere_metabox_prefix + 'floor_bathrooms_"]', $(this)),
                        input_bathrooms = $('input[name*="' + ere_metabox_prefix + 'floor_bedrooms"]', $(this)),
                        label_image_url = $('label[for*="' + ere_metabox_prefix + 'floor_image_url_"]', $(this)),
                        input_image_url = $('input[id*="' + ere_metabox_prefix + 'floor_image_url"]', $(this)),
                        input_image_id = $('input[id*="' + ere_metabox_prefix + 'floor_image_id"]', $(this)),
                        input_image_button = $('button[class*="ere_floorsImg"]', $(this)),
                        label_description = $('label[for*="' + ere_metabox_prefix + 'floor_description_"]', $(this)),
                        input_description = $('input[id*="' + ere_metabox_prefix + 'floor_description"]', $(this));

                    label_name.attr('for', ere_metabox_prefix + 'floor_name_' + $i);
                    input_name.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_name]');
                    input_name.attr('id', ere_metabox_prefix + 'floor_name_' + $i);

                    label_price.attr('for', ere_metabox_prefix + 'floor_price_' + $i);
                    input_price.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_price]');
                    input_price.attr('id', ere_metabox_prefix + 'floor_price_' + $i);

                    label_price_postfix.attr('for', ere_metabox_prefix + 'floor_price_postfix_' + $i);
                    input_price_postfix.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_price_postfix]');
                    input_price_postfix.attr('id', ere_metabox_prefix + 'floor_price_postfix_' + $i);

                    label_size.attr('for', ere_metabox_prefix + 'floor_size_' + $i);
                    input_size.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_size]');
                    input_size.attr('id', ere_metabox_prefix + 'floor_size_' + $i);

                    label_size_postfix.attr('for', ere_metabox_prefix + 'floor_size_postfix_' + $i);
                    input_size_postfix.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_size_postfix]');
                    input_size_postfix.attr('id', ere_metabox_prefix + 'floor_size_postfix_' + $i);

                    label_bedrooms.attr('for', ere_metabox_prefix + 'floor_bedrooms_' + $i);
                    input_bedrooms.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_bedrooms]');
                    input_bedrooms.attr('id', ere_metabox_prefix + 'floor_bedrooms_' + $i);

                    label_bathrooms.attr('for', ere_metabox_prefix + 'floor_bathrooms_' + $i);
                    input_bathrooms.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_bathrooms]');
                    input_bathrooms.attr('id', ere_metabox_prefix + 'floor_bathrooms_' + $i);

                    label_image_url.attr('for', ere_metabox_prefix + 'floor_image_url_' + $i);
                    input_image_url.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_image][url]');
                    input_image_url.attr('id', ere_metabox_prefix + 'floor_image_url_' + $i);

                    input_image_id.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_image][id]');
                    input_image_id.attr('id', ere_metabox_prefix + 'floor_image_id_' + $i);

                    input_image_button.attr('id', $i);

                    label_description.attr('for', ere_metabox_prefix + 'floor_description_' + $i);
                    input_description.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_description]');
                    input_description.attr('id', ere_metabox_prefix + 'floor_description_' + $i);
                    $i++;
                });
            };
            $('#ere_floors').sortable({
                revert: 100,
                placeholder: "detail-placeholder",
                handle: ".sort-floors-row",
                cursor: "move",
                stop: function (event, ui) {
                    ere_execute_floor_order();
                }
            });
            // Floor image
            var vars_plupload = {};
            var ere_floor_images = function (index) {
                vars_plupload['id' + index] = new plupload.Uploader({
                    browse_button:  'ere-floor-'+index,
                    file_data_name: 'property_upload_file',
                    container: 'ere-floor-plupload-container-'+index,
                    url: ajax_url + "?action=ere_property_img_upload_ajax&nonce=" + upload_nonce,
                    filters: {
                        mime_types: [
                            {title: file_type_title, extensions: "jpg,jpeg,gif,png"}
                        ],
                        max_file_size: image_max_file_size,
                        prevent_duplicates: true
                    }
                });
                vars_plupload['id' + index].init();

                vars_plupload['id' + index].bind('FilesAdded', function (up, files) {
                    var maxfiles = max_property_images;
                    if (up.files.length > maxfiles) {
                        up.splice(maxfiles);
                        alert('no more than ' + maxfiles + ' file(s)');
                        return;
                    }
                    up.refresh();
                    vars_plupload['id' + index].start();
                });
                vars_plupload['id' + index].bind('Error', function (up, err) {
                    document.getElementById('ere-floor-errors-log-'+index).innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });
                vars_plupload['id' + index].bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);
                    if (response.success) {
                        $('#ere-floor-plupload-container-' + index).find('.ere_floor_image_url').val(response.full_image);
                        $('#ere-floor-plupload-container-' + index).find('.ere_floor_image_id').val(response.attachment_id);
                    }
                });
            };
            ere_floor_images("0");

            var ere_remove_floor = function () {
                $('.remove-floors-row', css_class_wrap).on('click', function (event) {
                    event.preventDefault();
                    var $this = $(this),
                        parent = $this.closest('.add-sort-table'),
                        button_add = parent.find('#add-floors-row'),
                        increment = parseInt(button_add.data('increment')) - 1;

                    $this.closest('tr').remove();
                    button_add.data('increment', increment);
                    button_add.attr('data-increment', increment);
                    ere_execute_floor_order();
                });
            };
            ere_remove_floor();

            var ere_execute_floor = function () {
                var $this = $('[name="floors_enable"][checked]', '.property-floors-control'),
                    enable_val = $this.val(),
                    floor_data = $this.closest('.property-floors-control').next('.property-floors-data');
                if (enable_val == 1) {
                    floor_data.slideDown('slow');
                } else if (enable_val == 0) {
                    floor_data.slideUp('slow');
                }
                $('input[name="floors_enable"]', '.property-floors-control').each(function () {
                    $(this).on('click', function () {
                        enable_val = $(this).val();
                        if (enable_val == 1) {
                            floor_data.slideDown('slow');
                            if(dtGlobals.isiOS) {
                                ere_floor_images("0");
                            }
                        } else if (enable_val == 0) {
                            floor_data.slideUp('slow');
                        }
                    });
                });
            };
            ere_execute_floor();
            $('#add-floors-row').on('click', function (e) {
                e.preventDefault();

                var row_num = $(this).data("increment") + 1;
                $(this).data('increment', row_num);
                $(this).attr({
                    "data-increment": row_num
                });

                var new_floor = '' +
                    '<tr>' +
                    '<td class="row-sort">' +
                    '<span class="sort sort-floors-row"><i class="fa fa-navicon"></i></span>' +
                    '</td>' +
                    '<td class="sort-middle">' +
                    '<div class="sort-inner-block">' +
                    '<div class="row">' +
                    '<div class="col-sm-12">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_name_' + row_num + '">' + floor_name_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_name]" type="text" id="' + ere_metabox_prefix + 'floor_name_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_price_' + row_num + '">' + floor_price_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_price]" type="number" id="' + ere_metabox_prefix + 'floor_price_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_price_postfix_' + row_num + '">' + floor_price_postfix_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_price_postfix]" type="text" id="' + ere_metabox_prefix + 'floor_price_postfix_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_size_' + row_num + '">' + floor_size_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_size]" type="number" id="' + ere_metabox_prefix + 'floor_size_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_size_postfix_' + row_num + '">' + floor_size_postfix_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_size_postfix]" type="text" id="' + ere_metabox_prefix + 'floor_size_postfix_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_bedrooms_' + row_num + '">' + floor_bedrooms_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_bedrooms]" type="number" id="' + ere_metabox_prefix + 'floor_bedrooms_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_bathrooms_' + row_num + '">' + floor_bathrooms_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_bathrooms]" type="number" id="' + ere_metabox_prefix + 'floor_bathrooms_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_image_url_' + row_num + '">' + floor_image_text + '</label>' +
                    '<div id="ere-floor-plupload-container-' + row_num + '" class="file-upload-block">' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_image][url]" type="text" id="' + ere_metabox_prefix + 'floor_image_url_' + row_num + '" class="ere_floor_image_url form-control">' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_image][id]" type="hidden" id="' + ere_metabox_prefix + 'floor_image_id_' + row_num + '" class="ere_floor_image_id">' +
                    '<button type="button" id="ere-floor-' + row_num + '" style="position: absolute" title="' + floor_upload_text + '" class="ere_floorsImg"><i class="fa fa-file-image-o"></i></button>' +
                    '</div>' +
                    '<div id="ere-floor-errors-log"></div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_description_' + row_num + '">' + floor_description_text + '</label>' +
                    '<textarea name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_description]" rows="4" id="' + ere_metabox_prefix + 'floor_description_' + row_num + '" class="form-control"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '<td class="row-remove">' +
                    '<span data-remove="' + row_num + '" class="remove-floors-row remove"><i class="fa fa-remove"></i></span>' +
                    '</td>' +
                    '</tr>';

                $('#ere_floors').append(new_floor);
                ere_remove_floor();
                ere_floor_images(row_num);
            });
            // Property Thumbnails
            var ere_property_gallery_event = function () {

                // Set Featured Image
                $('.icon-featured', '.ere-property-gallery').on('click', function () {

                    var $this = $(this);
                    var thumb_id = $this.data('attachment-id');
                    var icon = $this.find('i');

                    $('.media-thumb .featured-image-id').remove();
                    $('.media-thumb .icon-featured i').removeClass('fa-star').addClass('fa-star-o');

                    $this.closest('.media-thumb').append('<input type="hidden" class="featured-image-id" name="featured_image_id" value="' + thumb_id + '">');
                    icon.removeClass('fa-star-o').addClass('fa-star');
                });

                $('.icon-delete', '.ere-property-gallery').on('click', function () {
                    var $this = $(this),
                        icon_delete = $this.children('i'),
                        thumbnail = $this.closest('.media-thumb-wrap'),
                        property_id = $this.data('property-id'),
                        attachment_id = $this.data('attachment-id');

                    icon_delete.addClass('fa-spinner fa-spin');

                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'ere_remove_property_attachment_ajax',
                            'property_id': property_id,
                            'attachment_id': attachment_id,
                            'type': 'gallery',
                            'removeNonce': upload_nonce
                        },
                        success: function (response) {
                            if (response.success) {
                                thumbnail.remove();
                                thumbnail.hide();
                            }
                            icon_delete.removeClass('fa-spinner fa-spin');
                        },
                        error: function () {
                            icon_delete.removeClass('fa-spinner fa-spin');
                        }
                    });
                });
            };

            ere_property_gallery_event();

            // Property Thumbnails
            var ere_property_attachments_event = function () {
                $('.icon-delete', '.ere-property-attachments').on('click', function () {
                    var $this = $(this),
                        icon_delete = $this.children('i'),
                        thumbnail = $this.closest('.media-thumb-wrap'),
                        property_id = $this.data('property-id'),
                        attachment_id = $this.data('attachment-id');

                    icon_delete.addClass('fa-spinner fa-spin');

                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'ere_remove_property_attachment_ajax',
                            'property_id': property_id,
                            'attachment_id': attachment_id,
                            'type': 'attachments',
                            'removeNonce': upload_nonce
                        },
                        success: function (response) {
                            if (response.success) {
                                thumbnail.remove();
                                thumbnail.hide();
                            }
                            icon_delete.removeClass('fa-spinner fa-spin');
                        },
                        error: function () {
                            icon_delete.removeClass('fa-spinner fa-spin');
                        }
                    });
                });
            };

            ere_property_attachments_event();

            // Property Gallery images
            var ere_property_gallery_images = function () {

                $("#property_gallery_thumbs_container").sortable();

                /* initialize uploader */
                var uploader = new plupload.Uploader({
                    browse_button: 'ere_select_gallery_images',          // this can be an id of a DOM element or the DOM element itself
                    file_data_name: 'property_upload_file',
                    container: 'ere_gallery_plupload_container',
                    drop_element: 'ere_gallery_plupload_container',
                    multi_selection: true,
                    url: ajax_url + "?action=ere_property_img_upload_ajax&nonce=" + upload_nonce,
                    filters: {
                        mime_types: [
                            {title: file_type_title, extensions: "jpg,jpeg,gif,png"}
                        ],
                        max_file_size: image_max_file_size,
                        prevent_duplicates: true
                    }
                });
                uploader.init();

                uploader.bind('FilesAdded', function (up, files) {
                    var propertyThumb = "";
                    var maxfiles = max_property_images;
                    if (up.files.length > maxfiles) {
                        up.splice(maxfiles);
                        alert('no more than ' + maxfiles + ' file(s)');
                        return;
                    }
                    plupload.each(files, function (file) {
                        propertyThumb += '<div id="holder-' + file.id + '" class="col-sm-2 media-thumb-wrap"></div>';
                    });
                    document.getElementById('property_gallery_thumbs_container').innerHTML += propertyThumb;
                    up.refresh();
                    uploader.start();
                });

                uploader.bind('UploadProgress', function (up, file) {
                    document.getElementById("holder-" + file.id).innerHTML = '<span><i class="fa fa-spinner fa-spin"></i></span>';
                });

                uploader.bind('Error', function (up, err) {
                    document.getElementById('ere_gallery_errors_log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });

                uploader.bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);

                    if (response.success) {

                        var $html =
                            '<figure class="media-thumb">' +
                            '<img src="' + response.url + '" alt="" />' +
                            '<div class="media-item-actions">' +
                            '<a class="icon icon-delete" data-property-id="0"  data-attachment-id="' + response.attachment_id + '" href="javascript:;" ><i class="fa fa-trash-o"></i></a>' +
                            '<a class="icon icon-featured" data-property-id="0"  data-attachment-id="' + response.attachment_id + '" href="javascript:;" ><i class="fa fa-star-o"></i></a>' +
                            '<input type="hidden" class="property_image_ids" name="property_image_ids[]" value="' + response.attachment_id + '"/>' +
                            '<span style="display: none;" class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></span>' +
                            '</div>' +
                            '</figure>';

                        document.getElementById("holder-" + file.id).innerHTML = $html;
                        ere_property_gallery_event();
                    }
                });
            };
            ere_property_gallery_images();
            // Property Documents
            var ere_property_attachments = function () {

                $("#property_attachments_thumbs_container").sortable();

                /* initialize uploader */
                var uploader = new plupload.Uploader({
                    browse_button: 'ere_select_file_attachments',          // this can be an id of a DOM element or the DOM element itself
                    file_data_name: 'property_upload_file',
                    container: 'ere_attachments_plupload_container',
                    drop_element: 'ere_attachments_plupload_container',
                    multi_selection: true,
                    url: ajax_url + "?action=ere_property_attachment_upload_ajax&nonce=" + upload_nonce,
                    filters: {
                        mime_types: [
                            {title: file_type_title, extensions: attachment_file_type}
                        ],
                        max_file_size: attachment_max_file_size,
                        prevent_duplicates: true
                    }
                });
                uploader.init();

                uploader.bind('FilesAdded', function (up, files) {
                    var propertyThumb = "";
                    var maxfiles = max_property_attachments;
                    if (up.files.length > maxfiles) {
                        up.splice(maxfiles);
                        alert('no more than ' + maxfiles + ' file(s)');
                        return;
                    }
                    plupload.each(files, function (file) {
                        propertyThumb += '<div id="holder-' + file.id + '" class="col-lg-4 col-md-4 col-sm-6 col-xs-12 media-thumb-wrap"></div>';
                    });
                    document.getElementById('property_attachments_thumbs_container').innerHTML += propertyThumb;
                    up.refresh();
                    uploader.start();
                });

                uploader.bind('UploadProgress', function (up, file) {
                    document.getElementById("holder-" + file.id).innerHTML = '<span><i class="fa fa-spinner fa-spin"></i></span>';
                });

                uploader.bind('Error', function (up, err) {
                    document.getElementById('ere_attachments_errors_log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });

                uploader.bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);

                    if (response.success) {

                        var $html =
                            '<figure class="media-thumb">' +
                            '<img src="' + response.thumb_url + '" alt="" />' +
                            '<a href="'+ response.url +'">' + response.file_name + '</a>'+
                            '<div class="media-item-actions">' +
                            '<a class="icon icon-delete" data-property-id="0"  data-attachment-id="' + response.attachment_id + '" href="javascript:;" ><i class="fa fa-trash-o"></i></a>' +
                            '<input type="hidden" class="property_attachment_ids" name="property_attachment_ids[]" value="' + response.attachment_id + '"/>' +
                            '<span style="display: none;" class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></span>' +
                            '</div>' +
                            '</figure>';

                        document.getElementById("holder-" + file.id).innerHTML = $html;
                        ere_property_attachments_event();
                    }
                });
            };
            ere_property_attachments();
            // Image 360
            var ere_image_360 = function () {

                var uploader_image_360 = new plupload.Uploader({
                    browse_button: 'ere_select_images_360',
                    file_data_name: 'property_upload_file',
                    container: 'ere_image_360_plupload_container',
                    url: ajax_url + "?action=ere_property_img_upload_ajax&nonce=" + upload_nonce,
                    filters: {
                        mime_types: [
                            {title: file_type_title, extensions: "jpg,jpeg,gif,png"}
                        ],
                        max_file_size: image_max_file_size,
                        prevent_duplicates: true
                    }
                });
                uploader_image_360.init();

                uploader_image_360.bind('FilesAdded', function (up, files) {
                    var maxfiles = max_property_images;
                    if (up.files.length > maxfiles) {
                        up.splice(maxfiles);
                        alert('no more than ' + maxfiles + ' file(s)');
                        return;
                    }
                    plupload.each(files, function (file) {

                    });
                    up.refresh();
                    uploader_image_360.start();
                });
                uploader_image_360.bind('Error', function (up, err) {
                    document.getElementById('ere_image_360_errors_log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });
                uploader_image_360.bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);
                    if (response.success) {
                        $('.ere_image_360_url').val(response.full_image);
                        $('.ere_image_360_id').val(response.attachment_id);
                        var plugin_url = $('#ere_property_image_360_view').attr('data-plugin-url');
                        var _iframe = '<iframe width="100%" height="200" scrolling="no" allowfullscreen src="' + plugin_url + 'public/assets/packages/vr-view/index.html?image=' + response.full_image + '"></iframe>';
                        $('#ere_property_image_360_view').html(_iframe);
                    }
                });
            };
            ere_image_360();

            var ere_get_states_by_country = function () {
                var $this = $(".ere-property-country-ajax", css_class_wrap);
                if ($this.length) {
                    var selected_country = $this.val();
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: {
                            'action': 'ere_get_states_by_country_ajax',
                            'country': selected_country,
                            'type': 0,
                            'is_slug':'1'
                        },
                        beforeSend: function () {
                            $this.parent().children('.ere-loading').remove();
                            $this.parent().append('<span class="ere-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            $(".ere-property-state-ajax", css_class_wrap).html(response);
                            var val_selected = $(".ere-property-state-ajax", css_class_wrap).attr('data-selected');
                            if (typeof val_selected !== 'undefined') {
                                $(".ere-property-state-ajax", css_class_wrap).val(val_selected);
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
                if ($this.length) {
                    var selected_state = $this.val();
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: {
                            'action': 'ere_get_cities_by_state_ajax',
                            'state': selected_state,
                            'type': 0,
                            'is_slug':'1'
                        },
                        beforeSend: function () {
                            $this.parent().children('.ere-loading').remove();
                            $this.parent().append('<span class="ere-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            $(".ere-property-city-ajax", css_class_wrap).html(response);
                            var val_selected = $(".ere-property-city-ajax", css_class_wrap).attr('data-selected');
                            if (typeof val_selected !== 'undefined') {
                                $(".ere-property-city-ajax", css_class_wrap).val(val_selected);
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
                if ($this.length) {
                    var selected_city = $this.val();
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: {
                            'action': 'ere_get_neighborhoods_by_city_ajax',
                            'city': selected_city,
                            'type': 0,
                            'is_slug':'1'
                        },
                        beforeSend: function () {
                            $this.parent().children('.ere-loading').remove();
                            $this.parent().append('<span class="ere-loading"><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function (response) {
                            $(".ere-property-neighborhood-ajax", css_class_wrap).html(response);
                            var val_selected = $(".ere-property-neighborhood-ajax", css_class_wrap).attr('data-selected');
                            if (typeof val_selected !== 'undefined') {
                                $(".ere-property-neighborhood-ajax", css_class_wrap).val(val_selected);
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
            var ere_property_multi_step = $(".ere-property-multi-step");
            ere_property_multi_step.find('.ere-btn-next').on('click', function () {
                if(dtGlobals.isiOS) {
                    ere_property_gallery_images();
                    ere_property_attachments();
                    ere_image_360();
                    ere_floor_images("0");
                }
                if ($('#step-location').attr('aria-hidden') === 'false') {
                    if (typeof geocomplete !== 'undefined') {
                        geocomplete.trigger("geocode");
                    }
                }
            });
            ere_property_multi_step.find('.ere-btn-edit').on('click', function () {
                if(dtGlobals.isiOS) {
                    ere_property_gallery_images();
                    ere_property_attachments();
                    ere_image_360();
                    ere_floor_images("0");
                }
                if ($('#step-location').attr('aria-hidden') === 'false') {
                    if (typeof geocomplete !== 'undefined') {
                        geocomplete.trigger("geocode");
                    }
                }
            });
            var enable_filter_location=ere_property_vars.enable_filter_location;
            if(enable_filter_location=='1')
            {
                $('.ere-property-country-ajax', css_class_wrap).select2();
                $('.ere-property-state-ajax', css_class_wrap).select2();
                $('.ere-property-city-ajax', css_class_wrap).select2();
                $('.ere-property-neighborhood-ajax', css_class_wrap).select2();
            }
        }
    });
})(jQuery);