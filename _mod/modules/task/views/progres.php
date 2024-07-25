<?php
foreach($progres as $key=>$row):
    $help='';
    $add=false;
    if (isset($row['add']))
        $add=$row['add'];

    if (isset($row['help']))
        $help=$row['help'];

    ?>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-<?=$this->_preference_['align_label'];?>"><?=$row['title'].$help;?></label>
        <div class="col-lg-9">
            <div class ="input-group" style="width:<?=($add)?'25%':'100%';?>">
                <?=$row['isi'];?>
                <?php if ($add):
                    ?>
                    <span class="input-group-append">
                        <span class="input-group-text text-primary" style="background-color:transparent;border:none;"> % </span>
                    </span>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?php
endforeach;?>
<br/>&nbsp;
<span class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="simpan_progres"><b><i class="icon-floppy-disk "></i></b> <?=_l('fld_save_progres_mitigasi');?></span>
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