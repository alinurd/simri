$(function () {
    $("#append_token").click(function () {
        var parent = $(this).parent();
        var id = $("input[name='id']").val();
        var data = { 'id': id };
        var url = modul_name + "/get-token";
        _ajax_("get", parent, data, $("#token"), url, '', '', '', 'val');
    })
    $(document).on("click", "#showpass", function (e) {
        if ($(this).prop("checked")) {
            $("input[name='password']").attr("type", "text");
            $("input[name='passwordc']").attr("type", "text");
        } else {
            $("input[name='password']").attr("type", "password");
            $("input[name='passwordc']").attr("type", "password");
        }
    })
});