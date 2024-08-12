<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 mb-3 border">
            <div class="row">
                <div class="col-md-12">
                    <a class="btn bg-slate btn-labeled btn-labeled-left button-action mr-3"
                        href="<?= base_url( $module_name ) ?>">
                        <b><i class="icon-exit"></i></b> Back To Kajian Risiko </a>
                    <a class="btn bg-primary btn-labeled btn-labeled-left button-action "
                        href="<?= base_url( $module_name . "/register/list/" . $kajian_id ) ?>">
                        <b><i class="icon-redo2"></i></b> Risk Register </a>
                    <a class="btn bg-success btn-labeled btn-labeled-left button-action pull-right <?= $disabledSubmit ?>"
                        href="<?= base_url( $module_name . "/register/submit/" . $kajian_id ) ?>">
                        <b><i class="icon-checkmark-circle"></i></b> Submit </a>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="alert alert-info shadow-none border-none m-0">
                        <p>Risk Register Ini akan Di Kirim Ke Officer
                            <b><?= ( ! empty( $headerRisk["owner_name"] ) ? $headerRisk["owner_name"] : "" ) ?></b>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>