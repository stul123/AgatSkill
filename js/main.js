$('.input_block input').on("blur", function() {
    var val = $(this).val();
    if (val == "") {
        $(this).next("label").removeClass("focused")
    } else {
        $(this).next("label").addClass("focused")
    }
});

$('.input_block input').on("focus", function() {
    var val = $(this).val();
    if (val != "") {
        $(this).next("label").removeClass("focused")
    }
});