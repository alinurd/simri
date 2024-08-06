Dropzone.autoDiscover = false;
$(document).ready(function () {
    DropzoneUpload();
    $('#request_date,#release_date').pickadate({
        selectMonths: false,
        selectYears: false,
        formatSubmit: 'yyyy/mm/dd',
    });
})

$(document).on("click", "#link_dokumen_pendukungbtnLink", function () {
    var getTotalLinKDocPendukung = $(".link_dokumen_pendukung").length + 1;
    var htmlString = '<div class="input-group input-group-sm m-3 link_dokumen_pendukung"><div class="input-group-prepend"><span class="input-group-text"><i class="icon-link"></i></span></div><input type="text" class="form-control" name="link_dokumen_pendukung[' + getTotalLinKDocPendukung + ']" value=""><div class="input-group-append mr-3"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-link_dokumen_pendukung"><i class="fa fa-trash"></i></button></div></div>';
    $(htmlString).insertAfter($(".link_dokumen_pendukung").last());
    getTotalLinKDocPendukung = 0;
})

$(document).on("click", "#link_dokumen_kajianbtnLink", function () {
    var getTotalLinkInputAttchment = $(".link_dokumen_kajian").length + 1;
    var htmlStringattchment = '<div class="input-group input-group-sm m-3 link_dokumen_kajian"><div class="input-group-prepend"><span class="input-group-text"><i class="icon-link"></i></span></div><input type="text" class="form-control" name="link_dokumen_kajian[' + getTotalLinkInputAttchment + ']" value=""><div class="input-group-append mr-3"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-link_dokumen_kajian"><i class="fa fa-trash"></i></button></div></div>';
    $(htmlStringattchment).insertAfter($(".link_dokumen_kajian").last());
    getTotalLinkInputAttchment = 0;
})

$(document).on("click", ".btn-remove-link_dokumen_pendukung", function () {
    $(this).parent().parent().remove();
})

$(document).on("click", ".btn-remove-link_dokumen_kajian", function () {
    $(this).parent().parent().remove();
})

