<div class="jumbotron border p-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="dropzone pointer border-info bg-light" id="dokumen-rfa">
                <div class="fallback">
                    <input name="dokumen_rfa[doc][]" type="file" multiple />
                </div>
            </div>
            <div class="mt-2">
                <label style="color:red;font-size:10px;"><i>*) Supported File : pdf, doc, docx, xls, xlsx</i> </label>
            </div>
        </div>
    </div>
</div>

<div id="preview-template" style="display:none">
    <div class="dz-preview dz-complete dz-image-preview p-0" style="box-shadow: none;">
        <table class="table table-sm table-striped table-bordered">
            <tbody>
                <tr>
                    <td class="text-center">
                        <div class="dz-details">
                            <!-- <div class="dz-size"><span data-dz-size=""></span></div> -->
                            <div class="dz-filename m-0"><span data-dz-name=""></span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dz-image text-center m-3">
                            <img data-dz-thumbnail draggable="false" class="" style="height:64px;width:64px;" />
                        </div>
                    </td>
                </tr>
                <tr class="d-none">
                    <td>
                        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                    </td>
                </tr>
                <tr class="d-none">
                    <td>
                        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                        <div class="dz-success-mark"></div>
                        <div class="dz-error-mark"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="dz-remove border m-1" href="javascript:undefined;" data-dz-remove=""><i
                                class="icon-trash" style="font-size:12px;">&nbsp;Delete</i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



<!-- <div class="dz-preview dz-file-preview">
    <div class="dz-details">
        <div class="dz-filename"><span data-dz-name></span></div>
        <div class="dz-size" data-dz-size></div>
        <img data-dz-thumbnail />
    </div>
    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
</div> -->