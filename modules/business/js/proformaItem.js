$(document).ready( function() {
	$.itemsRefresh();
	
	var payment_amount = $("#payment").is(":checked");
	if(payment_amount == true) {		
		$(".payment_amount").show();
	} else {
		$(".payment_amount").hide();
	}
	
	$("#payment").on( 'switchChange.bootstrapSwitch' , function(event, state) {
		if( state ) {
			$(".payment_amount").show();
		} else {
			$(".payment_amount").hide();
		}
	});
	
	$("#btnAddItem").click( function() {
		$.itemAddSession();
	});
	
	$(".itemDeleteS").click( function() {
		var key = parseInt($(this).data('key'));
		var id = parseInt($(this).data('id'));
		if(id) {
			$.itemDelete( key, id );
			$.itemsRefresh();
		}
	});

	$("#addNewRow").click( function() {
		var row = $.addItemRow();
		var lp = $("tr.item-row td.lp").data("position");
		var next = lp++;
		var prev = next - 1;
		
		if(prev>0) {
		row = row.replace('data-position="' + prev + '"' , 'data-position="' + next + '"');
		row = row.replace('<span class="badge badge-primary item-lp">' + prev + '</span>' , '<span class="badge badge-primary item-lp">' + next + '</span>');
		}
		//console.log(row);
		$("#invoiceItemList").append( '<tr class="item-row">' + row + '</tr>' );
	});

/** Obsługa zmiany wartości netto **/

	$("#item_price_netto").blur( function() {
		var num = $("#item_num").val();
		if(num<=0) {
			num = 1;
			$("#item_num").val(num)
		}
		var vat = $("#item_vat").val();
		var netto = $("#item_price_netto").val();
		netto = netto.replace("," , ".");
		$("#item_price_netto").val( netto );
		
		var brutto = $.countPriceBrutto(netto, vat);
		if(brutto) {
			$("#item_price_brutto").val( brutto );
			$("#item_price_brutto_all").val( brutto * num );
			$("#item_price_vat").val(brutto - netto);
		}
		
		$.convertNumber();
	});
/** Obsługa zmiany wartości brutto **/

	$("#item_price_brutto").blur( function() {
		var num = $("#item_num").val();
		if(num<=0) {
			num = 1;
			$("#item_num").val(num)
		}
		var vat = $("#item_vat").val();
		var brutto = $("#item_price_brutto").val();
		brutto = brutto.replace("," , ".");
		$("#item_price_brutto").val( brutto );
		
		var netto = $.countPriceNetto(brutto, vat);
		$("#item_price_netto").val( netto );
		$("#item_price_brutto_all").val( brutto * num );
		$("#item_price_vat").val(brutto - netto);
		
		$.convertNumber();
	});

/** Obsługa zmiany wartości VAT **/

	$("#item_vat").change( function() {
		var num = $("#item_num").val();
		if(num<=0) {
			num = 1;
			$("#item_num").val(num)
		}
		var vat = $(this).val();
		var netto = $("#item_price_netto").val();
		netto = netto.replace("," , ".");
		
		var brutto = $.countPriceBrutto(netto, vat);
		var price_vat = brutto - netto;

		if(netto == 0) {
			var netto = $.countPriceNetto(brutto, vat);
		} else { 
			$("#item_price_netto").val( netto );
		}
		
		//console.log("netto: " + netto + "/ brutto: " + brutto + " / num: " + num + " / vat: " + vat + "/ wartosc vat: " + price_vat);

		$("#item_price_netto").val( netto );
		$("#item_price_brutto").val( brutto );
		$("#item_price_brutto_all").val( brutto * num );
		$("#item_price_vat").val( price_vat );
		
		$.convertNumber();
	});
	
/** Obsługa zmiany ilości **/

	$("#item_num").blur( function() {
		var num = $("#item_num").val();
		if(!num) {
			num = 1;
		}
		var vat = $("#item_vat").val();
		if(!vat) {
			vat = 23;
		}
		var brutto = $("#item_price_brutto").val();
		if(!brutto) {
			brutto = 0;
		} else {
			brutto = brutto.replace("," , ".");
		}
		$("#item_price_brutto").val( brutto );

		var netto = $("#item_price_netto").val();
		if(!isNaN(netto)) {
			netto = netto.replace("," , ".");
			if(netto == 0) {
				var netto = $.countPriceNetto(brutto, vat);
			} else { 
				$("#item_price_netto").val( netto );
			}
		}
		
		$("#item_price_netto").val( netto );
		$("#item_price_brutto").val( brutto );
		$("#item_price_brutto_all").val( brutto * num );
		if( brutto && netto ) {
			$("#item_price_vat").val(brutto - netto);
		} else {
			$("#item_price_vat").val(0);
		}

		$.convertNumber();
	});
});

/**
 * Czyszczenie formularza dodawania produktu / usługi do faktury
 */

$.clearForm = function() {
	$("#item_name").val("");
	$("#item_num").val("");
	$("#item_price_netto").val("");
	$("#item_price_vat").val("");
	$("#item_price_brutto").val("");
	$("#item_price_brutto_all").val("");
}

