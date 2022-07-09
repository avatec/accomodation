$(document).ready( function() {
	var expire = parseInt( $("body").data('expire') );
	setInterval(function() {
		var expire_in = expire - parseInt((new Date().getTime() / 1000));
		var expire_in_minutes = Math.floor(expire_in / 60);
		var expire_in_seconds = expire_in - expire_in_minutes * 60;

		if( expire_in_minutes == 1 && expire_in_seconds == 0 ) {
			alertify.alert('Twoja sesja wkrótce wygaśnie. Odśwież stronę, aby ją przedłużyć');
		}

		if( expire_in_minutes <= 0 && expire_in_seconds <= 0 ) {
			alertify.alert('Twoja sesja wygasła !<br/>Zostaniesz wylogowany(a) automatycznie za 3 sekundy lub po kliknięciu OK', function() {
				location.reload();
			});
			setTimeout(function() {
				location.reload();
			}, 3000);
		}

		if( expire_in_minutes <= 9 ) {
			expire_in_minutes = '0' + expire_in_minutes;
		}
		if( expire_in_seconds <= 9 ) {
			expire_in_seconds = '0' + expire_in_seconds;
		}
		$("#minutes").html( expire_in_minutes );
		$("#seconds").html( expire_in_seconds );
	}, 1000);
	
	// enable bootstrap switch
	if(typeof $.fn.bootstrapSwitch !== 'undefined') {
		$("input[type=radio],input[type=checkbox]").bootstrapSwitch();
	}

	// icon picker
    $(".icon-picker").iconPicker();

    // data picker
    $(".dataPicker").datetimepicker({
	    lang: 'pl',
	    i18n:{
			de:{
				months:[
				'Styczeń','Luty','Marzec','Kwiecień',
				'Maj','Czerwiec','Lipiec','Sierpień',
				'Wrzesień','Październik','Listopad','Grudzień'
				],
				dayOfWeek:[
				"Nd", "Pn", "Wt", "Śr",
				"Cz", "Pt", "So",
				]
			}
		},
		timepicker: false,
		format:'Y-m-d'
    });
    $(".dataPickerTime").datetimepicker({
	    lang: 'pl',
	    i18n:{
			de:{
				months:[
				'Styczeń','Luty','Marzec','Kwiecień',
				'Maj','Czerwiec','Lipiec','Sierpień',
				'Wrzesień','Październik','Listopad','Grudzień'
				],
				dayOfWeek:[
				"Nd", "Pn", "Wt", "Śr",
				"Cz", "Pt", "So",
				]
			}
		},
		timepicker: true,
		format:'Y-m-d H:i:00'
    });

	// input mask
	$("[data-mask]").inputmask();

	// select
	$('select').select2();

	$('[data-toggle="tooltip"]').mouseover( function() {
		if($(this).attr("href")) {
			$(this).css('cursor','pointer');
		} else {
			$(this).css('cursor','help');
		}
	});

	// tooltip
	var placement = $('[data-toggle="tooltip"]').data('placement');
	$('[data-toggle="tooltip"]').tooltip({
		html:true,
		placement: (!placement ? placement : 'right'),
		container: 'body'
	});

	$('.confirm').click( function( event ) {
		var returnLink = $(this).attr("href");

		alertify.confirm("Tej operacji nie można cofnąć, czy jesteś pewny(a) ?", function (e) {
			if (e) {
				document.location.href = returnLink;
			} else {
				return false;
			}
		});
		return false;
	});

	$("#makeBackup").click( function() {
		$("html").css("cursor" , "wait");
		alertify.alert("<h3>Trwa generowanie backup.</h3><p>Poczekaj na komunikat o zakończeniu i nie wykonuj żadnych operacji w tym czasie. Moze to potrwać <u>kilkadziesiąt sekund</u></p>");
		$.ajax({
		   url: '/cron/backup.php',
		   type: 'POST',
		   data: 'start=true',
		   success: function(data, textStatus, jqXHR) {
		    	$("html").css("cursor" , "default");
		    	alertify.alert(data);
		   },
		   error: function(jqXHR, textStatus, errorThrown) {
			   alertify.alert("<h3>Wystąpił błąd podczas tworzenia kopii zapasowej</h3><p>" + textStatus + " " + errorThrown + "</p>");
		   }
		});
	});

	// remember position of menu
	$("#menu ul.main li a").click( function() {
		var CookieValue = $(this).parent("li").attr("rel");
		$("#menu ul.main li").removeClass("highlight");
		$(this).parent("li").addClass("highlight");
		$.cookie("pms" , CookieValue, { expires : 1, path: '/' });
	});

	var StoredCookie = $.cookie("pms");
	if(StoredCookie) {
		$("#menu ul.main li[rel=" + StoredCookie + "] ul").slideToggle();
		$("#menu ul.main li").removeClass("highlight");
		$("#menu ul.main li").find("span.current-page").remove();
		$("#menu ul.main li[rel=" + StoredCookie + "]").addClass("highlight").append("<span class=\"current-page\"></span>");

		$("#menu ul.main li li").removeClass("highlight");
		$("#menu ul.main li li").find("span.current-page").remove();

	}

	$("*[maxlength]").on('keyup', function() {
		var id = $(this).attr('name');
		var length = $(this).val().length;
		var maxlength = $(this).prop("maxlength");
		var result = parseInt(maxlength) - parseInt(length);
		if( result > (maxlength/2)) {
			$("#" + id + "_label").html('pozostało ' + result + ' znaków do wprowadzenia');
		} else {
			$("#" + id + "_label").html('<b class="text-danger">pozostało ' + result + ' znaków do wprowadzenia</b>');
		}
	});

});
