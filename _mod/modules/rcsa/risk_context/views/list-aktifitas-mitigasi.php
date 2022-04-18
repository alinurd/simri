<div class='table-responsive'>
    <?=_l('fld_list_aktifitas_mitigasi');?>
    <span class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right" data-id="<?=$parent['id'];?>" id="add_aktifitas_mitigasi"><b><i class="icon-file-plus "></i></b> <?=_l('fld_add_aktifitas_mitigasi');?></span><br/>&nbsp;<br/>&nbsp;
    <table class="table table-hover" id="tbl_list_aktifitas_mitigasi">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th><?=_l('fld_aktifitas_mitigasi');?></th>
                <th><?=_l('fld_pic');?></th>
                <th><?=_l('fld_koordinator');?></th>
                <th><?=_l('fld_due_date');?></th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            foreach($list_aktifitas as $row):?>
            <tr>
                <td><?=++$no;?></td>
                <td><?=$row['aktifitas_mitigasi'];?></td>
                <td>
                    <ol>
                    <?php 
                        $a = json_decode($row['penanggung_jawab_detail_id']);
                    
                        if (is_array($a)) {
                            foreach ($a as $v) {
                                echo "<li>".$picku[$v]['title']."</li>";
                            }
                        }else{
                            $ll = (isset($picku[$a]))?$picku[$a]['title']:'-';
                            echo "<li>".$ll."</li>";
                        }                    
                    ?>
                    </ol>
                </td>
                <td><?=$row['koordinator_detail'];?></td>
                <td><?=date('d-m-Y', strtotime($row['batas_waktu_detail']));?></td>
                <td class="pointer">
                    <i class="icon-database-edit2  text-primary-400 update-aktifitas-mitigasi" data-rcsa="<?=$parent['id'];?>" data-id="<?=$row['id'];?>"></i> | 
                    <i class="icon-database-remove  text-danger-400 delete-aktifitas-mitigasi" data-id="<?=$row['id'];?>"></i> </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>