/**
	Dodawanie do sesji produktu
	--------------------------- **/
	
	$.itemAddSession = function() {
		
		var item_name = $("#item_name").val();
		var item_num = $("#item_num").val();
		var item_num_type = $("#item_num_type").val();
		var item_price_netto = $("#item_price_netto").val();
		var item_vat = $("#item_vat").val();
		var item_price_vat = $("#item_price_vat").val();
		var item_price_brutto = $("#item_price_brutto").val();

		console.log("* function request [itemAddSession] from [business/invoiceItem.js]");
		
		var postdata = { name: item_name, num: item_num, num_type: item_num_type, price_netto: item_price_netto, vat: item_vat, price_vat: item_price_vat, price_brutto: item_price_brutto };
				
		$.ajax({
			url: "/ajax/business/proformaAddItem/",
			method: "post",
			data : postdata,
			beforeSend: function() {
				$("#btnAddItem").hide();
				$(".fa-spin").fadeIn();	
			},
			success: function(data) {
				if( data ) {
					console.log( data );
					var obj = $.parseJSON( data );
					if(obj) {
						alert( obj.join("\n") );
					}
				}
			},
			complete: function(data) {
				$(".fa-spin").fadeOut(400, function() {
					$("#btnAddItem").fadeIn();
					$.clearForm();
					$.itemsRefresh();

				});
			}
		});
	}
	
	$.itemsRefresh = function() {
		console.log("* function request [itemRefresh] from [business/invoiceItem.js]");
		$.ajax({
			url: "/ajax/business/proformaGetItem/",
			method: "post",
			data : {  },
			success: function(data) {
				if( data ) {
					var obj = $.parseJSON( data );
				
					//console.log( "[OK] function itemsRefresh gets json data succesfully" );
					$.itemsView( obj );
					
					$(".itemDelete").click( function() {
						var key = $(this).data('key');
						key = parseInt( key );
						
						$.itemDelete( key );
						$.itemsRefresh();
					});
				} else {
					//console.log( "[ERROR] function itemsRefresh returns empty data");
					$("#invoiceAddInput .item-view").remove();
				}
			}
		});
	}

/**
 * Usuwanie produktu z sesji istniejących produktów
 */
 
$.itemDelete = function( key, id ) {
	id = parseInt( id );
	key = parseInt( key );
	
	if(!id) { id = null; }
	//console.log("itemDelete receive key: " + key + ", id: " + id);
	$.ajax({
		url: "/ajax/business/proformaDeleteItem/",
		method: "post",
		data : { key: key, id: id },
		beforeSend: function() {
			//$(".fa-spin").fadeIn();
		},
		success: function(data) {
			console.info("$.itemDelete function: " + data);
		},
		complete: function() {
			$.itemsRefresh();
		}
	});
}
	
	$.itemsView = function( obj ) {
		var html;
		var num = obj.length;

		//console.log( "[itemsView] Numer of items: " + num );
		for( var i=0; i<=num; i++ ) {
			if( obj[i] ) {
				html += '<tr class="item-row item-view">';
				html += '<td><p class="form-control-static">' + obj[i].name + '</p></td>';
				html += '<td><p class="form-control-static">' + obj[i].num + '</p></td>';
				html += '<td><p class="form-control-static">' + obj[i].num_type + '</p></td>';
				html += '<td><p class="form-control-static">' + parseFloat(obj[i].price_netto).toFixed(2) + '</p></td>';
				html += '<td><p class="form-control-static">' + parseFloat(obj[i].vat).toFixed(2) + '</p></td>';
				html += '<td><p class="form-control-static">' + parseFloat(obj[i].price_vat).toFixed(2) + '</p></td>';
				html += '<td><p class="form-control-static">' + parseFloat(obj[i].price_brutto).toFixed(2) + '</p></td>';
				html += '<td><p class="form-control-static">' + parseFloat(obj[i].price_brutto_all).toFixed(2) + '</p></td>';
				html += '<td><button type="button" class="btn btn-danger btn-xs itemDelete" data-key="' + i + '"><span class="fa fa-remove"></span></button></td>';
				html += '</tr>';
			}
		}
		$("#invoiceAddInput .item-view").remove();
		$("#invoiceAddInput").append( html );
	}

$.convertVat = function( vat ) {
	var vatt = vat ? vat.replace("%", "") : '0';
	vatt = (vatt * 0.01) + 1
	return vatt;
}

$.convertNumber = function() {
	var netto = $("#item_price_netto");
	var brutto = $("#item_price_brutto");
	var vat = $("#item_price_vat");
	var brutto_all = $("#item_price_brutto_all");
	
	if( netto.val()>0 ) {
		netto.val( parseFloat(netto.val(), 10).toFixed(2) );
	}
	if( brutto.val()>0 ) {
		brutto.val( parseFloat(brutto.val(), 10).toFixed(2) );
	}
	if( vat.val()>0 ) {
		vat.val( parseFloat(vat.val(), 10).toFixed(2) );
	}
	if( brutto_all.val()>0 ) {
		brutto_all.val( parseFloat(brutto_all.val(), 10).toFixed(2) );
	}
}

$.countPriceBrutto = function( amount, vat ) {
	vat = $.convertVat( vat );	
	if( amount>0 && vat>0 ) { 
		return amount * vat;
	} else {
		return 0;
	}
}

$.countPriceNetto = function( amount, vat ) {
	vat = $.convertVat( vat );
	if( amount>0 && vat>0 ) { 
		return amount / vat;
	} else {
		return 0;
	}
}

$.addItemRow = function() {
	var item_row = $("tr.first-item-row").html();
	return item_row;
}