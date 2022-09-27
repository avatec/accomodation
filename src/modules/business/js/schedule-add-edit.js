$(document).ready( function() {
	$(".p1").hide();
	$(".p2").show();
	$(".p3").hide();
	
	$("#own").change( function() {
		var result = $(this).val();
		if(result == "TRUE") {
			$(".p1").hide();
		} else if( result == "FALSE") {
			$(".p1").show();
		} else {
			$(".p1").hide();
		}
	});
	
	$("#cycle").change( function() {
		var result = $(this).val();
		switch( result ) {
			// Co miesiąc
			case "MONTHLY":
				$(".p2").show();
				$(".p3").show();
				$(".p4").html("od");
			break;
			
			// Co rok
			case "YEARLY":
				$(".p2").show();
				$(".p3").hide();
				$(".p4").html("od");
			break;
			
			// Pojedyńczo
			case "SINGLE":
				$(".p2").hide();
				$(".p3").hide();
				$(".p4").html("w dniu");
			break;
			
			// Do określonej daty
			case "TILL_DATE":
				$(".p2").show();
				$(".p3").hide();
				$(".p4").html("od");
			break;
			
			default:
				$(".p2").hide();
				$(".p3").hide();
				$(".p4").html("od");
			break;
		}
	});
	
});