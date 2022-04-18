$(function(){
	$("#append_param_string").click(function(){
        var parent = $(this).parent();
        var url = modul_name + "/get-icon";
        _ajax_("get", parent, [], '', url, "show_icon");
    })

    $(document).on("click",".icomoon", function(){
        var target = $(this).children('div').children('div').text();
		var isi=target.split(' ');
        $('#param_string').val(isi[0]);
        $('#prepend_param_string').children('span').html('<i class="'+isi[0]+'"></i>');
		$("#modal_general").modal('hide');
    })

    $(document).on("click",".fontawesome", function(){
        var target = $(this).children('div').text();
        target=target.trim();
        var isi='fa fa-'+target.replace(' (alias)','');
        $('#param_string').val(isi);
        $('#prepend_param_string').children('span').html('<i class="'+isi+'"></i>');
		$("#modal_general").modal('hide');
    })

    $("#kode").change(function(){
        var parent = $(this).parent();
        var id = $(this).val();
        var data={'id':id};
        var url = modul_name + "/list-url";
        _ajax_("post", parent, data, '', url, 'set_url');
    })
});

$(document).ready(function(){
    if (mode_aksi=='edit'){
        $('#prepend_param_string').children('span').html('<i class="'+$('#param_string').val()+'"></i>');
    }
})

function show_icon(hasil){
	$("#modal_general").find(".modal-body").html(hasil.combo);
	$("#modal_general").modal("show");
}

function set_url(hasil){
    $('#param_text').parent().html(hasil.combo);
    $('.select').select2({
		allowClear: true
	});
}