var tr;
var target;
$(function(){
    $(document).on('click','.notes', function(){
		var id = $(this).data("id");
		var owner = $(this).data("owner");
        target = $('input[name="'+id+'_'+owner+'"]');
        var value = target.val();
        tr=$(this).closest('tr');
        edit_note(value);
    });

    $(document).on("click","#revisi", function () {
		var parent = $(this).parent();
		var data = $("#form_propose").serialize();
		var url = modul_name+"/revisi-propose";
		_ajax_("post", parent, data, '', url, 'risk_revisi');
    })
    
    $(document).on('keyup',"textarea[name=\"note_propose_detail\"]", function(){
		var isi=$(this).val();
        target.val(isi);
		if(isi.length>0){
			tr.find('.icon-notebook').removeClass('text-primary').addClass('text-primary');
		}else{
			tr.find('.icon-notebook').removeClass('text-primary');
		}
    });
});

function risk_revisi(){
    window.location.href = base_url+"/"+modul_name;
}

function edit_note(hasil){
    $("#modal_general").find(".modal-title").html('Tambah/Ubah Catatan');
    var note='<strong>Masukkan catatan anda dibawah ini :</strong><br/><textarea name="note_propose_detail" cols="40" rows="10" id="note_propose_detail" placeholder="silahkan masukkan catatan anda disini" maxlength="500" size="500" class="form-control" style="overflow: hidden; width: 100% !important; height: 200px;">'+hasil+'</textarea>';

	$("#modal_general").find(".modal-body").html(note);
	$("#modal_general").modal("show");
}
