(function ($) {
  $(document).ready(function() {
	
	// below script use for frontpage language select
	var str = window.location.pathname; 
     if($('body').hasClass('node-home_page')) {
	 $('div#block-dropdownlanguage li').each(function(){
	$(this).children('a').attr('href', $(this).children('a').attr('href')+'/home');
	});
	   
   }   
  
  //language select end
  $( "nav ul.tabs.primary li:nth-child(2)" ).hide();
	slickInit();
		//});
	_hpe_service.init({ 
		stickyHeader: false,
			header: {
				el: "#hpe_global_header"
			},
			footer: {
				el: "#hpe_global_footer"
			},
			analytics: {
				enabled: false
			}
		});

  });

  function slickInit() {
	if ($('body').has('[class^=slick-container-],[class*= slick-container-]')) {
		let _this = $(this);
		$('[class^=slick-container-],[class*= slick-container-]').not('.w3-modal [class^=slick-container-], .w3-modal [class*= slick-container-]').find('[class^=js-view-dom-id]').each(function() {
			$(this).addClass('w3-image-container slick-container-1').attr('data-slick','{"autoplay":true, "autoplaySpeed":3000, "arrows":true,"dots":true}');
			$(this).slick({
				speed: 500,
				prevArrow: '<div class="w3-left-arrow w3-text-grey w3-hover-text-green w3-jumbo">&lsaquo;</div>',
				nextArrow: '<div class="w3-right-arrow w3-text-grey w3-hover-text-green w3-jumbo">&rsaquo;</div>',
			});
		});
	}
}

function modal_tab(e){
	var tab = e.currentTarget.attributes.name.nodeValue;
	if($('.w3-modal [id^="news-"]').hasClass('w3-show')){
		$('.w3-modal [name^="news-"]').removeClass('w3-text-green');
		$('[id^="news-"]').removeClass('w3-show');
		$(e.currentTarget).addClass('w3-text-green');
	}
	$('#'+tab).addClass('w3-show');	
		
}

$(window).on('load',function() {	
	$('.w3-preloader').fadeOut();
 $('nav.tabs ul').addClass('tabs primary');	
 
 //$('nav.tabs ul').addClass('tabs primary');	
});
})(jQuery);


