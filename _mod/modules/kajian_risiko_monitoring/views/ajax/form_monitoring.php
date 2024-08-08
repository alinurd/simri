<div class="row justify-content-center">
    <div class="col-md-8">
        <form class="form-horizontal" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="jumbotron p-3 mb-3 border d-flex justify-content-center" id="jumbotron-form">
                <div class="col-md-10">
                    <hr>
                    <div class="form-group row mb-3">
                        <label for="mitigasi" class="col-md-2 col-form-label">Mitigasi<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="mitigasi" class="form-control" readonly
                                value="<?= ( ! empty( $formdata["mitigasi"] ) ? $formdata["mitigasi"] : "" ) ?>"
                                id="mitigasi" placeholder="mitigasi">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="deadline" class="col-md-2 col-form-label">Deadline<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="deadline"
                                value="<?= ( ! empty( $formdata["deadline"] ) ? $formdata["deadline"] : "" ) ?>"
                                id="deadline" placeholder="Deadline" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="status" class="col-md-2 col-form-label">Status<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <select class="form-control select" name="status" id="status">
                                <option value="not-started" <?= ( ! empty( $formdata["status"] ) && $formdata["status"] == "not-started" ? "selected" : "" ) ?>>Not Started</option>
                                <option value="on-progress" <?= ( ! empty( $formdata["status"] ) && $formdata["status"] == "on-progress" ? "selected" : "" ) ?>>On Progress</option>
                                <option value="closed" <?= ( ! empty( $formdata["status"] ) && $formdata["status"] == "closed" ? "selected" : "" ) ?>>
                                    Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="detail_progress" class="col-md-2 col-form-label">Detail Progress<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="detail_progress"
                                value="<?= ( ! empty( $formdata["detail_progress"] ) ? $formdata["detail_progress"] : "" ) ?>"
                                class="form-control" id="detail_progress" placeholder="Detail Progress">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="tanggal_update" class="col-md-2 col-form-label">Tanggal Update<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="tanggal_update"
                                value="<?= ( ! empty( $formdata["tanggal_update"] ) ? date( "Y-m-d", strtotime( $formdata["tanggal_update"] ) ) : "" ) ?>"
                                class="form-control pickadate" id="tanggal_update" placeholder="Tanggal Update">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="dokumen_pendukung" class="col-md-2 col-form-label">Dokumen Pendukung</label>
                        <div class="col-md-10">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="dokumen_pendukung">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="dokumen_pendukung"
                                        aria-describedby="dokumen_pendukung" name="dokumen_pendukung">
                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                            </div>
                            <!-- <input type="text" name="dokumen_pendukung"
                                value="<?= ( ! empty( $formdata["dokumen_pendukung"] ) ? $formdata["dokumen_pendukung"] : "" ) ?>"
                                class="form-control" id="dokumen_pendukung" placeholder="Dokumen Pendukung"> -->
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" id="btn-submit-monitoring"
                                class="btn bg-success btn-labeled btn-labeled-left pull-right" type-btn="<?= $type ?>"
                                data-id="<?= ( ! empty( $formdata["id_monitoring"] ) ) ? $formdata["id_monitoring"] : "" ?>"
                                data-id-mitigasi="<?= ( ! empty( $formdata["id"] ) ) ? $formdata["id"] : "" ?>"
                                data-url="<?= ( ! empty( $btnUrl ) ) ? $btnUrl : "" ?>"
                                data-id-kajian="<?= ( ! empty( $idkajian ) ) ? $idkajian : "" ?>">
                                <b><i class="icon-checkmark-circle"></i></b>Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>