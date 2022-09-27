$(document).ready( function() {
	var object_id = $("input[name=object_id]").val();
	var room_id = $("input[name=room_id]").val();
	$.readReservation( object_id,room_id );
});

$.readReservation = function( object_id, room_id ) {
	console.info('$.readReservation');
	console.log('object_id:' + object_id + '/room_id:' + room_id);
	$.ajax({
		url: "/ajax/booking/read/",
		method: "get",
		data : { object_id: object_id, room_id: room_id },
		beforeSend: function() {
			$(".fa-spin").fadeIn();
		},
		success: function(data) {
			console.info(data);
			switch( data ) {
				default:
					var json = $.parseJSON(data);
					$.setReservation( json );
				break;
				
				case "ERROR_NO_DATA_SENDED":
					console.log("System can't read args - maybe it's empty?");
				break;
				
				case "NO_RESERVATION":
					console.log("There is not reservation for selected room");
				break;
			}
			$(".fa-spin").fadeOut();
		}
	});	
};

$.setReservation = function( reservation ) {	
	var len;
	var keys = reservation.length;
	for(key=0;key<keys;key++) {
		len = reservation[key].length;
		for(i=0; i<len; i++) {
			if( i == 0 ) {
				$("#calendar .day[data-date=" + reservation[key][i] + "]").addClass("day-checkin").css("cursor" , "not-allowed");
			}
			if( (i+1) == len ) {
				$("#calendar .day[data-date=" + reservation[key][i] + "]").addClass("day-checkout");
			}
			if( i>0 && i<len ) {
				$("#calendar .day[data-date=" + reservation[key][i] + "]").addClass("day-check").css("cursor" , "not-allowed");
			}
		}
		
	}
};