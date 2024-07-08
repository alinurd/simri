var tbl_mitigasi;
var tr;
var target;
var pesan = '';
var sts_penyebab_risiko = 0;
$(function () {
    $('<input>').attr({
        type: 'hidden',
        id: 'idOfHiddenInput',
        name: 'idOfHiddenInput'
    }).appendTo('#datatable-list');

    $('#datatable-list').on('init.dt', function () {
        readyCheckbox();
    }).DataTable().column(0).visible(false);
    $('#chk_list_parent').click(function (event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function () {

                this.checked = true;
            });
            $('#btn_save_modul').removeClass('disabled');
        } else {
            $(':checkbox').each(function () {
                this.checked = false;

            });
            $("#idOfHiddenInput").val('');
            $('#btn_save_modul').addClass('disabled');
        }
    });

    $('#btn_lap').click(function (event) {
        event.preventDefault();
        var x = $(this);
        var jml = 0;
        var data = $("#idOfHiddenInput").val();
        let triggerDelay = 100;
        let removeDelay = 1000;

        if (data != "") {
            looding('light', x.parent().parent());
            $.ajax({
                type: 'post',
                url: x.data('url'),
                data: { id: data },
                dataType: "json",
                success: function (result) {
                    stopLooding(x.parent().parent());
                    $.each(result, function (index, val) {
                        _createIFrame(val, index * triggerDelay, removeDelay);
                    })
                },
                error: function (msg) {
                    stopLooding(x.parent().parent());
                },
                complate: function () { }
            })
        }
    });

    $('#btn_lap_sum').click(function (event) {
        event.preventDefault();
        var x = $(this);
        var jml = 0;
        var data = $("#idOfHiddenInput").val();
        let triggerDelay = 100;
        let removeDelay = 1000;

        if (data != "") {
            looding('light', x.parent().parent());
            $.ajax({
                type: 'post',
                url: x.data('url'),
                data: { id: data },
                // dataType: "json",
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (result) {
                    stopLooding(x.parent().parent());

                    var blob = new Blob([result], { type: 'appalication/vnd.ms-excel' });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'Laporan-Risk-Register-Summary.xls';

                    document.body.appendChild(link);

                    link.click();

                    document.body.removeChild(link);
                },
                error: function (msg) {
                    stopLooding(x.parent().parent());
                },
                complate: function () { }
            })
        }
    });

    $(document).on('click', 'input[name="chk_list[]"]', function (event) {


        var len = $('input[name="chk_list[]"]:checked').length;
        if (len > 0) {
            $('#btn_save_modul').removeClass('disabled');
            $('#chk_list_parent').prop('checked', true);
        } else {
            $('#btn_save_modul').addClass('disabled');
            $('#chk_list_parent').prop('checked', false);
        }
        updateCheckboxes($(this));
    });

    $(document).on("click", "#btn_reset_one", function () {
        if (confirm("Anda akan membatalkan approval untuk risk context ini, \nYakin akan melanjutkan ?")) {
            var parent = $(this).parent();
            var nilai = $(this).attr('data-id');
            var data = { 'id': nilai };
            var url = modul_name + "/reset-approval";
            _ajax_("post", parent, data, '', url, 'reset_approval');
        }
    })

    $("#period_id").change(function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        var target_combo = $("#term_id");
        var url = "ajax/get-term";
        _ajax_("post", parent, data, target_combo, url);
    })

    $("#term_id").change(function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        var target_combo = $("#minggu_id");
        var url = "ajax/get-minggu";
        _ajax_("post", parent, data, target_combo, url);
    })



    $(document).on("change", "#penyebab_id", function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = { 'id': nilai, 'kel': 23 };
        var url = "ajax/get-library";
        _ajax_("post", parent, data, '', url, 'update_list_library');
        $(".tmpdel").remove();
    })

    $(document).on("click", "#indikator_dampak", function () {
        var parent = $(this).parent();
        // var rcsa = $(this).data('rcsa');
        var id = $(this).data('id');
        var data = { 'id': 0, 'rcsa_detail_no': id, 'bk_tipe': 1 };
        var url = modul_name + "/indikator-dampak";
        _ajax_("post", parent, data, '', url, 'indikator_dampak');
    })

    $(document).on("click", "#indikator_dampak_residual", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var data = { 'id': 0, 'rcsa_detail_no': id, 'bk_tipe': 2 };
        var url = modul_name + "/indikator-dampak";
        _ajax_("post", parent, data, '', url, 'indikator_dampak');
    })

    $(document).on("click", "#indikator_dampak_target", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var data = { 'id': 0, 'rcsa_detail_no': id, 'bk_tipe': 3 };
        var url = modul_name + "/indikator-dampak";
        _ajax_("post", parent, data, '', url, 'indikator_dampak');
    })

    $(document).on("click", "#indikator_like", function () {
        var parent = $(this).parent();
        // var rcsa = $(this).data('rcsa');
        var id = $(this).data('id');
        var dampak = $('input[name="impact_id_2"]').val();
        var kpi = $('#id_kpi').val();
        var data = { 'id': 0, 'rcsa_detail_no': id, 'bk_tipe': 1, 'dampak_id': dampak, 'id_kpi': kpi };
        var url = modul_name + "/indikator-like";
        _ajax_("post", parent, data, '', url, 'indikator_like');
    })

    $(document).on("click", "#indikator_like_residual", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var dampak = $('input[name="impact_residual_id"]').val();
        var kpi = $('#id_kpi').val();
        var data = { 'id': 0, 'rcsa_detail_no': id, 'bk_tipe': 2, 'dampak_id': dampak, 'id_kpi': kpi };

        var url = modul_name + "/indikator-like";
        _ajax_("post", parent, data, '', url, 'indikator_like');
    })

    $(document).on("click", "#indikator_like_target", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var dampak = $('input[name="impact_target_id"]').val();
        var kpi = $('#id_kpi').val();
        var data = { 'id': 0, 'rcsa_detail_no': id, 'bk_tipe': 3, 'dampak_id': dampak, 'id_kpi': kpi };
        var url = modul_name + "/indikator-like";
        _ajax_("post", parent, data, '', url, 'indikator_like');
    })

    $(document).on("click", ".delete-like-indi", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var rcsa = $(this).data('parent');
        var bk = $(this).data('bk');

        var dampak = $('input[name="impact_id_2"]').val();

        var data = { 'id': id, 'rcsa_detail_no': rcsa, 'bk_tipe': bk, 'dampak_id': dampak };
        var url = modul_name + "/delete-indikator-like";
        _ajax_("post", parent, data, '', url, 'indikator_like');
    })

    $(document).on("click", "#back_like_indi", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var bk = $(this).data('bk');
        if (parseFloat(bk) == 1) {
            var dampak = $('input[name="impact_id_2"]').val();
        } else if (parseFloat(bk) == 2) {
            var dampak = $('input[name="impact_residual_id"]').val();
        } else if (parseFloat(bk) == 3) {
            var dampak = $('input[name="impact_target_id"]').val();
        }
        var kpi = $('#id_kpi').val();
        var data = { 'id': id, 'rcsa_detail_no': id, 'bk_tipe': bk, 'dampak_id': dampak, 'id_kpi': kpi };

        var url = modul_name + "/indikator-like";
        _ajax_("post", parent, data, '', url, 'indikator_like');
    })

    $(document).on("click", "#add_like_indi", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var rcsa = $(this).data('parent');
        var kpi = $('#id_kpi').val();
        var data = { 'id': id, 'rcsa_detail_no': rcsa, 'bk_tipe': 1, 'id_kpi': kpi };
        var url = modul_name + "/indikator-like-add";
        _ajax_("post", parent, data, '', url, 'indikator_like');
    })

    // function edit 
    $(document).on("click", ".update-like-indi", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var rcsa = $(this).data('parent');
        var kpi = $('#id_kpi').val();
        var data = { 'id': id, 'rcsa_detail_no': rcsa, 'bk_tipe': 1, 'id_kpi': kpi };
        // url edit ke controller
        var url = modul_name + "/indikator-like-add";
        _ajax_("post", parent, data, '', url, 'indikator_like');
    })

    $(document).on("click", ".update-like-indi-residual", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var rcsa = $(this).data('parent');
        var kpi = $('#id_kpi').val();
        var data = { 'id': id, 'rcsa_detail_no': rcsa, 'bk_tipe': 2, 'id_kpi': kpi };
        var url = modul_name + "/indikator-like-add";
        _ajax_("post", parent, data, '', url, 'indikator_like');
    })

    $(document).on("click", ".update-like-indi-target", function () {
        var parent = $(this).parent();
        var id = $(this).data('id');
        var rcsa = $(this).data('parent');
        var kpi = $('#id_kpi').val();
        var data = { 'id': id, 'rcsa_detail_no': rcsa, 'bk_tipe': 3, 'id_kpi': kpi };
        var url = modul_name + "/indikator-like-add";
        _ajax_("post", parent, data, '', url, 'indikator_like');
    })

    $(document).on('change', '#aspek_risiko_id', function () {
        var parent = $(this).parent().parent().parent();
        var id = $(this).val();
        var text = $(this).find("option:selected").text();
        if (text == "dll") {
            $("#aspek_det").parent().parent().parent().show()
        } else {
            $("#aspek_det").parent().parent().parent().hide()
        }
        var data = { 'id': id };
        var target_combo = $("#like_id_3");
        var url = "ajax/get-like-aspekrisiko";
        _ajax_("post", parent, data, target_combo, url);
    })


    // simpan like indi 
    $(document).on('click', '#simpan_like_indi', function () {
        var parent = $(this).parent().parent().parent();

        var bk = $('input[name="bk_tipe"]').val();
        if (parseFloat(bk) == 1) {
            var dampak = $('input[name="impact_id_2"]').val();
        } else if (parseFloat(bk) == 2) {
            var dampak = $('input[name="impact_residual_id"]').val();
        } else if (parseFloat(bk) == 3) {
            var dampak = $('input[name="impact_target_id"]').val();
        }
        var data = $("#form_like_indi").serializeArray();
        data.push({ name: "dampak_id", value: dampak });
        var target_combo = $("#parent_risk");
        // ke controller untuk simpan 
        var url = modul_name + "/simpan-like-indi";
        _ajax_("post", parent, data, target_combo, url, 'indikator_like');
    })

    $(document).on("change", 'input[name=\"tipe_analisa_no\"]', function () {
        var nilai = $(this).val();
        $("#div_analisa_kualitatif").removeClass("d-none").addClass("d-none");
        $("#div_analisa_kuantitatif").removeClass("d-none").addClass("d-none");
        $("#div_analisa_semi").removeClass("d-none").addClass("d-none");
        if (nilai == 1) {
            $("#div_analisa_kualitatif").removeClass("d-none");
        } else if (nilai == 2) {
            $("#div_analisa_kuantitatif").removeClass("d-none");
        } else {
            $("#div_analisa_semi").removeClass("d-none");
        }

    })

    $(document).on("change", "#tipe_risiko_id", function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = { 'id': nilai, 'kel': 1 };
        var target_combo = $("#penyebab_id");
        var url = "ajax/get-library";
        _ajax_("post", parent, data, target_combo, url);
    })

    $(document).on("change", "#klasifikasi_risiko_id", function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = { 'id': nilai };
        var target_combo = $("#tipe_risiko_id");
        var url = "ajax/get-rist-type";
        _ajax_("post", parent, data, target_combo, url);
    })

    $(document).on("click", ".btnNext", function () {
        $('.nav-tabs').find('.active').closest('li').next('li').find('a').trigger('click');
    });

    $(document).on("change", "#like_id, #impact_id", function () {
        var parent = $(this).parent();
        var like = $("#like_id").val();
        var impact = $("#impact_id").val();
        var data = { 'like': like, 'impact': impact };
        var url = "ajax/get-risiko-inherent";
        _ajax_("post", parent, data, '', url, 'result_inherent');
    });

    $(document).on("change", "#like_id_3, #impact_id_3", function () {
        var parent = $(this).parent();
        var like = $("#like_id_3").val();

        var impact = $('input[name="impact_id_3"]').val();
        var data = { 'like': like, 'impact': impact };
        var url = "ajax/get-risiko-inherent-semi";
        _ajax_("post", parent, data, '', url, 'result_inherent');
    });

    $(document).on("change", "#like_residual_id, #impact_residual_id", function () {
        var parent = $(this).parent();
        var like = $("#like_residual_id").val();
        var impact = $("#impact_residual_id").val();
        var tipe = $('input[name=\"tipe_analisa_no\"]:checked').val();
        var data = { 'like': like, 'impact': impact, 'tipe': tipe };
        var url = "ajax/get-risiko-inherent";
        $("#like_target_id").val(like).change();
        $("#impact_target_id").val(impact).change();
        _ajax_("post", parent, data, '', url, 'result_residual');
    });

    $(document).on("change", "#like_target_id, #impact_target_id", function () {
        var parent = $(this).parent();
        var like = $("#like_target_id").val();
        var impact = $("#impact_target_id").val();
        var tipe = $('input[name=\"tipe_analisa_no\"]:checked').val();
        var data = { 'like': like, 'impact': impact, 'tipe': tipe };
        var url = "ajax/get-risiko-inherent";
        _ajax_("post", parent, data, '', url, 'result_target');
    });

    $(document).on("change", "#like_residual_id_3, #impact_residual_id_3", function () {
        var parent = $(this).parent();
        var like = $("#like_residual_id_3").val();
        var impact = $('input[name="impact_residual_id"]').val();
        var data = { 'like': like, 'impact': impact };
        console.log(data);
        var url = "ajax/get-risiko-inherent-semi";
        _ajax_("post", parent, data, '', url, 'result_residual');
    });

    $(document).on("change", "#like_target_id_3, #impact_target_id_3", function () {
        var parent = $(this).parent();
        var like = $("#like_target_id_3").val();
        var impact = $('input[name="impact_target_id"]').val();
        var data = { 'like': like, 'impact': impact };
        console.log(data);
        var url = "ajax/get-risiko-inherent-semi";
        _ajax_("post", parent, data, '', url, 'result_target');
    });


    $(document).on("click", ".btnPrevious", function () {
        $('.nav-tabs').find('.active').closest('li').prev('li').find('a').trigger('click');
    });

    $(document).on("click", "#back_identifikasi", function () {
        var parent = $(this).parent().parent().parent();
        var nilai = $(this).data('id');
        var data = {
            'id': nilai
        };
        var target_combo = $("#parent_risk");
        var url = modul_name + "/identifikasi-risiko";
        _ajax_("post", parent, data, target_combo, url);
        $('.card-footer').html('');
    })

    $(document).on("click", "#add_identifikasi", function () {
        var parent = $(this).parent().parent().parent();
        var nilai = $(this).data('id');
        var data = {
            'id': nilai
        };
        var target_combo = $("#parent_risk");
        var url = modul_name + "/add-identifikasi";
        _ajax_("post", parent, data, target_combo, url);
        $('.card-footer').html('<span style="color:red;">*) Wajib diisi </span>');
    })

    $(document).on("click", ".update-identifikasi", function () {
        var parent = $(this).parent().parent().parent();
        var nilai = $(this).data('rcsa');
        var edit = $(this).data('id');
        var data = {
            'id': nilai,
            'edit': edit
        };
        var target_combo = $("#parent_risk");
        var url = modul_name + "/edit-identifikasi";
        _ajax_("post", parent, data, target_combo, url);
        $('.card-footer').html('<span style="color:red;">*) Wajib diisi </span>');
        setTimeout(function () {
            var text = $("#aspek_risiko_id").find("option:selected").text();
            if (text == "dll") {
                $("#aspek_det").parent().parent().parent().show()
            } else {
                $("#aspek_det").parent().parent().parent().hide()
            }
        }, 1000);

    })

    $(document).on("click", ".add-text-peristiwa", function () {
        var id = $(this).attr('data-id');

        if (id == 0) {
            $(this).closest("td").closest("tr").find("input[name=\"peristiwa_id[]\"]").hide();
            $(this).closest("td").closest("tr").find(".select2-container").hide();
            $(this).closest("td").closest("tr").find("#peristiwa_id_text").removeClass("d-none");
            $(this).closest("td").closest("tr").find("#peristiwa_id_text").val("").focus();
            $(this).removeClass("icon-file-empty").addClass('icon-dash');
            $(this).attr("data-id", 1);
        } else {
            $(this).closest("td").closest("tr").find("input[name=\"peristiwa_id[]\"]").show();
            $(this).closest("td").closest("tr").find(".select2-container").show();
            $(this).closest("td").closest("tr").find("#peristiwa_id_text").addClass("d-none");
            $(this).closest("td").closest("tr").find("#peristiwa_id_text").val("").focus();
            $(this).removeClass("icon-dash").addClass('icon-file-empty');
            $(this).attr("data-id", 0);
        }
    })

    $(document).on("click", ".add-text-dampak", function () {
        var id = $(this).attr('data-id');

        if (id == 0) {
            $(this).closest("td").closest("tr").find("input[name=\"dampak_id[]\"]").hide();
            $(this).closest("td").closest("tr").find(".select2-container").hide();
            $(this).closest("td").closest("tr").find("#dampak_id_text").removeClass("d-none");
            $(this).closest("td").closest("tr").find("#dampak_id_text").val("").focus();
            $(this).removeClass("icon-file-empty").addClass('icon-dash');
            $(this).attr("data-id", 1);
        } else {
            $(this).closest("td").closest("tr").find("input[name=\"dampak_id[]\"]").show();
            $(this).closest("td").closest("tr").find(".select2-container").show();
            $(this).closest("td").closest("tr").find("#dampak_id_text").addClass("d-none");
            $(this).closest("td").closest("tr").find("#dampak_id_text").val("").focus();
            $(this).removeClass("icon-dash").addClass('icon-file-empty');
            $(this).attr("data-id", 0);
        }
    })

    $(document).on("click", ".manual_combo", function () {
        var id = $(this).attr('data-id');
        var key = $(this).data('key');

        if (key == 'penyebab_id') {
            $(".tmpdel").remove();
        }

        if (id == 0) {
            $(this).closest(".form-group").find("#" + key).hide();
            $(this).closest(".form-group").find(".select2-container").hide();
            $(this).closest(".form-group").find("#txt_" + key).removeClass("d-none");
            $(this).closest(".form-group").find("#txt_" + key).val("").focus();
            $(this).find('i').removeClass("icon-file-empty").addClass('icon-dash');
            $(this).attr("data-id", 1);

            if (key == 'penyebab_id') {
                $("#peristiwa_id_text").removeClass("d-none");
                $("#dampak_id_text").removeClass("d-none");
                $("#peristiwa_id").addClass("d-none");
                $("#dampak_id").addClass("d-none");
                $("#peristiwa_id").closest("td").find(".select2-container").hide();;
                $("#dampak_id").closest("td").find(".select2-container").hide();;
            }

            sts_penyebab_risiko = 1;
        } else {
            $(this).closest(".form-group").find("#" + key).show();
            $(this).closest(".form-group").find(".select2-container").show();
            $(this).closest(".form-group").find("#txt_" + key).addClass("d-none");
            $(this).closest(".form-group").find("#txt_" + key).val("");
            $(this).find('i').removeClass("icon-dash").addClass('icon-file-empty');
            $(this).attr("data-id", 0);
            sts_penyebab_risiko = 0;

            if (key == 'penyebab_id') {
                $("#peristiwa_id_text").addClass("d-none");
                $("#dampak_id_text").addClass("d-none");
                $("#peristiwa_id").removeClass("d-none");
                $("#dampak_id").removeClass("d-none");
                $("#peristiwa_id").closest("td").find(".select2-container").show();;
                $("#dampak_id").closest("td").find(".select2-container").show();;
            }
        }

    })
    $(document).on("click", "#add_text_like", function () {
        var id = $(this).attr('data-id');
        console.log(id);
        if (id == 0) {
            $(this).closest(".form-group").find("#kri_id").hide();;
            $(this).closest(".form-group").find(".select2-container").hide();;
            $(this).closest(".form-group").find("#txt_like").removeClass("d-none");
            $(this).closest(".form-group").find("#txt_like").val("").focus();
            $(this).find('i').removeClass("icon-plus-circle2").addClass('icon-dash');
            $(this).attr("data-id", 1);
        } else {
            $(this).closest(".form-group").find("#kri_id").show();;
            $(this).closest(".form-group").find(".select2-container").show();;
            $(this).closest(".form-group").find("#txt_like").addClass("d-none");
            $(this).closest(".form-group").find("#txt_like").val("");
            $(this).find('i').removeClass("icon-dash").addClass('icon-plus-circle2');
            $(this).attr("data-id", 0);
        }
    })

    $(document).on("change", ".tipe_kri", function () {
        var parent = $(this).parent().parent().parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        var target_combo = $(this).closest('tr').find('.kri');
        var url = "ajax/get-kri";
        _ajax_("post", parent, data, target_combo, url);
    })

    // $(document).on("change",".kri", function () {
    // 	var mak=0;
    // 	var cek=0;
    // 	$(".kri").each(function() {
    // 		cek = ($('option:selected',this).index()); 
    // 		if (cek>mak){
    // 			mak=cek;
    // 		}
    // 	});
    // 	var parent = $(this).parent().parent().parent();
    // 	var like_id = $('input[name="like_id"]').val();
    // 	var data = {
    // 		'id': mak,
    // 		'like_id': like_id,
    // 	};
    // 	var target_combo = $(this).closest('tr').find('.kri');
    // 	var url = "ajax/get-risiko-dampak";
    // 	_ajax_("post", parent, data, target_combo, url, 'result_dampak');
    // })

    $(document).on('click', '#simpan_dampak_indi', function () {
        var mak = 0;
        var cek = 0;
        $(".kri").each(function () {
            cek = ($('option:selected', this).index());
            if (cek > mak) {
                mak = cek;
            }
        });
        var parent = $(this).parent().parent().parent();
        var tipe = $('input[name=\"tipe_analisa_no\"]:checked').val();
        var bktipe = $('input[name=\"bk_tipe\"]').val();
        console.log(tipe);
        console.log(bktipe);
        if (tipe == 2) {
            if (bktipe == 2) {
                var like_id = $('input[name="like_residual_id"]').val();
            } else if (bktipe == 3) {
                var like_id = $('input[name="like_target_id"]').val();
            } else {
                var like_id = $('input[name="like_id_2"]').val();
            }
        } else {
            if (bktipe == 2) {
                var like_id = $('#like_residual_id_3 :selected').data('temp');
            } else if (bktipe == 3) {
                var like_id = $('#like_target_id_3 :selected').data('temp');
            } else {
                var like_id = $('#like_id_3 :selected').data('temp');
            }
        }
        console.log(like_id);



        var parent = $(this).parent().parent().parent();
        var data = $("#form_dampak_indi").serializeArray();
        data.push({ name: 'like_id', value: like_id });
        data.push({ name: 'mak', value: mak });
        var target_combo = '';
        var url = modul_name + "/simpan-dampak-indi";
        _ajax_("post", parent, data, target_combo, url, 'result_dampak');
    })

    $(document).on("click", "#add_dampak_indi", function () {
        var cbo = '<select name="peristiwa_id[]" id="peristiwa_id" class="form-control select" style="width:100%;">' + cboperistiwa + '</select>';
        var row = $("#tbl_list_dampak_indi > tbody");

        row.append('<tr class="tmpdel"><td>&nbsp;</td><td style="padding-left:0px;">' + tipe_kri + edit_dampak + '</td><td style="padding-left:0px;">' + kri + '</td><td style="padding-left:0px;">' + detail + '</td><td class="text-center pointer" style="padding-right:0px;"><i class="icon-database-remove text-danger-400 del-peristiwa"></i></td></tr>');

        $('.select').select2({
            allowClear: false
        });
    });

    $(document).on('click', '.del-dampak-indi', function () {
        if (confirm(Globals.hapus)) {
            var parent = $(this).parent().parent().parent();
            var nilai = $(this).data('id');
            var data = {
                'id': nilai
            };
            var target_combo = '';
            var url = modul_name + "/delete-indikator-dampak";
            _ajax_("post", parent, data, target_combo, url);
            $(this).closest('td').closest('tr').remove();
        }
    });

    $(document).on("click", ".add-peristiwa", function () {
        if (sts_penyebab_risiko == 0) {
            var cbo = '<select name="peristiwa_id[]" id="peristiwa_id" class="form-control select" style="width:100%;">' + cboperistiwa + '</select>';
            cbo += '<input type="text" name="peristiwa_id_text[]" value="" class="form-control d-none" id="peristiwa_id_text" placeholder="Peristiwa Risiko">';
        } else {
            var cbo = '<input type="text" name="peristiwa_id_text[]" value="" class="form-control" id="peristiwa_id_text" placeholder="Peristiwa Risiko">';
        }
        var row = $("#tblperistiwa > tbody");

        row.append('<tr class="tmpdel"><td style="padding-left:0px;">' + cbo + '</td><td class="text-right pointer" width="10%" style="padding-right:0px;"><i class="icon-database-remove text-danger-400 del-peristiwa"></i></i>&nbsp;&nbsp;<i class="icon-file-empty text-success-400 add-text-peristiwa" data-id="0"></i></td></tr>');

        if (sts_penyebab_risiko == 0) {
            $('.select').select2({
                allowClear: false
            });
        }
    });

    $(document).on("click", ".add-dampak", function () {
        if (sts_penyebab_risiko == 0) {
            var cbo = '<select name="dampak_id[]" id="dampak_id" class="form-control select" style="width:100%;">' + cbodampak + '</select>';
            cbo += '<input type="text" name="dampak_id_text[]" value="" class="form-control d-none" id="dampak_id_text" placeholder="Dampak Risiko">';
        } else {
            var cbo = '<input type="text" name="dampak_id_text[]" value="" class="form-control" id="dampak_id_text" placeholder="Dampak Risiko">';
        }
        var row = $("#tbldampak > tbody");

        row.append('<tr class="tmpdel"><td style="padding-left:0px;">' + cbo + '</td><td class="text-right pointer" width="10%" style="padding-right:0px;"><i class="icon-database-remove text-danger-400 del-dampak"></i></i>&nbsp;&nbsp;<i class="icon-file-empty text-success-400 add-text-dampak" data-id="0"></i></td></tr>');

        if (sts_penyebab_risiko == 0) {
            $('.select').select2({
                allowClear: false
            });
        }
    });

    $(document).on('click', '.del-peristiwa', function () {
        if (confirm(Globals.hapus)) {
            $(this).closest('td').closest('tr').remove();
        }
    });

    $(document).on('click', '.del-dampak', function () {
        if (confirm(Globals.hapus)) {
            $(this).closest('td').closest('tr').remove();
        }
    });
    $(document).on('change', '#treatment_id', function () {
        var nilai = $(this).val();
        if (nilai == 1) {
            $('#efek_mitigasi').val('4');
            $('#efek_mitigasi').trigger('change');
        }
    });
    $(document).on('change', 'select[name=\"peristiwa_id[]\"], select[name=\"penyebab_id\"]', function () {
        var text = '';
        $("select[name=\"peristiwa_id[]\"] :selected").each(function (i, sel) {
            text += $(sel).text() + ' karena ';
        });

        $("select[name=\"penyebab_id\"] :selected").each(function (i, sel) {
            text += $(sel).text() + ' karena ';
        });
        text = text.substr(0, (text.length - 8));
        // console.log(text);
        $("#risiko_dept").val(text);
    });

    $(document).on('click', '#simpan_identifikasi', function () {
        var cek = cek_isian_identifikasi();
        if (cek) {
            var parent = $(this).parent().parent().parent();
            var data = new FormData($("#form_identifikasi")[0]);
            // console.log(formData);
            // var data = $("#form_identifikasi").serialize();
            var target_combo = $("#parent_risk");
            var url = modul_name + "/simpan-identifikasi";
            _ajax_file_("post", parent, data, target_combo, url);
        } else {
            alert(pesan);
        }
    })

    $(document).on('click', '#simpan_identifikasi_awal', function () {
        var cek = cek_isian_identifikasi(true);
        if (cek) {
            var parent = $(this).parent().parent().parent();
            // console.log(formData);
            var data = $("#form_identifikasi").serialize();
            var target_combo = $("#parent_risk");
            var url = modul_name + "/simpan-identifikasi-awal";
            _ajax_("post", parent, data, target_combo, url);
        } else {
            alert(pesan);
        }
    })

    $(document).on('click', '#simpan_evaluasi', function () {
        var parent = $(this).parent().parent().parent();
        var data = $("#form_identifikasi").serialize();
        var treatment = $("#treatment_id").val();
        var efek_mitigasi = $("#efek_mitigasi").val();
        var hasil = true;
        pesan = 'data dibawah ini wajib diisi:\n';

        if (!treatment) {
            hasil = false;
            pesan += '- Treatment\n';
        }
        if (efek_mitigasi == 0) {
            hasil = false;
            pesan += '- Efek Mitigasi\n';
        }

        if (!hasil) {
            alert(pesan);
            return hasil
        }
        var target_combo = $("#parent_risk");
        var url = modul_name + "/simpan-evaluasi";
        _ajax_("post", parent, data, target_combo, url);
    })

    $(document).on('click', '#simpan_target', function () {
        var parent = $(this).parent().parent().parent();
        var data = $("#form_identifikasi").serialize();
        var target_combo = $("#parent_risk");
        var url = modul_name + "/simpan-target";
        _ajax_("post", parent, data, target_combo, url);
    })

    $(document).on('click', '#add_mitigasi', function () {
        var parent = $(this).parent().parent().parent();
        var rcsa_detail = $(this).data('id');
        var data = {
            'id': 0,
            'rcsa_detail': rcsa_detail
        };
        var target_combo = '';
        var url = modul_name + "/add-mitigasi";
        _ajax_("post", parent, data, target_combo, url, 'mitigasi');
    })

    $(document).on('click', '.update-mitigasi', function () {
        var parent = $(this).parent().parent().parent();
        var nilai = $(this).data('id');
        var rcsa_detail = $(this).data('rcsa');
        var data = {
            'id': nilai,
            'rcsa_detail': rcsa_detail
        };
        var target_combo = '';
        var url = modul_name + "/add-mitigasi";
        _ajax_("post", parent, data, target_combo, url, 'mitigasi');
    })

    $(document).on('click', '.delete-mitigasi', function () {
        if (confirm(Globals.hapus)) {
            var parent = $(this).parent().parent().parent();
            var nilai = $(this).data('id');
            tr = $(this).closest('tr');
            var data = {
                'id': nilai
            };
            var target_combo = '';
            var url = modul_name + "/delete-mitigasi";
            _ajax_("post", parent, data, target_combo, url, 'del_mitigasi');
        }
    })

    $(document).on('click', '.delete-aktifitas-mitigasi', function () {
        if (confirm(Globals.hapus)) {
            var parent = $(this).parent().parent().parent();
            var nilai = $(this).data('id');
            tr = $(this).closest('tr');
            var data = {
                'id': nilai
            };
            var target_combo = '';
            var url = modul_name + "/delete-aktifitas-mitigasi";
            _ajax_("post", parent, data, target_combo, url, 'del_mitigasi');
        }
    })

    $(document).on('click', '.delete-identifikasi', function () {
        if (confirm(Globals.hapus)) {
            var parent = $(this).parent().parent().parent();
            var nilai = $(this).data('id');
            tr = $(this).closest('tr');
            var data = {
                'id': nilai
            };
            var target_combo = '';
            var url = modul_name + "/delete-identifikasi";
            _ajax_("post", parent, data, target_combo, url, 'del_identifikasi');
        }
    })

    $("#type_ass_id").change(function () {
        var id = $(this).val();
        if (id == 129) {
            $("#judul_ass").parent().parent().removeClass("d-none");
        } else {
            $("#judul_ass").parent().parent().addClass("d-none");
        }
    });

    $(document).on('click', '.add_aktifitas_mitigasi', function () {
        var parent = $(this).parent().parent().parent();
        var nilai = $(this).data('id');
        var data = {
            'mitigasi_id': nilai,
            'part': 0,
            'id': 0
        };
        var target_combo = '';
        var url = modul_name + "/add-aktifitas-mitigasi";
        _ajax_("post", parent, data, target_combo, url, 'aktifitas_mitigasi');
    })

    $(document).on('click', '#add_aktifitas_mitigasi', function () {
        var parent = $(this).parent().parent().parent();
        var nilai = $(this).data('id');
        var data = {
            'mitigasi_id': nilai,
            'id': 0,
            'part': 1,
        };
        var target_combo = $("#entry_aktifitas_mitigasi");
        var url = modul_name + "/add-aktifitas-mitigasi";
        _ajax_("post", parent, data, target_combo, url);
    })

    $(document).on('click', '.update-aktifitas-mitigasi', function () {
        var parent = $(this).parent().parent().parent();
        var nilai = $(this).data('rcsa');
        var id = $(this).data('id');
        var data = {
            'mitigasi_id': nilai,
            'id': id
        };
        var target_combo = $("#entry_aktifitas_mitigasi");
        var url = modul_name + "/add-aktifitas-mitigasi";
        _ajax_("post", parent, data, target_combo, url);
    })

    $(document).on('click', '#simpan_mitigasi', function () {
        var cek = cek_isian_mitigasi();
        if (cek) {
            var parent = $(this).parent().parent().parent();
            var data = $("#form_general").serialize();
            var target_combo = '';
            var url = modul_name + "/simpan-mitigasi";
            _ajax_("post", parent, data, target_combo, url, 'list_mitigasi');
        } else {
            alert(pesan);
        }
    })

    $(document).ready(function () {
        $('#division').select2({
            placeholder: "-- Select a division --",
            allowClear: false,
        });
        $("#type_ass_id").trigger("change");
    })

    $(document).on('click', '#simpan_aktifitas_mitigasi', function () {
        var cek = cek_isian_aktifitas_mitigasi();
        if (cek) {
            var parent = $(this).parent().parent().parent();
            var data = $("#form_general").serialize();
            var target_combo = $("#modal_general").find(".modal-body");
            var url = modul_name + "/simpan-aktifitas-mitigasi";
            _ajax_("post", parent, data, target_combo, url, 'result_simpan_aktifitas');
        } else {
            alert(pesan);
        }
    })

    $(document).on('click', '.copy-data', function () {
        var parent = $(this).parent().parent().parent();
        var target_combo = $("#modal_general").find(".modal-body");
        var id = $(this).data('id');
        var data = { 'id': id };
        var url = modul_name + "/" + $(this).data('url');
        _ajax_("post", parent, data, target_combo, url, 'copy_data');
    })

    $(document).on("click", "#proses_copy", function () {
        var parent = $(this).parent();
        var periode = $("#periode_copy").val();
        var term = $("#term_copy").val();
        var minggu = $("#minggu_copy").val();
        var id = $("input[name='id']").val();

        var data = {
            'periode': periode,
            'term': term,
            'minggu': minggu,
            'id': id,
        };
        var target_combo = $("#term_copy");
        var url = modul_name + "/proses-copy";
        _ajax_("post", parent, data, target_combo, url, 'proses_copy');
    })


    $(document).on('click', '.notes', function () {
        var id = $(this).data("id");
        var owner = $(this).data("owner");
        target = $('input[name="' + id + '_' + owner + '"]');
        var value = target.val();
        tr = $(this).closest('tr');
        edit_note(value);
    });

    $(document).on('keyup', "textarea[name=\"note_propose_detail\"]", function () {
        var isi = $(this).val();
        target.val(isi);
        if (isi.length > 0) {
            tr.find('.icon-notebook').removeClass('text-primary').addClass('text-primary');
        } else {
            tr.find('.icon-notebook').removeClass('text-primary');
        }
    });

    $('#modal_general').on('hidden.bs.modal', function () {
        $('.select').select2({
            allowClear: false,
        });
    })
});

