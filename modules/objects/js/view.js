$(document).ready( function() {
	$("a[rel^='prettyPhoto']").magnificPopup({
		type:'image',
		zoom: {
			enabled: true
		},
		gallery:{
			enabled:true
  		},
  		image: {
  			titleSrc: 'title'
  		}
  	});

  	$(".photo-gallery").each( function() {
		var attr = $(this).attr('rel');
		$("div[rel='" + attr + "']").magnificPopup({
			type:'image',
			delegate: 'a',
			zoom: {
				enabled: true
			},
			gallery:{
				enabled:true
	  		},
	  		image: {
	  			titleSrc: 'title'
	  		}
	  	});
  	});

  	$('.popup-movie').magnificPopup({
		disableOn: 700,
		type: 'iframe',
		mainClass: 'mfp-fade',
		removalDelay: 160,
		preloader: false,
		fixedContentPos: false
    });
});
