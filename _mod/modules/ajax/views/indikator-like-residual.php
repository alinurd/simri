<div class='table-responsive' id="div_like_indi">
  
    <table class="table table-hover table-bordered tabel-framed" id="tbl_list_like_indi_inherent">
        <thead>
            <tr class="bg-success-300 text-center">
                <th rowspan="2" width="5%">No</th>
                <th rowspan="2"><?=_l('fld_kri');?></th>
                <th rowspan="2" width="10%" class="text-center">Satuan</th>
                <th rowspan="2" width="10%" class="text-center"><?=_l('fld_pembobotan');?></th>
                <th rowspan="2" width="10%" class="text-center"><?=_l('fld_score');?></th>
                <th colspan="3" width="15%">Keterangan</th>
               
            </tr>
            <tr class="text-center">
                <th class="bg-danger-400">Merah</th>
                <th class="bg-orange-400">Kuning</th>
                <th class="bg-success-400">Hijau</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            $ttl=0;
            $gTtl=[0,0,0];
            foreach($list_like_indi as $row):
                $nilai = ($row['pencapaian']/100)*($row['pembobotan']*count($list_like_indi));
                $ttl+=floatval($nilai);
                $gTtl[0]+=floatval($row['pembobotan']);
                $gTtl[1]+=floatval($row['score']);
                $gTtl[2]+=$nilai;
                ?>
                <tr>
                    <td><?=++$no;?></td>
                    <td><?=$row['kri'];?></td>
                    <td class="text-center"><?=$row['satuan'];?></td>
                    <td class="text-center"><?=$row['pembobotan'];?></td>
                    <!-- <td class="text-center"><?php //$row['pencapaian'];?></td> -->
                    <td class="text-center"><?=$row['score'];?></td>
                    <td class="text-center"><?=$row['p_5'];?> <?=$row['s_5_min'];?> - <?=$row['s_5_max'];?></td>
                    <td class="text-center"><?=$row['p_4'];?> <?=$row['s_4_min'];?> - <?=$row['s_4_max'];?></td>
                    <td class="text-center"><?=$row['p_3'];?> <?=$row['s_3_min'];?> - <?=$row['s_3_max'];?></td>
                    <td class="text-center"><?=$row['p_2'];?> <?=$row['s_2_min'];?> - <?=$row['s_2_max'];?></td>
                    <td class="text-center"><?=$row['p_1'];?> <?=$row['s_1_min'];?> - <?=$row['s_1_max'];?></td>
                    <!-- <td class="text-center"><?php //$nilai;?></td> -->
                   
                </tr>
            <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr class="bg-grey-300">
                <th colspan="2"></th>
                <th width="10%" class="text-center"></th>
                <th width="10%" class="text-center"><?=$gTtl[0];?></th>
                <th width="10%" class="text-center"><?=$gTtl[1];?></th>
                <th width="10%" class="text-center"></th>
                <th width="10%" class="text-center"></th>
                <th width="10%" class="text-center"></th>
               
            </tr>
        </tfoot>
    </table>

</div>
<script>
    var like_no='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$param['like_no']));?>';
    var like='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$param['likes']));?>';

    var like_code='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$param['warna']['like_code']));?>';
    var impact_code='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$param['warna']['impact_code']));?>';
    var level_color='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$param['warna']['level_color']));?>';
    var id='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$param['warna']['id']));?>';
    var level_risk_no='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$param['warna']['level_risk_no']));?>';
    var color='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$param['warna']['color']));?>';
    var color_text='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$param['warna']['color_text']));?>';

    $(function(){
        $('[data-popup="tooltip"]').tooltip();
        $('#like_text_kuantitatif_residual').val(like);
        $('input[name="like_residual_id"]').val(like_no);

        $("#risiko_residual_text").val(parseFloat(like_code)*parseFloat(impact_code));
		$("#level_residual_text").val(level_color);
		$("input[name=\"risiko_residual\"]").val(id);
		$("input[name=\"level_residual\"]").val(level_risk_no);
		$("#level_residual_text").css("background-color",color);
        $("#level_residual_text").css("color",color_text);
    })
</script>