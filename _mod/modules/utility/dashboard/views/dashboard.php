<style>
    #modal-startup.modal.fade.show {
        backdrop-filter: blur(5px) !important;
    }
</style>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Filter</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right"><?= _l( 'fld_owner_id' ); ?></label>
                    <div class="col-lg-10">
                        <?php echo form_dropdown( 'owner', $owner, '', 'class="form-control select" style="width:100%;" id="owner"' ); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right"><?= _l( 'fld_type_ass_id' ); ?></label>
                    <div class="col-lg-10">
                        <?php echo form_dropdown( 'type_ass', $type_ass, '', 'class="form-control select" style="width:100%;" id="type_ass"' ); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right"><?= _l( 'fld_period_id' ); ?></label>
                    <div class="col-lg-10">
                        <?php echo form_dropdown( 'period', $period, _TAHUN_ID_, 'class="form-control select" style="width:100%;"  id="period"' ); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right"><?= _l( 'fld_term_id' ); ?></label>
                    <div class="col-lg-10">
                        <?php echo form_dropdown( 'term', $term, _TERM_ID_, 'class="form-control select" style="width:100%;"  id="term"' ); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right"><?= _l( 'fld_minggu_id' ); ?></label>
                    <div class="col-lg-10">
                        <?php echo form_dropdown( 'minggu', $minggu, _MINGGU_ID_, 'class="form-control select" style="width:100%;"  id="minggu"' ); ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <span class="btn btn-primary pointer pull-right" id="proses"> Proses </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Peta Risiko</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="maps">
                <!-- <ul class="nav nav-tabs nav-tabs-top">
                    <li class="nav-item">
                        <a href="#content-tab-00" class="nav-link active show" data-toggle="tab">Inheren
                            <?= $jml_inherent; ?> </a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-01" class="nav-link " data-toggle="tab">Current <?= $jml_residual; ?> </a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-02" class="nav-link " data-toggle="tab">Residual <?= $jml_target; ?> </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="content-tab-00"><?= $map_inherent; ?></div>
                    <div class="tab-pane fade" id="content-tab-01"><?= $map_residual; ?></div>
                    <div class="tab-pane fade" id="content-tab-02"><?= $map_target; ?></div>
                </div> -->
                <?= $matrix_peta_risiko ?>
            </div>
        </div>
    </div>
</div>
<div class="row d-none">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Detail Pelaksanaan Mitigasi</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="result_grap2" style="height:420px;overflow-y:hidden;overflow-x:hidden;">
                <?= $data_grap1; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Detail Ketepatan Pelaporan</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="result_grap4" style="height:420px;overflow-y:auto;overflow-x:hidden;">
                <?= $data_grap2; ?>
            </div>
        </div>
    </div>
</div>

<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Taksonomi & Tipe Risiko</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="result_grap1" style="height:420px;overflow-y:tru;overflow-x:true;">
                <?= $grap1; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Tipe Risiko</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="result_grap2" style="height:420px;overflow-y:hidden;overflow-x:hidden;">
                <?= $data_grap1; ?>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-12">

        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Progrres Aktifivas Mitigasaaaaaaaaaaaaai</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="result_grap3" style="height:420px;overflow-y:hidden;overflow-x:hidden;">
                <?= $grap2; ?>
            </div>
        </div>
    </div>
</div>

 


<!-- Modal -->
<div class="modal fade" id="modal-startup" tabindex="-1" role="dialog" aria-labelledby="modal-startupTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header  bg-bumn-gradient-1 p-2 text-center">
                <h5 class="modal-title" id="modal-startupTitle"><i class="icon-info22 info3"></i>&nbsp;Info Update</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">
                <div class="card shadow-none border mb-0">
                    <div class="card-header text-center">
                        <h3><?= $notif_startup["title"] ?></h3>
                    </div>
                    <div class="card-body">
                        <?= html_entity_decode( $notif_startup["message"] ) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center p-2">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        var status = (parseInt("<?= $notif_startup["status"] ?>") == 1) ? true : false;
        $("#modal-startup").modal({
            backdrop: "static",
            keyboard: false,
            show: status,
        });
    })
</script>