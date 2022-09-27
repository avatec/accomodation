$(document).ready( function() {
	$('input[type="url"]').blur( function( i,e ) {
		return $.validate( 'url', $(this) );
	});
});


$.validate = function( type, elem ) {
	
	switch( type )
	{
		case "url":
			if(elem.val()) {
				var pattern = new RegExp('^(https?:\\/\\/)'+ // protocol
				  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
				  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
				  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
				  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
				  '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
				  if( pattern.test(elem.val()) == true ) {
					  var error = elem.parent('.form.group').hasClass('has-error');
					  if(error) {
						  elem.parent('.form.group').removeClass('has-error');
					  }
					  return true; 
				  } else {
					  console.error('Invalid url');
					  elem.parent('.form-group').addClass('has-error');
					  elem.focus();
					  return false;
				  }
			  }
		break;
	}
	
}