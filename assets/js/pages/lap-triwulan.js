$(function(){
    $(document).on("keypress","#search_text", function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        console.log(keycode);
        if(keycode == '13'){
            $("#btnSearch").trigger("click");
        }
    })

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
    
    $(".detail").click(function(){
        var text = $(this).data('barcode');

        var parent = $(this).parent();
        var data={'text':text,'kel':2};
        var url = modul_name + "/get-search";
        _ajax_("post", parent, data, '', url, 'show_result');
    })

    $(document).on("keyup","#nama", function(){
        $('#detail_visitor').val($(this).val());
    })

    $(document).on('click','.detail-peta', function() {
		var parent = $(this).parent().parent().parent();
		 var owner = $("#owner").val();
		var period = $("#period").val();
		var type_ass = $("#type_ass").val();
		var term = $("#term").val();
		var id = $(this).data('id');
		var level = $(this).data('level');
		var data={'id':id, 'level':level, 'period':period,'owner':owner,'type_ass':type_ass, 'term':term};
		var target_combo = '';
		var url = "ajax/get-detail-map";
		_ajax_("post", parent, data, target_combo, url, 'list_map');
    })

    $(document).on('click','.detail-rcsa', function() {
		var parent = $(this).parent().parent().parent();
		var id = $(this).data('id');
		var data={'id':id};
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
    
    $(document).on('click','#proses', function() {
        var parent = $(this).parent().parent().parent();
		var owner = $("#owner").val();
		var period = $("#period").val();
		var type_ass = $("#type_ass").val();
		var term = $("#term").val();
		var minggu = $("#minggu").val();
		var data={'period':period,'owner':owner,'type_ass':type_ass, 'term':term,'minggu':minggu};
		var url = modul_name+"/get-map";
		_ajax_("post", parent, data, '', url,'result_map');
    });

    $(document).on('click','#proses-cetak', function() {
        var parent = $(this).parent().parent().parent();
		var owner = $("#owner").val();
		var csrf = $("input[name='csrf_tridicom']").val();
		var period = $("#period").val();
		var type_ass = $("#type_ass").val();
		var term = $("#term").val();
		var minggu = $("#minggu").val();
		var data={'period':period,'owner':owner,'type_ass':type_ass, 'term':term,'minggu':minggu};
		var url = modul_name+"/get-map-cetak";
        // _ajax_("get", parent, data, '', url,'result_map_cetak');
        
        // var xhr = new XMLHttpRequest();
        // xhr.open('post', url, true);
        
        // xhr.responseType = 'blob';
        // xhr.setRequestHeader('Content-Type', 'application/json;charset=utf-8');
        // xhr.onload = function () {
        //     if (this.status == 200) {
        //         var blob = this.response;
        //         // console.log(blob);
        //         var a = document.createElement('a');
        //         var urldownload = window.URL.createObjectURL(blob);
        //         a.href = urldownload;
        //         a.download = 'User Information.xls';
        //         a.click();
        //     }
        // }
       
        $.ajax({
            url:url,
            cache:false,
            type:'POST',
            data:data,
            xhrFields:{
                responseType: 'blob'
            },
            success: function(data){
                var a = document.createElement('a');
                var urldownload = window.URL.createObjectURL(data);
                a.href = urldownload;
                a.download = 'Laporan Triwulan.xls';
                a.click();

            },
            error:function(){
                
            }
        });
  
    });
});

function list_map(hasil){
    // $('#result').html(hasil.combo);
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function result_map(hasil){
    $("#maps").html(hasil.combo);
    $("#result_grap1").html(hasil.grap1);
    $("#result_grap2").html(hasil.data_grap1);
}

function result_map_cetak(hasil){
    var a = document.createElement('a');
    var url = window.URL.createObjectURL(hasil);
    a.href = url;
    a.download = 'User Information.xls';
    a.click();

   
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

function show_result(hasil){
    $("#modal_general_title").find(".modal-body").html(hasil.combo);
    $("#modal_general_title").modal("show");
    var elems = Array.prototype.slice.call(document.querySelectorAll('.form-switchery-primary'));
    elems.forEach(function(html) {
    var switchery = new Switchery(html, { color: '#2196F3' });
    });
}

function jumlah_change(){
    $('#jml').trigger('change');
}