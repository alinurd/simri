var tbl_mitigasi;
$(function () {
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
	$("#owner_no").change(function () {
		var parent = $(this).parent();
		var nilai = $(this).val();
		var data = {
			'id': nilai
		};
		var target_combo = $("#owner_code");
		var url = "ajax/get-owner-code";
		_ajax_("post", parent, data, target_combo, url, 'result_map');
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

	$(document).on("click", "#btn_import", function () {
		var parent = $(this).parent();
		// var nilai = $(this).data('id');
		// var data={'rcsa_id':nilai};
		var url = "lost-event-database/upload_form";
		_ajax_("post", parent, {}, '', url, 'import_data');
	})
});
function result_map(hasil) {
	var a = hasil.combo;
	$("#owner_code").val(a);
	setTimeout(function () {
		var parent = $("#owner_no").parent();
		var nilai = $("#owner_no").val();
		var data = {
			'id': nilai
		};
		var target_combo = $("#peristiwa");
		var url = "ajax/get-peristiwa";
		_ajax_("post", parent, data, target_combo, url);
	}, 500);
}

function import_data(hasil) {
	$("#modal_full").find(".modal-title").html('Import');
	$("#modal_full").find(".modal-body").html(hasil.combo);
	$("#modal_full").modal("show");
}
