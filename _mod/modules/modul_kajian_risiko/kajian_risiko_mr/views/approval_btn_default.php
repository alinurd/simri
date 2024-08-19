<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 mb-3 border">
            <a class="btn bg-slate btn-labeled btn-labeled-left button-action mr-3"
                href="<?= base_url( $module_name ) ?>">
                <b><i class="icon-exit"></i></b> Back To Kajian Risiko MR List </a>
            <!-- <button class="btn bg-success btn-labeled btn-labeled-left button-action" type="button" id="btn-history"
                id-kajian="<?= $kajian_id ?>" data-url="<?= base_url( $module_name . "/getHistoryData" ) ?>">
                <b><i class="icon-history"></i></b>History</button> -->
            <a class="btn bg-primary btn-labeled btn-labeled-left button-action pull-right"
                href="<?= base_url( $module_name . "/register/list/{$kajian_id}" ) ?>">
                <b><i class="icon-file-text2"></i></b>Risk Register </a>
        </div>
    </div>
</div>