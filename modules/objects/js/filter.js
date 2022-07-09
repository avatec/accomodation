$(document).ready( function() {
	$("table tbody tr").hide();
	
	$("#yearSelect").change( function() {
		var year = $(this).val();
		var month = $("#monthSelect").val();
		
		$.tableFilter(year, month);
	});

	
	$("#monthSelect").change( function() {
		var year = $("#yearSelect").val();
		var month = $(this).val();
		
		$.tableFilter(year, month);
	});
	
});

$.tableFilter = function(y,m) {
	var fd = y + "-" + m;
	$("table tbody tr").each( function(e,i) {
		if( $(this).data('date') ==  fd) {
			$(this).show();
		} else {
			$(this).hide();
		}
	});
}