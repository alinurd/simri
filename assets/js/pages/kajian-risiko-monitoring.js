$(document).ajaxComplete(function () {
    $('.select').select2({
        allowClear: false
    });
    $(".pickadate").pickadate({
        selectMonths: true,
        selectYears: true,
        formatSubmit: "yyyy-mm-dd",
    });
});

$(document).on("click", "#btn-reg-edit", function () {
    var id = $(this).attr("data-id");
    var url = $(this).attr("data-url");
    $.ajax({
        type: "post",
        url: url,
        data: { "id": id, "mode": "show" },
        dataType: "html",
        beforeSend: function () {
            looding('light', $("#content-monitoring"));
        },
        success: function (result) {
            $("#modal_form").find(".modal-title").text("Edit Risk Register Kajian Risiko");
            $("#modal_form").find(".modal-body").html(result);
            $("#modal_form").modal("show");

        },
        error: function () {
            alert('Error While Showing Risk Register Kajian Risiko');
        },
        complete: function () {
            stopLooding($("#content-monitoring"));
        }
    })

})
$(document).on("click", "#btn-submit-register", function (e) {
    var id = $(this).attr("data-id");
    var url = $(this).attr("data-url");
    var idkajian = $(this).attr("data-id-kajian");
    var formData = $("#form-register-kajian").serializeArray();
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
            $("#modal_form").modal("hide");
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
$(document).on("click", ".add-monitoring", function (e) {
    var url = $(this).attr("data-url");
    var idkajian = $(this).attr("data-kajian-id");
    var idmitigasi = $(this).attr("data-mitigasi-id");
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
            looding('light', $("#content-monitoring"));
        },
        success: function (result) {
            $("#modal_form").find(".modal-title").text("Add Monitoring Mitigasi Risiko");
            $("#modal_form").find(".modal-body").html(result);
            $("#modal_form").modal("show");
        },
        error: function () {
            alert('Error While Showing Risk Register Kajian Risiko');
        },
        complete: function () {
            stopLooding($("#content-monitoring"));
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
            looding('light', $("#content-monitoring"));
        },
        success: function (result) {
            $("#modal_form").find(".modal-title").text("Edit Monitoring Mitigasi Risiko");
            $("#modal_form").find(".modal-body").html(result);
            $("#modal_form").modal("show");
        },
        error: function () {
            alert('Error While Showing Risk Register Kajian Risiko');
        },
        complete: function () {
            stopLooding($("#content-monitoring"));
        }
    })
})
$(document).on("click", "#btn-submit-monitoring", function (e) {

    var formUpload = new FormData();
    var url = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    var idkajian = $(this).attr("data-id-kajian");
    var idmitigasi = $(this).attr("data-id-mitigasi");
    var type = $(this).attr("type-btn");
    var filename = $("#file-monitoring").val();
    var getFileInput = document.getElementById('dokumen_pendukung').files[0];
    var dataForm = $("#form-monitoring").serializeArray();
    formUpload.append("id", id);
    formUpload.append("mode", type);
    formUpload.append("idkajian", idkajian);
    formUpload.append("idmitigasi", idmitigasi);
    formUpload.append("file_monitoring", filename);
    formUpload.append("file", getFileInput);
    $.each(dataForm, function (i, v) {
        formUpload.append(v.name, v.value);
    })
    $.ajax({
        type: "post",
        url: url,
        data: formUpload,
        enctype: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        dataType: "html",
        beforeSend: function () {
            looding('light', $("#jumbotron-form"));
        },
        success: function (result) {
            $("#modal_form").modal("hide");
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
$(document).on("change", ".residual-select", function (e) {
    var data = { 'likelihood': $("#likelihood-residual").val(), "impact": $("#impact-residual").val() };
    var url = "/kajian-risiko/getlevelrisk";
    _ajax_("post", $("#result-residual-level"), data, "", url, "result_level_residual");
})
function result_level_residual(result) {
    if (result != null) {
        $("#risk-residual").val(result.id).trigger("change");
        $("#result-residual-level").html("<b>" + result.level_color + "</b>");
        $("#result-residual-level").css("background-color", result.color);
        $("#result-residual-level").css("color", result.color_text);
    } else {
        $("#risk-residual").val("").trigger("change");
        $("#result-residual-level").html("<b>No Result</b>");
        $("#result-residual-level").css("background-color", "rgb(240, 240, 240)");
        $("#result-residual-level").css("color", "rgb(0, 0, 0)");
    }
}

// $(document).on("submit", "#form-monitoring", function (e) {
//     e.preventDefault();
//     var form = $("#form-monitoring")[0];
//     // var myData = new FormData(form);
//     console.log(form.files);
//     alert('aaaa');
// });




