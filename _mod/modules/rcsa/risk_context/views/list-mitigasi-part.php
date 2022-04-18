<?php
    $no_edit='';
    $events='auto';
    $no_edit_hide='';
    if(intval($parent['status_id'])>0){
        $no_edit_hide=' d-none ';
        $no_edit=' disabled="disabled" ';
        $events='none';
    }
?>

<thead>
    <tr>
        <th>No</th>
        <th><?=_l('fld_mitigasi');?></th>
        <th class="text-right"><?=_l('fld_biaya');?></th>
        <th><?=_l('fld_pic');?></th>
        <th><?=_l('fld_koordinator');?></th>
        <th ><?=_l('fld_jml_aktifitas');?></th>
        <th><?=_l('fld_due_date');?></th>
        <th width="20%" colspan="2" class="text-center">Aksi</th>
    </tr>
</thead>
<tbody>
    <?php
    $no=0;
    foreach($mitigasi as $row):
        $del='';
        if (intval($row['jml'])==0 && intval($parent['status_id'])==0){
            $del = '| <i class="icon-database-remove  text-danger-400 delete-mitigasi"  data-rcsa="'.$rcsa_detail['id'].'" data-id="'.$row['id'].'" data-popup="tooltip" data-html="true" title=" Hapus data Mitigasi "></i> ';
        }
    ?>
    <tr>
        <td><?=++$no;?></td>
        <td><?=$row['mitigasi'];?></td>
        <td class="text-right"><?=number_format($row['biaya']);?></td>
        <td><?=$row['penanggung_jawab'];?></td>
        <td><?=$row['koordinator'];?></td>
        <td><?=$row['jml'];?></td>
        <td><?=date('d-m-Y', strtotime($row['batas_waktu']));?></td>
        <td class="pointer text-center">
            <i class="icon-database-edit2  text-primary-400 update-mitigasi <?=$no_edit_hide;?>" data-rcsa="<?=$rcsa_detail['id'];?>" data-id="<?=$row['id'];?>" data-popup="tooltip" data-html="true" title=" Update Mitigasi "></i> <?=$del;?>
        </td>
        <td class="pointer text-center">
            <span class="btn bg-primary-300 add_aktifitas_mitigasi pointer" data-id="<?=$row['id'];?>" data-popup="tooltip" data-html="true" title=" Tambah Aktifitas Mitigasi "> <i class="icon-file-plus "></i> Aktifitas </span> 
        </td>
    </tr>
    <?php endforeach;?>
</tbody>