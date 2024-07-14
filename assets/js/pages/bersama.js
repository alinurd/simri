var obj_tmp = "test";
function stopLooding(parent) {
  $(parent).unblock();
}

(function () {
  setTimeout(doubleScrl, 1000);
})();

function looding(tipe, parent) {
  if (tipe == "dark") {
    $(parent).block({
      message: '<i class="icon-spinner9 spinner"></i>',
      overlayCSS: {
        backgroundColor: "#1B2024",
        opacity: 0.85,
        cursor: "wait",
      },
      css: {
        border: 0,
        padding: 0,
        backgroundColor: "none",
        color: "#fff",
      },
    });
  } else {
    $(parent).block({
      message: '<i class="icon-spinner9 spinner"></i>',
      overlayCSS: {
        backgroundColor: "#fff",
        opacity: 0.8,
        cursor: "wait",
      },
      css: {
        border: 0,
        padding: 0,
        backgroundColor: "none",
      },
    });
  }
}

function _maxLength(e, c) {
  if (!e && !e.getAttribute && !e.value) {
    return;
  }

  var f = parseInt(e.getAttribute("maxlength"), 10);
  if (isNaN(f)) {
    f = 0;
  }
  var d = e.parentNode.getElementsByTagName("input");
  if (d && d[0]) {
    var b = d[0];
    $("#span_" + c).html(b.value);
  }
  if (!b && !b.value) {
    return;
  }
  var a = e.value.length;
  if (a > f) {
    e.value = e.value.substring(0, f);
    b.value = 0;
    $("#span_" + c).html(0);
  } else {
    b.value = f - a;
    $("#span_" + c).html(b.value);
  }
}

function _ajax_(
  tipe,
  parent,
  data,
  target_combo,
  url,
  proses_result,
  sts_loading,
  tipe_result
) {
  url = base_url + url;
  // data[csrf_token_name] = Cookies.get(csrf_cookie_name);
  data[csrf_token_name] = csrf_hash;

  if (typeof sts_loading == "undefined") sts_loading = true;

  if (sts_loading) looding("light", parent);
  if (typeof proses_result == "undefined") proses_result = "";
  if (typeof tipe_result == "undefined") tipe_result = "html";

  $.ajax({
    type: tipe,
    url: url,
    data: data,
    dataType: "json",
    // contentType: "application/json",
    success: function (result) {
      if (sts_loading) stopLooding(parent);

      if (proses_result.length == 0) {
        if (tipe_result == "html") {
          target_combo.html(result.combo);
        } else {
          target_combo.val(result.combo);
        }
      } else {
        window[proses_result](result);
      }
    },
    error: function (msg) {
      // console.table(msg.getResponseHeader("Connection"));
      // console.table(msg);
      if (sts_loading) stopLooding(parent);

      if (msg.getResponseHeader("Connection") == "close" && msg.status == 200) {
        alert(msg.responseText);
      } else {
        alert("Error Load Database");
      }
    },
    complate: function () { },
  });
}

function _ajax_file_(
  tipe,
  parent,
  data,
  target_combo,
  url,
  proses_result,
  sts_loading,
  tipe_result
) {
  url = base_url + url;
  data[csrf_token_name] = csrf_hash;

  if (typeof sts_loading == "undefined") sts_loading = true;

  if (sts_loading) looding("light", parent);
  if (typeof proses_result == "undefined") proses_result = "";
  if (typeof tipe_result == "undefined") tipe_result = "html";

  $.ajax({
    type: tipe,
    url: url,
    data: data,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (result) {
      if (sts_loading) stopLooding(parent);

      if (proses_result.length == 0) {
        if (tipe_result == "html" && target_combo !== "") {
          target_combo.html(result.combo);
        } else if (target_combo !== "") {
          target_combo.val(result.combo);
        }
      } else {
        return window[proses_result](result);
      }
    },
    error: function (msg) {
      // console.log(msg);
      if (sts_loading) stopLooding(parent);
      //pesan_toastr('Error', 'err', 'Error', 'toast-top-center');
    },
    complete: function () { },
  });
}

