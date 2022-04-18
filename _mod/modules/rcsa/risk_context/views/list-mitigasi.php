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
<div class='table-responsive'>
    <?=_l('fld_list_mitigasi');?>
   
    <?php if($rcsa_detail['treatment_id']!=1 && $rcsa_detail['efek_mitigasi']!=4):?>
    <span <?=$no_edit;?> class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right" data-id="<?=$rcsa_detail['id'];?>" id="add_mitigasi" style="pointer-events:<?=$events;?>"><b><i class="icon-file-plus "></i></b> <?=_l('fld_add_mitigasi');?></span>
    <?php endif?>
    <br/>&nbsp;<br/>&nbsp;

    <table class="table table-hover" id="tbl_list_mitigasi">
        <thead>
            <tr>
                <th>No</th>
                <th><?=_l('fld_mitigasi');?></th>
                <th class="text-right"><?=_l('fld_biaya');?></th>
                <th><?=_l('fld_pic');?></th>
                <th><?=_l('fld_koordinator');?></th>
                <th ><?=_l('fld_status_jangka');?></th>
                <th ><?=_l('fld_jml_aktifitas');?></th>
                <th><?=_l('fld_due_date');?></th>
                <th width="20%" colspan="2" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            foreach($mitigasi as $row):
                $jangka=" - ";
                if (intval($row['status_jangka'])==1){
                    $jangka="Jangka Pendek";
                }elseif (intval($row['status_jangka'])==2){
                    $jangka="Jangka Panjang";
                }

                $del='';
                if (intval($row['jml'])==0 && intval($parent['status_id'])==0){
                    $del = '| <i class="icon-database-remove  text-danger-400 delete-mitigasi"  data-rcsa="'.$rcsa_detail['id'].'" data-id="'.$row['id'].'" data-popup="tooltip" data-html="true" title=" Hapus data Mitigasi "></i> ';
                }
            ?>
            <tr>
                <td><?=++$no;?></td>
                <td><?=$row['mitigasi'];?></td>
                <td class="text-right"><?=number_format($row['biaya']);?></td>
                <td>
                    <ol>
                    <?php 
                        $a = json_decode($row['penanggung_jawab_id']);
                    
                        if (is_array($a)) {
                            foreach ($a as $v) {
                                echo "<li>".$picku[$v]['title']."</li>";
                            }
                        }else{
                            echo "<li>".$picku[$a]['title']."</li>";
                        }                    
                    ?>
                    </ol>
                </td>
                <td><?=$row['koordinator'];?></td>
                <td><?=$jangka;?></td>
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
    </table>
</div>

<script>
    $(function(){
        $('[data-popup="tooltip"]').tooltip();
        // tbl_mitigasi = $('#tbl_list_mitigasi').dataTable({
        //     "columns": [
        //             { "width": "5%" },
        //             null,
        //             { "width": "10%" },
        //             null,
        //             null,
        //             { "width": "7%" },
        //             null
        //             { "width": "15%" }
        //         ]
        // });
    })
</script>