function DropzoneUpload() {
    /** dropzone document pendukung */
    $("#link_dokumen_pendukung").dropzone({
        url: base_url + modul_name + "/uploadAttachmentFile",
        paramName: "dokumen_pendukung",
        maxFilesize: 10,
        // addRemoveLinks: true,
        dictDefaultMessage: "Drag or Drop Your Document here",
        dictDuplicateFile: "Duplicate Files Cannot Be Uploaded",
        preventDuplicates: true,
        previewTemplate: $("#preview-template").html(),
        dictRemoveFile: "<i class='icon-trash'>&nbsp;Delete</i>",
        params: {
            csrf_tridicom: csrf_hash,
            field: "dokumen_pendukung",
            id: $("input[name=id]").val()
        },

        init: function () {
            var thisDropzone = this;
            $.ajax({
                url: base_url + modul_name + '/getAttachmentFile',
                type: 'get',
                data: { idrisk: $("input[name=id]").val(), field: "dokumen_pendukung" },
                dataType: 'json',
                success: function (response) {
                    $.each(response, function (key, value) {
                        var getFile = {
                            id: value.id,
                            serverFileName: value.server_filename,
                            file_type: value.file_type,
                            file_path: value.file_path,
                            name: value.name,
                            size: value.size
                        };
                        thisDropzone.emit("addedfile", getFile);
                        thisDropzone.emit("thumbnail", getFile, base_url + "assets/default/file-thumb-default.png");
                        thisDropzone.emit("complete", getFile);
                        var pathLink = value.file_path.replace("./", "");
                        $(".dz-preview").attr("link", base_url + pathLink + value.server_filename);
                        $(".dz-preview").css("box-shadow", "none");
                        $(".dz-remove").addClass("border");

                    });
                }
            });
            this.on('sending', function (file, xhr, formData) {
                console.log(file);
                console.log(xhr);
            })

            this.on("addedfile", function (file, xhr, formData) {
                looding('light', file.previewElement);
            })

            this.on("complete", function (file) {
                stopLooding(file.previewElement);
            });
        },
        removedfile: function (file) {
            deleteNotification(file);
        },
        success: function (file, response) {
            // file.previewElement.classList.add("dz-success");
            file.id = response.id;
            file.serverFileName = response.server_filename;
            file.name = response.filename;
            file.file_type = response.file_type;
            file.file_path = response.file_path;
            file.previewElement.querySelector("[data-dz-name]").innerHTML = response.filename;
            file.previewElement.querySelector("img").src = base_url + "assets/default/file-thumb-default.png";
            stopLooding(file.previewElement);
        },
        error: function (file, response) {
            file.previewElement.classList.add("dz-error");
        },

    });

    /** dropzone document kajian */
    $("#link_dokumen_kajian").dropzone({
        url: base_url + modul_name + "/uploadAttachmentFile",
        paramName: "dokumen_kajian",
        maxFilesize: 10,
        // addRemoveLinks: true,
        dictDefaultMessage: "Drag or Drop Your Document here",
        dictDuplicateFile: "Duplicate Files Cannot Be Uploaded",
        preventDuplicates: true,
        dictRemoveFile: "<i class='icon-trash'>&nbsp;Delete</i>",
        previewTemplate: $("#preview-template").html(),
        params: {
            csrf_tridicom: csrf_hash,
            field: "dokumen_kajian",
            id: $("input[name=id]").val()
        },
        init: function () {
            var thisDropzone = this;
            $.ajax({
                url: base_url + modul_name + '/getAttachmentFile',
                type: 'get',
                data: { idrisk: $("input[name=id]").val(), field: "dokumen_kajian" },
                dataType: 'json',
                success: function (response) {
                    $.each(response, function (key, value) {
                        var getFile = {
                            id: value.id,
                            serverFileName: value.server_filename,
                            file_type: value.file_type,
                            file_path: value.file_path,
                            name: value.name,
                            size: value.size
                        };
                        thisDropzone.emit("addedfile", getFile);
                        thisDropzone.emit("thumbnail", getFile, base_url + "assets/default/file-thumb-default.png");
                        thisDropzone.emit("complete", getFile);
                        var pathLink = value.file_path.replace("./", "");
                        $(".dz-preview").attr("link", base_url + pathLink + value.server_filename);
                        $(".dz-preview").css("box-shadow", "none");
                        $(".dz-remove").addClass("border");
                    });
                }
            });

            this.on('sending', function (file, xhr, formData) {
                console.log(file);
                console.log(xhr);
            })

            this.on("addedfile", function (file, xhr, formData) {
                looding('light', file.previewElement);
                // file.previewElement.querySelector(".dz-progress").remove();
            })
            this.on("complete", function (file) {
                stopLooding(file.previewElement);
            });
        },
        removedfile: function (file) {
            deleteNotification(file);
        },
        success: function (file, response) {
            // file.previewElement.classList.add("dz-success");
            file.id = response.id;
            file.serverFileName = response.server_filename;
            file.name = response.filename;
            file.file_type = response.file_type;
            file.file_path = response.file_path;
            file.previewElement.querySelector("[data-dz-name]").innerHTML = response.filename;
            file.previewElement.querySelector("img").src = base_url + "assets/default/file-thumb-default.png";
        },
        error: function (file, response) {
            file.previewElement.classList.add("dz-error");
        }
    });

}

$(document).on("click", ".dz-preview", function () {
    var url = $(this).attr("link");
    window.open(url);
})

function deleteNotification(file) {
    var notyConfirm = new Noty({
        text: '<h6 class="mb-3">Please confirm your action</h6><label>are you sure you want to permanently delete this data ?</label>',
        timeout: false,
        modal: true,
        layout: "center",
        theme: "  p-0 bg-white",
        closeWith: "button",
        type: "confirm",
        buttons: [
            Noty.button("<i class='icon-undo2 mr-2'></i>Cancel", "btn btn-outline-secondary pull-left", function () {
                notyConfirm.close();
            }),
            Noty.button(
                '<i class="icon-trash mr-2"></i> Delete',
                "btn btn-danger ml-1",
                function () {
                    notyConfirm.close();
                    $.ajax({
                        url: base_url + modul_name + "/deleteAttachmentFile",
                        type: "POST",
                        dataType: "Json",
                        data: { 'id': file.id, 'file_type': file.file_type, 'file_path': file.file_path, 'server_filename': file.serverFileName, idrisk: $("input[name=id]").val() },
                        beforeSend: function () {
                            looding('light', file.previewElement);
                        }, complete: function () {
                            stopLooding(file.previewElement);
                        },

                    });
                    file.previewElement.remove();
                }, { id: "button1", "data-status": "ok" }
            ),
        ],
    }).show();
}





