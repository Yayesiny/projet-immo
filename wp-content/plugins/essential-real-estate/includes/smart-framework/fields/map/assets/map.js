/**
 * map field script
 *
 * @package field
 * @version 1.0
 * @author  g5plus
 */

/**
 * Define class field
 */
var GSF_MapClass = function($container) {
	this.$container = $container;
};

(function($) {
	"use strict";

	/**
	 * Define class field prototype
	 */
	GSF_MapClass.prototype = {
		init: function() {
			this.$location = this.$container.find('.gsf-map-location-field');
			this.$address = this.$container.find('.gsf-map-address input');
			this.$findAddress = this.$container.find('.gsf-map-address button');
			this.$canvas = this.$container.find('.gsf-map-canvas');

			this.geocoder = new google.maps.Geocoder();

			this.bindMap();
			this.mapListener();
			this.findAddress();
			this.autoComplete();
		},

		/**
		 * Bind map on canvas
		 */
		bindMap: function () {
			var locationValue = this.$location.val(),
				js_options = this.$canvas.data('options');

			locationValue = locationValue ? locationValue.split(',') : [-33.868419, 151.193245];
			var latLng = new google.maps.LatLng(locationValue[0], locationValue[1]);

			var config_default = {
				center: latLng,
				zoom: 16,
				scrollwheel: false,
				streetViewControl: 0,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
					position: google.maps.ControlPosition.LEFT_BOTTOM
				}
			};
			if (js_options) {
				config_default = $.extend(config_default, js_options);
			}

			this.map = new google.maps.Map(this.$canvas[0], config_default);
			this.marker = new google.maps.Marker({
				position: latLng,
				map: this.map,
				draggable: true
			});
		},

		/**
		 * Map listener
		 */
		mapListener: function () {
			var field = this;

			// Event Click
			google.maps.event.addListener(field.map, 'click', function (event) {
				field.marker.setPosition(event.latLng);
				field.$location.val(event.latLng.lat() + ',' + event.latLng.lng());

				field.changeField();
			});

			// Event Drag
			google.maps.event.addListener(field.marker, 'drag', function (event) {
				field.$location.val(event.latLng.lat() + ',' + event.latLng.lng());

				field.changeField();
			});
		},

		findAddress: function () {
			var field = this;
			field.$findAddress.on('click', function () {
				var address = field.$address.val();
				field.geocoder.geocode({'address': address}, function (results, status) {
					if (status === google.maps.GeocoderStatus.OK) {
						field.map.setCenter(results[0].geometry.location);
						field.marker.setPosition(results[0].geometry.location);
						field.$location.val(results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng());

						field.changeField();
					}
				});
			});
			field.$address.on('keydown', function(event) {
				if (event.which === 13) {
					event.preventDefault();
					field.$findAddress.trigger('click');
				}
			});
		},

		autoComplete: function () {
			var field = this;
			field.$address.autocomplete({
				source: function (request, response) {
					field.geocoder.geocode({
						'address': request.term
					}, function (results) {
						response($.map(results, function (item) {
							return {
								label: item.formatted_address,
								value: item.formatted_address,
								latitude: item.geometry.location.lat(),
								longitude: item.geometry.location.lng()
							};
						}));
					});
				},

				select: function (event, ui) {
					var latLng = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
					field.map.setCenter(latLng);
					field.marker.setPosition(latLng);
					field.$location.val(ui.item.latitude + ',' + ui.item.longitude);

					field.changeField();
				}
			});
		},

		changeField: function() {
			var $field = this.$container.closest('.gsf-field'),
				value = GSFFieldsConfig.fields.getValue($field);
			GSFFieldsConfig.required.checkRequired($field, value);
		}
	};

	/**
	 * Define object field
	 */
	var GSF_MapObject = {
		init: function() {
			/**
			 * Init Fields after make clone template
			 */
			var $configWrapper = $('.gsf-meta-config-wrapper');
			$configWrapper = $configWrapper.length ? $configWrapper : $('body');

			$configWrapper.on('gsf_make_template_done', function() {
				$('.gsf-field-map-inner').each(function () {
					var field = new GSF_MapClass($(this));
					field.init();
				});
			});

			/**
			 * Init Clone Field after field cloned
			 */
			$('.gsf-field.gsf-field-map').on('gsf_add_clone_field', function(event){
				var $items = $(event.target).find('.gsf-field-map-inner');
				if ($items.length) {
					var field = new GSF_MapClass($items);
					field.init();
				}
			});
		}
	};

	/**
	 * Init Field when document ready
	 */
	$(document).ready(function() {
		GSF_MapObject.init();
		GSFFieldsConfig.fieldInstance.push(GSF_MapObject);

		/**
		 * Resize map when section clicked (process map not init when canvas invisible)
		 */
		$('.gsf-tab a').on('gsf-tab-clicked', function(event) {
			var sectionId = $(this).attr('href');
			$('.gsf-map-canvas', sectionId).each(function() {
				if ((typeof (google) != "undefined") && (typeof (google.maps) != "undefined") && (typeof (google.maps.event) != "undefined")) {
					google.maps.event.trigger(this,'resize');
				}
			});
		});
	});
})(jQuery);