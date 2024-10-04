$(function () {
    $(".select-filter").select2({
        allowClear: false,
    });
})

function getdataKajianDashboard(param) {
    var year = $("select[identity='" + param.seriesName + "']").attr("filter-year");
    var url = modul_name + "/getDataModalDashboard";
    var data = { type: param.seriesName, name: param.name, year: year };
    if (typeof param.seriesIndex != 'undefined') {
        $.ajax({
            type: "post",
            url: url,
            data: data,
            cache: false,
            dataType: "json",
            beforeSend: function () {
                looding('light', $("#card_" + param.seriesName));
            },
            success: function (result) {
                $("#modal_general").find(".modal-title").text(result.title);
                $("#modal_general").find(".modal-body").html(result.content);
                $("#modal_general").modal("show");
            },
            error: function () {
                alert('Error While Getting data kajian Dashboard');
            },
            complete: function () {
                stopLooding($("#card_" + param.seriesName));
            }
        })
    }
}

$(document).on("change", ".select-filter", function (e) {
    var identity = $(this).attr("identity");
    var tahun = $(this).val();
    var url = modul_name + "/getDataChart/" + identity + "/" + tahun;
    $.ajax({
        type: "post",
        url: url,
        // data: data,
        cache: false,
        dataType: "html",
        beforeSend: function () {
            looding('light', $("#card_" + identity));
            $("#".identity).html("");
        },
        success: function (result) {
            $("#" + identity).html(result);
        },
        error: function () {
            alert('Error While Getting data kajian Dashboard');
        },
        complete: function () {
            $(".select-filter").select2({
                allowClear: false,
            });
            stopLooding($("#card_" + identity));
        }
    })
})