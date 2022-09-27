$(document).ready( function() {
	$("#form-layer").hide();
	$("#form-text").hide();
	$("#loading-layer").hide();
		
	$("#btnAddCommentForm").click( function() {
		$("#blockComments").modal('hide');
		$("#blockCommentsAdd").modal('show');
		$("#form-layer").show();
	});
	
	$("#btnAddComment").click( function() {
		var object_id = $("#object_id").val();
		var token = $("input[name=token]").val();
		var rank = $("#rank").val();
		var name = $("#name").val();
		var text = $("#text").val();
		
		
		
		$.ajax({
			method: "post",
			url: '/ajax/objects/add-comment/',
			data: { rank: rank, name: name, text: text, object_id: object_id, token: token },
			beforeSend: function()
			{
				$("#form-layer").hide();
				$("#form-text").hide();
				$("#loading-layer").show();
			},
			success: function( data )
			{
				if( data ) {
					var json = $.parseJSON( data );
					if( typeof json === 'object' && json ) {
						if(json.RESULT == false) {
							var errorMSG = '<div class="alert alert-danger"><h4>Wystąpiły błędy:</h4><br/>';
							$.each( json.ERROR, function(i,e) {
								errorMSG += e + '<br/>';
							});
							errorMSG += '</div>';
							$("#form-text").html( errorMSG );
							$("#form-layer").show();
							$("#form-text").show();
						} else {
							$("#form-text").show();
							$("#btnAddComment").hide();
						}
					}
				}
				console.log( 'Result after add-comment' );
				console.info( data );
			},
			complete: function() {
				$("#loading-layer").hide();
			}
		});
	});
});