var sts_open=false;
var kel=1;
$(function(){
	$("#kelompok").change(function(){
        var parent = $(this).parent();
        var kel = $(this).val();
        var data={'kel':kel};
        var url = modul_name + "/list-history";
        _ajax_("post", parent, data, $('#url').parent(), url, 'list_history');
    })
});

function list_history(hasil){
    $('#content_history').html(hasil.combo);
}