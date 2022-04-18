var start_actual_time=0;
var end_actual_time=0;

$(function(){
	$("#filterStatis").change(function(){
        var mode = $(this).val();
        var text = $("#search_text").val();

        var parent = $(this).parent();
        var data={'mode':mode};
        var url = modul_name + "/get-search";
        _ajax_("post", parent, data, '', url, 'show_result');
    })

    $(document).on("click",".download-att", function(){
        var parent = $(this).parent();
        var id = $(this).data('id');
        var data={'id':id};
        var url = "order-product/download-att";
        _ajax_("post", parent, data, $('#url').parent(), url, 'show_att');
    })

    $(document).on("keyup","#nama", function(){
       $('#detail_visitor').val($(this).val());
    })

    $(document).on("change","#jml", function(){
        updown= '<div class="input-group detail-visitor"><span class="input-group-prepend"><span class="input-group-text"><i class="icon-user"></i></span></span><input type="text" name="detail_visitor[]" value="" class="form-control" ></div>';
        $('.detail-visitor').remove();
        var jml=$(this).val();
        for (i = 1; i < jml; i++) {
            $('#div_updown').after(updown);
          } 
     })
});

function show_result(hasil){
    $("#tblStatistik").html(hasil.combo);
}

function jumlah_change(){
    $('#jml').trigger('change');
}