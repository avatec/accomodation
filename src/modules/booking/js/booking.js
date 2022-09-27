$(document).ready( function() {
	$("#errorNotice").hide();
	
	$("#adultPlus").click( function() {
		var num = $(".adult").html();	
		num++;
		$(".adult").html(num);
		
		var checkin = $("#checkin").val();
		var checkout = $("#checkout").val();
		var days = $("#days").val();
		$.countAmount(checkin, checkout,days);	
		
		var adult = $(".adult").html();
		$("#res_adult").val( adult );
		
		var child1 = $(".child1").html();
		$("#res_child1").val( child1 );
		
		var child2 = $(".child2").html();
		$("#res_child2").val( child2 );	
	});
	
	$("#adultMinus").click( function() {
		var num = $(".adult").html();	
		num--;
		if(num<1) {
			//alert("Nie można ustawić mniej niż jedną osobę dorosłą");
			return false;
		} else {
			$(".adult").html(num);
			
			var checkin = $("#checkin").val();
			var checkout = $("#checkout").val();
			var days = $("#days").val();
			$.countAmount(checkin, checkout,days);	
			
			var adult = $(".adult").html();
			$("#res_adult").val( adult );
			
			var child1 = $(".child1").html();
			$("#res_child1").val( child1 );
			
			var child2 = $(".child2").html();
			$("#res_child2").val( child2 );	
		}
	});
	
	$("#child1Plus").click( function() {
		var num = $(".child1").html();	
		num++;
		$(".child1").html(num);
		
		var checkin = $("#checkin").val();
		var checkout = $("#checkout").val();
		var days = $("#days").val();
		$.countAmount(checkin, checkout,days);
		
		var adult = $(".adult").html();
		$("#res_adult").val( adult );
		
		var child1 = $(".child1").html();
		$("#res_child1").val( child1 );
		
		var child2 = $(".child2").html();
		$("#res_child2").val( child2 );		
	});
	
	$("#child1Minus").click( function() {
		var num = $(".child1").html();	
		num--;
		if(num<0) {
			//alert("Nie można ustawić mniej niż jedną osobę dorosłą");
			return false;
		} else {
			$(".child1").html(num);
			
			var checkin = $("#checkin").val();
			var checkout = $("#checkout").val();
			var days = $("#days").val();
			$.countAmount(checkin, checkout,days);	
			
			var adult = $(".adult").html();
			$("#res_adult").val( adult );
			
			var child1 = $(".child1").html();
			$("#res_child1").val( child1 );
			
			var child2 = $(".child2").html();
			$("#res_child2").val( child2 );	
		}
	});
	
	$("#child2Plus").click( function() {
		var num = $(".child2").html();	
		num++;
		$(".child2").html(num);
		
		var checkin = $("#checkin").val();
		var checkout = $("#checkout").val();
		var days = $("#days").val();
		$.countAmount(checkin, checkout,days);
		
		var adult = $(".adult").html();
		$("#res_adult").val( adult );
		
		var child1 = $(".child1").html();
		$("#res_child1").val( child1 );
		
		var child2 = $(".child2").html();
		$("#res_child2").val( child2 );	
	});
	
	$("#child2Minus").click( function() {
		var num = $(".child2").html();	
		num--;
		if(num<0) {
			//alert("Nie można ustawić mniej niż jedną osobę dorosłą");
			return false;
		} else {
			$(".child2").html(num);
			
			var checkin = $("#checkin").val();
			var checkout = $("#checkout").val();
			var days = $("#days").val();
			$.countAmount(checkin, checkout,days);
			
			var adult = $(".adult").html();
			$("#res_adult").val( adult );
			
			var child1 = $(".child1").html();
			$("#res_child1").val( child1 );
			
			var child2 = $(".child2").html();
			$("#res_child2").val( child2 );
		}
	});
	
	$("#countAmount").click(function() {
		var checkin = $("#checkin").val();
		var checkout = $("#checkout").val();
		var days = $("#days").val();
		var adult = $(".adult").html();
		$("#res_adult").val( adult );
		
		var child1 = $(".child1").html();
		$("#res_child1").val( child1 );
		
		var child2 = $(".child2").html();
		$("#res_child2").val( child2 );
		
		$.countAmount(checkin, checkout,days);	
	});
	
/**
 * Wybór daty przyjazdu
 * -------------------------------------------------------------------
 */
 
	$("#calendar-checkin").click( function() {
		$('#calendarModal').modal('show');
		var text_checkin = $("#calendarLabel").data('text-checkin');
		$("#calendarLabel span").html( text_checkin );
		
		$(".day").click(function() {
			if( $(this).hasClass("day-checkin") || $(this).hasClass("day-checkout") ) {
				return false;
			}
			$(".day").each( function() {
				$(this).removeClass("day-checkin");
			});
			$(this).addClass("day-checkin");
			var ci = $(this).data('date');
			if(ci) {
				$("#checkin").val(ci);
				$.update("checkin");
				$('#calendarModal').modal('hide');
			}
		}).unbind(".day");
	});
	
/**
 * Wybór daty wyjazdu
 * -------------------------------------------------------------------
 */
 
	$("#calendar-checkout").click( function() {
		
		$("#calendar-checkin").unbind("click");
		$(".day").unbind("click");
		
		$('#calendarModal').modal('show');
		
		var text_checkout = $("#calendarLabel").data('text-checkout');
		$("#calendarLabel span").html( text_checkout );
		
		$(".day").click( function() {
			if( $(this).hasClass("day-checkin") || $(this).hasClass("day-checkout") ) {
				return false;
			}
			$(".day").each( function() {
				$(this).removeClass("day-checkout");
			});
			$(this).addClass("day-checkout");
			var co = $(this).data('date');
			if(co) {
				$("#checkout").val(co);
				$.update("checkout");
				$("#calendarModal").modal('hide');
				
				$("#calendar-checkin").unbind("click");
				$(".day").unbind("click");
			}
		});
	});
});