function cek_isian_identifikasi(awal = false) {
    var hasil = true;
    pesan = 'data dibawah ini wajib diisi:\n';

    if (isNaN(parseFloat($('#aktifitas_id').val()))) {
        hasil = false;
        pesan += '- Aktifitas\n';
    }

    if (isNaN(parseFloat($('#sasaran_id').val()))) {
        hasil = false;
        pesan += '- Sasaran Aktifitas\n';
    }
    if (isNaN(parseFloat($('#klasifikasi_risiko_id').val()))) {
        hasil = false;
        pesan += '- Klasifikasi Risiko\n';
    }

    if ($('#tahapan').val() == '') {
        hasil = false;
        pesan += '- Tahapan Proses\n';
    }

    if (isNaN(parseFloat($('#tipe_risiko_id').val()))) {
        hasil = false;
        pesan += '- Tipe Risiko\n';
    }

    if ((($('#esg_risk').val())) == 0) {
        hasil = false;
        pesan += '- ESG Risk\n';
    }
    if ((($('#fraud_risk').val())) == 0) {
        hasil = false;
        pesan += '- Fraud Risk\n';
    }
    if ((($('#smap').val())) == 0) {
        hasil = false;
        pesan += '- SMAP\n';
    }

    if ($('#penyebab_id').val() == 0) {
        hasil = false;
        pesan += '- Penyebab Risiko\n';
    }
    if ($('#peristiwa_id').val() == 0) {
        pesan += '- Peristiwa Risiko\n';
        hasil = false;
    }
    if ($('#dampak_id').val() == 0) {
        hasil = false;
        pesan += '- Dampak Risiko\n';
    }
    if ($('#risiko_dept').val().length == 0) {
        hasil = false;
        pesan += '- Risiko Departement\n';
    }


    if (!awal) {

        var tipe = $('input[name=\"tipe_analisa_no\"]:checked').val();
        if (tipe == 1) {
            if ($('#like_text').val().length == 0 || $('#like_text').val() == 0) {
                hasil = false;
                pesan += '- Risk Indikator Likelihood\n';
            }
            if ($('#impact_text').val().length == 0 || $('#impact_text').val() == 0) {
                hasil = false;
                pesan += '- Risk Indikator Dampak\n';
            }

            if (isNaN(parseFloat($('#like_id').val()))) {
                hasil = false;
                pesan += '- Likelihood Inheren\n';
            }

            if (isNaN(parseFloat($('#impact_id').val()))) {
                hasil = false;
                pesan += '- Dampak Inheren\n';
            }
        } else if (tipe == 2) {

            if (isNaN(parseFloat($('input[name=\"indikator_like_cek\"]').val()))) {
                hasil = false;
                pesan += '- Input Risk Indikator Likelihood\n';
            }
            if (isNaN(parseFloat($('input[name=\"indikator_dampak_cek\"]').val()))) {
                hasil = false;
                pesan += '- Input Risk Indikator Dampak\n';
            }



            if (isNaN(parseFloat($('input[name=\"like_id_2\"]').val()))) {
                hasil = false;
                pesan += '- Likelihood Inheren\n';
            }
            if (isNaN(parseFloat($('input[name=\"impact_id_2\"]').val()))) {
                hasil = false;
                pesan += '- Dampak Inheren\n';
            }
        } else if (tipe == 3) {
            if (isNaN(parseFloat($('#aspek_risiko_id').val()))) {
                hasil = false;
                pesan += '- Aspek Risiko\n';
            }
            if (isNaN(parseFloat($('#like_id_3').val()))) {
                hasil = false;
                pesan += '- Likelihood Inheren\n';
            }
            // if (isNaN(parseFloat($('#impact_id_3').val()))){
            // 	hasil=false;
            // 	pesan+='- Dampak Inherent\n';
            // }
            // if (isNaN(parseFloat($('input[name=\"like_id_3\"]').val()))){
            // 	hasil=false;
            // 	pesan+='- Likelihood Inherent\n';
            // }
            if (isNaN(parseFloat($('input[name=\"impact_id_3\"]').val()))) {
                hasil = false;
                pesan += '- Dampak Inheren\n';
            }
        }

        if (isNaN(parseFloat($('#efek_kontrol').val())) || parseFloat($('#efek_kontrol').val()) == 0) {
            hasil = false;
            pesan += '- Efek Kontrol\n';
        }
    }

    return hasil;
}

