$(function(){
	$("#append_token").click(function(){
        var parent = $(this).parent();
        var id=$("input[name='id']").val();
        var data={'id':id};
        var url = modul_name + "/get-token";
        _ajax_("get", parent, data, $("#token"), url,'','','','val');
    })
});