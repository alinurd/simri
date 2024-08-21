var tbl_mitigasi;
$(function () {
  $("<input>")
    .attr({
      type: "hidden",
      id: "idOfHiddenInput",
      name: "idOfHiddenInput",
    })
    .appendTo("#datatable-list");

  $("#datatable-list")
    .on("init.dt", function () {
      readyCheckbox();
    })
    .DataTable()
    .column(0)
    .visible(false);
  $("#chk_list_parent").click(function (event) {
    if (this.checked) {
      // Iterate each checkbox
      $(":checkbox").each(function () {
        this.checked = true;
      });
      $("#btn_save_modul").removeClass("disabled");
    } else {
      $(":checkbox").each(function () {
        this.checked = false;
      });
      $("#idOfHiddenInput").val("");
      $("#btn_save_modul").addClass("disabled");
    }
  });

  $("#btn_lap").click(function (event) {
    event.preventDefault();
    var x = $(this);
    var jml = 0;
    var data = $("#idOfHiddenInput").val();
    let triggerDelay = 100;
    let removeDelay = 1000;

    if (data != "") {
      looding("light", x.parent().parent());
      $.ajax({
        type: "post",
        url: x.data("url"),
        data: { id: data },
        dataType: "json",
        success: function (result) {
          stopLooding(x.parent().parent());
          $.each(result, function (index, val) {
            _createIFrame(val, index * triggerDelay, removeDelay);
          });
        },
        error: function (msg) {
          stopLooding(x.parent().parent());
        },
        complate: function () {},
      });
    }
  });

  $("#btn_kri").click(function (event) {
    event.preventDefault();
    var x = $(this);
    var jml = 0;
    var data = $("#idOfHiddenInput").val();
    let triggerDelay = 100;
    let removeDelay = 1000;

    if (data != "") {
      looding("light", x.parent().parent());
      $.ajax({
        type: "post",
        url: x.data("url"),
        data: { id: data },
        dataType: "json",
        success: function (result) {
          stopLooding(x.parent().parent());
          $.each(result, function (index, val) {
            _createIFrame(val, index * triggerDelay, removeDelay);
          });
        },
        error: function (msg) {
          stopLooding(x.parent().parent());
        },
        complate: function () {},
      });
    }
  });

  $(document).on("click", 'input[name="chk_list[]"]', function (event) {
    var len = $('input[name="chk_list[]"]:checked').length;
    if (len > 0) {
      $("#btn_save_modul").removeClass("disabled");
      $("#chk_list_parent").prop("checked", true);
    } else {
      $("#btn_save_modul").addClass("disabled");
      $("#chk_list_parent").prop("checked", false);
    }
    updateCheckboxes($(this));
  });

  $(document).on("click", "#btn_reset_one", function () {
    if (
      confirm(
        "Anda akan membatalkan approval untuk progress mitigasi ini, \nYakin akan melanjutkan ?"
      )
    ) {
      var parent = $(this).parent();
      var nilai = $(this).attr("data-id");
      var data = { id: nilai };
      var url = modul_name + "/reset-approval";
      _ajax_("post", parent, data, "", url, "reset_approval");
    }
  });

  $(document).on("click", ".btnNext", function () {
    $(".nav-tabs")
      .find(".active")
      .closest("li")
      .next("li")
      .find("a")
      .trigger("click");
  });

  $(document).on("click", ".btnPrevious", function () {
    $(".nav-tabs")
      .find(".active")
      .closest("li")
      .prev("li")
      .find("a")
      .trigger("click");
  });

  $(document).on("click", ".delete-progres", function () {
    var objek = $(this);
    var notyConfirm = new Noty({
      text: Globals.hapus,
      timeout: false,
      modal: true,
      layout: "center",
      theme: "  p-0 bg-white",
      closeWith: "button",
      type: "confirm",
      buttons: [
        Noty.button("Cancel", "btn btn-link", function () {
          notyConfirm.close();
        }),

        Noty.button(
          'Delete <i class="icon-paperplane ml-2"></i>',
          "btn bg-blue ml-1",
          function () {
            notyConfirm.close();
            var parent = objek.parent().parent().parent();
            var nilai = objek.data("id");
            var mitigasi = objek.data("mitigasi");
            var data = {
              id: nilai,
              mitigasi_id: mitigasi,
            };
            var target_combo = "";
            var url = modul_name + "/hapus-progres";
            _ajax_("post", parent, data, target_combo, url, "del_progres");
          },
          { id: "button1", "data-status": "ok" }
        ),
      ],
    }).show();
  });

  $(document).on("click", ".add-kri", function () {
    var parent = $(this).parent();
    // var kpi = $(this).data('parent');
    var id = $(this).data("id");
    var rcsa_id = $(this).data("parent");
    var minggu = $(this).data("minggu");
    var data = { kpi_id: id, minggu: minggu, rcsa_id: rcsa_id, edit_id: 0 };
    var url = modul_name + "/kri-add";
    _ajax_("post", parent, data, "", url, "indikator_kri");
  });

  $(document).on("click", ".edit-kri", function () {
    var parent = $(this).parent();
    var kpi = $(this).data("parent");
    var id = $(this).data("id");
    var rcsa_id = $(this).data("rcsa");
    var minggu = $(this).data("minggu");
    var data = { kpi_id: kpi, edit_id: id, minggu: minggu, rcsa_id: rcsa_id };
    var target_combo = $(".entri_kri");
    var url = modul_name + "/kri-edit";
    // _ajax_("post", parent, data, target_combo, url);

    _ajax_("post", parent, data, "", url, "indikator_kpi");
  });

  $(document).on("click", "#add_kri", function () {
    var parent = $(this).parent();
    var kpi = $(this).data("parent");
    var id = $(this).data("id");
    var rcsa_id = $(this).data("rcsa");
    var minggu = $(this).data("minggu");
    var data = { kpi_id: kpi, edit_id: id, minggu: minggu, rcsa_id: rcsa_id };
    var target_combo = $(".entri_kri");
    var url = modul_name + "/kri-edit";
    _ajax_("post", parent, data, "", url, "indikator_kpi");

    // _ajax_("post", parent, data, target_combo, url);
  });

  $(document).on("click", "#simpan_kri", function () {
    var parent = $(this).parent().parent().parent();

    var data = $("#form_kri").serialize();
    var target_combo = $("#parent_risk");
    // var target_combo = $("#list_kri");
    var url = modul_name + "/simpan-kri";

    _ajax_("post", parent, data, target_combo, url, "indikator_kri");
  });

  $(document).on("click", "#back_list_kri", function () {
    var parent = $(this).parent();
    var id = $(this).data("rcsa");
    var kpi_id = $("input[name='kpi_id']").data("rcsa");
    var minggu = $(this).data("minggu");

    var data = { id: id, minggu: minggu, kpi_id: kpi_id };
    var url = modul_name + "/list-kpi";
    _ajax_("post", parent, data, "", url, "indikator_kpi");
  });

  $(document).on("click", ".delete-kri", function () {
    var parent = $(this).parent();
    var id = $(this).data("id");
    var parent_id = $(this).data("parent");
    var rcsa_id = $(this).data("rcsa");
    var minggu = $(this).data("minggu");
    var data = {
      kpi_id: parent_id,
      minggu: minggu,
      rcsa_id: rcsa_id,
      edit_id: id,
    };
    var url = modul_name + "/kri-delete";
    _ajax_("post", parent, data, "", url, "indikator_kri");
  });

  $(document).on("click", "#add_kpi", function () {
    var parent = $(this).parent();
    var lap_id = $(this).data("parent");
    var minggu = $(this).data("minggu");
    var data = { minggu: minggu, rcsa_id: lap_id, edit_id: 0 };
    var url = modul_name + "/kpi-add";
    _ajax_("post", parent, data, "", url, "indikator_kpi");
  });

  $(document).on("click", ".edit_kpi", function () {
    var parent = $(this).parent();
    var id = $(this).data("id");
    var lap_id = $(this).data("parent");
    var minggu = $(this).data("minggu");
    var data = { minggu: minggu, rcsa_id: lap_id, edit_id: id };
    var url = modul_name + "/kpi-add";
    _ajax_("post", parent, data, "", url, "indikator_kpi");
  });

  $(document).on("click", ".delete_kpi", function () {
    var parent = $(this).parent();
    var id = $(this).data("id");
    var lap_id = $(this).data("parent");
    var minggu = $(this).data("minggu");
    var data = { minggu: minggu, rcsa_id: lap_id, edit_id: id };
    var url = modul_name + "/kpi-delete";
    _ajax_("post", parent, data, "", url, "indikator_kpi");
  });

  $(document).on("click", "#back_list_kpi", function () {
    var parent = $(this).parent();
    var id = $(this).data("id");
    var minggu = $(this).data("minggu");

    var data = { id: id, minggu: minggu };
    var url = modul_name + "/list-kpi";
    _ajax_("post", parent, data, "", url, "indikator_kpi");
  });

  $(document).on("click", "#input-kpi", function () {
    var parent = $(this).parent().parent().parent();
    var nilai = $(this).data("id");
    var minggu = $("#minggu").val();
    if (minggu.length > 0) {
      var data = { id: nilai, minggu: minggu };
      var url = modul_name + "/list-kpi";
      _ajax_("post", parent, data, "", url, "indikator_kpi");
    } else {
      alert("Anda belum memilih data bulan pelaporan");
    }
  });

  $(document).on("click", "#simpan_kpi", function () {
    var parent = $(this).parent().parent().parent();

    var data = $("#form_like_indi").serialize();
    var target_combo = $("#parent_risk");
    var url = modul_name + "/simpan-kpi";
    _ajax_("post", parent, data, target_combo, url, "indikator_kpi");
  });

  $(document).on("click", "#add_progres", function () {
    var parent = $(this).parent().parent().parent();
    var nilai = $(this).data("id");
    var data = {
      mitigasi_id: nilai,
      id: 0,
    };
    var target_combo = $("#entry_progres");
    var url = modul_name + "/add-progres";
    _ajax_("post", parent, data, target_combo, url);
  });

  $(document).on("click", ".update-progres", function () {
    var parent = $(this).parent().parent().parent();
    var mitigasi = $(this).data("mitigasi");
    var id = $(this).data("id");
    var data = {
      mitigasi_id: mitigasi,
      id: id,
    };
    var target_combo = $("#entry_progres");
    var url = modul_name + "/add-progres";
    _ajax_("post", parent, data, target_combo, url);
  });

  $(document).on("click", "#simpan_progres", function () {
    var uraian = $("#uraian").val();
    var target = $("#target").val();
    var aktual = $("#aktual").val();
    if (uraian.length > 0 && target.length > 0 && aktual.length > 0) {
      var parent = $(this).parent().parent().parent();
      // var data = $("#form_progres").serialize();
      var data = $("#form_general").serialize();

      var url = modul_name + "/simpan-progres";
      _ajax_("post", parent, data, "", url, "simpanProgres");
    } else {
      alert("Target, Aktual dan Uraian wajib diisi!");
      $("#uraian").focus();
    }
  });

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


  $(document).on("click", "#simpan_dampak_indi", function () {
    var mak = 0;
    var cek = 0;
    $(".kri").each(function () {
      cek = $("option:selected", this).index();
      if (cek > mak) {
        mak = cek;
      }
    });
    var parent = $(this).parent().parent().parent();
    var tipe = $('input[name="tipe_analisa_no"]:checked').val();
    var bktipe = $('input[name="bk_tipe"]').val();
    var month = $('input[name="month"]').val();
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
        var like_id = $("#like_residual_id_3 :selected").data("temp");
      } else if (bktipe == 3) {
        var like_id = $("#like_target_id_3 :selected").data("temp");
      } else {
        var like_id = $("#like_id_3 :selected").data("temp");
      }
    }

    var parent = $(this).parent().parent().parent();
    var data = $("#form_dampak_indi").serializeArray();

    data.push({ name: "like_id", value: like_id });
    data.push({ name: "mak", value: mak });
    data.push({ name: "month", value: month });
    var target_combo = "";
    var url = modul_name + "/simpan-dampak-indi";
    _ajax_("post", parent, data, target_combo, url, "resultInherent");
  });

  $(document).on("click", "#view-kpi", function () {
    var parent = $(this).parent().parent().parent();
    var id = $(this).data("id");
    var minggu = $("#minggu").val();
    var data = { rcsa_id: id, minggu: minggu };
    var url = modul_name + "/view-kpi";
    _ajax_file_("post", parent, data, "", url, "view_kpi");
  });

  $(document).on("click", ".review-kpi", function () {
    var parent = $(this).parent().parent().parent();
    var id = $(this).data("id");
    var minggu = 0;
    var data = { rcsa_id: id, minggu: minggu };
    var url = modul_name + "/review-kpi";
    _ajax_("post", parent, data, "", url, "view_kpi");
  });

  $(document).on("click", ".propose-mitigasi", function () {
    var parent = $(this).parent().parent().parent();
    var id = $(this).data("id");
    var data = {
      id: id,
    };
    var url = "/progress-mitigasi/propose/" + id;

    var notyConfirm = new Noty({
      text: '<h6 class="mb-3">Please confirm your action</h6><label>Anada akan mengajukan propose Mitigasi, yakin akan melanjutkan ?</label>',
      timeout: false,
      modal: true,
      layout: "center",
      theme: "  p-0 bg-white",
      closeWith: "button",
      type: "confirm",
      buttons: [
        Noty.button("Cancel", "btn btn-link", function () {
          notyConfirm.close();
        }),

        Noty.button(
          'Propose <i class="icon-paperplane ml-2"></i>',
          "btn bg-blue ml-1",
          function () {
            notyConfirm.close();
            // _ajax_("post", parent, data, '', url, 'propose_mitigasi');
            window.location = url;
          },
          { id: "button1", "data-status": "ok" }
        ),
      ],
    }).show();
  });

  $("#period").change(function () {
    var parent = $(this).parent();
    var nilai = $(this).val();
    var data = {
      id: nilai,
    };
    var target_combo = $("#term");
    var url = "ajax/get-term";
    _ajax_("post", parent, data, target_combo, url);
  });

  $("#term").change(function () {
    var parent = $(this).parent();
    var nilai = $(this).val();
    var data = {
      id: nilai,
    };
    var target_combo = $("#minggu");
    var url = "ajax/get-minggu";
    _ajax_("post", parent, data, target_combo, url);
  });

  $("#period_id").change(function () {
    var parent = $(this).parent();
    var nilai = $(this).val();
    var data = {
      id: nilai,
    };
    var target_combo = $("#term_id");
    var url = "ajax/get-term";
    _ajax_("post", parent, data, target_combo, url);
  });

  $("#term_id").change(function () {
    var parent = $(this).parent();
    var nilai = $(this).val();
    var data = {
      id: nilai,
    };
    var target_combo = $("#minggu_id");
    var url = "ajax/get-minggu";
    _ajax_("post", parent, data, target_combo, url);
  });
});

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
  }, 200);

  // $("#idOfHiddenInput").val(checkboxes);
}

