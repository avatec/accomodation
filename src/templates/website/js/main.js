/**
 * Promoted objects slider
 */

$(document).ready(function($){
	$('.gallery-promotion-items').responsivecarousel({ 
		autoRotate: 10000,
		visible: 3, 
		itemMinWidth: 320, 
		itemMargin: 10 
	});
	
	$(".promotion-items").hammer().on("swipeleft" , function(event) {
		$("#promotion-items-nav a.previous").trigger('click');
	});
	
	$(".promotion-items").hammer().on("swiperight" , function(event) {
		$("#promotion-items-nav a.next").trigger('click');
	});
});