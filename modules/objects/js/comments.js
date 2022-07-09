$(document).ready( function() {
	$("#btnAddPlusRecommend").click( function() {
		var text = $(this).data('text');
		var object_id = $(this).data('id');
		if( $.cookie("rm") == object_id ) {
			alert(text);
			return false;
		}
		$.post('/ajax/objects/AddPlusRecommend/' , { id: object_id }, function(r) {
			var plus = $("#btnAddPlusRecommend").find('b').html();
			$("#btnAddPlusRecommend").find("b").html( parseInt(plus) + 1 );
			$.cookie("rm", object_id, { expires : 3600 });
		});
	});
	
	$("#btnAddMinusRecommend").click( function() {
		var object_id = $(this).data('id');
		var text = $(this).data('text');
		if( $.cookie("rm") == object_id ) {
			alert(text);
			return false;
		}
		$.post('/ajax/objects/AddMinusRecommend/' , { id: object_id }, function(r) {
			var minus = $("#btnAddMinusRecommend").find('b').html();
			$("#btnAddMinusRecommend").find("b").html( parseInt(minus) + 1 );
			$.cookie("rm", object_id, { expires : 3600 });
		});
	});
	
	$(".btnAddPlusRecommendComment").click( function() {
		var text = $(this).data('text');
		var comment_id = $(this).data('id');
		var t = $(this);
		if( $.cookie("cr") == comment_id ) {
			alert(text);
			return false;
		}
		$.post('/ajax/objects/AddPlusRecommendComment/' , { id: comment_id }, function(r) {
			var plus = t.find('b').html();
			t.find('b').html( parseInt(plus) + 1 );
			$.cookie("cr", comment_id, { expires : 3600 });
		});
	});
	
	$(".btnAddMinusRecommendComment").click( function() {
		var comment_id = $(this).data('id');
		var text = $(this).data('text');
		var t = $(this);
		if( $.cookie("cr") == comment_id ) {
			alert(text);
			return false;
		}
		$.post('/ajax/objects/AddMinusRecommendComment/' , { id: comment_id }, function(r) {
			var minus = t.find('b').html();
			t.find('b').html( parseInt(minus) + 1 );
			$.cookie("cr", comment_id, { expires : 3600 });
		});
	});
});