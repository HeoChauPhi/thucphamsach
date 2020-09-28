/*jslint browser: true*/
/*global $, jQuery, Modernizr, enquire, audiojs*/

(function($) {
  var iScrollPos = 0;

  // Ajax Pagination by taxonomy term
  var pagination_ajax_loadmore = function() {
    var parent_ajax = $(this).parents('.ajax-loadmore-pagination');

    if ( parent_ajax.hasClass('pager-unvisible') ) {
      return false;
    }

    var post_type = parent_ajax.find('input[name="post_type"]').val();
    var taxonomy = parent_ajax.find('input[name="taxonomy"]').val();
    var term_id = parent_ajax.find('input[name="term_id"]').val();
    var current_posts_id = parent_ajax.find('input[name="current_posts_id"]').val();
    var more_items = parent_ajax.find('input[name="more_items"]').val();
    var max_items = parent_ajax.find('input[name="max_items"]').val();
    var list_result = parent_ajax.find('input[name="list_result"]').val();

    $.ajax({
      type : "post",
      dataType : "json",
      url : themeAjax.ajaxurl,
      data : {
        action: "pager_loadmore_by_term",
        post_type: post_type,
        taxonomy: taxonomy,
        term_id: term_id,
        current_posts_id: current_posts_id,
        max_items: max_items,
        more_items: more_items,
        list_result: list_result
      },
      beforeSend: function() {
        //parent_views.find('.load-views').empty();
        parent_ajax.find('.ajax-loadmore-pagination-inner').append('<span class="ajax-load-icon"></span>');
      },
      success: function(response) {
        parent_ajax.find('.ajax-load-icon').remove();
        $(list_result).append(response.markup);
        parent_ajax.find('input[name="current_posts_id"]').val(response.post_ids);
        parent_ajax.addClass(response.pager_class);
        //console.log(response.post_ids);
      },
      error: function(response) {
        console.log('error');
      }
    });

    return false;
  }

  // Swich when web loading on mobile or small device
  function mobileMenu() {
    $(this).toggleClass('open');
    $('body').toggleClass('cover-overflow');
    $('.main-menu').toggleClass('menu-show');
  }

  function mobileMenuClose() {
    $('body').removeClass('cover-overflow');
    $('.main-menu').removeClass('menu-show');
    $('.js-toogle--menu').removeClass('open');
  }

  // Back to Top
  function backToTopShow() {
    var height_show = $('.header-full').outerHeight(true);

    if ($(this).scrollTop() > height_show) {
      $('.js-back-top').addClass('btn-show');
    } else {
      $('.js-back-top').removeClass('btn-show');
    }
  }

  function backToTop() {
    $("html, body").animate({ scrollTop: 0 }, 600);
  }

  // Scroll Down
  function scrollDown() {
    var height_scroll = $('.header-full').outerHeight(true);

    $("html, body").animate({ scrollTop: height_scroll }, 600);
  }

  // Counter up
  function counterUp() {
    $('.js-count-up').counterUp({
      delay: 5,
      time: 500
    });
  }

  // Change Style Quantity input
  function productQiantity() {
    // This button will increment the value
    $('[data-quantity="plus"]').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('data-field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If is not undefined
        if (!isNaN(currentVal) && currentVal > 0) {
            // Increment
            $('input[name='+fieldName+']').val(currentVal + 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(1);
        }
    });
    // This button will decrement the value till 0
    $('[data-quantity="minus"]').click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).attr('data-field');
        // Get its current value
        var currentVal = parseInt($('input[name='+fieldName+']').val());
        // If it isn't undefined or its greater than 0
        if (!isNaN(currentVal) && currentVal > 1) {
            // Decrement one
            $('input[name='+fieldName+']').val(currentVal - 1);
        } else {
            // Otherwise put a 0 there
            $('input[name='+fieldName+']').val(1);
        }
    });
  }

  function quantityStyle($name) {
    $($name).wrap('<div class="group-quantity" />');
    $($name).before('<span data-quantity="minus" class="quantity-minus qty-control" data-field="quantity">-</span>');
    $($name).after('<span data-quantity="plus" class="quantity-plus qty-control" data-field="quantity">+</span>');
    productQiantity();
  }

  // Slick slider override product detail
  function productGallerySlider() {
    $('.single-product-details .wpgis-slider-for').slick('slickSetOption', {
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      fade: true,
      //adaptiveHeight: true,
      infinite: true
    }, true);
    $('.single-product-details .wpgis-slider-nav').slick('slickSetOption', {
      slidesToShow: 3,
      slidesToScroll: 1,
      dots: false,
      centerMode: true,
      focusOnSelect: true,
      infinite: true,
      arrows: false
    }, true);
    $('.single-product-details .wpgis-slider-for, .single-product-details .wpgis-slider-nav').slick('refresh');
  }

  // Function filter when select change
  function filter_store() {
    var filters = {};
    var $filterCount = $('.store-count');

    var $table = $('.stores-result').isotope({
      layoutMode: 'vertical',
      itemSelector: '.store-item'
    });

    var iso = $table.data('isotope');

    $('.stores-filter-items').on( 'change', function( event ) {
      var $select = $( event.target );
      // get group key
      var filterGroup = $select.attr('value-group');
      // set filter for group
      filters[ filterGroup ] = event.target.value;
      // combine filters
      var filterValue = concatValues( filters );
      // set filter for Isotope
      $table.isotope({ filter: filterValue });
      $filterCount.text( iso.filteredItems.length );
    });
  }

  function concatValues( obj ) {
    var value = '';
    for ( var prop in obj ) {
      value += obj[ prop ];
    }
    return value;
  }

  function Deg2Rad(deg) {
    return deg * Math.PI / 180;
  }

  function PythagorasEquirectangular(lat1, lon1, lat2, lon2) {
    lat1 = Deg2Rad(lat1);
    lat2 = Deg2Rad(lat2);
    lon1 = Deg2Rad(lon1);
    lon2 = Deg2Rad(lon2);
    var R = 6371; // km
    var x = (lon2 - lon1) * Math.cos((lat1 + lat2) / 2);
    var y = (lat2 - lat1);
    var d = Math.sqrt(x * x + y * y) * R;
    return d;
  }
  
  /* ==================================================================
   *
   * Loading Jquery
   *
   ================================================================== */
  $(document).ready(function() {
    // Call to function
    $('.js-toogle--menu').on('click', mobileMenu);
    $('.js-close--menu').on('click', mobileMenuClose);
    //$('.js-back-top').on('click', backToTop);
    $('.js-scroll-down').on('click', scrollDown);

    $('.block-post-slider').slick({
      autoplay: true,
      autoplaySpeed: 3000,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      adaptiveHeight: true
    });

    $('.block-products-slide').slick({
      dots: false,
      infinite: true,
      speed: 300,
      slidesToShow: 5,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            infinite: true,
            dots: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });

    $('.product-cat-filter .filter-list').slick({
      arrows: true,
      slidesToShow: 1,
      dots: false,
      focusOnSelect: false,
      infinite: false,
      arrows: false,
      variableWidth: true,
      draggable: false
    });

    $('.block-other-post .other-post-item').matchHeight({property: 'min-height'});
    $('.block-products-slide .product-slide-item-inner').matchHeight({property: 'min-height'});
    quantityStyle('.cart .quantity input[name="quantity"]');
    productGallerySlider();
    $('.ajax-loadmore-pagination a').on('click', pagination_ajax_loadmore);
    $('.fancybox-viewmap').fancybox();
    filter_store();
  });

  $(window).scroll(function() {
    backToTopShow();
  });

  $(window).load(function() {
    // Call to function
  });

  $(window).resize(function() {
    // Call to function
  });

})(jQuery);