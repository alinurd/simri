
<!-- Search field -->
<div class="row h-100">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row h-100">
                    <div class="col-sm-12 col-md-6 col-lg-5">
                        <?=lang('lbl_form');?><hr/>
                        <?php
                        foreach($form1 as $row):?>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4" style="font-weight: normal;"><?=$row['label'];?></label>
                            <div class="col-lg-8">
                                <strong><?=$row['content'];?></strong>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-7">
                        <?=lang('lbl_form');?><hr/>
                        <?php
                        foreach($form2 as $row):?>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4" style="font-weight: normal;"><?=$row['label'];?></label>
                            <div class="col-lg-8">
                                <strong><?=$row['content'];?></strong>
                            </div>
                        </div>
                        <?php endforeach;
                        echo form_open_multipart(base_url('dashboard/simpan-aktifitas'), array('id'=>'form_visitor','class'=>'form-horizontal'));?>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-12" style="font-weight: normal;"><?=lang('form_detail_visitor');?></label>
                            <div class="col-lg-12"  style="background-color:#c4dbed;padding:15px;">
                                <strong><?=$detail;?></strong>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info"><b><h5><?=lang('button_proses');?></h5> </b></button>
                        <?php form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /search field -->