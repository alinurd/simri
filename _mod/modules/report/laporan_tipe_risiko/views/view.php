
<!-- Search field -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Filter</h6>
            </div>
            <div class="card-body">
                <?php echo form_open($this->uri->uri_string,array('id'=>'form_report', 'class'=>'form-horizontal','role'=>'form"'));?>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right">Risk Owner</label>
                    <div class="col-lg-10">
                        <?php echo form_dropdown('owner', $owner,"", 'class="form-control select" style="width:100%;"  id="owner"');?>
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
                    <div class="col-lg-10">
                        <?php echo form_dropdown('term', $term, _TERM_ID_, 'class="form-control select" style="width:100%;"  id="term"');?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <span class="btn btn-primary pointer pull-right" id="proses"> Proses </span>
                    </div>
                </div>
                <?=form_close();?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Result</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="result_lap">
                
            </div>
        </div>
    </div>
</div>