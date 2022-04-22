<div class="card-header header-elements-inline">
    <h5 class="card-title">Filter dan Periode yang akan Disimpan</h5>
</div>
<div class="card-body">
    <div class="form-group row">
        <label class="col-form-label col-lg-2"><?= _l('fld_period_id'); ?></label>
        <div class="col-lg-10">
            <?php echo form_dropdown('period', $period, _TAHUN_ID_, 'class="form-control select" style="width:100%;"  id="period"'); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2"><?= _l('fld_term_id'); ?></label>
        <div class="col-lg-10">
            <?php echo form_dropdown('term', $term, _TERM_ID_, 'class="form-control select" style="width:100%;"  id="term"'); ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <span class="btn btn-primary pointer pull-right" id="proses_check"> Filter Checklist </span>
        </div>
    </div>
</div>