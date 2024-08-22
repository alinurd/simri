$(function () {
    $('.pickadate-kajian').pickadate({
        selectMonths: false,
        selectYears: false,
        formatSubmit: 'yyyy/mm/dd',
        hiddenName: true
    });

    $('.selectpic').select2({
        allowClear: false,
        multiple: true,
    });
    $('.select-form').select2({
        allowClear: false,
    });
    if (window.setPicSelect) {
        setPicselect(setPicSelect);
    }
    $('#form-register').validate();
})

// $(document).on("click", "#btn-history", function (e) {
//     e.preventDefault();
//     var kajian_id = $(this).attr("id-kajian");
//     var url = $(this).attr("data-url");
//     $.ajax({
//         type: "post",
//         url: url,
//         data: { "id_kajian": kajian_id },
//         dataType: "html",
//         beforeSend: function () {
//             looding('light', $("body"));
//         },
//         success: function (result) {
//             $("#modal_general").find(".modal-title").text("History Approval Kajian Risiko");
//             $("#modal_general").find(".modal-body").html(result);
//             $("#modal_general").modal("show");
//         },
//         error: function () {
//             alert('Error While Showing Risk Register Kajian Risiko');
//         },
//         complete: function () {
//             stopLooding($("body"));
//         }
//     })
// })

$(document).on("click", "#btn-submit-register", function (e) {
    var form = $('#form-register');
    if (form.valid()) {
        form.submit();
    }
})

$(document).on("click", "#btn-upload-dokument-mr", function (e) {
    var url = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    $.ajax({
        type: "post",
        url: url,
        data: { csrf_token_name: csrf_hash },
        cache: false,
        dataType: "html",
        beforeSend: function () {
            looding('light', $("#btn-upload-dokument-mr"));
        },
        success: function (result) {
            $("#result-dokumen").html(result);
            $("#modal_dokumen_mr").modal("show");
        },
        error: function () {
            alert('Error While Showing Upload Dokumen Risiko MR');
        },
        complete: function () {
            stopLooding($("#btn-upload-dokument-mr"));
        }
    })

})

$(document).on("click", "#submit-dokumen-mr", function (e) {
    var formUpload = new FormData();
    var url = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    var getfileexist = $("#file-exist").val();
    var getFileInput = document.getElementById('dokumen-mr-input').files[0];
    formUpload.append("id", id);
    formUpload.append("file", getFileInput);
    formUpload.append("fileexist", getfileexist);
    formUpload.append(csrf_token_name, csrf_hash);
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
            looding('light', $("#card-dokumen-risiko-mr"));
        },
        success: function (result) {
            $("#result-dokumen").html(result);
        },
        error: function () {
            alert('Error While Showing Risk Register Kajian Risiko');
        },
        complete: function () {
            stopLooding($("#card-dokumen-risiko-mr"));
        }
    })

})

$(document).on("click", "#btn-clear-dokumen", function (e) {
    var formUpload = new FormData();
    var url = $(this).attr("data-url");
    var filename = $(this).attr("data-filename");

    $.ajax({
        type: "post",
        url: url,
        data: { csrf_token_name: csrf_hash, "filename": filename },
        cache: false,
        dataType: "html",
        beforeSend: function () {
            looding('light', $("#result-dokumen"));
        },
        success: function (result) {
            $("#result-dokumen").html(result);
        },
        error: function () {
            alert('Error While Showing Risk Register Kajian Risiko');
        },
        complete: function () {
            stopLooding($("#result-dokumen"));
        }
    })
})

$(document).on('change', "#dokumen-mr-input", function () {
    var filename = $(this).val().split("\\");
    $("#label-dokumen-mr-input").html(filename[2]);
});

function setPicselect(paramdatapic) {
    if (paramdatapic != "") {
        var dataPic = JSON.parse(paramdatapic);
        $.each($(".selectpic"), function (i, v) {
            $(this).val(dataPic[i]).change();
        });
    }
}

