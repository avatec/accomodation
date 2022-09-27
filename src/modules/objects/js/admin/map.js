$(document).ready( function() {
	$.googleMap();

	$("#address").blur( function() {
		$.googleMapLocate( $(this).val(), $("#city").val() );
	});
	$("#city").blur( function() {
		$.googleMapLocate( $("#address").val(), $(this).val() );
	});
});

$.googleMap = function() {
	var lat = $("#google-map").data('lat');
	var lng = $("#google-map").data('lng');
	var zoom = $("#google-map").data('zoom');

	if(!lat) { var lat = 52.143181; }
	if(!lng) { var lng = 19.533691; }
	if(!zoom) { var zoom = 6; }

	var myOptions = {
		scrollwheel: false,
		navigationControl: false,
		mapTypeControl: false,
		scaleControl: true,
		draggable: true,
		zoomControl: true,
		zoom: zoom,
		center: new google.maps.LatLng(lat,lng),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	map = new google.maps.Map(
		document.getElementById("google-map"), myOptions
	);
	/**
	marker = new google.maps.Marker({
		map: map,
		position: new google.maps.LatLng(lat, lng)
	});
	**/

}

$.googleMapLocate = function( address, city ) {

	$.googleMap(false);
	if(city) {
		address = address + ", " + city;
	} else {
		return false;
	}

	console.log( address );
	
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
