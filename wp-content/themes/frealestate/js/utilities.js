(function($) {

	"use strict";

	function frealestate_mainMenuClear() {

		if ( $(window).width() >= 800 ) {
		
			$('#navmain > div > ul > li:has("ul")').removeClass('level-one-sub-menu');
			$('#navmain > div > ul li ul li:has("ul")').removeClass('level-two-sub-menu');										
		}

		if ( $('ul:first-child', $('#navmain > div') ).is( ":visible" ) ) {

			$('ul:first-child', $('#navmain > div') ).css('display', '');
		}
	}

	function frealestate_mainMenuInit() {

		if ( $(window).width() >= 800 ) {
		
			$('#navmain > div > ul > li:has("ul")').addClass('level-one-sub-menu');
			$('#navmain > div > ul li ul li:has("ul")').addClass('level-two-sub-menu');										
		}
	}

	function frealestate_init_loading_effects() {

	    $('#header-logo').addClass("animations-hidden").viewportChecker({
            classToAdd: 'animated bounce',
            offset: 1
          });

	    

	    $('#page-header, article h1').addClass("animations-hidden").viewportChecker({
	            classToAdd: 'animated bounceInUp',
	            offset: 1
	          });

	    $('#main-content-wrapper h2, #main-content-wrapper h3')
	            .addClass("animations-hidden").viewportChecker({
	            classToAdd: 'animated bounceInUp',
	            offset: 1
	          });

	    $('img').addClass("animations-hidden").viewportChecker({
	            classToAdd: 'animated zoomIn',
	            offset: 1
	          });

	    $('#sidebar').addClass("animations-hidden").viewportChecker({
	            classToAdd: 'animated bounceInRight',
	            offset: 1
	          });

	    $('.before-content, .after-content').addClass("animations-hidden").viewportChecker({
	            classToAdd: 'animated bounce',
	            offset: 1
	          });

	    $('article, article p, article li')
        .addClass("animations-hidden").viewportChecker({
            classToAdd: 'animated zoomIn',
            offset: 1
          })

	    $('#footer-main h1, #footer-main h2, #footer-main h3')
	        .addClass("animations-hidden").viewportChecker({
	            classToAdd: 'animated bounceInUp',
	            offset: 1
	          });

	    $('#footer-main p, #footer-main ul, #footer-main li, .footer-title, .col3a, .col3b, .col3c')
        .addClass("animations-hidden").viewportChecker({
            classToAdd: 'animated zoomIn',
            offset: 1
          });

    $('.footer-social-widget')
        .addClass("animations-hidden").viewportChecker({
            classToAdd: 'animated rubberBand',
            offset: 1
          });

    $('#footer-menu')
        .addClass("animations-hidden").viewportChecker({
            classToAdd: 'animated bounceInDown',
            offset: 1
          });
	}

	$( document ).ready(function() {

		$('#header-spacer').height( $('#header-main-fixed').height() );

		if (frealestate_options && frealestate_options.loading_effect) {
			
			frealestate_init_loading_effects();
		}

		if (jQuery('#wpadminbar').length > 0) {
		
			jQuery('#header-main-fixed').css('top', jQuery('#wpadminbar').height() + 'px');
			jQuery('#wpadminbar').css('position', 'fixed');
		}

		frealestate_mainMenuInit();

		if ( $(window).width() < 800 ) {
		
			$('#navmain > div > ul > li').each(
		       function() {
		         if ($(this).find('> ul.sub-menu').length > 0) {

		           $(this).prepend('<span class="sub-menu-item-toggle"></span>');
		         }
		       }
		     );

		   $('.sub-menu-item-toggle').on('click', function(e) {

		     e.stopPropagation();

		     var subMenu = $(this).parent().find('> ul.sub-menu');

		     $('#navmain ul ul.sub-menu').not(subMenu).hide();
		     $(this).toggleClass('sub-menu-item-toggle-expanded');
		     subMenu.toggle();
		     subMenu.find('ul.sub-menu').toggle();
		   });

		}

		$('#navmain > div').on('click', function(e) {

			e.stopPropagation();

			// toggle main menu
			if ( $(window).width() < 800 ) {

				var parentOffset = $(this).parent().offset(); 
				
				var relY = e.pageY - parentOffset.top;
			
				if (relY < 36) {
				
					$('ul:first-child', this).toggle(400).parent().toggleClass('mobile-menu-expanded');
				}
			}
		});

		// re-init main menu on screen resize
		$(window).resize(function () {
	       
	    	frealestate_mainMenuClear();
	    	frealestate_mainMenuInit();
		});

		$('.scrollup').click(function () {
			
			$("html, body").animate({
				  scrollTop: 0
			  }, 600);

			return false;
		});

		var unslider = $( '.slider' ).unslider({
						speed: 500,               //  The speed to animate each slide (in milliseconds)
						delay: 3000,              //  The delay between slide animations (in milliseconds)
						complete: function() {},  //  A function that gets called after every slide animation
						keys: true,               //  Enable keyboard (left, right) arrow shortcuts
						dots: true,               //  Display dot navigation
						fluid: true              //  Support responsive design. May break non-responsive designs
					});
	
		$('.unslider-arrow').click(function() {
				var fn = this.className.split(' ')[1];
			
				//  Either do unslider.data('unslider').next() or .prev() depending on the className
				unslider.data('unslider')[fn]();
				
				unslider.data('unslider').stop();
		});

		$(window).scroll(function () {

			var fadeInScrollUp = $(this).scrollTop() > 100;

			if (fadeInScrollUp) {

				$('.scrollup').fadeIn();

			} else {

				$('.scrollup').fadeOut();

			}
		});
	});

})(jQuery);
