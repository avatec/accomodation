$(document).ready( function() {
	$.googleObjectsMap();
});

$.googleObjectsMap = function( ) {
	console.log('starting map');
	var lat = parseFloat($("#gmapobj").data('lat'));
	var lng = parseFloat($("#gmapobj").data('lng'));
	var zoom = parseInt($("#gmapobj").data('zoom'));

	if(!lat) { lat = 52.143181; }
	if(!lng) { lng = 19.533691; }
	if(!zoom) { zoom = 7; }

	var myOptions = {
		scrollwheel: true,
		navigationControl: true,
		mapTypeControl: false,
		scaleControl: false,
		draggable: true,
		zoomControl: true,
		zoom: zoom,
		center: new google.maps.LatLng(lat,lng),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(
		document.getElementById("gmapobj"), myOptions
	);
	
	var markers = [];
	
	var re = $.getJSON('/ajax/objects/get-for-map/' , {} , function(r) {
		for(i=0; i<r.length; i++) {
			markers[i] = [ r[i].name, r[i].map_lat, r[i].map_lng, r[i].link, r[i].short_description, r[i].city ];
		}
	});
	
	re.done( function() {
		var infoWindow = new google.maps.InfoWindow(), marker, i;
		
		for( i=0; i<markers.length; i++ ) {
			marker = new google.maps.Marker({
				map: map,
				title: markers[i][0],
				position: new google.maps.LatLng(markers[i][1], markers[i][2])
			});
			
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
	            return function() {
	                infoWindow.setContent('<div class="info_content" style="max-width: 320px"><h5>' + markers[i][0] + '<br/><small>' + markers[i][5] + '</small></h5><p>' + markers[i][4] + '</p><br/><a class="btn btn-sm btn-info" href="' + markers[i][3] + '">zobacz ofertę <i class="fa fa-arrow-right"></i></a></div>');
	                infoWindow.open(map, marker);
	            }
	        })(marker, i));
		}
	});

	console.log( re );

	/**marker[i] = new google.maps.Marker({
				map: map,
				position: new google.maps.LatLng(r[i].map_lat, r[i].map_lng)
			});
			
			infowindow[i] = new google.maps.InfoWindow({
				content: r[i].name
			});
			
			google.maps.event.addListener(marker, "click", function(){
				infowindow.open(map,marker[i]);
			});**/
			
	//if(name) {
		

		
	//infowindow.open(map,marker);
	//}
}