<?=$info_parent;
$disabled='';
$events='auto';
if ($lanjut):
    ?>
    <div class="alert alert-primary alert-dismissible"><?=$hidden['ket'];?></div>
<?php else:
    $events='none';
    $disabled=' disabled="disabled" ';?>
    <div class="alert alert-danger alert-dismissible"><?=$hidden['ket'];?></div>
<?php endif;

echo form_open_multipart(base_url(_MODULE_NAME_.'/proses_propose_mitigasi/'.$parent['id']), array('id'=>'form_propose','class'=>'form-horizontal'), $hidden);
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <span class="btn bg-primary-400 btn-labeled btn-labeled-left legitRipple" data-dismiss="modal"><b><i class="icon-list"></i></b> <?=_l('fld_list_identifikasi');?></span>
                <span data-id="<?=$parent['id'];?>" class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="proses_propose_mitigasi"  style="pointer-events:<?=$events;?>"><b><i class="icon-floppy-disk "></i></b> <?=_l('fld_proses_propose');?></span>
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
<?php echo form_close();?>