var checkboxes = [];

function readyCheckbox() {
    $('input[name="chk_list[]"]:checked').each(function () {
        id = $(this).val();
        var arrPos = checkboxes.indexOf(id);
        if (arrPos == -1) {
            checkboxes.push(id);
        }
    });

    setTimeout(function () {
        $('input[name="chk_list[]"]').each(function (index) {
            idx = $(this).val();
            var arrPosx = checkboxes.indexOf(idx);
            if (!$(this).is(":checked")) {
                if (arrPosx > -1) {
                    checkboxes.splice(arrPosx, 1);
                }
            }
        });
        $("#idOfHiddenInput").val(checkboxes);
    }, 200)

    // $("#idOfHiddenInput").val(checkboxes);

}

function _createIFrame(url, triggerDelay, removeDelay) {
    //Add iframe dynamically, set SRC, and delete
    setTimeout(function () {
        var frame = $('<iframe style="display: none;" class="multi-download"></iframe>');
        frame.attr('src', url);
        $(document.body).after(frame);
        setTimeout(function () {
            frame.remove();
        }, removeDelay);
    }, triggerDelay);
}

function updateCheckboxes(checkbox) {
    //Get the row id
    var id = checkbox.val();

    //Check the array for the id
    var arrPos = checkboxes.indexOf(id);

    //If it exists and we unchecked it, remove it
    if (arrPos > -1 && !checkbox.checked) {
        checkboxes.splice(arrPos, 1);
    }
    //Else it doesn't exist and we checked it
    else {
        checkboxes.push(id);
    }

    setTimeout(function () {
        $('input[name="chk_list[]"]').each(function (index) {
            idx = $(this).val();
            var arrPosx = checkboxes.indexOf(idx);
            if (!$(this).is(":checked")) {
                if (arrPosx > -1) {
                    checkboxes.splice(arrPosx, 1);
                }
            }
        });

        $("#idOfHiddenInput").val(checkboxes);
    }, 200)
}

