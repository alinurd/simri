
    <table class="table table-hover table-bordered tabel-framed" id="tbl_kri">
        <thead>
            <tr class="bg-primary-300">
                <th width="5%">No</th>
                <th><?=_l('fld_kpi');?></th>
                <th width="10%" class="text-center"><?=_l('fld_score');?></th>
                <th width="10%" class="text-center"><?=_l('fld_indikator');?></th>
                <th width="10%" class="text-center"><?=_l('fld_jml_kpi');?></th>
                <th width="8%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            foreach($list as $row):
                $bg='';
                if ($row['indikator']==1){
                    $bg='bg-success-400';
                }elseif ($row['indikator']==2){
                    $bg='bg-orange-400';
                }elseif ($row['indikator']==3){
                    $bg='bg-danger-400';
                }
                ?>
                <tr>
                    <td><?=++$no;?></td>
                    <td><?=$row['title'];?></td>
                    <td class="text-center"><?=$row['score'];?></td>
                    <td class="text-center <?=$bg;?>"><?=$row['indikator'];?></td>
                    <td class="text-center">0</td>
                    <td class="pointer text-center">
                        <i class="icon-database-edit2 text-primary-400 edit_kpi" data-parent="<?=$parent;?>"  data-minggu="<?=$minggu;?>" data-id="<?=$row['id'];?>"></i> | 
                        <i class="icon-database-remove text-danger-400 delete_kpi" data-parent="<?=$parent;?>"  data-minggu="<?=$minggu;?>" data-id="<?=$row['id'];?>"></i> </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>