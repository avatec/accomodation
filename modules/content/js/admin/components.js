$(function() {
	// Tryb OnePage
	var opv = $("#opv");
	if( opv.bootstrapSwitch('state') == true ) {
		$("#opv_layer").show();
	} else {
		$("#opv_layer").hide();
	}
	
	opv.on('switchChange.bootstrapSwitch', function(event, state) {
		if( state == true ) {
			$("#opv_layer").show();
		} else {
			$("#opv_layer").hide();
		}
	});
	
	// Editable
	var editable = $("#editable");
	if( editable.bootstrapSwitch('state') == true ) {
		$(".editable_layer").show();
	} else {
		$(".editable_layer").hide();
	}
	
	editable.on('switchChange.bootstrapSwitch', function(event, state) {
		if( state == true ) {
			$(".editable_layer").show();
		} else {
			$(".editable_layer").hide();
		}
	});
});