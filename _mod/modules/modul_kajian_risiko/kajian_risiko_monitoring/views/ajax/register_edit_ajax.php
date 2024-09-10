<div class="row justify-content-center">
    <div class="col-md-12">
        <form class="form-horizontal" action="<?= $formUrl ?>" method="post" enctype="multipart/form-data"
            id="form-register-kajian">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="jumbotron p-2 border" id="jumbotron-form">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card shadow-none m-0 border">
                            <div class="card-body">
                                <h6 class="text-center m-0">Form Risk Register Kajian Risiko</h6>
                                <hr>
                                <div class="form-group row mb-3">
                                    <label for="risiko" class="col-md-4 col-form-label text-right">Peristiwa Risiko<sup
                                            class="text-danger ml-1">(*)</sup></label>
                                    <div class="col-md-8">
                                        <input type="hidden" name="risiko"
                                            value="<?= ( ! empty( $formdata["risiko"] ) ? $formdata["risiko"] : "" ) ?>">
                                        <input type="text" class="form-control border" readonly
                                            value="<?= ( ! empty( $formdata["library"] ) ? $formdata["library"] : "" ) ?>"
                                            id="risiko" placeholder="Risiko" required>
                                    </div>
                                </div>

                                <div class="form-group row mb-3">
                                    <label for="risk-current" class="col-md-4 col-form-label text-right">Risk Level
                                        Current
                                        (RL)<sup class="text-danger ml-1">(*)</sup></label>
                                    <div class="col-md-8">
                                        <input type="hidden" name="current_risk_level" id="risk-current"
                                            value="<?= ( ! empty( $formdata["current_risk_level"] ) ? $formdata["current_risk_level"] : "" ) ?>"
                                            required>
                                        <div class="alert alert-sm border shadow-none m-0 p-1 text-center"
                                            style="background-color:<?= $formdata["current_level_color"] ?>;color:<?= $formdata["current_text_level_color"] ?>">
                                            <b><?= $formdata["current_level_name"] ?></b>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="residual-level" class="col-md-4 col-form-label text-right">Risk Level
                                        Residual
                                        (RL)<sup class="text-danger ml-1">(*)</sup></label>
                                    <div class="col-md-8">
                                        <input type="hidden"
                                            value="<?= ( ! empty( $formdata["residual_risk_level"] ) ? $formdata["residual_risk_level"] : "" ) ?>"
                                            name="residual_risk_level" required>
                                        <div class="alert alert-sm border shadow-none m-0 p-1 text-center"
                                            style="background-color:<?= $formdata["residual_level_color"] ?>;color:<?= $formdata["residual_text_level_color"] ?>">
                                            <b><?= $formdata["residual_level_name"] ?></b>
                                        </div>

                                    </div>
                                </div>
                                <div class="jumbotron p-3 border">
                                    <div class="row justify-content-center">
                                        <div class="col-md-10">
                                            <h6 class="m-0 text-center">Edit Risk Level
                                                Current </h6>
                                            <hr>
                                            <div class="form-group row mb-3">
                                                <label for="impact-current"
                                                    class="col-md-4 col-form-label text-right">Level Dampak
                                                    Current<sup class="text-danger ml-1">(*)</sup></label>
                                                <div class="col-md-8">
                                                    <select class="form-control select current-select border bg-white"
                                                        name="impact_current_level" id="impact-current"
                                                        required="required">
                                                        <?php if( ! empty( $levelImpact ) )
                                                        {
                                                            foreach( $levelImpact as $kImpact => $vImpact )
                                                            { ?>
                                                                <option value="<?= $vImpact["code"] ?>" <?= ( ! empty( $formdata["impact_current_level"] ) && $formdata["impact_current_level"] == $vImpact["code"] ? "selected" : "" ) ?>><?= $vImpact["code"] ?> -
                                                                    <?= $vImpact["level"] ?>
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label for="likelihood-current"
                                                    class="col-md-4 col-form-label text-right">Level
                                                    Kemungkinan
                                                    Current<sup class="text-danger ml-1">(*)</sup></label>
                                                <div class="col-md-8">
                                                    <select class="form-control select current-select border bg-white"
                                                        name="likelihood_current_level" id="likelihood-current"
                                                        required="required">
                                                        <?php if( ! empty( $levelLikelihood ) )
                                                        {
                                                            foreach( $levelLikelihood as $kLikelihood => $vLikelihood )
                                                            { ?>
                                                                <option value="<?= $vLikelihood["code"] ?>" <?= ( ! empty( $formdata["likelihood_current_level"] ) && $formdata["likelihood_current_level"] == $vLikelihood["code"] ? "selected" : "" ) ?>><?= $vLikelihood["code"] ?> -
                                                                    <?= $vLikelihood["level"] ?>
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-3">
                                                <label for="result-current-level"
                                                    class="col-md-4 col-form-label text-right">Risk Level
                                                    Current
                                                    (RL)</label>
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-10" id="level-current-risk">
                                                            <div role="alert" id="result-current-level"
                                                                class="alert alert-sm shadow-none border text-center m-0 p-1 text-center"
                                                                style="cursor:default;background-color:<?= ( ! empty( $formdata["current_level_color"] ) ? $formdata["current_level_color"] : "" ) ?>;color:<?= ( ! empty( $formdata["current_text_level_color"] ) ? $formdata["current_text_level_color"] : "" ) ?>">
                                                                <b><?= ( ! empty( $formdata["current_level_name"] ) ? $formdata["current_level_name"] : "No Result" ) ?></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="button" data-dismiss="modal"
                                            class="btn btn-danger btn-labeled btn-labeled-left">
                                            <b><i class="icon-cancel-circle2"></i></b>Close</button>
                                        <button type="button" id="btn-submit-register"
                                            class="btn bg-success btn-labeled btn-labeled-left pull-right"
                                            data-id="<?= $formdata["id"] ?>" data-url="<?= $btnUrl ?>"
                                            data-id-kajian="<?= $formdata["id_kajian_risiko"] ?>">
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