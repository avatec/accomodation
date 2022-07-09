$(document).ready( function() {
	if( $('#announcement_create').bootstrapSwitch('state') ) {
		$(".blockedHidden").show();
	} else {
		$(".blockedHidden").hide();
	}
	
	$('#announcement_create').on('switchChange.bootstrapSwitch', function(event, state) {
		$(".blockedHidden").slideToggle();
	});
});