$(function(){
	$("#test_email").click(function(){
        var parent = $(this).parent();
        var id=$("input[name='id']").val();
        var data = $("#form_input").serialize();
        var url = modul_name + "/sent-email";
        _ajax_("post", parent, data, [], url, "show_message");
    })
});


function show_message(hasil){
	$("#modal_general").find(".modal-body").html(hasil.combo);
	$("#modal_general").modal("show");
}