$=jQuery
jQuery(document).ready(function () {


    // Header Nav Js
     jQuery('.menu-top-menu-container').meanmenu({
    meanMenuContainer: '.main-navigation',
    meanScreenWidth:"767",
    meanRevealPosition: "right",
  });
     jQuery("a.fancybox, .gallery .gallery-item .gallery-icon a, a[href$='.jpg'],a[href$='.jpeg'],a[href$='.png'],a[href$='.gif']").attr('rel','postsgallery').fancybox({ 
    
   }
  );
   

       /* back-to-top button*/

     $('.back-to-top').hide();
     $('.back-to-top').on("click",function(e) {
     e.preventDefault();
     $('html, body').animate({ scrollTop: 0 }, 'slow');
    });

    $(window).scroll(function(){
      var scrollheight =400;
      if( $(window).scrollTop() > scrollheight ) {
           $('.back-to-top').fadeIn();

          }
        else {
              $('.back-to-top').fadeOut();
             }
     });

           // slider

           var owllogo = $("#owl-slider-demo");

              owllogo.owlCarousel({
                  items:1,
                  loop:true,
                  nav:false,
                  dots:false,
                  smartSpeed:900,
                  autoplay:true,
                  autoplayTimeout:5000,
                  fallbackEasing: 'easing',
                  transitionStyle : "fade",
                  autoplayHoverPause:true,
                  animateOut: 'fadeOut'
              });

              var owl = $(".property-slider");
              owl.owlCarousel({
              items:1,
              loop:true,
              nav:true,
              autoplay:true,
              autoplayTimeout:4000,
              fallbackEasing: 'easing',
              transitionStyle : "fade",
              dots:false,
              autoplayHoverPause:true,
              responsive:{
                  0:{
                      items:1
                  },
                  580:{
                      items:1
                  },
                  1000:{
                      items:1
                  }
              }
              
              });

              var owl = $("#agent-slider");
              owl.owlCarousel({
              items:1,
              loop:true,
              nav:true,
              autoplay:true,
              autoplayTimeout:4000,
              fallbackEasing: 'easing',
              transitionStyle : "fade",
              dots:false,
              autoplayHoverPause:true,
              responsive:{
                  0:{
                      items:1
                  },
                  480:{
                      items:2
                  },
                  580:{
                      items:3
                  },
                  1000:{
                      items:4
                  }
              }
              
              });

              var owl = $("#city-slider");
              owl.owlCarousel({
              items:3,
              loop:true,
              nav:false,
              autoplay:true,
              autoplayTimeout:4000,
              fallbackEasing: 'easing',
              transitionStyle : "fade",
              dots:true,
              autoplayHoverPause:true,
              responsive:{
                  0:{
                      items:1
                  },
                  480:{
                      items:2
                  },
                  580:{
                      items:2
                  },
                  1000:{
                      items:3
                  }
              }
              
              });

             var owl = $("#clients-slider");
              owl.owlCarousel({
              items:3,
              loop:true,
              nav:false,
              dots:false,
              smartSpeed:900,
              autoplay:true,
              autoplayTimeout:1000,
              fallbackEasing: 'easing',
              transitionStyle : "fade",
              autoplayHoverPause:true,
              responsive:{
                  0:{
                      items:2
                  },
                  580:{
                      items:4
                  },
                  1000:{
                      items:6
                  }
              }
              
              });

              var owl = $("#testimonial-slider");
              owl.owlCarousel({
              items:1,
              loop:true,
              nav:false,
              autoplay:true,
              autoplayTimeout:4000,
              fallbackEasing: 'easing',
              transitionStyle : "fade",
              dots:true,
              autoplayHoverPause:true,
              
              });

              $('.play').on('click', function() {
                owl.trigger('play.owl.autoplay', [1000])
              })
              $('.stop').on('click', function() {
                owl.trigger('stop.owl.autoplay')
              })

              var property_slide_show = $('#property-slide-show').owlCarousel({
                items: 1,
                loop: false,
                center: true,
                margin: 10,
                callbacks: true,
                dots: false,
                URLhashListener: true,
                autoplayHoverPause: true,
                startPosition: 'URLHash'

              });

              property_slide_show.on('translated.owl.carousel', function() {
                  var activeIndex = $('#property-slide-show').find('.owl-item.center').index();
                  $('.slider-thumbnail-img').removeClass('active');
                  $('.slider-thumbnail-img').eq(activeIndex).addClass('active');
                  
              });

            /* for counter */

    function count($this){
      var current = parseInt($this.html(), 10);
      current = current + 1; /* Where 1 is increment */

      $this.html(++current);
      if(current > $this.data('count')){
        $this.html($this.data('count'));
      } else {
        setTimeout(function(){count($this)}, 50);
      }
    }

    jQuery(".start-count").each(function() {
      jQuery(this).data('count', parseInt(jQuery(this).html(), 10));
      jQuery(this).html('0');
      count(jQuery(this));
    });

  //      parallax
     $(function(){
        $.stellar({
          horizontalScrolling: false,
          verticalOffset: 40
        });
      });


     // Isotop For Gallery 

     $(".portfolio-gallery-menu ul li ").click(function() {
        $(".portfolio-gallery-menu ul li.active").removeClass("active"), $(this).addClass("active")
    })

      /*isotope*/
      var $container = $('.portfolio-gallery-demo').isotope({
          itemSelector : '.single-gallery',
          columnWidth: '.single-gallery',
          isFitWidth: true
      });
      $container.isotope({ filter: '*' });

          // filter items on button click
      $('.portfolio-gallery-menu ul').on( 'click', 'li', function() {
          var filterValue = $(this).attr('data-filter');
          $container.isotope({ filter: filterValue });
      });
        
      });



