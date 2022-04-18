<?=$info_parent;
$disabled='';
if ($lanjut):?>
    <div class="alert alert-primary alert-dismissible"><?=$hidden['ket'];?></div>
<?php else:
    $disabled=' disabled="disabled" ';?>
    <div class="alert alert-danger alert-dismissible"><?=$hidden['ket'];?></div>
<?php endif;?>

<?php
echo form_open_multipart(base_url(_MODULE_NAME_.'/simpan-propose/'.$id), array('id'=>'form_propose','class'=>'form-horizontal'), $hidden);
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="<?=base_url(_MODULE_NAME_);?>" class="btn bg-primary-400 btn-labeled btn-labeled-left legitRipple" ><b><i class="icon-list"></i></b> <?=_l('fld_list_identifikasi');?></a>
                <button type="submit" data-id="<?=$parent['id'];?>" class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="propose" <?=$disabled;?>><b><i class="icon-floppy-disk "></i></b> <?=_l('fld_proses_propose');?></button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <?=$note_propose;?>
            </div>
        </div>
    </div>
</div>
<?=$regis;?>

<?php echo form_close();?>
<?=$info_alur;?>
<div class="text-center d-none" id="propose">
    <a href="<?=base_url(_MODULE_NAME_.'/proses-propose/'.$parent['id']);?>" class="btn btn-primary pointer"> Propose Data </a>
</div>