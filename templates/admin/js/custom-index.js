var $grid_color = "#e6e6e6";

var $dark_blue = "#2b3d51";
var $info = "#53ACDF";
var $info_light = "#B0D0EC";
var $danger = "#D66061";
var $danger_light = "#E9AFB0";
var $warning = "#ffb61c";
var $success = "#76BBAD";
var $success_light = "#C2DBAC";
var $yellow = "#ffee00";
var $facebook = "#4c66a4";
var $twitter = "#00acee";
var $linkedin = "#1a85bd";
var $gplus = "#dc4937";
var $grey = "#666666";

var $blue_one = "#74b1d4";
var $blue_two = "#84bad9";
var $blue_three = "#9bc7e0";
var $blue_four = "#afd2e6";
var $blue_five = "#badff2";


$(document).ready(function () {
	sparklineGraphs();
});

// Sparkline
function sparklineGraphs() {

	// Pie charts
	$(function () {

		$("#spark_1").sparkline([1,1,4 ], {
			type: 'pie',
			sliceColors: [$info, $success, $warning],
		});

		$("#spark_2").sparkline([2,3,2 ], {
			type: 'pie',
			sliceColors: [$info, $success, $warning],
		});

		$("#spark_3").sparkline([3,1,4 ], {
			type: 'pie',
			sliceColors: [$info, $success, $warning],
		});

		$("#spark_4").sparkline([5,1,2,1 ], {
			type: 'pie',
			sliceColors: [$info, $success, $warning],
		});

		$("#spark_5").sparkline([3,3,4,2 ], {
			type: 'pie',
			sliceColors: [$info, $success, $warning],
		});

		$("#spark_6").sparkline([5,1,1,3,7], {
			type: 'pie',
			sliceColors: [$blue_one, $blue_two, $blue_three, $blue_four, $blue_five],
			width: '136px ',
			height: '136px'
		});
	});
}

//Resize charts and graphs on window resize
$(document).ready(function () {
	$(window).resize(function(){
		sparklineGraphs();
	});
});

//Datepicker
$(function() {
	$("#datepicker" ).datepicker();
});

// Appointments
$( "ul.appointments li" ).click(function() {
	$(this).css('text-decoration', 'line-through');
});

//Flot graphs
// Donut 1
$(function () {
	var data, chartOptions;
	data = [
		{ label: "", data: Math.floor (Math.random() * 100 + 80) }, 
		{ label: "", data: Math.floor (Math.random() * 100 + 60) }, 
	];
	chartOptions = {
		series: {
			pie: {
				show: true,  
				innerRadius: .8, 
				stroke: {
					width: 1,
				}
			}
		}, 
		shadowSize: 0,
		legend: {
			position: 'sw'
		},
		tooltip: true,
		tooltipOpts: {
			content: '%s: %y'
		},
		grid:{
			hoverable: true,
			clickable: false,
			borderWidth: 0,
		},
		shadowSize: 0,
		colors: [$danger, $danger_light],
	};
	var holder = $('#donut-chart-1');
	if (holder.length) {
		$.plot(holder, data, chartOptions );
	} 
});

//Donut 2
$(function () {
	var data, chartOptions;
	data = [
		{ label: "", data: Math.floor (Math.random() * 100 + 40) }, 
		{ label: "", data: Math.floor (Math.random() * 100 + 690) }, 
	];
	chartOptions = {        
		series: {
			pie: {
				show: true,  
				innerRadius: .8, 
				stroke: {
					width: 1,
				}
			}
		}, 
		shadowSize: 0,
		legend: {
			position: 'sw'
		},
		tooltip: true,
		tooltipOpts: {
			content: '%s: %y'
		},
		grid:{
			hoverable: true,
			clickable: false,
			borderWidth: 0,
		},
		shadowSize: 0,
		colors: [$info_light, $info],
	};
	var holder = $('#donut-chart-2');
	if (holder.length) {
		$.plot(holder, data, chartOptions );
	} 
});

//Donut 3
$(function () {
	var data, chartOptions;
	data = [
		{ label: "", data: Math.floor (Math.random() * 100 + 130) }, 
		{ label: "", data: Math.floor (Math.random() * 100 + 460) }, 
	];
	chartOptions = {        
		series: {
			pie: {
				show: true,  
				innerRadius: .8, 
				stroke: {
					width: 1,
				}
			}
		}, 
		shadowSize: 0,
		legend: {
			position: 'sw'
		},
		tooltip: true,
		tooltipOpts: {
			content: '%s: %y'
		},
		grid:{
			hoverable: true,
			clickable: false,
			borderWidth: 0,
		},
		shadowSize: 0,
		colors: [$success_light, $success],
	};
	var holder = $('#donut-chart-3');
	if (holder.length) {
		$.plot(holder, data, chartOptions );
	} 
});