function _createIFrame(url, triggerDelay, removeDelay) {
  //Add iframe dynamically, set SRC, and delete
  setTimeout(function () {
    var frame = $(
      '<iframe style="display: none;" class="multi-download"></iframe>'
    );
    frame.attr("src", url);
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
  }, 200);
}

function del_progres(hasil) {
  $("#list_progres").html(hasil.list_progres);
  alert(hasil.combo);
}

function list_progres(hasil) {
  $("#entry_progres").html(hasil.update);
  $("#list_progres").html(hasil.list_progres);
}

function propose_mitigasi(hasil) {
  $("#modal_general").find(".modal-title").html("Propose Mitigasi");
  $("#modal_general").find(".modal-body").html(hasil.combo);
  $("#modal_general").find(".modal-footer").addClass("d-none");
  $("#modal_general").modal("show");
}

function result_progres(hasil) {
  location.reload();
}

function indikator_kpi(hasil) {
  $("#modal_general").find(".modal-title").html("Daftar KPI");
  $("#modal_general").find(".modal-body").html(hasil.combo);
  $("#modal_general").find(".modal-footer").addClass("d-none");
  $("#modal_general").modal("show");
}

function indikator_kri(hasil) {
  $("#modal_general").find(".modal-title").html("Daftar KRI");
  $("#modal_general").find(".modal-body").html(hasil.combo);
  $("#modal_general").find(".modal-footer").addClass("d-none");
  $("#modal_general").modal("show");
}

