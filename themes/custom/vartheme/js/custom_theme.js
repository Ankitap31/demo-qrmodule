(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals.
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function decode(s) {
		if (config.raw) {
			return s;
		}
		return decodeURIComponent(s.replace(pluses, ' '));
	}

	function decodeAndParse(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		s = decode(s);

		try {
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	var config = $.cookie = function (key, value, options) {

		// Write
		if (value !== undefined) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setDate(t.getDate() + days);
			}

			value = config.json ? JSON.stringify(value) : String(value);

			return (document.cookie = [
				config.raw ? key : encodeURIComponent(key),
				'=',
				config.raw ? value : encodeURIComponent(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read
		var cookies = document.cookie.split('; ');
		var result = key ? undefined : {};
		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				result = decodeAndParse(cookie);
				break;
			}

			if (!key) {
				result[name] = decodeAndParse(cookie);
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) !== undefined) {
			// Must not alter options, thus extending a fresh object...
			$.cookie(key, '', $.extend({}, options, { expires: -1 }));
			return true;
		}
		return false;
	};

}));

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
		var showChar = 150;
		var ellipsestext = "...";
		var moretext = "View More";
		var lesstext = "View Less";
		$('.more p').each(function() {
			var content = $(this).html();
			if(content.length > showChar) {
	
				var c = content.substr(0, showChar);
				var h = content.substr(showChar-1, content.length - showChar);
				var html = c + '<span class="moreelipses">'+ellipsestext+'</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
				$(this).html(html);
			}
	
		});
	
		$(".morelink").click(function(){
			if($(this).hasClass("less")) {
				$(this).removeClass("less");
				$(this).html(moretext);
			} else {
				$(this).addClass("less");
				$(this).html(lesstext);
			}
			$(this).parent().prev().toggle();
			$(this).prev().toggle();
			return false;
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
		var countrynid = $(".country-switcher").find("option:last-child").val();
		var country_id = countrynid.split(',');
		var nodeid = country_id[1];	
		$.ajax ({
				url: '/hrcms/get_default_country/'+ nodeid,
				success: function (data) {
				 $('.country-switcher').val(data);
				}
		})
			
			function notityAlerts(){
				if ($(".dot").css("display") == 'block') {
					$.ajax({
						url: '/hrcms/get_userprofile_status',
						success: function (data) {
							if (data == 1){
								$("#block-views-block-push-notification-block-1").css("display", "block");
							}
						}
					})
				}
			}
			notityAlerts();	
	$('.w3-preloader').fadeOut();
 $('nav.tabs ul').addClass('tabs primary');	
 
 //$('nav.tabs ul').addClass('tabs primary');	

});

document.onreadystatechange = function () {
    if (document.readyState !== "complete") {
        if (document.querySelector('.original-content-sidebar') !== null && document.querySelector('.page-pre-loader-layout-sidebar') !== null){
          document.querySelector(".original-content-sidebar").style.visibility = "hidden";
          document.querySelector(".page-pre-loader-layout-sidebar").style.visibility = "visible";
        }
        if (document.querySelector('.original-content-language-switcher') !== null && document.querySelector('.page-pre-loader-layout-language-switcher') !== null) {
          document.querySelector(".original-content-language-switcher").style.visibility = "hidden";
          document.querySelector(".page-pre-loader-layout-language-switcher").style.visibility = "visible";
        }
        if (document.querySelector('.original-content-maincontent') !== null && document.querySelector('.page-pre-loader-layout-maincontent') !== null) {
        document.querySelector(".original-content-maincontent").style.visibility = "hidden";
        document.querySelector(".page-pre-loader-layout-maincontent").style.visibility = "visible"; 
        }
    } else { 
        if (document.querySelector('.original-content-sidebar') !== null && document.querySelector('.original-content-sidebar') !== null) {
          document.querySelector(".original-content-sidebar").style.visibility = "visible";
          document.querySelector(".page-pre-loader-layout-sidebar").style.display = "none";
        }
        if (document.querySelector('.original-content-language-switcher') !== null && document.querySelector('.page-pre-loader-layout-language-switcher') !== null) {
          document.querySelector(".original-content-language-switcher").style.visibility = "visible";
          document.querySelector(".page-pre-loader-layout-language-switcher").style.display = "none";
        }
        if (document.querySelector('.original-content-maincontent') !== null && document.querySelector('.page-pre-loader-layout-maincontent') !== null) {     
        document.querySelector(".original-content-maincontent").style.visibility = "visible";
        document.querySelector(".page-pre-loader-layout-maincontent").style.display = "none";
        }
    }
  };

})(jQuery);

