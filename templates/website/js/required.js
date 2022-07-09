$.required = function() {
	var errors = 0;
	
	$(document).submit( function() {
		errors = 0;
		$("*:required").each( function(i,e) {
			$(this).next('div[rel="required-cloud"]').remove();
			if($(this).val() == "") {
				$(this).css("border-color" , "red");
				var tag = $(this).prop('tagName');
				if( tag == "SELECT") {
					$(this).after("<div rel=\"required-cloud\" style=color:red;position:absolute;top:0;left;0;right:20%;top:20%;font-size:0.8em;z-index:1;>pole wymagane</div>");
				} else {
					$(this).after("<div rel=\"required-cloud\" style=color:red;position:absolute;top:0;left;0;right:10%;top:20%;font-size:0.8em;>pole wymagane</div>");	
				}
				
				errors++;
			}
		});
		
		if(errors == 0) {
			console.log("submit");
			return true;
		} else {
			console.log('chyba błędy ' + errors);
			return false;
		}
	});
	
	$("*:required").keyup( function() {
		console.log('keyup');
		var value = $(this).val();
		if( value.length > 0 ) {
			$(this).next('div[rel="required-cloud"]').remove();
			$(this).removeAttr("style");
		}
		
		if(errors == 0) {
			console.log("submit");
			return true;
		} else {
			return false;
		}
	});
	
	$("*:required").blur( function() {
		console.log( 'blur' );
		var value = $(this).val();
		if( value.length > 0 ) {
			$(this).next('div[rel="required-cloud"]').remove();
			$(this).removeAttr("style");
		}
		
		if(errors == 0) {
			console.log("submit");
			return true;
		} else {
			return false;
		}
	});
};