$(document).ready( function() {
	$(".request-photo-loading ").hide();
	
	$("#requestPhotos").click( function() {
		var object_id = $(this).data('object-id');
		var token = $("input[name=token]").val();
		
		$.ajax({
			method: "post",
			url: '/ajax/objects/request-photos/',
			data: { object_id: object_id, token: token },
			beforeSend: function()
			{
				$(".request-photo-loading").show();
			},
			success: function( data )
			{
				if( data == "OK" ) {
					alert("Twoja prośba została pomyślnie wysłana");
				} else {
					alert("Wystąpił nieoczekiwany błąd: " . data);
				}
			},
			complete: function() {
				$(".request-photo-loading").hide();
				$("#requestPhotos").hide();
			}
		});
	});
});