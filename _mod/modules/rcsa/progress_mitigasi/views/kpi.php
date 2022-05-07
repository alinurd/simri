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
                <th width="8%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            foreach($list as $row): 
                $bg='';
                if ($row['score'] >= $row['s_1_min'] && $row['score'] <= $row['s_1_max']){
                    $bg='"style = "background-color:#2c5b29;"';
                }elseif ($row['score'] >= $row['s_4_min'] && $row['score'] <= $row['s_4_max']){
                    $bg='"style = "background-color:#50ca4e;"';
                }elseif ($row['score'] >= $row['s_2_min'] && $row['score'] <= $row['s_2_max']){
                    $bg='"style = "background-color:#edfd17;"';
                }elseif ($row['score'] >= $row['s_5_min'] && $row['score'] <= $row['s_5_max']){
                    $bg='"style = "background-color:#f0ca0f;';
                }elseif ($row['score'] >= $row['s_3_min'] && $row['score'] <= $row['s_3_max']){
                    $bg='"style = "background-color:#e70808;"';
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
                   
                    <td class="pointer text-center">
                        <i class="icon-database-edit2 text-primary-400 edit_kpi" data-parent="<?=$parent;?>"  data-minggu="<?=$minggu;?>" data-id="<?=$row['id'];?>"></i> | 
                        <i class="icon-database-remove text-danger-400 delete_kpi" data-parent="<?=$parent;?>"  data-minggu="<?=$minggu;?>" data-id="<?=$row['id'];?>"></i> 
                    </td>
                </tr>
                <?php if (count($list_kpi)>0): ?>
                    <?php
                     $nod=-1;
                     $alphabet = range('A', 'Z');    
                    ?>
                    <?php foreach($list_kpi as $r):?>

                        <?php if ($r['kpi_id'] == $row['id']): ?>
                        <?php
                        $huruf=$alphabet[++$nod];
                        $bgx='';
                        if ($r['score'] >= $r['s_1_min'] && $r['score'] <= $r['s_1_max']){
                            $bgx='"style = "background-color:#2c5b29;"';
                        }elseif ($r['score'] >= $r['s_4_min'] && $r['score'] <= $r['s_4_max']){
                            $bgx='"style = "background-color:#50ca4e;"';
                        }elseif ($r['score'] >= $r['s_2_min'] && $r['score'] <= $r['s_2_max']){
                            $bgx='"style = "background-color:#edfd17;"';
                        }elseif ($r['score'] >= $r['s_5_min'] && $r['score'] <= $r['s_5_max']){
                            $bgx='"style = "background-color:#f0ca0f;"';
                        }elseif ($r['score'] >= $r['s_3_min'] && $r['score'] <= $r['s_3_max']){
                            $bgx='"style = "background-color:#e70808;"';
                        }     
                        ?>
                        <tr>
                            <td></td>
                            <td><?=$huruf.'. '.$r['title'];?></td>
                            <td class="text-center"><?=$r['score'];?></td>
                            <td class="text-center <?=$bgx;?>"><?=$r['indikator'];?></td>
                            <td class="pointer text-center">
                                <i class="icon-database-edit2 text-primary-400 edit-kri" data-minggu="<?=$minggu;?>" data-rcsa="<?=$parent?>" data-parent="<?=$row['id'];?>" data-id="<?=$r['id'];?>"></i> | 
                                <i class="icon-database-remove text-danger-400 delete-kri" data-minggu="<?=$minggu;?>" data-rcsa="<?=$parent;?>" data-parent="<?=$row['id'];?>" data-id="<?=$r['id'];?>"></i> 
                            </td>
                        </tr>
                    <?php endif;?>
                <?php endforeach;?>
                <?php endif;?>
            <?php endforeach;?>
        </tbody>
    </table>
    <br/>&nbsp;
    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
</div>