$(function(){

	$(document).on("click",".detail-used",function(){
		var parent = $(this).parent();
		var id = $(this).attr('data-id');
		var data={'id':id, 'tipe':1};
		var target_combo = $("#risk_type_no");
		var url = "ajax/get-detail-library";
		_ajax_("post", parent, data, '', url, "show_lib");
	})

	$("#kel").change(function(){
		var parent = $(this).parent();
		var id = $(this).attr('id');
		var nilai = $(this).val();
		var data={'id':nilai};
		var target_combo = $("#risk_type_no");
		var url = "ajax/get-rist-type";
		_ajax_("post", parent, data, target_combo, url);
	})
	
	$(document).on('click', '#add_cause', function(){
		kel = 2;
		var data = {
			'kel': kel
		};
		var parent = $(this).closest("tr");
		asal_event = parent;
		var url = modul_name + "/get-library";

		_ajax_("post", parent, data, '', url, 'show_event');
	})

	$(document).on('click', '#add_impact', function(){
		kel = 3;
		var data = {
			'kel': kel
		};
		var parent = $(this).closest("tr");
		asal_event = parent;
		var target_combo = "";
		var url = modul_name + "/get-library";

		_ajax_("post", parent, data, '', url, 'show_event');
	})

	$(document).on("click", "#simpan_library", function () {
		$(this).addClass('disabled');
		var library = $("#add_event_name").val();
		var event_no = 0;
		var jenis_resiko = $('#risk_type_no').val();
		var kel = $('input[name="add_kel"]').val();
		var data = {
			'library': library,
			'kel': kel,
			'event_no': event_no,
			'jenis_resiko': jenis_resiko
		};
		var parent = $(this).parent();
		var url = modul_name + "/simpan-library";

		_ajax_("post", parent, data, parent, url, "proses_simpan_library");
	})

	$(document).on("click", "#cancel_library", function () {
		$("#konten_event").removeClass('d-none');
		$("#konten_add_library").addClass('d-none');
	})

	$(document).on("click", "#add_library", function () {
		$("#konten_event").addClass('d-none');
		$("#konten_add_library").removeClass('d-none');
	})

	$(document).on("click", ".pilih-Cause, .pilih-Impact", function () {
		var pilih = $(this).data("value");
		$("#modal_general").modal("hide");
		var data = pilih.split("#");
		if ($(this).hasClass("pilih-Cause")) {
			var row = $("#instlmt_cause tbody");
			row.append('<tr><td>&nbsp;</td><td style="padding-left:0px;"><input type="text" name="library_text_no[]" value="'+data[1]+'" class="form-control" style="width:100%;"><input type="hidden" name="library_no[]" value="'+data[0]+'"><input type="hidden" name="id_edit[]" value="0"></td></td><td class="text-center"><i class="fa fa-cut text-warning pointer" title="menghapus data" id="sip" onclick="remove_install(this,0)"></i></td></tr>');
		} else if ($(this).hasClass("pilih-Impact")) {
			var row = $("#instlmt_impact tbody");
			row.append('<tr><td>&nbsp;</td><td style="padding-left:0px;"><input type="text" name="library_text_no[]" value="'+data[1]+'" class="form-control" style="width:100%;"><input type="hidden" name="library_no[]" value="'+data[0]+'"><input type="hidden" name="id_edit[]" value="0"></td><td class="text-center"><i class="fa fa-cut text-warning pointer" title="menghapus data" id="sip" onclick="remove_install(this,0)"></i></td></tr>');
		}
	})
});


function proses_simpan_library(hasil) {
	if (hasil.kel == 2) {
		var row = $("#instlmt_cause tbody");
		row.append('<tr><td>&nbsp;</td><td style="padding-left:0px;"><input type="text" name="library_text_no[]" value="'+hasil.event+'" class="form-control" style="width:100%;"><input type="hidden" name="library_no[]" value="'+hasil.id+'"><input type="hidden" name="id_edit[]" value="0"></td></td><td class="text-center"><i class="fa fa-cut text-warning pointer" title="menghapus data" id="sip" onclick="remove_install(this,0)"></i></td></tr>');
	} else if (hasil.kel == 3) {
		var row = $("#instlmt_impact tbody");
		row.append('<tr><td>&nbsp;</td><td style="padding-left:0px;"><input type="text" name="library_text_no[]" value="'+hasil.event+'" class="form-control" style="width:100%;"><input type="hidden" name="library_no[]" value="'+hasil.id+'"><input type="hidden" name="id_edit[]" value="0"></td><td class="text-center"><i class="fa fa-cut text-warning pointer" title="menghapus data" id="sip" onclick="remove_install(this,0)"></i></td></tr>');
	} 
	$("#modal_general").modal("hide");
}

function proses_cause(){
	var row = $("#instlmt_cause tbody");
	row.append('<tr><td>&nbsp;</td><td style="padding-left:0px;">' + cboCouse + editCouse + '</td><td class="text-center"><i class="fa fa-cut text-warning pointer" title="menghapus data" id="sip" onclick="remove_install(this,0)"></i></td></tr>');
}

function proses_impact(){
	var row = $("#instlmt_impact tbody");
	row.append('<tr><td>&nbsp;</td><td style="padding-left:0px;">' + cboImpact + editImpact + '</td><td class="text-center"><i class="fa fa-cut text-warning pointer" title="menghapus data" id="sip" onclick="remove_install(this,0)"></i></td></tr>');
}

function show_event(hasil) {
	$("#modal_general").find(".modal-body").html(hasil.library);
	$("#modal_general").find(".modal-title").html(hasil.title);
	$("#modal_general").modal("show");
}

function show_lib(hasil) {
	$("#modal_general").find(".modal-body").html(hasil.library);
	$("#modal_general").find(".modal-title").html(hasil.title);
	$("#modal_general").modal("show");
}
