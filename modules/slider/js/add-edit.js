$(document).ready( function() {
	$('input[name="start_day"]').keyup( function() {
		var v = $(this).val();
		if(v < 0) {
			$(this).val(1);
		}
		if(v > 31) {
			$(this).val(31);
		}
	});
	
	$('input[name="end_day"]').keyup( function() {
		var v = $(this).val();
		if(v < 0) {
			$(this).val(1);
		}
		if(v > 31) {
			$(this).val(31);
		}
	});
	
	$('input[name="start_month"]').keyup( function() {
		var v = $(this).val();
		if(v < 0) {
			$(this).val(1);
		}
		if(v > 12) {
			$(this).val(12);
		}
	});
	
	$('input[name="end_month"]').keyup( function() {
		var v = $(this).val();
		if(v < 0) {
			$(this).val(1);
		}
		if(v > 12) {
			$(this).val(12);
		}
	});


});