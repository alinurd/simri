<?php
$no_edit='';
$events='auto';
$no_edit_hide='';

if(intval($parent['status_id_mitigasi'])>1 && intval($parent['status_final_mitigasi'])>=0){
    $no_edit_hide=' d-none ';
    $no_edit=' disabled="disabled" ';
    $events='none';
}
foreach($progres as $key=>$row):
    $help='';
    $mandatori=false;
    if (isset($row['mandatori'])){
        $mandatori=$row['mandatori'];
    }
    $quired='';
    if ($mandatori){
        $quired='<sup class="text-danger">*)</sup>';
    }

    $help_popup=true;
    if (isset($row['help_popup'])){
        $help_popup=$row['help_popup'];
    }
    $br='';
    if (!$help_popup){
        $br='<br/>';
    }

    if (isset($row['help'])){
        $help=$row['help'];
    }
    

    ?>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-<?=$this->_preference_['align_label'];?>"><?=$quired.' '.$row['title'].$br.$help;?></label>
        <div class="col-lg-9">
            <div class ="input-group" style="width:100%;">
                <?=$row['isi'];?>
            </div>
        </div>
    </div>
    <?php
endforeach;?>
<span style="color:red;">*) Wajib diisi </span>';
<br/>&nbsp;
<span class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right <?=$no_edit_hide;?>" id="simpan_progres" style="pointer-events:<?=$events;?>"><b><i class="icon-floppy-disk "></i></b> <?=_l('fld_save_progres_mitigasi');?></span>
<hr>

<script>
    $(function () {
        $('[data-popup="tooltip"]').tooltip();
        $('.pickadate').pickadate({
            selectMonths: true,
            selectYears: true,
            formatSubmit: 'yyyy/mm/dd'
    	});
    })
</script>