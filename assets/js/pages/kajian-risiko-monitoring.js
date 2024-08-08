$(document).on("click", "#btn-reg-edit", function () {
    var id = $(this).attr("data-id");
    var url = $(this).attr("data-url");
    $.ajax({
        type: "post",
        url: url,
        data: { "id": id, "mode": "show" },
        dataType: "html",
        beforeSend: function () {
            looding('light', $("body"));
        },
        success: function (result) {
            $("#modal_general").find(".modal-title").text("Edit Risk Register Kajian Risiko");
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

$(document).on("click", "#btn-submit-register", function (e) {
    var id = $("#btn-submit-register").attr("data-id");
    var url = $("#btn-submit-register").attr("data-url");
    var idkajian = $("#btn-submit-register").attr("data-id-kajian");
    var formData = $("#form_general").serializeArray();
    formData.push({ name: "id", value: id });
    formData.push({ name: "mode", value: "edit" });
    formData.push({ name: "idkajian", value: idkajian });
    $.ajax({
        type: "post",
        url: url,
        data: $.param(formData),
        dataType: "html",
        beforeSend: function () {
            looding('light', $("#jumbotron-form"));
        },
        success: function (result) {
            $("#modal_general").modal("hide");
            $("#setTableMonitoring").html("");
            $("#setTableMonitoring").html(result);
        },
        error: function () {
            alert('Error While Showing Risk Register Kajian Risiko');
        },
        complete: function () {
            stopLooding($("#jumbotron-form"));
        }
    })

})

$(document).on("click", "#add-monitoring", function (e) {
    var url = $("#add-monitoring").attr("data-url");
    var idkajian = $("#add-monitoring").attr("data-kajian-id");
    var idmitigasi = $("#add-monitoring").attr("data-mitigasi-id");
    var formData = $("#form_general").serializeArray();
    formData.push({ name: "idmitigasi", value: idmitigasi });
    formData.push({ name: "mode", value: "create" });
    formData.push({ name: "idkajian", value: idkajian });
    $.ajax({
        type: "post",
        url: url,
        data: $.param(formData),
        dataType: "html",
        beforeSend: function () {
            looding('light', $("body"));
        },
        success: function (result) {
            $("#modal_general").find(".modal-title").text("Add Monitoring Mitigasi Risiko");
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
$(document).on("click", "#btn-edit-monitoring", function (e) {
    var url = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    var idKajian = $(this).attr("data-kajian-id");
    $.ajax({
        type: "post",
        url: url,
        data: { "mode": "edit", "id": id, "idkajian": idKajian },
        dataType: "html",
        beforeSend: function () {
            looding('light', $("body"));
        },
        success: function (result) {
            $("#modal_general").find(".modal-title").text("Add Monitoring Mitigasi Risiko");
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
$(document).on("click", "#btn-submit-monitoring", function (e) {
    var url = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    var idkajian = $(this).attr("data-id-kajian");
    var idmitigasi = $(this).attr("data-id-mitigasi");
    var type = $(this).attr("type-btn");
    var formData = $("#form_general").serializeArray();
    formData.push({ name: "id", value: id });
    formData.push({ name: "mode", value: type });
    formData.push({ name: "idkajian", value: idkajian });
    formData.push({ name: "idmitigasi", value: idmitigasi });

    $.ajax({
        type: "post",
        url: url,
        data: $.param(formData),
        dataType: "html",
        beforeSend: function () {
            looding('light', $("#jumbotron-form"));
        },
        success: function (result) {
            $("#modal_general").modal("hide");
            $("#setTableMonitoring").html("");
            $("#setTableMonitoring").html(result);
        },
        error: function () {
            alert('Error While Showing Risk Register Kajian Risiko');
        },
        complete: function () {
            stopLooding($("#jumbotron-form"));
        }
    })
})
$(document).on("click", "#btn-delete-monitoring", function (e) {
    var url = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    var idkajian = $(this).attr("data-kajian-id");
    $.ajax({
        type: "post",
        url: url,
        data: { "id": id, "mode": "delete", "idkajian": idkajian },
        dataType: "html",
        beforeSend: function () {
            looding('light', $("#setTableMonitoring"));
        },
        success: function (result) {
            $("#modal_general").modal("hide");
            $("#setTableMonitoring").html("");
            $("#setTableMonitoring").html(result);
        },
        error: function () {
            alert('Error While Showing Risk Register Kajian Risiko');
        },
        complete: function () {
            stopLooding($("#setTableMonitoring"));
        }
    })
})
$(document).on("click", "#btnModalRegister", function (e) {
    var url = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    $.ajax({
        type: "post",
        url: url,
        data: { "id": id },
        dataType: "html",
        beforeSend: function () {
            looding('light', $("body"));
        },
        success: function (result) {
            $("#modal_general").find(".modal-title").text("Kajian Risiko Monitoring");
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