function cek_isian_mitigasi() {
    var hasil = true;
    pesan = 'data dibawah ini wajib diisi:\n';
    if ($('#mitigasi').val().length == 0) {
        hasil = false;
        pesan += '- Mitigasi\n';
    }

    if ($('#biaya').val().length == 0) {
        hasil = false;
        pesan += '- Biaya\n';
    }

    if (isNaN(parseFloat($('#penanggung_jawab_id').val())) || parseFloat($('#penanggung_jawab_id').val()) == 0) {
        hasil = false;
        pesan += '- Penanggung Jawab\n';
    }

    if (isNaN(parseFloat($('#koordinator_id').val())) || parseFloat($('#koordinator_id').val()) == 0) {
        hasil = false;
        pesan += '- Koordinator\n';
    }

    if (isNaN(parseFloat($('#status_jangka').val())) || parseFloat($('#status_jangka').val()) == 0) {
        hasil = false;
        pesan += '- Status Jangka\n';
    }

    if ($('#batas_waktu').val().length == 0) {
        hasil = false;
        pesan += '- Batas Waktu\n';
    }
    return hasil;
}

function cek_isian_aktifitas_mitigasi() {
    var hasil = true;
    pesan = 'data dibawah ini wajib diisi:\n';
    // console.log($('#aktifitas_mitigasi').val());
    // console.log($('#aktifitas_mitigasi').val().length);
    if ($('#aktifitas_mitigasi').val().length == 0) {
        hasil = false;
        pesan += '- Mitigasi\n';
    }

    if ($('#batas_waktu_detail').val().length == 0) {
        hasil = false;
        pesan += '- Batas Waktu\n';
    }
    if (!hasil) {
        alert(pesan);
        return hasil
    }
    return hasil;
}

