var tbl_mitigasi;
$(function(){
	$(document).on("click",".btnNext", function () {
		$('.nav-tabs').find('.active').closest('li').next('li').find('a').trigger('click');
	});

	$(document).on("click",".btnPrevious", function () {
		$('.nav-tabs').find('.active').closest('li').prev('li').find('a').trigger('click');
	});

	$(document).on('click','.delete-progres', function(){
        var objek = $(this);
        var notyConfirm = new Noty({
                    text: Globals.hapus,
                timeout: false,
                modal: true,
                layout: 'center',
                theme: '  p-0 bg-white',
                closeWith: 'button',
                type: 'confirm',
                buttons: [
                    Noty.button('Cancel', 'btn btn-link', function () {
                        notyConfirm.close();
                    }),

                    Noty.button('Delete <i class="icon-paperplane ml-2"></i>', 'btn bg-blue ml-1', function () {
                            notyConfirm.close();
                            var parent = objek.parent().parent().parent();
                            var nilai = objek.data('id');
                            var mitigasi = objek.data('mitigasi');
                            var data = {
                                'id': nilai,
                                'mitigasi_id': mitigasi
                            };
                            var target_combo = '';
                            var url = modul_name + "/hapus-progres";
                            _ajax_("post", parent, data, target_combo, url, 'del_progres');
                        },
                        {id: 'button1', 'data-status': 'ok'}
                    )
                ]
                }).show();
	})

	$(document).on('click','#add_progres', function(){
		var parent = $(this).parent().parent().parent();
		var nilai = $(this).data('id');
		var data = {
			'mitigasi_id': nilai,
			'id': 0,
		};
		var target_combo = $("#entry_progres");
		var url = modul_name + "/add-progres";
		_ajax_("post", parent, data, target_combo, url);
	})

	$(document).on('click','.update-progres', function(){
		var parent = $(this).parent().parent().parent();
		var mitigasi = $(this).data('mitigasi');
		var id = $(this).data('id');
		var data = {
			'mitigasi_id': mitigasi,
			'id':id
		};
		var target_combo = $("#entry_progres");
		var url = modul_name + "/add-progres";
		_ajax_("post", parent, data, target_combo, url);
	})

	$(document).on('click','#simpan_progres', function(){
		var parent = $(this).parent().parent().parent();
		var data = $("#form_progres").serialize();
		var target_combo = '';
		var url = modul_name + "/simpan-progres";
		_ajax_("post", parent, data, target_combo, url, 'list_progres');
	})
});

$(document).on("click", "#cekLog", function () {
    var parent = $(this).parent();
    var id = $(this).data('id');
    var data = { 'id': id };
     var url = modul_name + "/get-ceklog";
    _ajax_("post", parent, data, '', url, 'resCekLog');
})
$(document).on("click", "#sendEmail", function () {
    return false
    var parent = $(this).parent();
    var id = $(this).data('id');
    var data = { 'id': id };
     var url = modul_name + "/send-email";
    _ajax_("post", parent, data, '', url, 'resCekLog');
})

function resCekLog(hasil) {
    $("#modal_general").find(".modal-title").html("Cek Log");
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function del_progres(hasil){
    $("#list_progres").html(hasil.list_progres);
    alert(hasil.combo);
}

function list_progres(hasil){
	$("#entry_progres").html(hasil.update);
	$("#list_progres").html(hasil.list_progres);
}