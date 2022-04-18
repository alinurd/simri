
<!-- Search field -->
<div class="row h-100">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header bg-primary text-center">
                <div class="row ">
                    <div class="col-lg-2">
                        <img src="<?=img_url('logo_icon_light.png');?>" alt="" width="150"/>
                    </div>
                    <div class="col-lg-10">
                        <h2 class="card-title"><?=$this->preference['nama_kantor'];?></h2>
                        <h5 class="card-title"><?=$this->preference['alamat_kantor'];?></h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
            <?php
                echo form_open_multipart($this->uri->uri_string.'/simpan-visitor', array('id'=>'form_visitor','class'=>'form-horizontal'));?>
                <div class="row h-100">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <?=lang('lbl_form');?><br/>
                        <span class="text-danger"><sup>*) required </sup></span><hr/>
                        <?php
                        foreach($form1 as $row):?>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2" style="font-weight: normal;"><?=$row['label'];?></label>
                            <div class="col-lg-10">
                                <?=$row['content'];?>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <?=lang('lbl_form');?><br/>
                        <span class="text-danger"><sup>*) required </sup></span><hr/>
                        <?php
                        foreach($form2 as $row):?>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2" style="font-weight: normal;"><?=$row['label'];?></label>
                            <div class="col-lg-10">
                                <?=$row['content'];?>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <button type="submit" class="btn btn-info btn-float" style="width:100%"><b><h3><?=lang('button_register');?></h3> </b></button>
                    </div>
                </div>
                <div class="col-lg-12">
                <hr/>
                    <a href="<?=base_url();?>" class="btn bg-warning-400 legitRipple " style="color:#ffffff;" ><< <?=lang('button_back');?></a>
                </div>
                <?php form_close();?>
            </div>
        </div>
    </div>
</div>
<!-- /search field -->

<script>
    var updown='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$jml));?>';
</script>