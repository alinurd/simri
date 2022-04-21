<div class='table-responsive' id="div_like_indi">
    <?=_l('fld_list_like_indi').$sub_title;?>
    <span class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right" data-parent="<?=$parent;?>" data-id="0" id="add_like_indi" ><b><i class="icon-file-plus "></i></b> <?=_l('fld_add_like_indi');?></span><br/>&nbsp;<br/>&nbsp;
    <table class="table table-hover table-bordered tabel-framed" id="tbl_list_like_indi">
        <thead>
            <tr class="bg-primary-300">
                <th width="5%"></th>
                <th><?=_l('fld_kri');?></th>
                <!--  fld_pembobotan ada di setingan bhasa -->
                <th width="10%" class="text-center"><?=_l('fld_pembobotan');?></th>
                <th width="10%" class="text-center"><?=_l('fld_pencapaian');?></th>
                <th width="10%" class="text-center"><?=_l('fld_score');?></th>
                <th width="10%" class="text-center"><?=_l('fld_nilai');?></th>
                <th width="8%">Aksi</th>
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
                    <td class="text-center"><?=$row['pembobotan'];?>%</td>
                    <td class="text-center"><?=$row['pencapaian'];?></td>
                    <td class="text-center"><?=$row['score'];?></td>
                    <td class="text-center"><?=$nilai;?></td>
                    <td class="pointer">
                        <!-- masuk ke javascript utk edit -->
                        <i class="icon-database-edit2 text-primary-400 update-like-indi" data-parent="<?=$parent;?>" data-id="<?=$row['id'];?>"></i> | 
                        <i class="icon-database-remove text-danger-400 delete-like-indi" data-parent="<?=$parent;?>" data-id="<?=$row['id'];?>" data-bk="1"></i> </td>
                </tr>
            <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr class="bg-grey-300">
                <th colspan="2"></th>
                <th width="10%" class="text-center"><?=$gTtl[0];?></th>
                <th width="10%" class="text-center"></th>
                <th width="10%" class="text-center"><?=$gTtl[1];?></th>
                <th width="10%" class="text-center"><?=$gTtl[2];?></th>
                <th width="8%">&nbsp;i</th>
            </tr>
        </tfoot>
    </table>

    <br/>&nbsp;<br/>&nbsp;
    <div class="form-group row">
        <label class="col-lg-1 col-form-label">T O T A L</label>
        <div class="col-lg-5">
            <div class ="form-group form-group-feedback form-group-feedback-right input-group">
                : &nbsp;&nbsp;&nbsp;<?=$param['ttl'];?>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-1 col-form-label">LIKELIHOOD</label>
        <div class="col-lg-5">
            <div class ="form-group form-group-feedback form-group-feedback-right input-group">
            : &nbsp;&nbsp;&nbsp;<span style="background-color:<?=$param['color'];?>;color:<?=$param['tcolor'];?>;padding:.2rem .6rem;"> <?=$param['likes'];?> </span>
            </div>
        </div>
    </div>

    <br/>&nbsp;
    <table class="table table-borderless">
        <tr>
            <?php
            foreach($param['param'] as $key=>$p):
                $color='#ffffff;';
                $tcolor='#000000';
                if (array_key_exists($key, $param['mLike'])){
                    $color=$param['mLike'][$key]['warna'];
                    $tcolor='#ffffff;';
                }
            ?>
            <td width="20%" class="text-center" style="background-color:<?=$color;?>;color:<?=$tcolor;?>;padding:.15rem 1.25rem;"> <small> L<?=$key?>  [ <?=$p['min'].' - '.$p['mak'];?> ]</small></td>
            <?php endforeach;?>
        </tr>
    </table>
    <br/>&nbsp;
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
        $('#like_text_kuantitatif').val(like);
        $('input[name="like_id_2"]').val(like_no);

        $("#risiko_inherent_text").val(parseFloat(like_code)*parseFloat(impact_code));
		$("#level_inherent_text").val(level_color);
		$("input[name=\"risiko_inherent\"]").val(id);
		$("input[name=\"level_inherent\"]").val(level_risk_no);
		$("#level_inherent_text").css("background-color",color);
		$("#level_inherent_text").css("color",color_text);
    })
</script>