$(function () {
  $("form").submit(function () {
    looding("light", $(this));
  });
  // Basic initialization
  $(".tokenfield").tokenfield();

  $(document).on("keyup", ".angka", function () {
    var jml = this.value.replace(/\D/g, "");
    var nilai = jml;
    $(this).val(nilai);
  });

  $(".form-check-primary").uniform({
    wrapperClass: "border-primary-600 text-primary-800",
  });

  // Colored switches
  var elems = Array.prototype.slice.call(
    document.querySelectorAll(".form-switchery-primary")
  );
  elems.forEach(function (html) {
    var switchery = new Switchery(html, { color: "#2196F3" });
  });

  // var primary = document.querySelector('.form-switchery-primary');
  // var switchery = new Switchery(primary, { color: '#2196F3' });

  $(".pickadate").pickadate({
    selectMonths: true,
    selectYears: true,
    formatSubmit: "yyyy/mm/dd",
  });
  // Touchspin
  var _componentTouchspin = function () {
    if (!$().TouchSpin) {
      console.warn("Warning - touchspin.min.js is not loaded.");
      return;
    }

    // Define variables
    var $touchspinContainer = $(".touchspin-postfix");

    // Initialize
    $touchspinContainer.TouchSpin({
      min: 0,
      max: 100,
      step: 0.1,
      decimals: 2,
      postfix: "%",
    });

    // Trigger value change when +/- buttons are clicked
    $touchspinContainer.on("touchspin.on.startspin", function () {
      $(this).trigger("blur");
    });
  };

  // Default initialization
  $(".summernote").summernote({
    height: 400,
    placeholder: "type everyting",
  });

  $(".trumbowyg").trumbowyg();

  $('[data-toggle="popover"]').popover();

  $(".select").select2({
    allowClear: false,
  });

  // Format icon
  function iconFormat(icon) {
    var originalOption = icon.element;
    if (!icon.id) {
      return icon.text;
    }
    var $icon =
      '<i class="icon-' + $(icon.element).data("icon") + '"></i>' + icon.text;

    return $icon;
  }

  // Initialize with options
  $(".select-icons").select2({
    templateResult: iconFormat,
    minimumResultsForSearch: Infinity,
    templateSelection: iconFormat,
    escapeMarkup: function (m) {
      return m;
    },
  });

  $(document).on("click", "a.delete", function (event) {
    var x = $(this);
    var notyConfirm = new Noty({
      text: '<h6 class="mb-3">Please confirm your action</h6><label>are you sure you want to permanently delete this data ?</label>',
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
            window.location.replace(x.attr("href"));
          },
          { id: "button1", "data-status": "ok" }
        ),
      ],
    }).show();
    return false;
  });

  $(".full_screen").on("click", function () {
    if (screenfull.enabled) {
      screenfull.toggle();
    }
  });

  $(document).on("click", ".detail-map", function () {
    var lat = $(this).data("lat");
    var lng = $(this).data("lng");
    var url = base_url + "maps/detail?lat=" + lat + "&lng=" + lng;
    window.open(url, "_blank");
  });

  $("body").on("keydown", "input:not(.notmove), select", function (e) {
    var self = $(this),
      form = self.parents("form:eq(0)"),
      focusable,
      next,
      prev;

    if (e.shiftKey) {
      if (e.keyCode == 13) {
        focusable = form.find("input,a,select,button").filter(":visible");
        prev = focusable.eq(focusable.index(this) - 1);

        if (prev.length) {
          prev.focus();
        } else {
          form.submit();
        }
        return false;
      }
    } else {
      if (e.keyCode == 13) {
        focusable = form.find("input,a,select,button").filter(":visible");
        next = focusable.eq(focusable.index(this) + 1);
        if (next.length) {
          next.focus();
        } else {
          form.submit();
        }
        return false;
      } else if (e.keyCode == 40) {
        focusable = form
          .find("input[name='" + $(this).attr("name") + "']")
          .filter(":visible");
        next = focusable.eq(focusable.index(this) + 1);
        if (next.length) {
          next.focus();
        } else {
          next = focusable.eq(focusable.index(0));
          next.focus();
        }
        return false;
      } else if (e.keyCode == 38) {
        focusable = form
          .find("input[name='" + $(this).attr("name") + "']")
          .filter(":visible");
        prev = focusable.eq(focusable.index(this) - 1);
        if (prev.length) {
          prev.focus();
        } else {
          next = focusable.eq(focusable.index(0));
          next.focus();
        }
        return false;
      }
    }
  });

  $(document).on("click", ".detail-img", function () {
    var url = base_url + "files/" + $(this).data("file");
    var img =
      '<div class="row"><div class="col-xl-12 text-center"><img src="' +
      url +
      '"></div></div>';
    $("#modal_general").find(".modal-body").html(img);
    $("#modal_general").modal("show");
  });

  $(document).on("click", ".risk-register", function () {
    var parent = $(this).parent();
    var nilai = $(this).data("id");
    var data = { rcsa_id: nilai };
    var url = "ajax/get-register";
    _ajax_("post", parent, data, "", url, "risk_register");
  });
  $(document).on("click", ".loss-event", function () {
    var parent = $(this).parent();
    var nilai = $(this).data("id");
    var data = { id: nilai };
    var url = "ajax/get-loss-event";
    _ajax_("post", parent, data, "", url, "loss_event");
  });

  $(document).on("click", ".risk-monitoring", function () {
    var parent = $(this).parent();
    var nilai = $(this).data("id");
    var data = { rcsa_id: nilai };
    var url = "ajax/get-monitoring";
    _ajax_("post", parent, data, "", url, "risk_register");
  });
});

