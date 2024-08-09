<div class="card card-body alpha-indigo border-indigo">
    <?php
    foreach($d_target['info'] as $key=>$row):
        $size="";
        if ($key==1){
            $size="style=width:35%;";
        }
        ?>
        <div class="form-group row">
            <label class="col-lg-3 col-form-label text-<?=$this->_preference_['align_label'];?>"><?=$row['title'];?></label>
            <div class="col-lg-9">
                <div class ="input-group" <?=$size;?>>
                    <?=$row['isi'];?>
                </div>
            </div>
        </div>
        <?php
    endforeach;?>
    <div id='list_mitigasi'>
    <?=$list_mitigasi;?>
    </div>
</div>

<?php
foreach($d_target['target'] as $key=>$row):
    $add=false;
    $help='';
    $mandatori=false;
    if (isset($row['mandatori'])){
        $mandatori=$row['mandatori'];
    }
    $required='';
    if ($mandatori){
        $required='<sup class="text-danger">*)</sup>&nbsp;&nbsp;';
    }
    
    if (isset($row['add']))
        $add=$row['add'];
    if (isset($row['help']))
        $help=$row['help'];

    ?>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-<?=$this->_preference_['align_label'];?>"><?=$required.$row['title'].$help;?></label>
        <div class="col-lg-9">
            <div class ="form-group form-group-feedback form-group-feedback-right">
                <?=$row['isi'];?>
                <?php if ($add):
                echo form_input('txt_'.$key,'','class="form-control d-none" id="txt_'.$key.'" placeholder="'.$row['title'].'"');
                ?>
                <div class="form-control-feedback text-primary form-control-feedback-lg pointer manual_combo" data-id="0" data-key="<?=$key;?>" id="add_<?=$key;?>"><i class="icon-plus3"></i></div>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?php
endforeach;?> 