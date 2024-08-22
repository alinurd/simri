<style>
    .select2-container--open {
    z-index: 9999999 !important;
}
</style>
<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" action="<?= $formUrl ?>" method="post" enctype="multipart/form-data" id="form-register">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="jumbotron p-2 border d-flex justify-content-center">
                <div class="col-md-12 p-0">
                    <div class="card shadow-none border m-0">
                        <div class="card-body p-5">
                            <div class="form-group row mb-3">
                                <label for="risiko-name" class="col-md-3 col-form-label text-right">Peristiwa Risiko<sup
                                        class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <input name="risiko" type="hidden" id="risiko"
                                        value="<?= ( ! empty( $register[0]["risiko"] ) ? $register[0]["risiko"] : "" ) ?>">
                                    <input type="text" class="form-control border"
                                        value="<?= ( ! empty( $register[0]["library"] ) ? $register[0]["library"] : "" ) ?>"
                                        id="risiko-name" placeholder="Peristiwa Risiko" readonly required="required">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="taksonomi-name" class="col-md-3 col-form-label text-right">Taksonomi
                                    BUMN<sup class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <input name="taksonomi" type="hidden" id="taksonomi"
                                        value="<?= ( ! empty( $register[0]["taksonomi"] ) ? $register[0]["taksonomi"] : "" ) ?>">
                                    <input type="text" value="<?= ( ! empty( $register[0]["taksonomi_name"] ) ? $register[0]["taksonomi_name"] : "" ) ?>" class="form-control border" id="taksonomi-name" placeholder="Taksonomi BUMN" readonly required="required">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="tipe-risiko-name" class="col-md-3 col-form-label text-right">Tipe Risiko<sup
                                        class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <input name="tipe_risiko" type="hidden" id="tipe-risiko"
                                        value="<?= ( ! empty( $register[0]["tipe_risiko"] ) ? $register[0]["tipe_risiko"] : "" ) ?>">
                                    <input type="text" value="<?= ( ! empty( $register[0]["tipe_risiko_name"] ) ? $register[0]["tipe_risiko_name"] : "" ) ?>" class="form-control border" id="tipe-risiko-name" placeholder="Tipe Risiko" readonly required="required">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inherent-likelihood" class="col-md-3 col-form-label text-right">Level
                                    Kemungkinan
                                    Inherent<sup class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <select class="form-control select-form inherent-select" name="likelihood_inherent_level"
                                        id="inherent-likelihood" required="required">
                                        <option value=""><i>-- Please Select --</i></option>
                                        <?php if( ! empty( $levelLikelihood ) )
                                        {
                                            foreach( $levelLikelihood as $kLikelihood => $vLikelihood )
                                            { ?>
                                                <option value="<?= $vLikelihood["code"] ?>" <?= ( ! empty( $register[0]["likelihood_inherent_level"] ) && $register[0]["likelihood_inherent_level"] == $vLikelihood["code"] ? "selected" : "" ) ?>><?= $vLikelihood["code"] ?> - <?= $vLikelihood["level"] ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inherent-impact" class="col-md-3 col-form-label text-right">Level Dampak
                                    Inherent<sup class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <select class="form-control select-form inherent-select" name="impact_inherent_level"
                                        id="inherent-impact" required="required">
                                        <option value=""><i>-- Please Select --</i></option>
                                        <?php if( ! empty( $levelImpact ) )
                                        {
                                            foreach( $levelImpact as $kImpact => $vImpact )
                                            { ?>
                                                <option value="<?= $vImpact["code"] ?>" <?= ( ! empty( $register[0]["impact_inherent_level"] ) && $register[0]["impact_inherent_level"] == $vImpact["code"] ? "selected" : "" ) ?>><?= $vImpact["code"] ?> - <?= $vImpact["level"] ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                           
                            <div class="form-group row mb-3">
                                <label for="inherent-level" class="col-md-3 col-form-label text-right">Risk Level
                                    Inherent
                                    (RL)</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="inherent_risk_level" id="inherent-level"
                                        value="<?= ( ! empty( $register[0]["inherent_risk_level"] ) ? $register[0]["inherent_risk_level"] : "" ) ?>">
                                    <div class="row">
                                        <div class="col-md-6" id="level-inherent-risk">
                                            <div role="alert" id="result-inherent-level"
                                                class="alert alert-sm shadow-none border text-center m-0 p-1"
                                                style="cursor:default;background-color:<?= ( ! empty( $register[0]["inherent_level_color"] ) ? $register[0]["inherent_level_color"] : "" ) ?>;color:<?= ( ! empty( $register[0]["inherent_text_level_color"] ) ? $register[0]["inherent_text_level_color"] : "" ) ?>;">
                                                <b><?= ( ! empty( $register[0]["inherent_level_name"] ) ? $register[0]["inherent_level_name"] : "No Result" ) ?></b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="likelihood-residual" class="col-md-3 col-form-label text-right">Level
                                    Kemungkinan
                                    Residual<sup class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <select class="form-control select-form residual-select" name="likelihood_residual_level"
                                        id="likelihood-residual" required="required">
                                        <option value=""><i>-- Please Select --</i></option>
                                        <?php if( ! empty( $levelLikelihood ) )
                                        {
                                            foreach( $levelLikelihood as $kLikelihood => $vLikelihood )
                                            { ?>
                                                <option value="<?= $vLikelihood["code"] ?>" <?= ( ! empty( $register[0]["likelihood_residual_level"] ) && $register[0]["likelihood_residual_level"] == $vLikelihood["code"] ? "selected" : "" ) ?>><?= $vLikelihood["code"] ?> - <?= $vLikelihood["level"] ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="impact-residual" class="col-md-3 col-form-label text-right">Level Dampak
                                    Residual<sup class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <select class="form-control select-form residual-select" name="impact_residual_level"
                                        id="impact-residual" required="required">
                                        <option value=""><i>-- Please Select --</i></option>
                                        <?php if( ! empty( $levelImpact ) )
                                        {
                                            foreach( $levelImpact as $kImpact => $vImpact )
                                            { ?>
                                                <option value="<?= $vImpact["code"] ?>" <?= ( ! empty( $register[0]["impact_residual_level"] ) && $register[0]["impact_residual_level"] == $vImpact["code"] ? "selected" : "" ) ?>><?= $vImpact["code"] ?> - <?= $vImpact["level"] ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                           
                            <div class="form-group row mb-3">
                                <label for="risk-residual" class="col-md-3 col-form-label text-right">Risk Level
                                    Residual
                                    (RL)</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="residual_risk_level" id="risk-residual"
                                        value="<?= ( ! empty( $register[0]["residual_risk_level"] ) ? $register[0]["residual_risk_level"] : "" ) ?>">
                                    <div class="row">
                                        <div class="col-md-6" id="level-residual-risk">
                                            <div role="alert" id="result-residual-level"
                                                class="alert alert-sm shadow-none border text-center m-0 p-1"
                                                style="cursor:default;background-color:<?= ( ! empty( $register[0]["residual_level_color"] ) ? $register[0]["residual_level_color"] : "" ) ?>;color:<?= ( ! empty( $register[0]["residual_text_level_color"] ) ? $register[0]["residual_text_level_color"] : "" ) ?>">
                                                <b><?= ( ! empty( $register[0]["residual_level_name"] ) ? $register[0]["residual_level_name"] : "No Result" ) ?></b>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="penyebab-risiko" class="col-md-3 col-form-label text-right">Penyebab
                                    Risiko<sup class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <div class="jumbotron jumbotron-fluid bg-light border p-3">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <?php
                                                if( ! empty( $register[0]["risk_cause"] ) )
                                                {
                                                    foreach( $register[0]["risk_cause"] as $kCause => $vCause )
                                                    {
                                                        ?>
                                                        <tr class="row-penyebab">
                                                            <td>
                                                                <input type="hidden" value="<?= $vCause["risk_cause_id"] ?>"
                                                                    name="risk_cause[]" id="penyebab-risiko">
                                                                <input type="text"
                                                                    class="form-control getLibrary count-penyebab bg-white border"
                                                                    id="penyebab_id_text" placeholder="Penyebab Risiko"
                                                                    value="<?= $vCause["risk_cause_name"] ?>"
                                                                    identity="<?= $kCause ?>" readonly>

                                                            </td>
                                                            <td>
                                                                <?php if( $kCause == 0 )
                                                                { ?>
                                                                    <button type="button"
                                                                        class="btn btn-success btn-sm btn-add-cause"><i
                                                                            class="icon-plus-circle2"></i></button>
                                                                <?php }
                                                                else
                                                                { ?>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm btn-del-cause"><i
                                                                            class="icon-bin"></i></button>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    ?>
                                                    <tr class="row-penyebab">
                                                        <td>
                                                            <input type="hidden" value="" name="risk_cause[]"
                                                                id="penyebab-risiko">
                                                            <input type="text"
                                                                class="form-control getLibrary count-penyebab bg-white border"
                                                                id="penyebab_id_text" value="" identity="1"
                                                                placeholder="Penyebab Risiko" required readonly>

                                                        </td>
                                                        <td><button type="button"
                                                                class="btn btn-success btn-add-cause btn-sm"><i
                                                                    class="icon-plus-circle2"></i></button></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="dampak-risiko" class="col-md-3 col-form-label text-right">Dampak Risiko<sup
                                        class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <div class="jumbotron jumbotron-fluid bg-light border p-3">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <?php
                                                if( ! empty( $register[0]["risk_impact"] ) )
                                                {
                                                    foreach( $register[0]["risk_impact"] as $kImpact => $vImpact )
                                                    {
                                                        ?>
                                                        <tr class="row-dampak">
                                                            <td>
                                                                <input type="hidden" value="<?= $vImpact["risk_impact_id"] ?>"
                                                                    name="risk_impact[]" id="dampak-risiko">
                                                                <input type="text" class="form-control getLibrary count-dampak bg-white border"
                                                                    id="dampak_id_text" placeholder="Dampak Risiko"
                                                                    value="<?= $vImpact["risk_impact_name"] ?>" identity="<?= $kImpact ?>" readonly
                                                                    required>
                                                            </td>
                                                            <td>
                                                                <?php if( $kImpact == 0 )
                                                                { ?>
                                                                    <button type="button"
                                                                        class="btn btn-success btn-add-impact btn-sm"><i
                                                                            class="icon-plus-circle2"></i></button>
                                                                <?php }
                                                                else
                                                                { ?>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm btn-del-impact"><i
                                                                            class="icon-bin"></i></button>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else
                                                {
                                                    ?>
                                                    <tr class="row-dampak">
                                                        <td>
                                                            <input type="hidden" value="" name="risk_impact[]"
                                                                id="dampak-risiko">
                                                            <input type="text" value=""
                                                                class="form-control getLibrary count-dampak bg-white border"
                                                                id="dampak_id_text" placeholder="Dampak Risiko" identity="1"
                                                                required readonly>

                                                        </td>
                                                        <td><button type="button"
                                                                class="btn btn-success btn-sm btn-add-impact"><i
                                                                    class="icon-plus-circle2"></i></button></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-md-3 col-form-label text-right">Mitigasi<sup
                                        class="text-danger ml-1">(*)</sup></label>
                                <div class="col-md-9">
                                    <div class="jumbotron jumbotron-fluid bg-light border p-3">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Mitigasi</strong></td>
                                                    <td><strong>PIC Penanggung Jawab</strong></td>
                                                    <td><strong>Deadline</strong></td>
                                                    <td></td>
                                                </tr>
                                                <?php if( ! empty( $mitigasi ) )
                                                {
                                                    foreach( $mitigasi as $kMit => $vMit )
                                                    {
                                                        $kMit+=1;
                                                        $vMit["pic"]=(!empty($vMit["pic"]) && json_decode($vMit["pic"]))? json_decode($vMit["pic"]):"";
                                                        ?>
                                                        <tr class="row-mitigasi border-top">
                                                            <td>
                                                                <textarea name="risk_mitigasi[mitigasi][<?=$kMit?>]"
                                                                    class="form-control mitigasi"
                                                                    placeholder="Mitigasi" required="required"><?= $vMit['mitigasi'] ?></textarea>
                                                            </td>
                                                            <td>
                                                                    <select class="form-control pic selectpic" name="risk_mitigasi[pic][<?=$kMit?>][list][]"
                                                                        id="impact-residual" required="required">
                                                                        <?php if( ! empty( $mitigasiPicData ) )
                                                                        {
                                                                            foreach( $mitigasiPicData as $kMitPicData => $vMitPicData )
                                                                            {?>
                                                                                <option value="<?= $kMitPicData ?>" <?= (is_array($vMit["pic"])&&in_array($kMitPicData,$vMit["pic"]) ? "selected" : "" ) ?>><?=$vMitPicData?></option>
                                                                            <?php }
                                                                        } ?>
                                                                    </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="risk_mitigasi[deadline][<?=$kMit?>]"
                                                                    class="form-control deadline pickadate-kajian bg-white border"
                                                                    placeholder="Deadline"
                                                                    value="<?= date( "Y-m-d", strtotime( $vMit["deadline"] ) ) ?>" required="required">
                                                            </td>
                                                            <td>
                                                                <?php if( $kMit == 1 )
                                                                { ?>
                                                                    <button type="button"
                                                                        class="btn btn-success btn-add-mitigasi btn-sm"><i
                                                                            class="icon-plus-circle2"></i></button>
                                                                <?php }
                                                                else
                                                                { ?>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm btn-del-mitigasi"><i
                                                                            class="icon-bin"></i></button>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                else
                                                { ?>
                                                    <tr class="row-mitigasi">
                                                        <td>
                                                            <textarea name="risk_mitigasi[mitigasi][1]"
                                                                class="form-control mitigasi"
                                                                placeholder="Mitigasi" required="required"></textarea>
                                                        </td>
                                                        <td>
                                                            <select class="form-control pic selectpic" name="risk_mitigasi[pic][1][list][]" required="required">
                                                                        <?php if( ! empty( $mitigasiPicData ) )
                                                                        {
                                                                            foreach( $mitigasiPicData as $kMitPicData => $vMitPicData )
                                                                            { ?>
                                                                                <option value="<?= $kMitPicData ?>"><?=$vMitPicData?></option>
                                                                            <?php }
                                                                        } ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="risk_mitigasi[deadline][1]"
                                                                class="form-control deadline pickadate-kajian bg-white border"
                                                                placeholder="Deadline" required="required">
                                                        </td>
                                                        <td><button type="button"
                                                                class="btn btn-success btn-sm btn-add-mitigasi"><i
                                                                    class="icon-plus-circle2"></i></button></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <a class="btn bg-slate btn-labeled btn-labeled-left button-action "
                                        href="<?= base_url( $module_name . "/register/list/" . $kajian_id ) ?>">
                                        <b><i class="icon-exit"></i></b>Back</a>
                                </div>
                                <div class="col-md-6">
                                <button type="button" id="btn-submit-register"
                                            class="btn bg-success btn-labeled btn-labeled-left pull-right">
                                            <b><i class="icon-checkmark-circle"></i></b>Save</button>
                                    <!-- <?php if( ! empty( $disabledSubmit ) )
                                    { ?>
                                        <button type="button"
                                            class="btn bg-success btn-labeled btn-labeled-left pull-right <?= $disabledSubmit ?>">
                                            <b><i class="icon-checkmark-circle"></i></b>SUBMITTED</button>
                                    <?php }
                                    else
                                    { ?>
                                        <button type="button" id="btn-submit-register"
                                            class="btn bg-success btn-labeled btn-labeled-left pull-right">
                                            <b><i class="icon-checkmark-circle"></i></b>Save</button>
                                    <?php } ?> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
     var getDeptData = `<?=json_encode($mitigasiPicData)?>`;
     var setPicSelect = `<?=!empty($setPicSelect)?$setPicSelect:''?>`;
</script>