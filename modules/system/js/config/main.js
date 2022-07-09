$(document).ready( function() {
	if( $('#service_blocked').bootstrapSwitch('state') ) {
		$(".blockedHidden").show();
	} else {
		$(".blockedHidden").hide();
	}
	
	$('#service_blocked').on('switchChange.bootstrapSwitch', function(event, state) {
		$(".blockedHidden").slideToggle();
	});
});