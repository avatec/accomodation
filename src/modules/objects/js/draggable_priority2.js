$( function() {
	$( ".selector" ).sortable({
		tolerance: "pointer"
	});
    $( "#sortable" ).sortable();
    $( "#sortable" ).sortable({
	    placeholder: "ui-state-highlight",
	    update: function (event, ui) {	       
	       ui.item.data('priority' , ui.placeholder.index());
	       var l = 1;
	       $("#sortable li").each( function(e) {
		       var li = $(this);
		       var id = li.data('id');
		       var priority = l;
		       l = l + 1;
		       
		       if( id && priority ) {
			       $.ajax({
				       async: true,
				       method: "POST",
				       url: "/ajax/objects/update-room-photo-priority",
				       data: { id: id, priority: priority },
				       success: function( r ) {
					       console.log( r );
				       }
			       });
		       } 
		   });
	    }
    });
    $( "#sortable" ).disableSelection();
});