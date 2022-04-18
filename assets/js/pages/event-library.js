$(function(){
	$("#kel").change(function(){
		var parent = $(this).parent();
		var id = $(this).attr('id');
		var nilai = $(this).val();
		var data={'id':nilai};
		var target_combo = $("#risk_type_no");
		var url = "ajax/get-rist-type";
		_ajax_("post", parent, data, target_combo, url);
	})
	
	$(document).on("click",".detail-used",function(){
		var parent = $(this).parent();
		var id = $(this).attr('data-id');
		var data={'id':id, 'tipe':2};
		var target_combo = $("#risk_type_no");
		var url = "ajax/get-detail-library";
		_ajax_("post", parent, data, '', url, "show_lib");
	})
});

function show_lib(hasil) {
	$("#modal_general").find(".modal-body").html(hasil.library);
	$("#modal_general").find(".modal-title").html(hasil.title);
	$("#modal_general").modal("show");
}
