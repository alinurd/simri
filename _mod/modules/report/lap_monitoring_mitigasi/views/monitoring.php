<?php
    $show='';
    if (isset($export)){
        if (!$export){
            $show=' d-none ';
        }
    }
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
         
            <a target="_blank" href="<?=base_url('/lap-mitigasi/cetak-register/'.$pos['period'].'/'.$pos['owner'].'/'.$pos['type_ass'])?>"><h6 class="card-title"><span class="btn bg-primary pointer pull-right <?=$show;?>" id="export_excel"> Export to Ms-Excel </span></h6></a>
            </div>
            <div class="card-body" >
                <table class="table table-borderless">
                    <tr><td width="20%">Nama Departemen</td><td><strong><?=$parent['owner_name'];?></strong></td></tr>
                    <tr><td><em>Sasaran Departmen</em></td><td><strong><?=$parent['sasaran_dept'];?></strong></td></tr>
                    <tr><td><em>Periode</em></td><td><strong><?=$parent['period_name'];?></strong></td></tr>
                </table>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered" border="1">
                        <thead class="bg-primary">
                            <tr>
                                <th rowspan="4">No.</th>
                                <th rowspan="4">Kode Risiko</th>
                                <th rowspan="4">Nama Risiko</th>
                                <th colspan="29">Monitoring Mitigasi Risiko</th>
                                <th rowspan="4">Status</th>
                            </tr>
                            <tr>
                                <!-- <th rowspan="3">Risk Indikator Inheren</th> -->
                                <th rowspan="3">Mitigasi</th>
                                <th rowspan="3">Biaya</th>
                                <th rowspan="3">PIC</th>
                                <th rowspan="3">Koordinator</th>
                                <th colspan="24">Progres Mitigasi (%)</th>
                            </tr>
                            <tr>
                                <?php foreach(range(1, 12) as $v):?>
                                    <th colspan="2"><?= date("M", mktime(0, 0, 0, $v, 10)); ?></th>

                                <?php endforeach?>

                            </tr>
                            <tr>
                                <?php foreach(range(1, 12) as $v):?>
                                    <th>Target</th>
                                    <th>Aktual</th>

                                <?php endforeach?>

                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no=0;
                        $identi_id=0;
                        $tmp=0;
                        $tmp2=0;
                        $core = [];
                        $rcsa = [];

                        foreach($rows as $row):

                            if (!in_array($row['penyebab_id'], $core)) {

                                $core[] = $row['penyebab_id'];
                            $jml=1;
                            // if (array_key_exists($row['id'], $aktif)){
                            //     $jml=count($aktif[$row['id']]);
                            // }
                            $jml_miti=1;
                            // if (array_key_exists($row['mitigasi_id'], $miti)){
                            //     $jml_miti=count($miti[$row['mitigasi_id']]);
                            // }
                            $jml_ident=1;
                            // if (array_key_exists($row['rcsa_detail_id'], $identi)){
                            //     $jml_ident=count($identi[$row['rcsa_detail_id']]);
                            // }
                        ?>

                           <tr>
                                <?php
                                //if ($row['rcsa_detail_id']!==$tmp):
                                  //  $tmp=$row['rcsa_detail_id'];
                                ?>
                                <td rowspan="<?=($jml>0)?$jml:$jml_ident;?>"><?=++$no;?></td>
                                <td rowspan="<?=($jml>0)?$jml:$jml_ident;?>"><?=$row['kode_risiko'];?></td>
                                <td rowspan="<?=($jml>0)?$jml:$jml_ident;?>"><?=$row['penyebab_risiko'];?></td>
                                <!-- <td rowspan="<?=($jml>0)?$jml:$jml_ident;?>" style="background-color:<?=$row['color'];?>;color:<?=$row['color_text'];?>;"><?=$row['level_color'];?></td> -->
                                
                                <?php //endif;?>
                                <?php
                                //if ($row['mitigasi_id']!==$tmp2):
                                   // $tmp2=$row['mitigasi_id'];
                                    ?>
                                <td rowspan="<?=($jml>0)?$jml:$jml_miti;?>"><?=$row['mitigasi'];?></td>
                                <td rowspan="<?=($jml>0)?$jml:$jml_miti;?>"><?=number_format($row['biaya']);?></td>
                                <td rowspan="<?=($jml>0)?$jml:$jml_miti;?>"><?=$row['penanggung_jawab'];?></td>
                                <td rowspan="<?=($jml>0)?$jml:$jml_miti;?>"><?=$row['koordinator'];?></td>
                                <?php if (array_key_exists($row['penyebab_id'], $mitigasi)):?>
                                    <?php $bl =[];$blx =[];if($mitigasi[$row['penyebab_id']]):?>
                                        <?php foreach($mitigasi[$row['penyebab_id']] as $key=>$mit):?>
                                            <?php
                                                if(!in_array($minggu[$mit['minggu_id']], $bl)){
                                                    $bl[$row['penyebab_id']][] = $minggu[$mit['minggu_id']];    
                                                    $blx[$row['penyebab_id']][$minggu[$mit['minggu_id']]][] = $mit;    
                                                }
                                            ?>
                                         <?php endforeach;;?>
                                    <?php endif;?>
                                <?php endif;?>
                                
                                <?php foreach(range(1, 12) as $v):
                                    $kt = date("F", mktime(0, 0, 0, $v, 10));    
                                ?>
                                    <?php if(isset($blx[$row['penyebab_id']][$kt])):?>   
                                    <?php 
                                        $target = array_column($blx[$row['penyebab_id']][$kt], 'target');
                                        $targetpersen = array_sum($target)/count($target);

                                        $aktual = array_column($blx[$row['penyebab_id']][$kt], 'aktual');
                                        $aktualpersen = array_sum($aktual)/count($aktual);
                                        
                                    ?>           
                                    <th><?= $targetpersen; ?></th>
                                    <th><?= $aktualpersen; ?></th>
                                    <?php else:?>
                                    <th></th>
                                    <th></th>
                                    <?php endif;?>              
                                  
                                <?php endforeach?>                           

                                
                                
                           </tr>
                           <?php }?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>