$(document).ready( function() {
	$(".he1").hide();

	$('input[name="login"]').keypress(function( e ) {
		var login = $(this).val();
		var code = e.which;
	    if(code==13)e.preventDefault();
	    if(code==32||code==13||code==188||code==186){
	        $(this).prop("data-toggle" , "popover").prop("title" , "Błąd").popover('show');
        	return false;
	    }
	    $(this).prop("data-toggle" , "popover").prop("title" , "Błąd").popover('hide');
	});
	
	if( $("#user_account").val() == "PRIVATE" ) {
		$(".he1").hide();
		$("#company_name").removeAttr("required");
		$("#company_pin").removeAttr("required");
	} else {
		$(".he1").show();
		$("#company_name").prop("required" , true);
		$("#company_pin").prop("required" , true);
	}

	$("#user_account").change( function() {
		if($(this).val() == "PRIVATE") {
			$(".he1").hide();
			$("#company_name").removeAttr("required");
			$("#company_pin").removeAttr("required");
		}
		if($(this).val() == "COMPANY") {
			$(".he1").show();
			$("#company_name").prop("required" , true);
			$("#company_pin").prop("required" , true);
		}
	});

	$("#submitSaveProfile").click( function() {
		var account = $("#user_account").val();
		if(account == "PRIVATE") {
			$("#company_name").removeAttr("required");
			$("#company_pin").removeAttr("required");
		}
		if(account == "COMPANY") {
			$("#company_name").attr("required" , true);
			$("#company_pin").attr("required" , true);
		}
	});


});
