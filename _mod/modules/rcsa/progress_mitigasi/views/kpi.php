<div class='table-responsive' id="div_like_indi">
    <?=_l('fld_list_kpi');?>
    <span class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right d-none" data-parent="<?=$parent;?>" data-minggu="<?=$minggu;?>" id="add_kpi" ><b><i class="icon-file-plus "></i></b> <?=_l('fld_add_kpi');?></span><br/>&nbsp;<br/>&nbsp;
    <table class="table table-hover table-bordered tabel-framed" id="tbl_list_like_indi">
        <thead>
            <tr class="bg-primary-300">
                <th width="5%">No</th>
                <th><?=_l('fld_kpi');?></th>
                <th width="10%" class="text-center"><?=_l('fld_score');?></th>
                <th width="10%" class="text-center"><?=_l('fld_indikator');?></th>
                <th width="10%" class="text-center"><?=_l('fld_jml_kri');?></th>
                <th width="7%" class="text-center"><?=_l('fld_add_kri');?></th>
                <th width="8%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            foreach($list as $row):
                $bg='';
                if ($row['score'] >= $row['s_1_min'] && $row['score'] <= $row['s_1_max']){
                    $bg='bg-success-400';
                }elseif ($row['score'] >= $row['s_2_min'] && $row['score'] <= $row['s_2_max']){
                    $bg='bg-orange-400';
                }elseif ($row['score'] >= $row['s_3_min'] && $row['score'] <= $row['s_3_max']){
                    $bg='bg-danger-400';
                }
                // if ($row['indikator']==1){
                //     $bg='bg-success-400';
                // }elseif ($row['indikator']==2){
                //     $bg='bg-orange-400';
                // }elseif ($row['indikator']==3){
                //     $bg='bg-danger-400';
                // }
                ?>
                <tr>
                    <td><?=++$no;?></td>
                    <td><?=$row['title'];?> </td>
                    <td class="text-center"><?=$row['score'];?></td>
                    <td class="text-center <?=$bg;?>"><?=$row['indikator'];?></td>
                    <td class="text-center"><?=$row['kri_count'];?></td>
                    <td class="text-center"><span class="btn btn-primary add-kri" data-parent="<?=$parent;?>"  data-minggu="<?=$minggu;?>"  data-id="<?=$row['id'];?>"> Tambah KRI </span></td>
                    <td class="pointer text-center">
                        <i class="icon-database-edit2 text-primary-400 edit_kpi" data-parent="<?=$parent;?>"  data-minggu="<?=$minggu;?>" data-id="<?=$row['id'];?>"></i> | 
                        <i class="icon-database-remove text-danger-400 delete_kpi" data-parent="<?=$parent;?>"  data-minggu="<?=$minggu;?>" data-id="<?=$row['id'];?>"></i> </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <br/>&nbsp;
    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
</div>