$.update = function( type ) {	
	console.info('$.update');
	var checkin = $("#checkin").val();
	
	if( type == "checkin" ) {
		console.log("Checkin contact server...");
		$.ajax({
			url: "/ajax/booking/checkin/",
			method: "get",
			data : { checkin: checkin },
			beforeSend: function() {
				$(".fa-spin").fadeIn();
			},
			success: function(data) {
				if(data) {
					var json = $.parseJSON(data);
					
					$("#calendar-checkin .item-day").html(json.checkin.day);
					$("#calendar-checkin span.item-month").html("<b>" + json.checkin.month + "</b> " + json.checkin.year);
					$("#calendar-checkin span.item-day-name").html(json.checkin.dayname);
					
					$(".fa-spin").fadeOut();
					return true;
				}
			}
		});	
	}
	
	var checkout = $("#checkout").val();
	
	if( type == "checkout" ) {
		$.ajax({
			url: "/ajax/booking/checkout/",
			method: "get",
			data : { checkout: checkout },
			beforeSend: function() {
				$(".fa-spin").fadeIn();
			},
			success: function(data) {
				if(data) {
					var json = $.parseJSON(data);
					
					$("#calendar-checkout .item-day").html(json.checkout.day);
					$("#calendar-checkout span.item-month").html("<b>" + json.checkout.month + "</b> " + json.checkout.year);
					$("#calendar-checkout span.item-day-name").html(json.checkout.dayname);		
								
					$(".fa-spin").fadeOut();
					return true;
				}
			}
		});	
	}
	
	var days = $.countDays(checkin, checkout);
	if(!isNaN(days)) {
		$("#days").val( days );
		$("span.item-days").html ( "<big>" + days + "</big>" );
		$.countAmount(checkin, checkout, days);
	}
}

$.countAmount = function( checkin, checkout, days ) {
	console.log( "Counting amount..." );
	var object_id = $("input[name=object_id]").val();
	var room_id = $("input[name=room_id]").val();
	var adult = $(".adult").html();
	var child1 = $(".child1").html();
	var child2 = $(".child2").html();
	
	console.log("Object_id: " + object_id + " / " + "Room_id: " + room_id + " / " + "Adult: " + adult + " / " + "Child1: " + child1 + " / " + "Child2" + child2);
	console.log("Checkin: " + checkin + " / " + "Checkout: " + checkout + " / " + "Days: " + days);
	$.ajax({
		url: "/ajax/booking/count-amount/",
		method: "get",
		data: { object_id: object_id, room_id: room_id, checkin: checkin, checkout: checkout, days: days, adult: adult, child1: child1, child2: child2 },
		beforeSend: function() {
			$(".fa-spin").fadeIn();
		},
		success: function( data ) {
			console.log(data);
			if(data) {
				var json = $.parseJSON(data);
				if( $.isNumeric( json.amount )) {
					$(".item-cost").html(json.amount);
					$("#res_amount").val(json.amount);
					
					$(".item-advance").html( json.advance );
					$("#res_advance_amount").val( json.advance );
				}
			}
		}
	});
}

$.prepareDays = function(checkin, days) {
	console.info('$.prepareDays');
	for(i=0;i<days;i++) {
		var start = new Date(checkin);
		var end = new Date();
		end.setDate(start.getDate()+i);
		var month = end.getMonth()+1;
		var day = end.getDate();
		var d = end.getFullYear() + "-" + ((month<=9) ? "0" + month : month) + "-" + ((day<=9) ? "0" + day : day);
		if(d!=checkin) {
			$("#calendar .day[data-date=" + d + "]").addClass("day-check");
		}
	}
};

$.countDays = function(date1, date2) {
    date1 = date1.split('-');
	date2 = date2.split('-');
	
	date1 = new Date(date1[0], date1[1], date1[2]);
	date2 = new Date(date2[0], date2[1], date2[2]);
	
	date1_unixtime = parseInt(date1.getTime() / 1000);
	date2_unixtime = parseInt(date2.getTime() / 1000);
	
	var timeDifference = date2_unixtime - date1_unixtime;
	var timeDifferenceInHours = timeDifference / 60 / 60;
	var timeDifferenceInDays = timeDifferenceInHours  / 24;
	return Math.round(timeDifferenceInDays);
};