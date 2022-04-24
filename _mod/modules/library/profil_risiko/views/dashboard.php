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
                    <label class="col-form-label col-lg-2 text-right"><?=_l('fld_owner_id');?></label>
                    <div class="col-lg-10">
                        <?php echo form_dropdown('owner', $owner,'', 'class="form-control select" style="width:100%;" id="owner"');?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right"><?=_l('fld_type_ass_id');?></label>
                    <div class="col-lg-10">
                        <?php echo form_dropdown('type_ass', $type_ass,'', 'class="form-control select" style="width:100%;" id="type_ass"');?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right"><?=_l('fld_period_id');?></label>
                    <div class="col-lg-10">
                        <?php echo form_dropdown('period', $period, _TAHUN_ID_, 'class="form-control select" style="width:100%;"  id="period"');?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right"><?=_l('fld_term_id');?></label>
                    <div class="col-lg-5">
                        <?php echo form_dropdown('term_mulai', $term, _TERM_ID_, 'class="form-control select term" style="width:100%;"  id="term_mulai"');?>
                    </div>
                    <div class="col-lg-5">
                        <?php echo form_dropdown('term_akhir', $term, _TERM_ID_, 'class="form-control select term" style="width:100%;"  id="term_akhir"');?>
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
                <ul class="nav nav-tabs nav-tabs-top">
                    <li class="nav-item">
                        <a href="#content-tab-00" class="nav-link active show" data-toggle="tab">Inheren <?=$jml_inherent;?> </a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-01" class="nav-link " data-toggle="tab">Residual <?=$jml_residual;?> </a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-02" class="nav-link " data-toggle="tab">Target <?=$jml_target;?> </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="content-tab-00"><?=$map_inherent;?></div>
                    <div class="tab-pane fade" id="content-tab-01"><?=$map_residual;?></div>
                    <div class="tab-pane fade" id="content-tab-02"><?=$map_target;?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Daftar Identifikasi</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="detail_list">
                
            </div>
        </div>
    </div>
</div>