function result_simpan_aktifitas(hasil) {
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("table#tbl_list_mitigasi").html(hasil.list_mitigasi);
}

function edit_note(hasil) {
    $("#modal_general").find(".modal-title").html('Tambah/Ubah Catatan');
    var note = '<strong>Masukkan catatan anda dibawah ini :</strong><br/><textarea name="note_propose_detail" cols="40" rows="10" id="note_propose_detail" placeholder="silahkan masukkan catatan anda disini" maxlength="500" size="500" class="form-control" style="overflow: hidden; width: 100% !important; height: 200px;">' + hasil + '</textarea>';

    $("#modal_general").find(".modal-body").html(note);
    $("#modal_general").modal("show");
    $("#note_propose_detail").focus();
}

function del_mitigasi(hasil) {
    tr.remove();
    alert('data terhapus');
}

function del_identifikasi(hasil) {
    tr.remove();
    alert('data terhapus');
}

function result_dampak(hasil) {

    if (hasil.bk_tipe == 1) {
        $('#impact_text_kuantitatif').val(hasil.text);
        $('input[name="impact_id_2"]').val(hasil.nil);

        $('#impact_text_kuantitatif_semi').val(hasil.text);
        $('input[name="impact_id_3"]').val(hasil.nil);

        $("#risiko_inherent_text").val(parseFloat(hasil.like_code) * parseFloat(hasil.impact_code));
        $("#level_inherent_text").val(hasil.level_color);
        $("input[name=\"risiko_inherent\"]").val(hasil.id);
        $("input[name=\"level_inherent\"]").val(hasil.level_risk_no);
        $("#level_inherent_text").css("background-color", hasil.color);
        $("#level_inherent_text").css("color", hasil.color_text);

    } else if (hasil.bk_tipe == 2) {
        $('#impact_text_kuantitatif_residual').val(hasil.text);
        $('input[name="impact_residual_id"]').val(hasil.nil);

        $("#risiko_residual_text").val(parseFloat(hasil.like_code) * parseFloat(hasil.impact_code));
        $("#level_residual_text").val(hasil.level_color);
        $("input[name=\"risiko_residual\"]").val(hasil.id);
        $("input[name=\"level_residual\"]").val(hasil.level_risk_no);
        $("#level_residual_text").css("background-color", hasil.color);
        $("#level_residual_text").css("color", hasil.color_text);
    } else if (hasil.bk_tipe == 3) {
        $('#impact_text_kuantitatif_target').val(hasil.text);
        $('input[name="impact_target_id"]').val(hasil.nil);

        $("#risiko_target_text").val(parseFloat(hasil.like_code) * parseFloat(hasil.impact_code));
        $("#level_target_text").val(hasil.level_color);
        $("input[name=\"risiko_target\"]").val(hasil.id);
        $("input[name=\"level_target\"]").val(hasil.level_risk_no);
        $("#level_target_text").css("background-color", hasil.color);
        $("#level_target_text").css("color", hasil.color_text);
    }


}