$(document).on("click", "#btnModalRegister", function () {
    var id = $(this).attr("data-id");
    var url = $(this).attr("data-url");
    $.ajax({
        type: "post",
        url: url,
        data: { "id_kajian": id },
        dataType: "html",
        beforeSend: function () {
            looding('light', $("body"));
        },
        success: function (result) {
            $(".modal-title").text("Risk Register Kajian Risiko");
            $("#modal_general").find(".modal-title").text("Risk Register Kajian Risiko");
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

$(document).on("click", "#risiko-name", function (e) {
    var parent = $(this).parent();
    var id = 1;
    var data = { 'id': 0, 'rcsa_detail_no': id, 'bk_tipe': 1 };
    var url = "risk-context/get-peristiwa";
    _ajax_("post", parent, data, '', url, 'risiko');
})

$(document).on("click", "#addPeristiwa", function () {
    var parent = $(this).parent();
    var id = 1;
    var data = { 'id': 0, 'rcsa_detail_no': id, 'bk_tipe': 1 };
    var url = "risk-context/add-peristiwa";
    _ajax_("post", parent, data, '', url, 'risiko');
})

$(document).on("click", "#pilihPeristiwa", function () {
    var idPeristiwa = $(this).data('id');

    var peristiwaName = $("#peristiwaName" + idPeristiwa).val();
    $('#risiko').val(idPeristiwa).trigger('change');
    $('#risiko-name').val(peristiwaName).trigger('change');

    var tipeName = $("#tipeName" + idPeristiwa).val();
    var tipeId = $("#tipeId" + idPeristiwa).val();
    $('#tipe-risiko').val(tipeId).trigger('change');
    $('#tipe-risiko-name').val(tipeName).trigger('change');

    var tasktonomiName = $("#tasktonomiName" + idPeristiwa).val();
    var tasktonomiId = $("#tasktonomiId" + idPeristiwa).val();
    $('#taksonomi').val(tasktonomiId).trigger('change');
    $('#taksonomi-name').val(tasktonomiName).trigger('change');

    $("#modal_general").modal("hide");
});

function risiko(result) {
    _similarity_lib(2, 70)
    $("#modal_general").find(".modal-title").html("Risiko");
    $("#modal_general").find(".modal-body").html(result.combo);
    $("#modal_general").modal("show");
}

$(document).on("click", "#getPeristiwa, #backListPeritwa", function () {
    var parent = $(this).parent();
    var id = 1;
    var data = { 'id': 0, 'rcsa_detail_no': id, 'bk_tipe': 1 };
    var url = "risk-context/get-peristiwa";
    _ajax_("post", parent, data, '', url, 'risiko');
})

$(document).on("click", ".savePeristiwa", function () {
    var parent = $(this).parent();
    var data = $("#form_peristiwa_baru").serializeArray();
    var hasil = true;
    pesan = "data dibawah ini wajib diisi:\n";

    if ($('input[name="peristiwaBaru"]').val() == 0) {
        hasil = false;
        pesan += "- Peristiwa Risiko\n";
    }

    $('select[name="kelBaru"]').each(function () {
        if ($(this).val() == 0) {
            hasil = false;
            pesan += "- Tasksonomi\n";
        }
    });
    $('select[name="tipeBaru"]').each(function () {
        if ($(this).val() == 0) {
            hasil = false;
            pesan += "- Tipe Risiko\n";
        }
    });

    if (!hasil) {
        alert(pesan);
        return false;
    }

    var url = "risk-context/simpan-peristiwa";
    _ajax_("post", parent, data, "", url, "resSavePeristiwa");
});

function resSavePeristiwa(lib) {
    $('#risiko').val(lib.idPeristiwa).trigger('change');
    $('#risiko-name').val(lib.peristiwaName).trigger('change');

    $('#tipe-risiko').val(lib.tipeId).trigger('change');
    $('#tipe-risiko-name').val(lib.tipeName).trigger('change');

    $('#taksonomi').val(lib.tasktonomiId).trigger('change');
    $('#taksonomi-name').val(lib.tasktonomiName).trigger('change');

    $("#modal_general").modal("hide");

}

$(document).on("click", ".btn-add-cause", function () {
    var getLenghtInputIdentity = $(".count-penyebab").length + 1;
    var htmlString = "<tr class='row-penyebab'><td><input type='hidden' value='' name='risk_cause[]' id='penyebab-risiko'><input type='text' class='form-control getLibrary count-penyebab bg-white border' id='penyebab_id_text' identity='" + getLenghtInputIdentity + "' placeholder='Penyebab Risiko' readonly required='required'></td><td><button type='button' class='btn btn-danger btn-del-cause btn-sm'><i class='icon-bin'></i></button></td></tr>";
    $(htmlString).insertAfter($(".row-penyebab").last());
    getLenghtInputIdentity = 0;
});

$(document).on("click", ".btn-add-impact", function () {
    var getLenghtInputIdentity = $(".count-dampak").length + 1;
    var htmlString = "<tr class='row-dampak'><td><input type='hidden' value='' name='risk_impact[]' id='dampak-risiko'><input type='text' class='form-control getLibrary count-dampak bg-white border' id='dampak_id_text' identity='" + getLenghtInputIdentity + "' placeholder='Dampak Risiko' readonly required='required'></td><td><button type='button' class='btn btn-danger btn-del-cause btn-sm'><i class='icon-bin'></i></button></td></tr>";
    $(htmlString).insertAfter($(".row-dampak").last());
    getLenghtInputIdentity = 0;
});

$(document).on("click", ".btn-add-mitigasi", function () {
    var optionSelectPic = "";
    $.each(JSON.parse(getDeptData), function (i, v) {
        optionSelectPic += "<option value='" + i + "'>" + v + "</option>";
    });
    var getLenghtInputIdentity = $(".row-mitigasi").length + 1;
    var htmlString = "<tr class='row-mitigasi border-top'><td><textarea  name='risk_mitigasi[mitigasi][]' class='form-control mitigasi' placeholder='Mitigasi' required='required'></textarea></td><td><select select class='form-control pic selectpic' name = 'risk_mitigasi[pic][" + getLenghtInputIdentity + "][list][]' required = 'required' >" + optionSelectPic + "</select></td ><td><input type='text' name='risk_mitigasi[deadline][]' class='form-control deadline pickadate-kajian bg-white border' placeholder='Deadline' required='required'></td><td><button type='button' class='btn btn-danger btn-sm btn-del-mitigasi'><i class='icon-bin'></i></button></td></tr > ";
    $(htmlString).insertAfter($(".row-mitigasi").last());
    getLenghtInputIdentity = 0;

    $('.pickadate-kajian').pickadate({
        selectMonths: false,
        selectYears: false,
        formatSubmit: 'yyyy/mm/dd',
        hiddenName: true
    });

    $('.selectpic').select2({
        allowClear: false,
        multiple: true,
    });
});

$(document).on("click", ".btn-del-cause", function () {
    $(this).parent().parent().remove();
})

$(document).on("click", ".btn-del-impact", function () {
    $(this).parent().parent().remove();
})

$(document).on("click", ".btn-del-mitigasi", function () {
    $(this).parent().parent().remove();
})

$(document).on('click', ".getLibrary, .backListLibrary", function (e) {
    var data = { 'lib': $(this).attr("id"), "identity": $(this).attr("identity") };
    var url = "risk-context/getLibraryModal";
    _ajax_("post", $(this).parent(), data, '', url, "library_modal");
})

function library_modal(result) {
    $("#modal_general").find(".modal-title").html(result.title);
    $("#modal_general").find(".modal-body").html(result.content);
    $("#modal_general").modal("show");
}

$(document).on("click", "#pilihLibrary", function () {
    var idLib = $(this).data('id');
    var typeLib = $(this).attr("data-lib");
    var libName = $("#libraryName" + idLib).val();
    var identity = $("#identity" + idLib).val();
    switch (parseInt(typeLib)) {
        case 1:
            $('#penyebab_id_text[identity="' + identity + '"]').prev("input").val(idLib);
            // $('input[name="penyebab_id"][identity="' + identity + '"]').val(idLib).trigger('change');
            $('#penyebab_id_text[identity="' + identity + '"]').val(libName).trigger('change');
            break;
        case 3:
            $('#dampak_id_text[identity="' + identity + '"]').prev("input").val(idLib);
            // $('input[name="dampak_id"][ identity="' + identity + '"]').val(idLib).trigger('change');
            $('#dampak_id_text[identity="' + identity + '"]').val(libName).trigger('change');
            break;
        default:
            break;
    }
    $("#modal_general").modal("hide");
})

$(document).on("click", "#addLibrary", function () {
    var parent = $(this).parent();
    var libtype = $(this).attr("lib-type");
    var identity = $(this).attr("identity");
    var data = { 'lib': libtype, "identity": identity };
    var url = "risk-context/addLibrary";
    _ajax_("post", parent, data, '', url, 'listlibrary');
})

function listlibrary(result) {
    $("#modal_general").find(".modal-title").html(result.lib);
    $("#modal_general").find(".modal-body").html(result.content);
    $("#modal_general").modal("show");
}

$(document).on("click", ".saveLibrary", function () {
    var parent = $(this).parent();
    var identity = $(this).attr("identity");
    var data = $("#form_library_baru").serializeArray();
    data.push({ name: "risktype", value: $("input[name='tipe_risiko_id']").val() });
    data.push({ name: "identity", value: identity });
    var hasil = true;
    pesan = "data dibawah ini wajib diisi:\n";

    if ($('input[name="libraryBaru"]').val() == 0) {
        hasil = false;
        pesan += "- " + $(this).attr("lib-type") + "\n";
    }
    if (!hasil) {
        alert(pesan);
        return false;
    }

    var url = "risk-context/simpanLibrary";
    _ajax_("post", parent, data, "", url, "resultaddlibrary");
});

function resultaddlibrary(lib) {
    switch (parseInt(lib.tipeLib)) {
        case 1:
            // $('input[name="penyebab_id"]').val(lib.idLibrary).trigger('change');
            // $('#penyebab_id_text').val(lib.libraryName).trigger('change');

            $('#penyebab_id_text[identity="' + lib.identity + '"]').prev("input").val(lib.idLibrary);
            $('#penyebab_id_text[identity="' + lib.identity + '"]').val(lib.libraryName).trigger('change');
            break;

        case 3:

            $('#dampak_id_text[identity="' + lib.identity + '"]').prev("input").val(lib.idLibrary);
            $('#dampak_id_text[identity="' + lib.identity + '"]').val(lib.libraryName).trigger('change');

            // $('input[name="dampak_id"]').val(lib.idLibrary).trigger('change');
            // $('#dampak_id_text[identity="' + identity + '"]').prev("input").val(idLib);
            // $('#dampak_id_text').val(lib.libraryName).trigger('change');
            break;
        default:
            break;
    }
    $("#modal_general").modal("hide");
}

$(document).on("change", ".inherent-select", function (e) {
    var data = { 'likelihood': $("#inherent-likelihood").val(), "impact": $("#inherent-impact").val() };
    var url = modul_name + "/getlevelrisk";
    _ajax_("post", $("#level-inherent-risk"), data, "", url, "result_level_inherent");
})

function result_level_inherent(result) {
    if (result != null) {
        $("#inherent-level").val(result.id).trigger("change");
        $("#result-inherent-level").html("<b>" + result.level_color + "</b>");
        $("#result-inherent-level").css("background-color", result.color);
        $("#result-inherent-level").css("color", result.color_text);
    } else {
        $("#inherent-level").val("").trigger("change");
        $("#result-inherent-level").html("<b>No Result</b>");
        $("#result-inherent-level").css("background-color", "rgb(240, 240, 240)");
        $("#result-inherent-level").css("color", "rgb(0, 0, 0)");
    }

}

$(document).on("change", ".residual-select", function (e) {
    var data = { 'likelihood': $("#likelihood-residual").val(), "impact": $("#impact-residual").val() };
    var url = modul_name + "/getlevelrisk";
    _ajax_("post", $("#level-residual-risk"), data, "", url, "result_level_residual");
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

$(document).on("click", "#btn-list-upload-dokumen-mr", function (e) {
    var url = $(this).attr("data-url");
    var data = {};
    $.ajax({
        type: "post",
        url: url,
        data: data,
        cache: false,
        dataType: "html",
        beforeSend: function () {
            looding('light', $("#btn-list-upload-dokumen-mr"));
        },
        success: function (result) {
            $("#modal_general").find(".modal-title").text("Daftar Kajian Risiko Yang Belum Terupload Dokumen");
            $("#modal_general").find(".modal-body").html(result);
            $("#modal_general").modal("show");

        },
        error: function () {
            alert('Error While Showing Kajian Risiko Notif Document');
        },
        complete: function () {
            stopLooding($("#btn-list-upload-dokumen-mr"));
        }
    })
})