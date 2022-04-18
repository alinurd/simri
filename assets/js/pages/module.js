$(function(){
	$("#append_icon").click(function(){
        var parent = $(this).parent();
        var url = modul_name + "/get-icon";
        _ajax_("get", parent, [], '', url, "show_icon");
    })

    $(document).on("click",".icomoon", function(){
        var target = $(this).children('div').children('div').text();
		var isi=target.split(' ');
        $('#icon').val(isi[0]);
        $('#prepend_icon').children('span').html('<i class="'+isi[0]+'"></i>');
		$("#modal_general").modal('hide');
    })

    $(document).on("click",".fontawesome", function(){
        var target = $(this).children('div').text();
        target=target.trim();
        var isi='fa fa-'+target.replace(' (alias)','');
        $('#icon').val(isi);
        $('#prepend_icon').children('span').html('<i class="'+isi+'"></i>');
		$("#modal_general").modal('hide');
    })
});

$(document).ready(function(){
    console.log(mode_aksi);
    if (mode_aksi=='edit'){
        $('#prepend_icon').children('span').html('<i class="'+$('#icon').val()+'"></i>');
    }
})

function show_icon(hasil){
	$("#modal_general").find(".modal-body").html(hasil.combo);
	$("#modal_general").modal("show");
}