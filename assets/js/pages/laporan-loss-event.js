$(function(){
	$("#owner, #period").change(function(){
		var id_owner = $("#owner").val();
		var id_period = $("#period").val();
		var owner = $("#owner option:selected").text();
		var data_owner = owner.trim();
		var data_period = $("#period option:selected").text();
		var id=parseFloat(id_owner) + "-" + parseFloat(id_period);
		$(".data_owner").text(data_owner);
		
		$(".data_period").text(data_period);

		var parent = $(this).parent();
		var data={'owner_no':id_owner,'period_id':id_period,'data_owner':data_owner,'data_period':data_period};
		var url = modul_name + "/proses_search";
		var target_combo ="";
		_ajax_("post", parent, data, '', url,'joni');	
	})

});
function joni(hasil){
$('#result_lap').html("");
$('#result_lap').html(hasil.combo);
console.log(hasil);
}