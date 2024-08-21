<?php
$events = 'auto';
if (intval($parent['bk_tipe'])>1){
    $events='none';
}
 
echo form_open_multipart($this->uri->uri_string, array('id'=>'form_dampak_indi','class'=>'form-horizontal'), $parent);?>
<div class='table-responsive' id="div_like_indi">
    <?=_l('fld_list_dampak_indi').' '.$sub_title;?>
    <!-- <span class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right" id="add_dampak_indi"  style="pointer-events:<?=$events;?>"><b><i class="icon-file-plus "></i></b> <?=_l('fld_add_dampak_indi');?></span><br/>&nbsp;<br/>&nbsp; -->
    <table class="table table-hover" id="tbl_list_dampak_indi">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%"><?=_l('fld_jenis_kri')._h('help_type_kri');?></th>
                <th width="45%"><?=_l('fld_kri')._h('help_kri');?></th>
                <th>Detail</th>
                <th width="6%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            $ttl=0;
            foreach($list_dampak_indi as $row):
                $edit=form_hidden(['edit_id[]'=>$row['id']]);
            ?>
            <tr>
                <td><?=++$no;?></td>
                <td><?=$row['cbo_tipe_kri'].$edit;?></td>
                <td><?=$row['cbo_kri'];?></td>
                <td><?=$row['detail_input'];?></td>
                <td class="pointer text-center">
                    <!-- <i class="icon-database-remove text-danger-400 del-dampak-indi" data-parent="<?=$parent['rcsa_detail_no'];?>" data-id="<?=$row['id'];?>"></i> </td> -->
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <br/>&nbsp;
    <hr/>
    <span class="btn bg-warning-400 btn-labeled btn-labeled-left legitRipple" data-dismiss="modal"><b><i class="icon-arrow-left5"></i></b> <?=_l('fld_back_dampak_indi');?></span>
    <span class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="simpan_dampak_indi"><b><i class="icon-floppy-disk "></i></b> <?=_l('fld_save_dampak_indi');?></span>
</div>
<?php echo form_close(); 
$edit=form_hidden(['edit_id[]'=>0]);
?>
<script>
var tipe_kri='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$tipe_kri));?>';
var kri='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$kri));?>';
var detail='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$detail));?>';
var edit_dampak='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$edit));?>';
$(function () {
        $('[data-popup="tooltip"]').tooltip();
    })
</script>