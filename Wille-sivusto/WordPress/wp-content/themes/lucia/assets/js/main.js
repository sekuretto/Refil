jQuery(document).ready(function($) {
	
	$(function(){
		 
		  $(".menu-item > a").mouseover(function(){
			  $(this).parent(".menu-item").addClass("menu-item-hovered");
		  });
		  $(".menu-item").mouseleave(function(){
			  $(this).removeClass("menu-item-hovered");
		  })
		  $(".lq-widget-news-wrap.owl-carousel").owlCarousel({
			  items: 1,
			  autoplay: true,
			  animateOut: 'fadeOut',
			  loop: true
		  });
		  $(".lq-search-label").click(function(){
			  $(".lq-search-wrap").css({"visibility":"visible","opacity":"1"}).toggle();
		  })
		  $(document).bind("click",function(e){   
			  if($(e.target).closest(".lq-search-wrap").length == 0 && $(e.target).closest(".lq-search-label").length == 0){
				  $(".lq-search-wrap").css({"visibility":"hidden","opacity":"0"});
			  }
		  })
		  $(".lq-shopping-cart-label").mouseover(function(){
			  $(".lq-shopping-cart-wrap").css({"visibility":"visible","opacity":"1"});
		  })
		  $(".lq-microwidget-shopping-cart").mouseleave(function(){
			  $(".lq-shopping-cart-wrap").css({"visibility":"hidden","opacity":"0"});
		  });
		  $(".lq-toggle-icon").click(function(){
			  $(".lq-mobile-drawer-header").toggle();
		  })
		  $(".lq-mobile-main-nav .menu-expand").click(function(){
			  $(this).siblings(".sub-menu").toggle();
		  })
	  });
	  
	  
	  var stickyTop = function() {

        var stickyTop;
        if ($("body.admin-bar").length) {

            if ($(window).width() < 765) {
                stickyTop = 46;
            } else {
                stickyTop = 32;
            }
        } else {
            stickyTop = 0;
        }
		
        return stickyTop;
    }

    $('.page_item_has_children').addClass('menu-item-has-children');
	
	var page_min_height = $(window).height() - $('.site-footer').outerHeight()- stickyTop();
	
	if($('.header-wrap').length)
		page_min_height = page_min_height - $('.header-wrap').outerHeight();
		
	if($('.page-title-bar').length)
		page_min_height = page_min_height - $('.page-title-bar').outerHeight();
		
	$('.page-wrap').css({'min-height':page_min_height});
	
	function onScroll(event){
    var scrollPos = $(document).scrollTop()+$(".lq-header").height();
	
	$('.lq-nav-main a[href^="#"]').each(function () {
        var currLink = $(this);
		var refElement = $(currLink.attr("href"));
		if(refElement.length){
        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
            $('.lq-nav-main li').removeClass("active");
            currLink.parent('li').addClass("active");
        }else{
            currLink.parent('li').removeClass("active");
        }
		}
    });
	
	$('.lq-nav-left a[href^="#"]').each(function () {
        var currLink = $(this);
		var refElement = $(currLink.attr("href"));
		if(refElement.length){
        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
            $('.lq-nav-left li').removeClass("active");
            currLink.parent('li').addClass("active");
        }else{
            currLink.parent('li').removeClass("active");
        }
		}
    });
	}

    function luciaFxdHeader() {
        var stickyHeight = stickyTop();

        var headerPosition = $(document).scrollTop();
		
		if( headerPosition > 200 )
			$('.back-to-top').fadeIn();
		else
			$('.back-to-top').fadeOut();

        var headerHeight = $(".lq-header").height();
		
		if ($(".header-image").length) {
			headerHeight += $(".header-image").outerHeight()-69;
		}
		
        if (headerPosition < headerHeight){
			$(".lq-fixed-header-wrap").hide();
			$(".lq-top-bar").show();
		}else{
			$(".lq-fixed-header-wrap").show().css({ 'top': stickyHeight });
			$(".lq-top-bar").hide();
		}
    }
	
    $(window).scroll(function() {
		if ($(window).width() > 767) {
        luciaFxdHeader();
		onScroll();
		}
    })
	
	$(window).resize(function(){
       	 $('.lq-mobile-drawer-header').hide();
	});
	
    /* smooth scroll*/
    $(document).on('click', "a.scroll,.site-nav a[href^='#'],.lq-main-nav a[href^='#']", function(e) {

        var selectorHeight = 0;
        if (!$('.fxd-header').length)
            selectorHeight = $('.lq-main-header').outerHeight();
        else
            selectorHeight = $('.fxd-header').outerHeight();

        e.preventDefault();
        var id = $(this).attr('href');

        if (typeof $(id).offset() !== 'undefined') {
            var goTo = $(id).offset().top - selectorHeight - stickyTop() + 1;
            $("html, body").animate({ scrollTop: goTo }, 500);
        }
    });
	
	$('#back-to-top, .back-to-top').click(function() {
        $('html, body').animate({ scrollTop: 0 }, '800');
        return false;
    });
	
	});

jQuery( function( $ ) {
	// Add space for Elementor Menu Anchor link
	var selectorHeight = $('.lq-fixed-header-wrap').height(); 		
	selectorHeight = selectorHeight - 1;
	
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addFilter( 'frontend/handlers/menu_anchor/scroll_top_distance', function( scrollTop ) {
			return scrollTop - selectorHeight ;
		} );
	} );
} );