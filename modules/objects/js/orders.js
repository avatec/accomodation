$(document).ready( function() {
	$("#payment-form").hide();
	$("#order-summary").hide();
	$(".online-type").hide();
	$(".sms-type").hide();
	
	$(".btn-promotion").click( function() {
		var pid = $(this).data('pid');
		var type = $(this).data('type');

		if( type.indexOf('ONLINE') !== -1 ) {
			$(".online-type").show();
		} else {
			$(".online-type").hide();
		}
		
		if( type.indexOf('SMS') !== -1 ) {
			$(".sms-type").show();
		} else {
			$(".sms-type").hide();
		}
		
		$("#order-summary").fadeOut(100);
		$("#payment-form").fadeIn(1000);
		
		$(".btn-payment").click( function() {
			var payment = $(this).data('payment');
			$.countAmount(pid, payment);
		});
	});	
});

$.countAmount = function(pid, payment) {
	$.ajax({
		type: "POST",
		data: { pid: pid, payment: payment },
		url: "/ajax/objects/order-amount/",
		success: function(data) {
			if (typeof data == 'string') {
				var json = $.parseJSON( data );
				console.log( json );
				if( typeof json == 'object') {
					if( json.amount ) {
						$(".summary-text").empty().append( json.text );
						$(".summary-amount").html( json.amount );	
						$("#order-summary").fadeIn(400);
						$("#order-summary-error").hide();
					} else {
						$("#order-summary").hide();
						$("#order-summary-error").show();
					}
				}
			}
		},
		complete: function() {
			$("#order-summary .form-hidden").html('<input type="hidden" name="pid" value="' + pid + '"/><input type="hidden" name="payment" value="' + payment + '"/>');
		}
	})
}