$(function() {
	$('a[href*="#"]:not([href="#"])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
				$('html, body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}

		}
	});
});

$(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
        $('#btn-up').addClass("fixed-position").fadeIn();
    } else {
        $('#btn-up').removeClass("fixed-position").fadeOut();
    }
});
$(document).ready(function() {
	$("#slider-top").carousel();
	$("#slider-partners").carousel({ interval: 3000, pause: 'hover' });
	$("select").selectpicker();
	$('input[type="checkbox"]').checkboxX({ threeState:false, size:'md', enclosedLabel:true });
	$.hideClickView();
	if($("#google-map").length > 0) {
		$.googleMap(false);
	}
	$.sendMessage();

	$("#panel-status-bar").hide();
	$("#panel-status-bar").show(500);
	if($("#panel-status-bar").hasClass("success") == true) {
		setTimeout(function() {
    		$("#panel-status-bar").fadeOut();
		}, 5000);
	}
	$("#panel-status-bar .panel-close").click( function() {
		$("#panel-status-bar").fadeOut();
	});

	$("#address").blur( function() {
		$.googleMapLocate( $(this).val(),$("#city").val() );
	});
	$("#city").blur( function() {
		$.googleMapLocate( $("#address").val(), $(this).val() );
	});
	$('[data-toggle="popover"]').popover();
	$('[data-toggle="tooltip"]').tooltip({ html: true, container: 'body', placement: 'auto' });

	$("*[maxlength]").keyup( function() {
		var id = $(this).attr('id');
		var length = $(this).val().length;
		var maxlength = $("#" + id).prop("maxlength");
		var result = parseInt(maxlength) - parseInt(length);
		if( result > 20) {
			$("#" + id + "_label").html('pozostało ' + result + ' znaków do wprowadzenia');
		} else {
			$("#" + id + "_label").html('<b class="text-danger">pozostało ' + result + ' znaków do wprowadzenia</b>');
		}
	});

	if( $("#promotion-horizontal").data('type') == "SLIDER") {

		$("#promotion-horizontal").carousel({
			interval: 5000,
			pause: 'hover'
		});

		
	}
	
	$('.confirm').click( function( event ) {
		if( alertify ) {
			var returnLink = $(this).attr("href");
			
			alertify.confirm("Tej operacji nie można cofnąć, czy jesteś pewny(a) ?", function (e) {
				if (e) {
					document.location.href = returnLink;
				} else {
					return false;
				}
			});
		} else {
			alert('Musisz ustawić Kernel::$Alertify = true');
		}
		return false;
	});
	
	if( typeof $.fn.datetimepicker == "function" ) {
		$(".dataPicker").datetimepicker({
		    lang: 'pl',
		    i18n:{
				de:{
					months:[
					'Styczeń','Luty','Marzec','Kwiecień',
					'Maj','Czerwiec','Lipiec','Sierpień',
					'Wrzesień','Październik','Listopad','Grudzień'
					],
					dayOfWeek:[
					"Nd", "Pn", "Wt", "Śr", 
					"Cz", "Pt", "So",
					]
				}
			},
			timepicker: false,
			format:'Y-m-d'
	    });
	    $(".dataPickerTime").datetimepicker({
		    lang: 'pl',
		    i18n:{
				de:{
					months:[
					'Styczeń','Luty','Marzec','Kwiecień',
					'Maj','Czerwiec','Lipiec','Sierpień',
					'Wrzesień','Październik','Listopad','Grudzień'
					],
					dayOfWeek:[
					"Nd", "Pn", "Wt", "Śr", 
					"Cz", "Pt", "So",
					]
				}
			},
			timepicker: true,
			format:'Y-m-d H:i:00'
	    });
	}

	if (typeof $.fn.prettyPhoto == "function") {
		$("a[rel^='prettyPhoto']").prettyPhoto({
			animation_speed: 'fast', /* fast/slow/normal */
			slideshow: 5000, /* false OR interval time in ms */
			autoplay_slideshow: false, /* true/false */
			opacity: 0.80, /* Value between 0 and 1 */
			show_title: true, /* true/false */
			allow_resize: true, /* Resize the photos bigger than viewport. true/false */
			default_width: 500,
			default_height: 344,
			counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
			theme: 'light_square', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
			horizontal_padding: 20, /* The padding on each side of the picture */
			hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
			wmode: 'opaque', /* Set the flash wmode attribute */
			autoplay: true, /* Automatically start videos: True/False */
			modal: true, /* If set to true, only the close button will close the window */
			deeplinking: false, /* Allow prettyPhoto to update the url to enable deeplinking. */
			overlay_gallery: true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
			keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
			ie6_fallback: true,
			social_tools: false
		});
	}
});

