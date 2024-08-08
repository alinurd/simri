<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" action="<?= $formUrl ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="jumbotron p-3 mb-3 border d-flex justify-content-center">
                <div class="col-md-10">
                    <hr>
                    <div class="form-group row mb-3">
                        <label for="risiko" class="col-md-2 col-form-label">Risiko<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="risiko" class="form-control"
                                value="<?= ( ! empty( $register[0]["risiko"] ) ? $register[0]["risiko"] : "" ) ?>"
                                id="risiko" placeholder="Risiko">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="taksonomi" class="col-md-2 col-form-label">Taksonomi BUMN<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text"
                                value="<?= ( ! empty( $register[0]["taksonomi"] ) ? $register[0]["taksonomi"] : "" ) ?>"
                                name="taksonomi" class="form-control" id="taksonomi" placeholder="Taksonomi BUMN">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="tipe-risiko" class="col-md-2 col-form-label">Tipe Risiko<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="tipe_risiko"
                                value="<?= ( ! empty( $register[0]["tipe_risiko"] ) ? $register[0]["tipe_risiko"] : "" ) ?>"
                                class="form-control" id="tipe-risiko" placeholder="Tipe Risiko">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="inherent-impact" class="col-md-2 col-form-label">Level Dampak Inherent<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="impact_inherent_level"
                                value="<?= ( ! empty( $register[0]["impact_inherent_level"] ) ? $register[0]["impact_inherent_level"] : "" ) ?>"
                                class="form-control" id="inherent-impact" placeholder="Level Dampak Inherent">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="inherent-likelihood" class="col-md-2 col-form-label">Level Kemungkinan Inherent<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text"
                                value="<?= ( ! empty( $register[0]["likelihood_inherent_level"] ) ? $register[0]["likelihood_inherent_level"] : "" ) ?>"
                                class="form-control" name="likelihood_inherent_level" id="inherent-likelihood"
                                placeholder="Level Kemungkinan Inherent">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="inherent-level" class="col-md-2 col-form-label">Risk Level Inherent (RL)<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="inherent_risk_level"
                                value="<?= ( ! empty( $register[0]["inherent_risk_level"] ) ? $register[0]["inherent_risk_level"] : "" ) ?>"
                                id="inherent-level" placeholder="Risk Level Inherent (RL)">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="penyebab-risiko" class="col-md-2 col-form-label">Penyebab Risiko<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <div class="jumbotron jumbotron-fluid bg-light border p-3">
                                <table class="table table-borderless">
                                    <tbody>
                                        <?php
                                        if( ! empty( $register[0]["risk_cause"] ) )
                                        {
                                            foreach( json_decode( $register[0]["risk_cause"] ) as $kCause => $vCause )
                                            {
                                                ?>
                                                <tr>
                                                    <td> <input type="text" name="risk_cause[]" class="form-control"
                                                            id="penyebab-risiko" placeholder="Penyebab Risiko"
                                                            value="<?= $vCause ?>">
                                                    </td>
                                                    <td>
                                                        <?php if( $kCause == 0 )
                                                        { ?>
                                                            <button type="button" class="btn btn-success btn-sm btn-add-cause"><i
                                                                    class="icon-plus-circle2"></i></button>
                                                        <?php }
                                                        else
                                                        { ?>
                                                            <button type="button" class="btn btn-danger btn-sm btn-del-cause"><i
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
                                            <tr>
                                                <td> <input type="text" name="risk_cause[]" class="form-control"
                                                        id="penyebab-risiko" placeholder="Penyebab Risiko">
                                                </td>
                                                <td><button type="button" class="btn btn-success  btn-add-cause btn-sm"><i
                                                            class="icon-plus-circle2"></i></button></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="dampak-risiko" class="col-md-2 col-form-label">Dampak Risiko<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <div class="jumbotron jumbotron-fluid bg-light border p-3">
                                <table class="table table-borderless">
                                    <tbody>
                                        <?php
                                        if( ! empty( $register[0]["risk_cause"] ) )
                                        {
                                            foreach( json_decode( $register[0]["risk_impact"] ) as $kImpact => $vImpact )
                                            {
                                                ?>
                                                <tr>
                                                    <td><input type="text" name="risk_impact[]" class="form-control"
                                                            id="dampak-risiko" placeholder="Dampak Risiko"
                                                            value="<?= $vImpact ?>">
                                                    </td>
                                                    <td>
                                                        <?php if( $kImpact == 0 )
                                                        { ?>
                                                            <button type="button" class="btn btn-success btn-add-impact btn-sm"><i
                                                                    class="icon-plus-circle2"></i></button>
                                                        <?php }
                                                        else
                                                        { ?>
                                                            <button type="button" class="btn btn-danger btn-sm btn-del-impact"><i
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
                                            <tr>
                                                <td><input type="text" name="risk_impact[]" class="form-control"
                                                        id="dampak-risiko" placeholder="Dampak Risiko">
                                                </td>
                                                <td><button type="button" class="btn btn-success btn-sm btn-add-impact"><i
                                                            class="icon-plus-circle2"></i></button></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label">Mitigasi<sup class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
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
                                            { ?>
                                                <tr>
                                                    <td><input type="text" name="risk_mitigasi[mitigasi][]"
                                                            class="form-control mitigasi" value="<?= $vMit["mitigasi"] ?>"
                                                            placeholder="Mitigasi">
                                                    </td>
                                                    <td><input type="text" name="risk_mitigasi[pic][]" class="form-control pic"
                                                            placeholder="PIC" value="<?= $vMit["pic"] ?>">
                                                    </td>
                                                    <td><input type="text" name="risk_mitigasi[deadline][]"
                                                            class="form-control deadline" placeholder="Deadline"
                                                            value="<?= $vMit["deadline"] ?>">
                                                    </td>
                                                    <td>
                                                        <?php if( $kImpact == 0 )
                                                        { ?>
                                                            <button type="button" class="btn btn-success btn-add-mitigasi btn-sm"><i
                                                                    class="icon-plus-circle2"></i></button>
                                                        <?php }
                                                        else
                                                        { ?>
                                                            <button type="button" class="btn btn-danger btn-sm btn-del-mitigasi"><i
                                                                    class="icon-bin"></i></button>
                                                        <?php } ?>


                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        { ?>
                                            <tr>
                                                <td><input type="text" name="risk_mitigasi[mitigasi][]"
                                                        class="form-control mitigasi" placeholder="Mitigasi">
                                                </td>
                                                <td><input type="text" name="risk_mitigasi[pic][]" class="form-control pic"
                                                        placeholder="PIC">
                                                </td>
                                                <td><input type="text" name="risk_mitigasi[deadline][]"
                                                        class="form-control deadline" placeholder="Deadline">
                                                </td>
                                                <td><button type="button" class="btn btn-success btn-sm"><i
                                                            class="icon-plus-circle2"></i></button></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="impact-residual" class="col-md-2 col-form-label">Level Dampak Residual<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="impact_residual_level"
                                value="<?= ( ! empty( $register[0]["impact_residual_level"] ) ? $register[0]["impact_residual_level"] : "" ) ?>"
                                class="form-control" id="impact-residual" placeholder="Level Dampak Residual">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="likelihood-residual" class="col-md-2 col-form-label">Level Kemungkinan Residual<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="likelihood_residual_level"
                                value="<?= ( ! empty( $register[0]["likelihood_residual_level"] ) ? $register[0]["likelihood_residual_level"] : "" ) ?>"
                                class="form-control" id="likelihood-residual" placeholder="Level Kemungkinan Residual">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="risk-residual" class="col-md-2 col-form-label">Risk Level Residual (RL)<sup
                                class="text-danger ml-1">(*)</sup></label>
                        <div class="col-md-10">
                            <input type="text" name="residual_risk_level"
                                value="<?= ( ! empty( $register[0]["residual_risk_level"] ) ? $register[0]["residual_risk_level"] : "" ) ?>"
                                class="form-control" id="risk-residual" placeholder="Risk Level Residual (RL)">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <a class="btn bg-slate btn-labeled btn-labeled-left button-action "
                                href="<?= base_url( $module_name . "/register/list/" . $kajian_id ) ?>">
                                <b><i class="icon-exit"></i></b>Back To List</a>
                        </div>
                        <div class="col-md-6">
                            <?php if( ! empty( $disabledSubmit ) )
                            { ?>
                                <button type="button"
                                    class="btn bg-success btn-labeled btn-labeled-left pull-right <?= $disabledSubmit ?>">
                                    <b><i class="icon-checkmark-circle"></i></b>SUBMITTED</button>
                            <?php }
                            else
                            { ?>
                                <button type="submit" class="btn bg-success btn-labeled btn-labeled-left pull-right">
                                    <b><i class="icon-checkmark-circle"></i></b>Save</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>