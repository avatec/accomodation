$(".bank-item").off().on('click' , function() {
    $(this).find('input[type=radio]').prop("checked" , 1);
    $(".bank-item").each( function() {
        $(this).removeClass("selected");
    });
    $(this).addClass("selected");
});
