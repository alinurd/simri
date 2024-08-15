<div class="row justify-content-center">
    <div class="col-md-12">
        <form method="post" id="form-monitoring" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="jumbotron p-2 border" id="jumbotron-form">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card shadow-none border m-0">
                            <div class="card-body">
                                <h6 class="text-center m-0">Form Monitoring Mitigasi Risiko</h6>
                                <hr>
                                <div class="form-group row mb-3">
                                    <label for="mitigasi" class="col-md-4 col-form-label text-right">Mitigasi<sup
                                            class="text-danger ml-1">(*)</sup></label>
                                    <div class="col-md-8">
                                        <input type="text" name="mitigasi" class="form-control border" readonly
                                            value="<?= ( ! empty( $formdata["mitigasi"] ) ? $formdata["mitigasi"] : "" ) ?>"
                                            id="mitigasi" placeholder="mitigasi" required="required">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="deadline" class="col-md-4 col-form-label text-right">Deadline<sup
                                            class="text-danger ml-1">(*)</sup></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control border" name="deadline"
                                            value="<?= ( ! empty( $formdata["deadline"] ) ? $formdata["deadline"] : "" ) ?>"
                                            id="deadline" placeholder="Deadline" required="required" readonly>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="status" class="col-md-4 col-form-label text-right">Status<sup
                                            class="text-danger ml-1">(*)</sup></label>
                                    <div class="col-md-8">
                                        <select class="form-control select" name="status" id="status"
                                            required="required">
                                            <option value="not-started" <?= ( ! empty( $formdata["status"] ) && $formdata["status"] == "not-started" ? "selected" : "" ) ?>>Not Started
                                            </option>
                                            <option value="on-progress" <?= ( ! empty( $formdata["status"] ) && $formdata["status"] == "on-progress" ? "selected" : "" ) ?>>On Progress
                                            </option>
                                            <option value="closed" <?= ( ! empty( $formdata["status"] ) && $formdata["status"] == "closed" ? "selected" : "" ) ?>>
                                                Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="detail_progress" class="col-md-4 col-form-label text-right">Detail
                                        Progress<sup class="text-danger ml-1">(*)</sup></label>
                                    <div class="col-md-8">
                                        <input type="text" name="detail_progress"
                                            value="<?= ( ! empty( $formdata["detail_progress"] ) ? $formdata["detail_progress"] : "" ) ?>"
                                            class="form-control" id="detail_progress" placeholder="Detail Progress"
                                            required="required">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="tanggal_update" class="col-md-4 col-form-label text-right">Tanggal
                                        Update<sup class="text-danger ml-1">(*)</sup></label>
                                    <div class="col-md-8">
                                        <input type="text" name="tanggal_update"
                                            value="<?= ( ! empty( $formdata["tanggal_update"] ) ? date( "Y-m-d", strtotime( $formdata["tanggal_update"] ) ) : "" ) ?>"
                                            class="form-control pickadate border" id="tanggal_update"
                                            placeholder="Tanggal Update" required="required">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="dokumen_pendukung" class="col-md-4 col-form-label text-right">Dokumen
                                        Pendukung</label>
                                    <div class="col-md-8">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Upload</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="form-control custom-file-input"
                                                    id="dokumen_pendukung" aria-describedby="dokumen_pendukung"
                                                    name="dokumen_pendukung">
                                                <label id="label-dokumen-pendukung" class="custom-file-label"
                                                    for="dokumen_pendukung">Choose
                                                    file</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="file_monitoring" id="file-monitoring"
                                            value="<?= ( ! empty( $formdata["dokumen_pendukung"] ) ? $formdata["dokumen_pendukung"] : "" ) ?>"
                                            class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="button" data-dismiss="modal"
                                            class="btn btn-danger btn-labeled btn-labeled-left">
                                            <b><i class="icon-cancel-circle2"></i></b>Close</button>
                                        <button type="button" id="btn-submit-monitoring"
                                            class="btn bg-success btn-labeled btn-labeled-left pull-right"
                                            type-btn="<?= $type ?>"
                                            data-id="<?= ( ! empty( $formdata["id_monitoring"] ) ) ? $formdata["id_monitoring"] : "" ?>"
                                            data-id-mitigasi="<?= ( ! empty( $formdata["id"] ) ) ? $formdata["id"] : "" ?>"
                                            data-url="<?= ( ! empty( $btnUrl ) ) ? $btnUrl : "" ?>"
                                            data-id-kajian="<?= ( ! empty( $idkajian ) ) ? $idkajian : "" ?>">
                                            <b><i class="icon-checkmark-circle"></i></b>Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>