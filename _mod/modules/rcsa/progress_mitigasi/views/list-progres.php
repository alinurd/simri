<?php
    
    $no_edit='';
    $events='auto';
    $no_edit_hide='';
   
    if(intval($parent['status_id_mitigasi'])>=1 && intval($parent['status_final_mitigasi'])>=0){
        $no_edit_hide=' d-none ';
        $no_edit=' disabled="disabled" ';
        $events='none';
    }
?>

<div class='table-responsive'>
    <?=_l('fld_list_progres_mitigasi');?>
    <span style="display: none;" class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right <?=$no_edit_hide;?>" data-id="<?=$aktifitas_mitigas['id'];?>" id="add_progres" id="add_mitigasi" style="pointer-events:<?=$events;?>" ><b><i class="icon-file-plus "></i></b> <?=_l('fld_add_progres_mitigasi');?></span>
    <br/>&nbsp;<br/>&nbsp;
    <table class="table table-hover" id="tbl_list_mitigasi">
        <thead>
            <tr>
                <th>No</th>
                <th><?=_l('fld_target');?></th>
                <th><?=_l('fld_aktual');?></th>
                <th><?=_l('fld_uraian');?></th>
                <th >Bulan Progress</th>
                <th ><?=_l('fld_tgl_update');?></th>
                <th><?=_l('fld_kendala');?></th>
                <?php if (empty($no_edit)):?>
                <th width="15%" class="text-center">Aksi</th>
                <?php endif;?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            foreach($detail_progres as $row):?>
            <tr>
                <td><?=++$no;?></td>
                <td><?=$row['target'];?></td>
                <td><?=$row['aktual'];?></td>
                <td><?=$row['uraian'];?></td>
                <td><?=(isset($minggu[$row['minggu_id']]))?$minggu[$row['minggu_id']]:'belum ditentukan';?></td>
                <td><?=date('d-m-Y', strtotime($row['created_at']));?></td>
                <td><?=$row['kendala'];?></td>
                <?php if (empty($no_edit)):?>
                <td class="pointer text-center">
                    <i class="icon-database-edit2  text-primary-400 update-progres <?= $no_edit_hide ?>" data-mitigasi="<?=$aktifitas_mitigas['id'];?>" data-id="<?=$row['id'];?>" data-popup="tooltip" data-html="true" title=" Update Progres Mitigasi "></i> | 
                    <i class="icon-database-remove  text-danger-400 delete-progres" data-mitigasi="<?=$aktifitas_mitigas['id'];?>" data-id="<?=$row['id'];?>" data-popup="tooltip" data-html="true" title=" Hapus data Progres Mitigasi "></i> </td>
                <?php endif;?>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>