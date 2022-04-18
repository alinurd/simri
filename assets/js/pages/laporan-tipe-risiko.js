$(function(){
    $("#period").change(function () {
		var parent = $(this).parent();
		var nilai = $(this).val();
		var data = {
			'id': nilai
		};
		var target_combo = $("#term");
		var url = "ajax/get-term";
		_ajax_("post", parent, data, target_combo, url);
    })

    $(document).on('click','#proses', function() {
		var parent = $(this).parent().parent().parent();
		var owner = $("#owner").val();
		var period = $("#period").val();
		var term = $("#term").val();
		var data={'period':period, 'term':term,'owner':owner};
		var url = modul_name+"/proses-search";
		_ajax_("post", parent, data, "", url, 'tipe_risk');
		
	});
	
	$(document).on('click','.detail-peta', function() {
		var parent = $(this).parent().parent().parent();
		var rowc = $(this).data('rowc');
		var row = $(this).data('row');
		var period = $("#period").val();
		var term = $("#term").val();
		var data={'rowc':rowc, 'row':row, 'period':period, 'term':term,};
		var target_combo = '';
		var url = modul_name+"/get-detail-map";
		_ajax_("post", parent, data, target_combo, url, 'list_map');
	});

	$(document).on('click','.detail-rcsa', function() {
		var parent = $(this).parent().parent().parent();
		var id = $(this).data('id');
		var dampak = $(this).data('dampak');
		var data={'id':id, 'bk_tipe':2,'dampak_id':dampak};
		var target_combo = '';
		var url = "ajax/get-detail-rcsa";
		_ajax_("post", parent, data, target_combo, url, 'list_mitigasi');
    })

    $(document).on('click','.detail-mitigasi', function() {
		var parent = $(this).parent().parent().parent();
		var id = $(this).data('id');
		var data={'id':id};
		var target_combo = '';
		var url = "ajax/get-detail-mitigasi";
		_ajax_("post", parent, data, target_combo, url, 'list_aktifitas_mitigasi');
    })
    $(document).on('click','.detail-progres-mitigasi', function() {
        var parent = $(this).parent().parent().parent();
		var id = $(this).data('id');
		var owner = $("#owner").val();
		var period = $("#period").val();
		var type_ass = $("#type_ass").val();
		var data={'id':id,'period':period,'owner':owner,'type_ass':type_ass};
		var target_combo = '';
		var url = "ajax/get-detail-progres-mitigasi";
		_ajax_("post", parent, data, target_combo, url, 'list_progres_aktifitas_mitigasi');
    })
    

});

function list_map(hasil){
    // $('#result').html(hasil.combo);
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function list_mitigasi(hasil){
    $('#result_mitigasi').html(hasil.combo);
}

function list_aktifitas_mitigasi(hasil){
    $('#result_aktifitas_mitigasi').html(hasil.combo);
}
function list_progres_aktifitas_mitigasi(hasil){
    $('#result_progres_aktifitas_mitigasi').html(hasil.combo);
}

function tipe_risk(hasil){
    $("#result_lap").html(hasil.combo);
	
	setTimeout(doubleScrl, 1000);
}