$.sendMessage = function() {
	$("#blockContactForm").hide();
	$("#sendMessage").click( function() {
		$("#blockContactForm .alert").hide();
		$("#blockContactForm").fadeIn();
		$("#blockContactForm form").show();
		$("#contact_name").val("");
		$("#contact_email").val("");
		$("#contact_phone").val("");
		$("#contact_msg").val("");
		window.location.replace("#blockContactForm");

		$("#btnSendMessage").click( function() {
			var token = $("input[name=token]").val();
			var name = $("#contact_name").val();
			var email = $("#contact_email").val();
			var phone = $("#contact_phone").val();
			var msg = $("#contact_msg").val();

			$.ajax({
				url: "/ajax/objects/sendMessage/",
				method: "get",
				data : { token: token, name: name, email: email, phone: phone, msg: msg },
				beforeSend: function() {
					$("body").loading({ message: 'Proszę czekać...<br/>Wysyłanie wiadomości<br/><br/><i class="fa fa-circle-o-notch fa-spin fa-2x"></i>' });
				},
				success: function(data) {
					data = data.split("\n");

					if(data[0] == "TOKEN_ERROR") {
						$("#blockContactForm .alert-danger").empty().html( data[1] ).fadeIn();
					}
					if(data[0] == "OK") {
						$("#blockContactForm form").fadeOut();
						$("#blockContactForm .alert-success").empty().html( data[1] ).fadeIn();
					}

					if(data[0] == "ERROR") {
						$("#blockContactForm .alert-danger").empty().html( data[1] ).fadeIn();
						console.log(data[1]);
					}
					console.log(data);
					$("body").loading('stop');
				}
			});
		});
	});
};

if( $("#google-map").length >= 1 ) {
	$.googleMap = function( ) {
		var name = $("#google-map").data('name');
		var lat = $("#google-map").data('lat');
		var lng = $("#google-map").data('lng');
		var zoom = $("#google-map").data('zoom');
	
		if(!lat) { lat = 52.143181; }
		if(!lng) { lng = 19.533691; }
		if(!zoom) { zoom = 6; }
	
		if( $("#google-map").length) {
			var myOptions = {
				scrollwheel: false,
				navigationControl: false,
				mapTypeControl: false,
				scaleControl: true,
				draggable: false,
				zoomControl: false,
				zoom: zoom,
				center: new google.maps.LatLng(lat,lng),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(
				document.getElementById("google-map"), myOptions
			);
	
			marker = new google.maps.Marker({
				map: map,
				position: new google.maps.LatLng(lat, lng)
			});
	
			if(name) {
				infowindow = new google.maps.InfoWindow({
					content: name
				});
	
				google.maps.event.addListener(marker, "click", function(){
					infowindow.open(map,marker);
				});
				infowindow.open(map,marker);
			}
		}
	}
	
	$.googleMapLocate = function( address, city )
	{
		$.googleMap(false);
		if( $("#google-map").length) {
			if(city) {
				address = address + ", " + city;
			} else {
				return false;
			}
	
	
			geocoder = new google.maps.Geocoder();
			geocoder.geocode( { 'address': address }, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
			    map.setCenter(results[0].geometry.location);
	
				map.setZoom(16);
				var newLatLng = results[0].geometry.location;
				document.getElementById("latlng").value = newLatLng;
				document.getElementById("zoom").value = map.zoom;
	
			    var marker = new google.maps.Marker({
			        map: map,
					draggable: true,
					animation: google.maps.Animation.DROP,
			        position: results[0].geometry.location
			    });
	
				google.maps.event.addListener(marker, "dragend", function (mEvent) {
					document.getElementById("latlng").value = mEvent.latLng.toString();
					document.getElementById("zoom").value = mEvent.getZoom();
				});
	
			  } else {
			  	switch(status) {
					case "ZERO_RESULTS":
						var msg = "błędna nazwa miejscowości, lub podana miejscowość nie istnieje";
					break;
				}
			    alert("Nie udało się znaleźć miejscowości na mapie - powód: " + msg);
			  }
	
			});
		}
	}
}

$.hideClickView = function() {
	var memory;

	$(".hide-click-view").each( function(i,e) {
		memory = $(this).html();
		$(this).html( "<a class=\"hide-click-view-btn btn btn-default btn-sm\">" + $(this).data('text') + "</a>");
	});

	$(".hide-click-view-btn").click( function() {
		$(this).empty().html( "<a href=\"tel:" + memory + "\" class=\"hide-click-view-btn\">" + memory + "</a>");
		//$(this + "a").removeClass("btn-primary").removeClass("btn").removeClass("btn-xs");
	});
}
