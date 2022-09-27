$(document).on('change', ':file', function() {
    var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');

    input.trigger('fileselect', [numFiles, label]);
});

/*
$(document).on('dragover dragleave drop' , function(e) {
    e.preventDefault();
    e.stopPropagation();
});


// On drag & drop upload
$(".gallery-file").on('dragover' , function(e) {
    console.log('on drag over');
    $(this).addClass("gallery-dragover");
    var text = $("#dropdown-upload-file").data('dragover');
    $("#dropdown-upload-file").html( text );
});

$(".gallery-file").on('dragleave' , function(e) {
    console.log('on drag leave');
    $(this).removeClass("gallery-dragover");
    var text = $("#dropdown-upload-file").data('dragleave');
    $("#dropdown-upload-file").html( text );
});
*/

$(document).ready( function() {
    /*
    $(".gallery-file").on('drop' , function(event) {
        $(this).removeClass("gallery-dragover");

        var files = event.originalEvent.dataTransfer.files;
        var num = 0;
        var max = files.length;

        if( max > 12 ) {
            $(this).removeClass("gallery-dragover");
            var text = $("#dropdown-upload-file").data('dragleave');
            $("#dropdown-upload-file").empty().html( text ).fadeIn(250);

            alertify.alert('Można wgrać maksymalnie 12 plików jednocześnie');
            return false;
        }

        var upload_new_max = uploaded_photos_num + max;

        if( (upload_new_max > 12) || (uploaded_photos_num > 12) ) {
            $(this).removeClass("gallery-dragover");
            var text = $("#dropdown-upload-file").data('dragleave');
            $("#dropdown-upload-file").empty().html( text ).fadeIn(250);

            alertify.alert('Można wgrać maksymalnie 12 plików jednocześnie');
            return false;
        }

        $("#upload-status").html( '0 z ' + max  );

        upload_start();

        for( i=0; i<files.length; i++ ) {

            var formdata = new FormData();
            formdata.append("upload", files[i] );
            formdata.append("controller" , "\\Modules\\UsersGallery");

            var ajax = new XMLHttpRequest();
            ajax.open( "POST", "/upload" );
            ajax.setRequestHeader("Accept-Filetype", "image/*");
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", function( event ) {

                var json = JSON.parse(event.target.responseText);
                if( json.error == true ) {
                    alertify.alert( json.error_code + ': Wystąpił błąd podczas uploadu' , json.msg);
                    return false;
                }

                if( json.success == true ) {
                    $("#photos-list").show();
                    $("#photos-list .row-gallery").append(
                        '<div class="col-6 col-sm-4 col-lg-3 col-gallery">' +
                            '<div class="box-gallery uploaded">' +
                                '<img src="' + json.thumb_url + '" alt="" class="img-fluid">' +
                                '<h5><span class="fal fa-arrows"></span> Przeciagnij, aby zmienić kolejność</h5>' +
                                '<h6><a href="/p/photos/delete?id={$item.id}">USUŃ <span class="fal fa-trash-alt"></span></a></h6>' +
                            '</div>' +
                        '</div>'
                    );
                    num = num + 1;
                    $("#upload-status").html( num + ' z ' + max  );
                    //alertify.success('Plik ' + num + ' z ' + max + ' został pomyślnie wgrany');
                }

                if( num >= max ) {
                    alertify.success("Wszystkie pliki zostały pomyślnie wgrane");
                    upload_end();
                    interval(function() {
                        location.reload();
                    },1000);
                }

                console.log( json );
            }, false);
            ajax.addEventListener("error", function(e) {
                console.error('error');
                console.log( e );
            }, false);
            ajax.addEventListener("abort", function(e) {
                console.warn('abort');
                console.log( e );
            }, false);

            ajax.send(formdata);
        }
    });
    */

    // On Click Uploader

	$(':file').off().on('fileselect', function(e, numFiles, label) {
        var attractions_id = document.getElementById('attractions_id').value;
            attractions_id = parseInt( attractions_id );

		var token = document.getElementById('token').value;
			console.log( token );

        var files = e.target.files;
            all_files = files.length;

        if( all_files > 12 ) {
            $(this).removeClass("gallery-dragover");
            var text = $("#dropdown-upload-file").data('dragleave');
            $("#dropdown-upload-file").empty().html( text ).fadeIn(250);

            alertify.alert('Można wgrać maksymalnie 12 plików jednocześnie');
            return false;
        }

        if( all_files > 12 ) {
            $(this).removeClass("gallery-dragover");
            var text = $("#dropdown-upload-file").data('dragleave');
            $("#dropdown-upload-file").empty().html( text ).fadeIn(250);

            alertify.alert('Można wgrać maksymalnie 12 plików jednocześnie');
            return false;
        }

        $("#upload-status").html( '0 z ' + all_files  );

        var text = $("#dropdown-upload-file").data('drop');
        $("#dropdown-upload-file").html( text );

        // pokazanie modala
        $("#ModalUploadProgress").modal('show');

        //upload_start();
        var num = 0;
        for( var i=0; i<all_files; i++ ) {
            var formdata = new FormData();
            formdata.append("controller" , "\\Modules\\Attractions\\Backend\\Photos");
            formdata.append("method" , "photo_uploader");
            formdata.append("upload", files[i] );
            formdata.append("attractions_id" , attractions_id);
			formdata.append("token" , token);

            var ajax = new XMLHttpRequest();
            ajax.addEventListener("load", function( event ) {
				console.log( event.target.responseText );
                var json = JSON.parse(event.target.responseText);
                if( json.error ) {
                    $("#ModalUploadProgress").modal('hide');
                    alertify.alert( 'Wystąpił błąd podczas uploadu: ' + json.msg);
                    console.error( json );
                    return false;
                }

                if( json.success ) {
                    num = num + 1;

                    var position = Math.round((num / all_files) * 100);
                    console.log( position );
                    $("#progressbar").val( position );

                    //upload_end();

                    if( num >= files.length ) {
                        $("#ModalUploadProgress").modal('hide');
                        alertify.success('Upload zakończony pomyślnie');
                        ajax.abort();

                        location.reload();
                    }

                    console.log( 'should be uploaded ' + json );
                }

                //$('.btn-remove-pdf').off().on('click' , delete_file_pdf);
            }, false);
            ajax.addEventListener("error", function(e) {
                console.error('error');
                console.log( e );
                alertify.error('Wystąpił nieoczekiwany błąd podczas uploadowanie plików');
            }, false);
            ajax.addEventListener("abort", function(e) {
                console.warn('abort');
                console.log( e );
                alertify.error('Anulowano uploadowanie plików');
            }, false);

            ajax.open( "POST", "/upload/admin/", true );
            ajax.setRequestHeader("Accept-Filetype", "image/*");
            ajax.send(formdata);
        }
    });

});

function enable_sortable()
{
    $("#photos-list-items").sortable({
        update: function( event, ui ) {
            ui.item.data('priority' , ui.placeholder.index());
            var l = 1;
            $(".ui-sortable-handle").each( function(e) {
               var li = $(this);
               var id = li.data('id');
               var priority = l;
               l = l + 1;

               if( id && priority ) {
                   $.ajax({
            	       async: true,
            	       method: "POST",
            	       url: "/ajax/users/update_gallery_position",
            	       data: { id: id, priority: priority },
            	       complete: function( r ) {

            	       }
                   });
               }
            });
            alertify.success('Zmieniono kolejność zdjęcia');
        }
    });
}

function count_photos()
{
    var photos = 0;
    $("#photos-list-items .box-gallery").each(function() {
        photos++;
    });

    return photos;
}
