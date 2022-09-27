var allFiles = 0;
var previewNode = document.querySelector("#template");
previewNode.id = "";
var max_photos = document.getElementById('mp').value;

var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);

var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
	url: "/panel/rooms/photos/upload/", // Set the url
	thumbnailWidth: 80,
	thumbnailHeight: 80,
	parallelUploads: 2,
	maxFiles: max_photos,
	previewTemplate: previewTemplate,
	autoQueue: false, // Make sure the files aren't queued until manually added
	previewsContainer: "#previews", // Define the container to display the previews
	clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
});

myDropzone.on("addedfile", function(file) {
  // Hookup the start button
  file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
});

// Update the total progress bar
myDropzone.on("totaluploadprogress", function(progress) {
  document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
});

myDropzone.on("sending", function(file, xhr, formData) {
	formData.append("room_id", document.querySelector("#room_id").value);
	document.querySelector("#total-progress").style.opacity = "1";
	file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
	file.previewElement.querySelector(".start").value = "Ładowanie pliku";
	
});

// Hide the total progress bar when nothing's uploading anymore
myDropzone.on("queuecomplete", function(progress) {
	document.querySelector("#total-progress").style.opacity = "0";
  	//$("body").loading('stop');
  	setTimeout(function() {
		location.reload();  	
  	},2000);
  	
});

myDropzone.on("complete" , function( file ) {
	file.previewElement.querySelector(".progress").style.opacity = "0";
	file.previewElement.querySelector(".start").style.opacity = "0";
	file.previewElement.querySelector(".delete").style.opacity = "0";
	file.previewElement.querySelector(".error").innerHTML = "Plik został załadowany";
	var elem = file.previewElement.querySelector(".error");
	//console.log(elem);
	$(elem).parent().parent().fadeOut(2000);
});

// Setup the buttons for all transfers
// The "add files" button doesn't need to be setup because the config
// `clickable` has already been specified.
document.querySelector("#actions .start").onclick = function() {
	//$("body").loading({ message: 'Proszę czekać...<br/>Trwa ładowanie plików na serwer<br/>Po zakończeniu ładowania ta informacja zniknie<br/><br/><i class="fa fa-circle-o-notch fa-spin fa-2x"></i>' });
	myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
};

document.querySelector("#actions .cancel").onclick = function() {
	myDropzone.removeAllFiles(true);
	//$("body").loading('stop');
};