function view_kpi(hasil) {
  $("#modal_general").find(".modal-title").html("Preview KRI");
  $("#modal_general").find(".modal-body").html(hasil.combo);
  $("#modal_general").find(".modal-footer").removeClass("d-none");
  $("#modal_general").modal("show");
}

function reset_approval(hasil) {
  location.reload();
}

$(document).on("click", "#simpanResidual", function () {
  var parent = $(this).parent().parent().parent();
  var like_id_3 = $("#like_id_3").val();
  var impact = $('input[name="impact_id_3"]').val();
  var like = $('input[name="mit_like_id"]').val();
  var likeCek = $('input[name="mit_like_id_cek"]').val();
  var aspek = $("#aspek").val();
  if (typeof aspek === "undefined" || aspek === null || aspek === "") {
    aspek = 0;
  }

  if (likeCek) {
    like = likeCek;
  }
  var color = $('input[name="color"]').val();
  var level_color = $('input[name="level_color"]').val();
  var id_edit = $('input[name="id_edit"]').val();
  var color_text = $('input[name="color_text"]').val();
  var score = $('input[name="score"]').val();
  var month = $('input[name="month"]').val();
  var id_detail = $('input[name="id_detail"]').val();
  var data = {
    aspek: aspek,
    like: like,
    impact: impact,
    color_text: color_text,
    id_edit: id_edit,
    level_color: level_color,
    color: color,
    score: score,
    id_detail: id_detail,
    month: month,
  };
  console.log(data);
  var url = modul_name + "/simpan-update-residual";
  _ajax_("post", parent, data, "", url, "simpanResidual");
});

