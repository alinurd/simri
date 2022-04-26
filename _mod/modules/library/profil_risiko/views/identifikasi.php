<ul class="nav nav-tabs nav-tabs-top">
    <li class="nav-item">
        <a href="#content-det-00" class="nav-link bg-primary active show" data-toggle="tab"><?=(isset($minggu[$post['term_mulai']]))?$minggu[$post['term_mulai']]:'';?> </a>
    </li>
    <li class="nav-item">
        <a href="#content-det-01" class="nav-link bg-info" data-toggle="tab"><?=(isset($minggu[$post['term_akhir']]))?$minggu[$post['term_akhir']]:'';?></a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active show" id="content-det-00">
    <table class="table table-hover table-bordered">
        <thead>
            <tr class="bg-primary-300">
                <th width="5%">No.</th>
                <th>Departemen</th>
                <th>Risiko Dept.</th>
                <th>Klasifikasi</th>
                <th>Risiko Inheren</th>
                <th>Risiko Residual</th>
                <th>Risiko Target</th>
                <th width="6%">Mitigasi</th>
                <th width="6%">Aktifitas Mitigasi</th>
                <th width="6%">Proges Mitigasi</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $no=0;
            foreach($detail as $row):?>
            <?php if($post['term_mulai']>0):?>
            <?php if($post['term_mulai']==$row['minggu_id']):?>

            <tr class="pointer detail-rcsa" data-id="<?=$row['id'];?>" data-dampak="<?=$row['impact_residual_id'];?>">
                <td><?=++$no;?></td>
                <td><?=$row['owner_name'];?></td>
                <td><?=$row['risiko_dept'];?></td>
                <td><?=$row['klasifikasi_risiko'].' | '.$row['tipe_risiko'];?></td>
                <td class="text-center" style="background-color:<?=$row['color'];?>;color:<?=$row['color_text'];?>;"><?=$row['level_color'];?><br/><small><?=$row['like_code'].'x'.$row['impact_code'].' : '.$row['risiko_inherent_text'];?></small></td>
                <td class="text-center" style="background-color:<?=$row['color_residual'];?>;color:<?=$row['color_text_residual'];?>;"><?=$row['level_color_residual'];?><br/><small><?=$row['like_code_residual'].'x'.$row['impact_code_residual'].' : '.$row['risiko_residual_text'];?></small></td>
                <td class="text-center" style="background-color:<?=$row['color_target'];?>;color:<?=$row['color_text_target'];?>;"><?=$row['level_color_target'];?><br/><small><?=$row['like_code_target'].'x'.$row['impact_code_target'].' : '.$row['risiko_target_text'];?></small></td>
                <td class="text-center"><span class="badge bg-orange-400 badge-pill align-self-center ml-auto"><?=$row['jml'];?></span></td>
                <td class="text-center"><span class="badge bg-teal-400 badge-pill align-self-center ml-auto"><?=$row['jml2'];?></span></td>
                <td class="text-center"><span class="badge bg-warning-400 badge-pill align-self-center ml-auto"><?=$row['jml3'];?></span></td>
            </tr>

            <?php endif; ?>
            <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div class="tab-pane fade" id="content-det-01">
    <table class="table table-hover table-bordered">
        <thead>
            <tr class="bg-primary-300">
                <th width="5%">No.</th>
                <th>Departemen</th>
                <th>Risiko Dept.</th>
                <th>Klasifikasi</th>
                <th>Risiko Inheren</th>
                <th>Risiko Residual</th>
                <th>Risiko Target</th>
                <th width="6%">Mitigasi</th>
                <th width="6%">Aktifitas Mitigasi</th>
                <th width="6%">Proges Mitigasi</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $no=0;
            foreach($detail as $row):?>
            <?php if($post['term_akhir']>0):?>
            <?php if($post['term_akhir']==$row['minggu_id']):?>

            <tr class="pointer detail-rcsa" data-id="<?=$row['id'];?>" data-dampak="<?=$row['impact_residual_id'];?>">
                <td><?=++$no;?></td>
                <td><?=$row['owner_name'];?></td>
                <td><?=$row['risiko_dept'];?></td>
                <td><?=$row['klasifikasi_risiko'].' | '.$row['tipe_risiko'];?></td>
                <td class="text-center" style="background-color:<?=$row['color'];?>;color:<?=$row['color_text'];?>;"><?=$row['level_color'];?><br/><small><?=$row['like_code'].'x'.$row['impact_code'].' : '.$row['risiko_inherent_text'];?></small></td>
                <td class="text-center" style="background-color:<?=$row['color_residual'];?>;color:<?=$row['color_text_residual'];?>;"><?=$row['level_color_residual'];?><br/><small><?=$row['like_code_residual'].'x'.$row['impact_code_residual'].' : '.$row['risiko_residual_text'];?></small></td>
                <td class="text-center" style="background-color:<?=$row['color_target'];?>;color:<?=$row['color_text_target'];?>;"><?=$row['level_color_target'];?><br/><small><?=$row['like_code_target'].'x'.$row['impact_code_target'].' : '.$row['risiko_target_text'];?></small></td>
                <td class="text-center"><span class="badge bg-orange-400 badge-pill align-self-center ml-auto"><?=$row['jml'];?></span></td>
                <td class="text-center"><span class="badge bg-teal-400 badge-pill align-self-center ml-auto"><?=$row['jml2'];?></span></td>
                <td class="text-center"><span class="badge bg-warning-400 badge-pill align-self-center ml-auto"><?=$row['jml3'];?></span></td>
            </tr>

            <?php endif; ?>
            <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
