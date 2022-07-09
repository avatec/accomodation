$(document).ready( function() {
	$("#contact-loading").hide();
	$("#contact-error").hide();
	$("#contact-success").hide();
	$("#contact-form").show();

	$("#contactBtnMain").click( function() {
		$("#contact-loading").hide();
		$("#contact-error").hide();
		$("#contact-success").hide();
		$("#contact-form").show();
	});

	$("#btnSendMessage").click( function() {
		var object_id = $(this).data('object-id');
		var contact_name = $("#contact_name").val();
		var contact_email = $("#contact_email").val();
		var contact_phone = $("#contact_phone").val();
		var contact_msg = $("#contact_msg").val();
		var token = $("input[name=token]").val();

		$.ajax({
			method: "post",
			url: '/ajax/objects/send-message/',
			data: { object_id: object_id, token: token, contact_name: contact_name, contact_email: contact_email, contact_phone: contact_phone, contact_msg: contact_msg },
			error: function( err ) {
				if( err.responseText ) {
					console.error( responseText );
				}
			},
			beforeSend: function()
			{
				$("#contact-loading").show();
			},
			success: function( data )
			{
				console.log( data );
				if( data ) {
					var json = $.parseJSON( data );
					if( typeof json === 'object' && json ) {
						if(json.RESULT == false) {
							var errorMSG = '<h4>Wystąpiły błędy:</h4><br/>';
							$.each( json.ERROR, function(i,e) {
								errorMSG += e + '<br/>';
							});
							$("#contact-error").html( errorMSG ).show();
							$("#contact-success").hide();
						} else {
							$("#contact-success").show();
							$("#contact-form").hide();
							$("#btnSendMessage").hide();
						}
					}
				}
			},
			complete: function() {
				$("#contact-loading").hide();
				$("#contact_name").val('');
				$("#contact_email").val('');
				$("#contact_phone").val('');
				$("#contact_msg").val('');
			}
		});
	});
});