$(document).on("click", "#indikator_dampak", function () {
  var parent = $(this).parent();
  // var rcsa = $(this).data('rcsa');
  var month = $('input[name="month"]').val();

  var id = $(this).data("id");
  var data = { id: 0, rcsa_detail_no: id, bk_tipe: 1, month: month };
  var url = modul_name + "/indikator-dampak";
  _ajax_("post", parent, data, "", url, "indikator_dampak");
});

$(document).on("click", "#indikator_dampak_residual", function () {
  var parent = $(this).parent();
  var id = $(this).data("id");
  var data = { id: 0, rcsa_detail_no: id, bk_tipe: 2 };
  var url = modul_name + "/indikator-dampak";
  _ajax_("post", parent, data, "", url, "indikator_dampak");
});

$(document).on("click", "#indikator_dampak_target", function () {
  var parent = $(this).parent();
  var id = $(this).data("id");
  var data = { id: 0, rcsa_detail_no: id, bk_tipe: 3 };
  var url = modul_name + "/indikator-dampak";
  _ajax_("post", parent, data, "", url, "indikator_dampak");
});

function indikator_dampak(hasil) {
  $("#modal_general").find(".modal-title").html("Daftar Risk Indicator Dampak");
  $("#modal_general").find(".modal-body").html(hasil.combo);
  $("#modal_general").modal("show");
}
$(document).on("click", "#refreshRiskDampak", function () {
  var url = modul_name + "/refreshAnalisaRisk";
  var id = $("#indikator_dampak").data("id");
  var data = { id_edit: id, type: "dampak" };
  _ajax_("post", $("#indikator_dampak"), data, "", url, "refresh_dampak");
});

