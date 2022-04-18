var sts_open=false;
var kel=1;
$(function(){
	$("#url_type").change(function(){
        var parent = $(this).parent();
        var id = $(this).val();
        var data={'id':id};
        var url = modul_name + "/list-url";
        _ajax_("post", parent, data, $('#url').parent(), url, 'set_url');
    })
});

function set_url(hasil){
    $('#url').parent().html(hasil.combo);
    $('.select').select2({
		allowClear: true
	});
}