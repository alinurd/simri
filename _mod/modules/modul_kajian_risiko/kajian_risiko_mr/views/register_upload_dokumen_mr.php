<div id="modal_dokumen_mr" class="modal fade " tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Dokumen Kajian Risiko MR</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-none border" id="card-dokumen-risiko-mr">
                            <div class="card-body bg-light">
                                <form method="post" id="form-dokumen-mr" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="dokumen-mr-input" class="mb-3"><strong>Input Dokumen
                                                Kajian Risiko
                                                MR</strong></label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="dokumen-mr-input"
                                                name="dokumen_kajian_risiko_mr" accept=".pdf, .doc, .docx, .xls, .xlsx"
                                                required>
                                            <label id="label-dokumen-mr-input" class="custom-file-label"
                                                for="dokumen-mr-input">Choose
                                                file...</label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <label style="color:red;font-size:10px"><i>*) Supported File : pdf, doc, docx,
                                                xls, xlsx</i>
                                        </label>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body" id="result-dokumen">

                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="icon-close2">
                            </i> Close</button>
                        <button type="button" class="btn btn-sm btn-success pull-right"
                            data-url="<?= base_url( $module_name . "/uploadDokumenMr/" . $kajian_id ) ?>"
                            data-id="<?= $kajian_id ?>" id="submit-dokumen-mr"><i class="icon-checkmark-circle">
                            </i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>