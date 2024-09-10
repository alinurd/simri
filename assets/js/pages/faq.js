$(document).on('change', "#dokumen_faq", function () {
    var filename = $(this).val().split("\\");
    $("#label-dokumen-faq").html(filename[2]);
});