function refresh_likehood(result) {
  $("#indikator_like").text(
    " Input Risk Indikator Likelihood [" + result + "]"
  );
  $("#indikator_like").attr("data-jml_like_indi", result);
}

function refresh_dampak(result) {
  $("#indikator_dampak").text(" Input Risk Indikator Dampak [" + result + "]");
  $("#indikator_dampak").attr("data-jml_dampak_indi", result);
}
$(document).on("click", "#updateAktifitas", function () {
  var parent = $(this).parent();

  var id = $(this).data("id");
  var rcsadetail = $(this).data("rcsadetail");
  var mitigasi = $(this).data("mitigasi");
  var mitdetail = $(this).data("mitdetail");
  var periode = $(this).data("periode");
  var bln = $(this).data("bln");

  var data = {
    id: id,
    rcsadetail: rcsadetail,
    mit: mitigasi,
    mitdetail: mitdetail,
    periode: periode,
    bln: bln,
  };
  var url = modul_name + "/form-update-aktifitas";
  _ajax_("post", parent, data, "", url, "aktififasMod");
});
$(document).on("change", "#aspek_risiko_id", function () {
  var parent = $(this).parent().parent().parent();
  var id = $(this).val();
  // console.log(id)
  $('input[name="aspek"]').val(id);
  var text = $(this).find("option:selected").text();
  if (text == "dll") {
    $("#aspek_det").parent().parent().parent().show();
  } else {
    $("#aspek_det").parent().parent().parent().hide();
  }
  var data = { id: id };
  var target_combo = $(".like_id_3");
  var url = "ajax/get-like-aspekrisiko";
  _ajax_("post", parent, data, target_combo, url);
});
$(document).on("change", "#like_id_3", function () {
  var parent = $(this).parent();
  // mit_like_id
  var like = $(this).val();
  var impact = $('input[name="impact_id_3"]').val();
  $('input[name="mit_like_id_cek"]').val(like);
  console.log(like);
  var mit_like_id_cek = $('input[name="mit_like_id_cek"]').val();
  console.log(mit_like_id_cek);

  var data = { like: like, impact: impact };
  var url = "ajax/get-risiko-inherent-semi";
  _ajax_("post", parent, data, "", url, "resultInherent");
});
$(document).on("click", "#indikator_like", function () {
  var parent = $(this).parent();
  // var rcsa = $(this).data('rcsa');
  var id = $(this).data("id");
  var dampak = $('input[name="impact_id_2"]').val();
  
  var month = $('input[name="month"]').val();
   var kpi = $("#id_kpi").val();
  var data = {
    id: 0,
    rcsa_detail_no: id,
    bk_tipe: 1,
    dampak_id: dampak,
    id_kpi: kpi,
    month: month,
  };
  console.log(data)
  var url = modul_name + "/indikator-like";
  _ajax_("post", parent, data, "", url, "indikator_like");
});
$(document).on("click", ".update-like-indi", function () {
  var parent = $(this).parent();
  var id = $(this).data("id");
  var rcsa = $(this).data("parent");
  var kpi = $("#id_kpi").val();
  var data = { id: id, rcsa_detail_no: rcsa, bk_tipe: 1, id_kpi: kpi };
  // url edit ke controller
  var url = modul_name + "/indikator-like-add";
  _ajax_("post", parent, data, "", url, "indikator_like");
});
// simpan like indi
$(document).on("click", "#simpan_like_indi", function () {
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
  var month = $('input[name="month"]').val();
  data.push({ name: "month", value: month });
  var target_combo = $("#parent_risk");
  // ke controller untuk simpan
  console.log(data)
  var url = modul_name + "/simpan-like-indi";
  _ajax_("post", parent, data, target_combo, url, "indikator_like");
});

