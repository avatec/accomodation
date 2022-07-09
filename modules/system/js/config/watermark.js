$(document).ready( function() {
	$(".watermark").change( function() {
		console.log( 'Input changed:' , window.URL.createObjectURL(this.files[0]) );
		
		$("#watermark_preview").attr("src" , window.URL.createObjectURL(this.files[0]));
		/**
		$(".btn-upload").prop("disabled" , false);
		alert('tak');
		document.querySelector('.btn-upload').setAttribute('disabled', false);
		**/
	});
});
