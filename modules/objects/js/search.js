$(document).ready( function() {
	$("#advancedSearch").click( function() {
		if( $(this).find("em").hasClass("fa-caret-down") ) {
			$(this).find("em").removeClass("fa-caret-down").addClass("fa-caret-up");
			$("#advancedSearchLayer").slideDown();
		} else {
			$(this).find("em").removeClass("fa-caret-up").addClass("fa-caret-down");
			$("#advancedSearchLayer").slideUp();
		}
	});

	$("#submitSearch").click( function() {
		var url;
		var query = [];
		var distances = [];
		var improvements = [];

		$('input.distance-checkbox').each( function(i,e) {
			if($(this).prop("checked")) {
				var name = $(this).prop('name');
				var pattern = /[0-9]+/g;
				var result = name.match(pattern);
				if( result ) {
					distances.push(result);
				}
			}
		});

		$('input.improvement-checkbox').each( function(i,e) {
			if($(this).prop("checked")) {
				var name = $(this).prop('name');
				var pattern = /[0-9]+/g;
				var result = name.match(pattern);
				if( result ) {
					improvements.push(result);
				}
			}
		});

		var q = $('input[name="q"]').val();
		var c = $('input[name="c"]').val();
		var s = $('select[name="s"]').val();
		var t = $('select[name="t"]').val();
		var l = $('select[name="l"]').val();
		var cf = $('input[name="cf"]').val();
		var ct = $('input[name="ct"]').val();
		var op = $('input[name="op"]').val();
		var ov = $('input[name="ov"]').val();
		var rp = $('input[name="rp"]').val();
		var photo = $('input[name="photo"]').is(':checked');
		var video = $('input[name="video"]').is(':checked');


		query.push('q=' + encodeURIComponent(q));

		if(c.length > 0) {
			query.push('c=' + encodeURIComponent(c));
		}
		if(s !== undefined && s !== '') {
			query.push('s=' + encodeURIComponent(s));
		}
		if(t !== undefined && t !== '') {
			query.push('t=' + encodeURIComponent(t));
		}
		if(l !== undefined && l !== '') {
			query.push('l=' + encodeURIComponent(l));
		}
		if(cf !== undefined && cf !== '') {
			query.push('cf=' + cf);
		}
		if(ct !== undefined && ct !== '') {
			query.push('ct=' + ct);
		}
		if(op !== undefined && op !== '') {
			query.push('op=' + op);
		}
		if(ov !== undefined && ov !== '') {
			query.push('ov=' + ov);
		}
		if(rp !== undefined && rp !== '') {
			query.push('rp=' + rp);
		}
		if( distances[0] ) {
			query.push('distance=' + distances.join(';') + ';');
		}
		if( improvements[0] ) {
			query.push('improvement=' + improvements.join(';') + ';');
		}

		if( photo ) {
			query.push('photo=1');
		}

		if( video ) {
			query.push('video=1');
		}

		for( var key in query ) {
			if( url == undefined ) {
				url = query[key] + '&';
			} else {
				url += query[key] + '&';
			}
		}

		if( url ) {
			var redirect = location.protocol + '//' + location.host + location.pathname + '?' + url.substring(0, url.length - 1);
			location.replace( redirect );
		}
		return false;
	});
});
