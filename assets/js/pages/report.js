$(function(){
	$("#waktu").change(function(){
        var sts=$(this).val();

        if (sts==1){
            $('#divTanggal').removeClass('d-none');
            $('#divBulan').addClass('d-none');
        }else{
            $('#divTanggal').addClass('d-none');
            $('#divBulan').removeClass('d-none');
        }
    })

    $("#tahun").change(function(){
        var parent = $(this).parent();
        var tahun = $(this).val();
        var data={'tahun':tahun};
        var url = modul_name + "/get-bulan";
        _ajax_("post", parent, data, $('#bulan'), url);
    })

    $("#proses").click(function(){
        var parent = $(this).parent().parent();
        var tahun = $(this).val();
        var data = $("#form_report").serializeArray();
        var url = modul_name + "/proses-search";
        _ajax_("post", parent, data, $('#result_lap'), url);
    })
});;

function product(hasil){
    $('#product').html(hasil.combo)
}