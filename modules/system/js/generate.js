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
		$("#pass").val(password);
		if($("#pass_repeat")) {
			$("#pass_repeat").val(password);	
		}
		
		$("#new_pass").val(password);
		if($("#new_pass_repeat")) {
			$("#new_pass_repeat").val(password);	
		}
		
		$("#new_password").val(password);
		return false;
	});	
});
