<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title"><?=_l('fld_title');?></h6>
                <div class="header-elements">
                    <span class="label"><?=(!empty($mode))?'<span class="badge bg-blue-400"> '.$mode_text.' </span>':'';?></span>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td width="20%"><em><?=_l('fld_owner_id');?></em></td><td><strong><?=$parent['owner_name'];?></strong></td></tr>
                    <tr><td><em><?=_l('fld_sasaran_dept');?></em></td><td><strong><?=$parent['sasaran_dept'];?></strong></td></tr>
                    <tr><td><em><?=_l('fld_term_id');?></em></td><td><strong><?=$parent['period_name']. ' - '.$parent['term'];?></strong></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="<?=base_url(_MODULE_NAME_.'/progress/'.$id);?>" class="btn bg-primary-400 btn-labeled btn-labeled-left legitRipple" id="back_identifikasi"><b><i class="icon-list"></i></b> <?=_l('fld_kembali_list_monitoring');?></a>
            </div>
        </div>
    </div>
</div>
<?php
echo form_open_multipart($this->uri->uri_string, array('id'=>'form_progres','class'=>'form-horizontal'));
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-top">
                    <li class="nav-item">
                        <a href="#content-tab-00" class="nav-link active show" data-toggle="tab"><?=_l('fld_progres_mitigasi');?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-01" class="nav-link " data-toggle="tab"><?=_l('fld_detail_informasi');?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="content-tab-00">
                        <div id="entry_progres"><?=$update;?></div>
                        <div id="list_progres"><?=$list_progres;?></div>
                    </div>
                    <div class="tab-pane fade" id="content-tab-01"><?=$informasi;?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close();?>

<script>
    $(function () {
        $('.select').select2({
            allowClear: false
        });
        $('[data-popup="tooltip"]').tooltip();
    })
</script>