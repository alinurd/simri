var sts_open=false;
var kel=1;
$(function(){

	$("#add_staft, #add_staft_review").click(function(){
        if ($(this).attr('id')=='add_staft_review'){
            kel=3;
        }else{
            kel=1;
        }
        if (!sts_open){
            var parent = $(this);
            var data={'kel':kel};
            var url = modul_name + "/list-staft";
            _ajax_("post", parent, data, '', url, "detail_nomor");
            sts_open=true;
        }else{
            $("#modal_general").modal("show");
        }
    })
    
    $(document).on("click",".del-detail",function(){
        var id=$(this).data('id');
        var parent=$(this).closest('tr');

        var globalConfirm = new Noty({
            text: '<h6 class="mb-3">Please confirm your action</h6><label>are you sure you want to permanently delete this data ?</label>',
            timeout: false,
            modal: true,
            layout: 'center',
            theme: '  p-0 bg-white',
            closeWith: 'button',
            type: 'confirm',
            buttons: [
                Noty.button('Cancel', 'btn btn-link', function () {
                    globalConfirm.close();
                }),

                Noty.button('Delete <i class="icon-paperplane ml-2"></i>', 'btn bg-blue ml-1', function () {

                    if (id>'0'){
                        var data={'id':id};
                        var url = modul_name + "/delete-staft";
                        _ajax_("post", parent, data, '', url);
                        sts_open=true;
                    }
                    
                    parent.remove();
                    globalConfirm.close();
                },
                {id: 'button1', 'data-status': 'ok'}
                )
            ]
        }).show();
	})

	$(document).on('click','.up, .down', function(){
		var row = $(this).parents("tr:first");
		$(".up,.down").show();
		if ($(this).is(".up")) {
			row.insertBefore(row.prev());
		} else {
			row.insertAfter(row.next());
		}
		// $("tbody tr:last .down").hide();
		$(this).closest('table').find('tbody tr:last').find('.down').hide();
		$(this).closest('table').find('tbody tr:first').find('.up').hide();
    });
    
    $(document).on("click",".pilih-staft",function(){
        var nama=$(this).data('nama');
        var jabatan=$(this).data('title');
        var id=$(this).data('id');
        if (kel==1){
            var row = $('#detail_staft > tbody');
            row.append('<tr><td>&nbsp;'+edit+'<input name="staft_id[]" type="hidden" value="'+id+'"></td><td class="text-center"><i class="icon icon-square-up pointer text-success up" title="Naik"></i> &nbsp;<i class="icon icon-square-down pointer text-warning down" title="Turun"></i></td><td>'+title+'</td><td>'+nama+'</td><td>'+jabatan+'</td><td class="text-center"><i class="icon icon-trash pointer text-success del-detail" title="Delete" data-id="0"></i></td></tr>');
        }else if (kel==3){
            var row = $('#detail_staft_review > tbody');
            row.append('<tr><td>&nbsp;'+edit_review+'<input name="staft_id_review[]" type="hidden" value="'+id+'"></td><td class="text-center"><i class="icon icon-square-up pointer text-success up" title="Naik"></i> &nbsp;<i class="icon icon-square-down pointer text-warning down" title="Turun"></i></td><td>'+nama+'</td><td>'+jabatan+'</td><td class="text-center"><i class="icon icon-trash pointer text-success del-detail" title="Delete" data-id="0"></i></td></tr>');
        }
        $("#modal_general").modal("hide");
    })

    $("#add_distribusi").click(function(){
        var row = $('#detail_distribusi > tbody');
        row.append('<tr><td>&nbsp;'+edit_distribusi+'</td><td class="text-center"><i class="icon icon-square-up pointer text-success up" title="Naik"></i> &nbsp;<i class="icon icon-square-down pointer text-warning down" title="Turun"></i></td><td>'+distribusi+'</td><td class="text-center"><i class="icon icon-trash pointer text-success del-detail" title="Delete" data-id="0"></i></td></tr>');

        $('.selectx').select2({
            allowClear: true
         });
    })
});

function detail_nomor(hasil){
	$("#modal_general").find(".modal-body").html(hasil.combo);
	$("#modal_general").modal("show");
}