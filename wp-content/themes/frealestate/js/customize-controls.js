/**
 * "Pro" theme section examples for the customizer by Justin Tadlock Copyright 2016
 * Licensed under the GNU GPL, version 2 or later.
 * Source: https://github.com/justintadlock/trt-customizer-pro
 */

( function( api ) {

	'use strict';

	// Extends our custom "example-1" section.
	api.sectionConstructor['frealestate'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
