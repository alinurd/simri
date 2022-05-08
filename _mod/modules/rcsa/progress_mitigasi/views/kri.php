<div class='table-responsive' id="div_like_indi">
    <div class="entri_kri">
        <?=$entri;?>
    </div>
    <?=_l('fld_list_kri');?>
    <span class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple  pull-right d-none" data-parent="<?=$parent;?>" data-id="0" id="add_kri" ><b><i class="icon-file-plus "></i></b> <?=_l('fld_add_kri');?></span><br/>&nbsp;<br/>&nbsp;
    <div id="list_kri">
    <table class="table table-hover table-bordered tabel-framed" id="tbl_kri">
        <thead>
            <tr class="bg-primary-300">
                <th width="5%">No</th>
                <th><?=_l('fld_kri');?></th>
                <th width="10%" class="text-center"><?=_l('fld_score');?></th>
                <th width="10%" class="text-center"><?=_l('fld_indikator');?></th>
                <th width="8%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            foreach($list as $row):
                $bg='';
                // if ($row['indikator']==1){
                //     $bg='bg-success-400';
                // }elseif ($row['indikator']==2){
                //     $bg='bg-orange-400';
                // }elseif ($row['indikator']==3){
                //     $bg='bg-danger-400';
                // }

                if ($row['score'] >= $row['s_1_min'] && $row['score'] <= $row['s_1_max']){
                    $bg='"style = "background-color:#2c5b29;"';
                }elseif ($row['score'] >= $row['s_2_min'] && $row['score'] <= $row['s_2_max']){
                    $bg='"style = "background-color:#50ca4e;"';
                }elseif ($row['score'] >= $row['s_3_min'] && $row['score'] <= $row['s_3_max']){
                    $bg='"style = "background-color:#edfd17;"';
                }elseif ($row['score'] >= $row['s_4_min'] && $row['score'] <= $row['s_4_max']){
                    $bg='"style = "background-color:#f0ca0f;';
                }elseif ($row['score'] >= $row['s_5_min'] && $row['score'] <= $row['s_5_max']){
                    $bg='"style = "background-color:#e70808;"';
                }
                ?>
                <tr>
                    <td><?=++$no;?></td>
                    <td><?=$row['title'];?></td>
                    <td class="text-center"><?=$row['score'];?></td>
                    <td class="text-center <?=$bg;?>"><?=$row['indikator'];?></td>
                    <td class="pointer text-center">
                        <i class="icon-database-edit2 text-primary-400 edit-kri" data-minggu="<?=$param['minggu'];?>" data-rcsa="<?=$param['rcsa_id'];?>" data-parent="<?=$parent;?>" data-id="<?=$row['id'];?>"></i> | 
                        <i class="icon-database-remove text-danger-400 delete-kri" data-minggu="<?=$param['minggu'];?>" data-rcsa="<?=$param['rcsa_id'];?>" data-parent="<?=$parent;?>" data-id="<?=$row['id'];?>"></i> 
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    </div>
    <br/>
    <span class="btn bg-warning-400 btn-labeled btn-labeled-left legitRipple" data-rcsa="<?=$param['rcsa_id'];?>" data-id="<?=$param['edit_id'];?>" data-minggu="<?=$param['minggu'];?>" id="back_list_kri"><b><i class="icon-arrow-left5"></i></b> <?=_l('fld_back_kpi');?></span>
</div>