function indikator_like(hasil) {
	// console.log(hasil.hasil.warna)
	
	// Update modal with relevant information
	$("#modal_general")
	  .find(".modal-title")
	  .html("Daftar Risk Indicator Likelihood");
	$("#modal_general").find(".modal-body").html(hasil.combo);
	$("#modal_general").modal("show");
  
	// Check if hasil.hasil.warna exists
	if (hasil.hasil.warna) {
		console.log(hasil.hasil.warna)
	  var likeCode = parseFloat(hasil.hasil.warna.like_code);
	  var impactCode = parseFloat(hasil.hasil.warna.impact_code);
	  var x = likeCode * impactCode;
	  var isi = hasil.hasil.warna.score + "-" + hasil.hasil.warna.level_color;
	  var isiLike = likeCode + "-" + hasil.hasil.warna.like_text;
  
	  // Enable save button
	  $("#simpanResidual").removeClass("disabled");
  
	  if (x === 0) {
		isi = "-";
		$("#simpanResidual").addClass("disabled");
	  } 
	  $('input[name="like_text_kuantitatif"]').val(isiLike);

	  // Update fields with residual likelihood data
	  $("#mit_level_residual_text").val(hasil.hasil.warna.level_color);
	  $('input[name="mit_like_id"]').val(likeCode);
	  $('input[name="mit_impact_id"]').val(impactCode);
	  $('input[name="level_color"]').val(hasil.hasil.warna.level_color);
	  $('input[name="color"]').val(hasil.hasil.warna.color);
	  $('input[name="color_text"]').val(hasil.hasil.warna.color_text);
	  $('input[name="score"]').val(hasil.hasil.warna.score);
  
	  // Update text and background color based on likelihood
	  $("#mit_level_residual_text").css("background-color", hasil.hasil.warna.color);
	  $("#mit_level_residual_text").css("color", hasil.hasil.warna.color_text);
  
	  // Check for stsDakmap and update additional fields
 		$('input[name="mit_like_id_cek"]').val(likeCode);
		$('input[name="mit_impact_id"]').val(impactCode);
		$('input[name="impact_text_kuantitatif"]').val(impactCode + "-" + hasil.hasil.warna.impact_text);
		$('input[name="impact_id"]').val(impactCode);
		$('input[name="impact_id_2"]').val(impactCode);
 	}
  }
  

