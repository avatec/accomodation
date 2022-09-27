$( document ).ready( function() {
	$("#CheckboxAll").click( function() {
		$(":checkbox").each( function( i,e ) {
			if(this.checked) {
				$(this).prop('checked', false);
				//$(':checkbox').val('1').checkboxX('refresh');
		    } else {
			    $(this).prop('checked', true);
		        //$(':checkbox').val('0').checkboxX('refresh');
		    }
		    //
		});
		//$(':checkbox').checkboxX();
	});
});