function list_mitigasi(hasil) {
    $("#entri_mitigasi").html(hasil.mitigasi);
    $("#list_mitigasi").html(hasil.list_mitigasi);
}

function result_inherent(hasil) {
    $("#risiko_inherent_text").val(parseFloat(hasil.like_code) * parseFloat(hasil.impact_code));
    $("#level_inherent_text").val(hasil.level_color);
    $("input[name=\"risiko_inherent\"]").val(hasil.id);
    $("input[name=\"level_inherent\"]").val(hasil.level_risk_no);
    $("#level_inherent_text").css("background-color", hasil.color);
    $("#level_inherent_text").css("color", hasil.color_text);
}

function result_residual(hasil) {
    $("#risiko_residual_text").val(parseFloat(hasil.like_code) * parseFloat(hasil.impact_code));
    $("#level_residual_text").val(hasil.level_color);
    $("input[name=\"risiko_residual\"]").val(hasil.id);
    $("input[name=\"level_residual\"]").val(hasil.level_risk_no);
    $("#level_residual_text").css("background-color", hasil.color);
    $("#level_residual_text").css("color", hasil.color_text);

}

function result_target(hasil) {
    $("#risiko_target_text").val(parseFloat(hasil.like_code) * parseFloat(hasil.impact_code));
    $("#level_target_text").val(hasil.level_color);
    $("input[name=\"risiko_target\"]").val(hasil.id);
    $("input[name=\"level_target\"]").val(hasil.level_risk_no);
    $("#level_target_text").css("background-color", hasil.color);
    $("#level_target_text").css("color", hasil.color_text);
}

