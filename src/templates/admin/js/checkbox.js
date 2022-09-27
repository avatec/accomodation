$(document).ready( function() {
    reload();

    $(".checkbox label input").unbind().bind('click' , function() {
        var d = $(this).parent().parent();
        if( $(this).prop("checked") == false ) {
            d.removeClass("checkbox-checked");
        } else {
            d.addClass("checkbox-checked");
        }

        reload();
    });
});

function reload()
{
    var cb = document.querySelectorAll(".checkbox");
    if( cb.length > 0 ) {
        for( var i=0; i<cb.length; i++ ) {
            var cbl = cb[i].childNodes[0];
            if( cbl.childNodes[0].hasAttribute('checked') ) {
                cb[i].classList.add('checkbox-checked');
            }
        }
    }
}
