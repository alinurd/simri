<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 mb-3 border">
            <a class="btn bg-slate btn-labeled btn-labeled-left button-action mr-3"
                href="<?= base_url( $module_name ) ?>">
                <b><i class="icon-exit"></i></b> Back To Kajian Risiko Monitoring </a>
            <a class="btn bg-warning btn-labeled btn-labeled-left button-action"
                href="<?= base_url( "kajian-risiko/register/propose/" . $kajian_id ) ?>">
                <b><i class="icon-file-text2"></i></b> Preview Risk Register </a>
            <button type="button" id="btnModalRegister" data-id="<?= $kajian_id ?>"
                data-url="<?= base_url( $module_name . "/monitoringModal" ) ?>"
                class="btn bg-primary btn-labeled btn-labeled-left button-action pull-right">
                <b><i class="icon-file-text2"></i></b> Monitoring </button>
        </div>
    </div>
</div>