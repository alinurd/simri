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
	
	$("#term").change(function () {
		var parent = $(this).parent();
		var nilai = $(this).val();
		var data = {
			'id': nilai
		};
		var target_combo = $("#minggu");
		var url = "ajax/get-minggu";
		_ajax_("post", parent, data, target_combo, url);
    })
    
    $(document).on('click','#proses', function() {
        var parent = $(this).parent().parent().parent();
		var owner = $("#owner").val();
		var period = $("#period").val();
		var term = $("#term").val();
		var minggu = $("#minggu").val();
		var data={'period':period,'owner':owner, 'term':term, 'minggu':minggu};
		var url = modul_name+"/get-lap";
		_ajax_("get", parent, data, $("#result"), url);
    });
});

$(document).ready(function(){
	$("#proses").trigger("click");
})