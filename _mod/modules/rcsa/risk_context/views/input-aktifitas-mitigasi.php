 <?php
    
    foreach($mitigasi as $key=>$row):
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
                </div>
            </div>
        </div>
        <?php
    endforeach;?>
    <span class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="simpan_aktifitas_mitigasi"><b><i class="icon-floppy-disk "></i></b> <?=_l('fld_save_aktifitas_mitigasi');?></span>
    <br/>
    <hr/>
<?php
    $min=1;
    $tgl1 = new DateTime();
	$tgl2 = new DateTime($parent['batas_waktu']);
	$max = $tgl2->diff($tgl1)->days + 1;
?>
<script>
    var min=<?php echo $min;?>;
    var max=<?php echo $max;?>;
    $(function () {
        $('.select').select2({
            allowClear: false,
            dropdownParent: $("#modal_general")
        });
        $('[data-popup="tooltip"]').tooltip();
        $('.pickadate2').pickadate({
            selectMonths: true,
            selectYears: true,
            formatSubmit: 'yyyy/mm/dd',
            // min: min,
            max: max
        });
    })
</script>
</div>