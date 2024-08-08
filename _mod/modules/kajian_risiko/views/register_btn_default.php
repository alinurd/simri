<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-3 mb-3 border">
            <a class="btn bg-slate btn-labeled btn-labeled-left button-action mr-3"
                href="<?= base_url( $module_name ) ?>">
                <b><i class="icon-exit"></i></b> Back To Kajian Risiko </a>
            <a class="btn bg-primary btn-labeled btn-labeled-left button-action <?= $disabledSubmit ?> "
                href="<?= base_url( $module_name . "/register/create/" . $kajian_id ) ?>">
                <b><i class="icon-database-add"></i></b> Create New </a>
            <button type="button" id="btnModalRegister" data-id="<?= $kajian_id ?>"
                data-url="<?= base_url( $module_name . "/riskRegisterModal" ) ?>"
                class="btn bg-warning btn-labeled btn-labeled-left button-action pull-right">
                <b><i class="icon-file-text2"></i></b> Risk Register </button>
        </div>
    </div>
</div>