function doubleScrl() {
  $(".double-scroll").doubleScroll({
    resetOnWindowResize: true,
    scrollCss: {
      "overflow-x": "auto",
      "overflow-y": "auto",
    },
    contentCss: {
      "overflow-x": "auto",
      "overflow-y": "auto",
    },
  });
  $(window).resize();
}

function risk_register(hasil) {
  $("#modal_general").find(".modal-title").html("Risk Register");
  $("#modal_general").find(".modal-body").html(hasil.combo);
  $("#modal_general").modal("show");
  setTimeout(doubleScrl, 1000);
}
function loss_event(hasil) {
  $("#modal_general").find(".modal-title").html("Loss Event");
  $("#modal_general").find(".modal-body").html(hasil.combo);
  $("#modal_general").modal("show");
}

function showMyImage(fileInput, gambar) {
  var files = fileInput.files;
  for (var i = 0; i < files.length; i++) {
    var file = files[i];

    var imageType = /image.*/;
    if (!file.type.match(imageType)) {
      continue;
    }

    var img = document.getElementById(gambar);
    img.file = file;
    var reader = new FileReader();
    reader.onload = (function (aImg) {
      return function (e) {
        aImg.src = e.target.result;
      };
    })(img);
    reader.readAsDataURL(file);
  }
}

function fullScreen(theURL) {
  window.open(theURL, "", "fullscreen=yes, scrollbars=auto");
}

function show_map(hasil) {
  $("#modal_general").find(".modal-body").html(hasil.combo);
  $("#modal_general").modal("show");
}

function remove_install(t, iddel, tbl) {
  if (typeof tbl == "undefined") var tbl = "";

  if (
    confirm(
      "Are you sure you want to permanently delete this transaction ?\nThis action cannot be undone"
    )
  ) {
    var ri = t.parentNode.parentNode.rowIndex;
    looding("light", t);
    var form = { iddel: iddel, tbl: tbl };
    var url = base_url + modul_name + "/del-child";
    if (iddel > "0") {
      $.ajax({
        type: "POST",
        url: url,
        data: form,
        dataType: "json",
        success: function (result) {
          if (result.sts == "0") {
            alert("Gagal Proses");
          } else {
            t.parentNode.parentNode.parentNode.deleteRow(ri - 1);
            alert(result.ket);
          }
          stopLooding(t);
        },
        failed: function (msg) {
          alert("Gagal Proses");
          stopLooding(t);
        },
        error: function (msg) {
          alert("Gagal Proses");
          stopLooding(t);
        },
      });
    } else {
      t.parentNode.parentNode.parentNode.deleteRow(ri - 1);
      alert("Berhasil dihapus");
      stopLooding(t);
    }
  }
  return false;
}


function _similarity_lib(t, th){
$(document).on('keyup', "input[name=\"peristiwaBaru\"], .libraryCekSimilarity, #library", function () {
  var parent = $(this).parent();
  // var input = $("#peristiwaBaru").val();
  var input = $(this).val();
  var data = { 'library': input,'type': t,'percent': th };
  var target_combo = $("#similarityResults");
  var url = "ajax/check-similarity-lib";
  _ajax_("post", parent, data, target_combo, url, "similarityResults");
});
}

function similarityResults(hasil) {
  $('#similarityResults').html(hasil.combo);
 var similarityCount = $("#similarityCount").val();
 var similarity = $("#similarity");
var percent = similarity.data("percent"); 
if(similarityCount>0){
  $('#similarityNUll').addClass('d-none');
  $('#similarityNUllLib').addClass('d-none');
}else{
  $('#similarityNUll').removeClass('d-none');
  $('#similarityNUllLib').addClass('d-none');
}
if (percent >= 100) {
  $('#similarityNote').removeClass('d-none');
  $('#btn_save').addClass('disabled').prop('disabled', true);
  $('#btn_save_quit').addClass('disabled').prop('disabled', true);
} else {
  $('#similarityNote').addClass('d-none');
  $('#btn_save').removeClass('disabled').prop('disabled', false);
  $('#btn_save_quit').removeClass('disabled').prop('disabled', false);
}
}
