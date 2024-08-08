<div class="row justify-content-center">
    <div class="col-md-8">
        <form class="form-horizontal" action="<?= $formUrl ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="jumbotron p-3 mb-3 border d-flex justify-content-center" id="jumbotron-form">
                <div class="col-md-10">
                    <hr>
                    <div class="form-group row mb-3">
                        <label for="risiko" class="col-md-2 col-form-label">Risiko<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="risiko" class="form-control" readonly
                                value="<?= ( ! empty( $formdata["risiko"] ) ? $formdata["risiko"] : "" ) ?>" id="risiko"
                                placeholder="Risiko">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="inherent-level" class="col-md-2 col-form-label">Risk Level Inherent (RL)<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="inherent_risk_level"
                                value="<?= ( ! empty( $formdata["inherent_risk_level"] ) ? $formdata["inherent_risk_level"] : "" ) ?>"
                                id="inherent-level" placeholder="Risk Level Inherent (RL)" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="risk-residual" class="col-md-2 col-form-label">Risk Level Residual (RL)<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="residual_risk_level"
                                value="<?= ( ! empty( $formdata["residual_risk_level"] ) ? $formdata["residual_risk_level"] : "" ) ?>"
                                class="form-control" id="risk-residual" placeholder="Risk Level Residual (RL)" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="impact-residual" class="col-md-2 col-form-label">Level Dampak Residual<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="impact_residual_level"
                                value="<?= ( ! empty( $formdata["impact_residual_level"] ) ? $formdata["impact_residual_level"] : "" ) ?>"
                                class="form-control" id="impact-residual" placeholder="Level Dampak Residual">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="likelihood-residual" class="col-md-2 col-form-label">Level Kemungkinan Residual<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="likelihood_residual_level"
                                value="<?= ( ! empty( $formdata["likelihood_residual_level"] ) ? $formdata["likelihood_residual_level"] : "" ) ?>"
                                class="form-control" id="likelihood-residual" placeholder="Level Kemungkinan Residual">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" id="btn-submit-register"
                                class="btn bg-success btn-labeled btn-labeled-left pull-right"
                                data-id="<?= $formdata["id"] ?>" data-url="<?= $btnUrl ?>"
                                data-id-kajian="<?= $formdata["id_kajian_risiko"] ?>">
                                <b><i class="icon-checkmark-circle"></i></b>Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>