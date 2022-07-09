$(document).ready( function() {
	var module = $("#payments_module").val();
	$(".layerhide").hide();
	$("#layer-" + module).show();
	
	$("#payments_module").on('change' , function() {
		var module = $(this).val();
		$(".layerhide").hide();
		$("#layer-" + module).show();
	});
});