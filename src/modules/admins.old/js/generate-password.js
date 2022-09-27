$.extend({
  	password: function (length) {
    	var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	    for( var i=0; i < length; i++ ) {
	        text += possible.charAt(Math.floor(Math.random() * possible.length));
		}
	    return text;
	}
});

$(document).ready( function() {
	$("#generatePassword").click( function() {
		var password = $.password(6,true);
		$("#password").val(password);
		if($("#password_repeat")) {
			$("#password_repeat").val(password);	
		}
		
		$("#new_password").val(password);
		if($("#new_password_repeat")) {
			$("#new_password_repeat").val(password);	
		}
		
		$("#new_password").val(password);
		return false;
	});	
});
