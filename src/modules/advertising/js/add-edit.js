$(document).ready(function() {
	$("#layerImages").hide();
	$("#layerText").hide();

	var type = $("#type").val();
	switch( type ) {
		case "IMAGE":
			$("#layerImages").show();
			$("#layerText").hide();
		break;

		case "TEXT":
			$("#layerImages").hide();
			$("#layerText").show();
		break;
	}

	$("#type").on('change' , function() {
		var type = $(this).val();
		switch( type ) {
			case "IMAGE":
				$("#layerImages").show();
				$("#layerText").hide();
			break;

			case "TEXT":
				$("#layerImages").hide();
				$("#layerText").show();
			break;
		}
	});


	$("#adPlaceSelect").change( function() {
		var state = $(this).val();
		if( state == "search" ) { $(".placeState").show(); } else { $(".placeState").hide(); }
	});

});
