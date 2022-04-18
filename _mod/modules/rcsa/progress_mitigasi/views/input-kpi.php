    <?php
echo form_open_multipart($this->uri->uri_string, array('id'=>'form_like_indi','class'=>'form-horizontal'), $param);
foreach($like as $key=>$row):
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

    $help_popup=true;
    if (isset($row['help_popup'])){
        $help_popup=$row['help_popup'];
    }
    $br='';
    if (!$help_popup){
        $br='<br/>';
    }
    
    ?>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-<?=$this->_preference_['align_label'];?>"><?=$required.$row['title'].$br.$help;?></label>
        <div class="col-lg-9">
            <div class ="form-group form-group-feedback form-group-feedback-right input-group">
                <?=$row['isi'];?>
                <?php if ($add):
                    echo form_input('txt_like','','class="form-control d-none" id="txt_like" placeholder="'.$row['title'].'"');
                    ?>
                    <div class="form-control-feedback text-primary form-control-feedback-lg pointer manual_like" data-id="0" id="add_text_like"><i class="icon-plus-circle2"></i></div>
                <?php endif;?>
            </div>
        </div>
    </div>
    <?php
    endforeach;?>
    <br/>
<hr/>
<span class="btn bg-warning-400 btn-labeled btn-labeled-left legitRipple" data-id="<?=$param['rcsa_id'];?>" data-minggu="<?=$param['minggu'];?>"  id="back_list_kpi"><b><i class="icon-arrow-left5"></i></b> <?=_l('fld_back_kpi');?></span>
<span class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="simpan_kpi"><b><i class="icon-floppy-disk "></i></b> <?=_l('fld_save_kpi');?></span>
<br/>
<hr/>
<?php echo form_close(); ?>
<script>
    $(function () {
        $('.select').select2({
            allowClear: false,
            dropdownParent: $("#modal_general")
        });
        $('[data-popup="tooltip"]').tooltip();
        $('.pickadate').pickadate({
            selectMonths: true,
            selectYears: true,
            formatSubmit: 'yyyy/mm/dd'
        });
    })
</script>
</div>