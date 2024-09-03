$(function () {
    $(document).on("click", "#showpass", function (e) {
        if ($(this).prop("checked")) {
            $("input[name='password']").attr("type", "text");
            $("input[name='passwordc']").attr("type", "text");
        } else {
            $("input[name='password']").attr("type", "password");
            $("input[name='passwordc']").attr("type", "password");
        }
    })
})