function aktififasMod(hasil) {
  $("#modal_general").find(".modal-title").html(hasil.title);
  $("#modal_general").find(".modal-body").html(hasil.combo);
  $("#modal_general").find(".modal-footer").addClass("d-none");
  $("#modal_general").modal("show");
}

function simpanProgres(hasil) {
  if (hasil) {
    alert("data berhasil disimpan");
    $("#modal_general").modal("hide");
    location.reload();
  } else {
    alert("gagal memproses data");
  }
}
function simpanResidual(hasil) {
  if (hasil) {
    alert("data berhasil disimpan");
    //   pesan_toastr(hasil.info, 'success', 'Success', 'toast-top-center');
  } else {
    alert("gagal memproses data");
    //   pesan_toastr('Error', 'err', 'Error', 'toast-top-center');
  }
}

function resultInherent(hasil) {
  console.log(hasil);
  var likeCode = parseFloat(hasil.like_code);
  var impactCode = parseFloat(hasil.impact_code);
  var x = likeCode * impactCode;
  var isi = hasil.score + "-" + hasil.level_color;
  $("#simpanResidual").removeClass("disabled");
  if (x == 0) {
    isi = "-";
    $("#simpanResidual").addClass("disabled");
  }

  $("#mit_level_residual_text").val(hasil.level_color);
  $('input[name="mit_like_id"]').val(hasil.like_code);
  $('input[name="mit_impact_id"]').val(hasil.impact_code);

  $('input[name="level_color"]').val(hasil.level_color);
  $('input[name="color"]').val(hasil.color);
  $('input[name="color_text"]').val(hasil.color_text);
  $('input[name="score"]').val(hasil.score);

  $("#mit_level_residual_text").css("background-color", hasil.color);
  $("#mit_level_residual_text").css("color", hasil.color_text);

  if (hasil.stsDakmap) {
    // $('input[name="mit_kri"]').val(hasil.id)
    // $('input[name="mit_detial"]').val(hasil.detial)
    $('input[name="mit_like_id_cek"]').val(hasil.like_code);
    $('input[name="mit_impact_id"]').val(hasil.impact_code);
    $('input[name="mit_like_id"]').val(hasil.impact_code);
    $('input[name="impact_text_kuantitatif"]').val(
      hasil.impact_code + "-" + hasil.impact_text
    );
    $('input[name="impact_id"]').val(hasil.impact_code);
    $('input[name="impact_id_2"]').val(hasil.impact_code);
  }
}
$(document).on("click", "#back_like_indi", function () {
  var parent = $(this).parent();
  var id = $(this).data("id");
  var bk = $(this).data("bk");
  if (parseFloat(bk) == 1) {
    var dampak = $('input[name="impact_id_2"]').val();
  } else if (parseFloat(bk) == 2) {
    var dampak = $('input[name="impact_residual_id"]').val();
  } else if (parseFloat(bk) == 3) {
    var dampak = $('input[name="impact_target_id"]').val();
  }
  var kpi = $("#id_kpi").val();
  var data = {
    id: id,
    rcsa_detail_no: id,
    bk_tipe: bk,
    dampak_id: dampak,
    id_kpi: kpi,
  };

  var url = modul_name + "/indikator-like";
  _ajax_("post", parent, data, "", url, "indikator_like");
});
function result_dampak(hasil) {
  console.log(hasil);
  if (hasil.bk_tipe == 1) {
    $("#impact_text_kuantitatif").val(hasil.text);
    $('input[name="impact_id_2"]').val(hasil.nil);

    $("#impact_text_kuantitatif_semi").val(hasil.text);
    $('input[name="impact_id_3"]').val(hasil.nil);

    $("#risiko_inherent_text").val(
      parseFloat(hasil.like_code) * parseFloat(hasil.impact_code)
    );
    $("#level_inherent_text").val(hasil.level_color);
    $('input[name="risiko_inherent"]').val(hasil.id);
    $('input[name="level_inherent"]').val(hasil.level_risk_no);
    $("#level_inherent_text").css("background-color", hasil.color);
    $("#level_inherent_text").css("color", hasil.color_text);
  } else if (hasil.bk_tipe == 2) {
    $("#impact_text_kuantitatif_residual").val(hasil.text);
    $('input[name="impact_residual_id"]').val(hasil.nil);

    $("#risiko_residual_text").val(
      parseFloat(hasil.like_code) * parseFloat(hasil.impact_code)
    );
    $("#level_residual_text").val(hasil.level_color);
    $('input[name="risiko_residual"]').val(hasil.id);
    $('input[name="level_residual"]').val(hasil.level_risk_no);
    $("#level_residual_text").css("background-color", hasil.color);
    $("#level_residual_text").css("color", hasil.color_text);
  } else if (hasil.bk_tipe == 3) {
    $("#impact_text_kuantitatif_target").val(hasil.text);
    $('input[name="impact_target_id"]').val(hasil.nil);

    $("#risiko_target_text").val(
      parseFloat(hasil.like_code) * parseFloat(hasil.impact_code)
    );
    $("#level_target_text").val(hasil.level_color);
    $('input[name="risiko_target"]').val(hasil.id);
    $('input[name="level_target"]').val(hasil.level_risk_no);
    $("#level_target_text").css("background-color", hasil.color);
    $("#level_target_text").css("color", hasil.color_text);
  }
}