function update_list_library(hasil) {
    cboperistiwa = hasil.peristiwa;
    cbodampak = hasil.dampak;
    $("select[name=\"peristiwa_id[]\"]").html(hasil.peristiwa);
    $("select[name=\"dampak_id[]\"]").html(hasil.dampak);
    $('.select').select2({
        allowClear: false
    });
}

function mitigasi(hasil) {
    $("#modal_general").find(".modal-title").html('Tambah/Ubah Mitigasi');
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function aktifitas_mitigasi(hasil) {
    $("#modal_general").find(".modal-title").html('Tambah/Ubah Aktifitas Mitigasi');
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function copy_data(hasil) {
    $("#modal_general").find(".modal-title").html('Copy Data Risk Context');
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}


function proses_copy(hasil) {
    $("#modal_general").modal("hide");
    location.reload();
}

function indikator_like(hasil) {
    $("#modal_general").find(".modal-title").html('Daftar Risk Indicator Likelihood');
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function indikator_dampak(hasil) {
    $("#modal_general").find(".modal-title").html('Daftar Risk Indicator Dampak');
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function reset_approval(hasil) {
    location.reload();
}

$(document).on("change", "#owner_id", function () {
    var url = $("#btn_new").attr("href").replace("add", "getDataDivisionDropdown");
    var getSelectedData = $(this).val();
    $('#division').select2({
        placeholder: "-- Select a division --",
        allowClear: false,
        ajax: {
            url: url + getSelectedData,
            dataType: 'json',
            processResults: function (data) {
                return {
                    results: data.items
                };
            }
        }
    });
});