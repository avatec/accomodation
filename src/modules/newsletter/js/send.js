$(document).ready( function() {
	if( $('#test_email').bootstrapSwitch('state') ) {
		$(".blockedHidden").show();
	} else {
		$(".blockedHidden").hide();
	}
	
	$('#test_email').on('switchChange.bootstrapSwitch', function(event, state) {
		$(".blockedHidden").slideToggle();
	});
});