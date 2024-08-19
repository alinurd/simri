$(document).on("click", "#btn-history", function (e) {
    var kajian_id = $(this).attr("id-kajian");
    var url = $(this).attr("data-url");
    $.ajax({
        type: "post",
        url: url,
        data: { "id_kajian": kajian_id },
        dataType: "html",
        beforeSend: function () {
            looding('light', $("body"));
        },
        success: function (result) {
            $("#modal_general").find(".modal-title").text("History Approval Kajian Risiko");
            $("#modal_general").find(".modal-body").html(result);
            $("#modal_general").modal("show");
        },
        error: function () {
            alert('Error While Showing Risk Register Kajian Risiko');
        },
        complete: function () {
            stopLooding($("body"));
        }
    })
})