$(document).ready( function() {
	$(".modalPaymentButton").on('click', function(evt) {
		console.log( "Click payment" );
		var id = $(this).data("id");
		var amount = $(this).data("amount");
		var payment_amount = $(this).data("payment-amount");
		$("#id").val( id );
		amount = parseFloat( amount );
		payment_amount = parseFloat(payment_amount);
		if( isNaN(payment_amount)) {
			payment_amount = 0;
		}
		var sum = amount - payment_amount;
		$("#amount").val( parseFloat(amount) - parseFloat(payment_amount) );
	});
});