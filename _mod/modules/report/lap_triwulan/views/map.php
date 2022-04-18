<div class="row">
    <div class="col-lg-6">
        <strong>Risiko Risidual</strong><br/>
        <?=$map_residual;?>
    </div>
    <div class="col-lg-6">
        <strong>Risiko Target</strong><br/>
        <?=$map_target;?>
    </div>
</div>
<?php
$tresi=$t_residual[1]['jml']+$t_residual[2]['jml']+$t_residual[3]['jml'];
$ttar=$t_target[1]['jml']+$t_target[2]['jml']+$t_target[3]['jml'];

?>
<div class='row'>
    <div class="col-lg-12">
        <strong>Ringkasan Kategori Risiko</strong><br/>
        <table class="table table-bordered" border="1">
            <thead>
                <tr class="text-center">
                    <th rowspan="2">Risiko</th>
                    <?php
                    foreach($level_risiko as $row):?>
                    <th width="13%" colspan="2" style="background-color:<?=$row['color'];?>;color:<?=$row['color_text'];?>"><?=$row['level_color'];?></th>
                    <?php endforeach;?>
                    <th width="8%" rowspan="2">Total</th>
                </tr>
                <tr class="text-center">
                    <?php
                    foreach($level_risiko as $row):?>
                    <th style="background-color:<?=$row['color'];?>;color:<?=$row['color_text'];?>">Jumlah</th>
                    <th style="background-color:<?=$row['color'];?>;color:<?=$row['color_text'];?>">%</th>
                    <?php endforeach;?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Risiko Risidual</td>
                    <td class="text-center"><?=$t_residual[3]['jml'];?></td>
                    <td width="5"><?=($tresi>0)?number_format(($t_residual[3]['jml']/$tresi)*100,2):0;?></td>
                    <td class="text-center"><?=$t_residual[2]['jml'];?></td>
                    <td width="5"><?=($tresi>0)?number_format(($t_residual[2]['jml']/$tresi)*100,2):0;?></td>
                    <td class="text-center"><?=$t_residual[1]['jml'];?></td>
                    <td width="5"><?=($tresi>0)?number_format(($t_residual[1]['jml']/$tresi)*100,2):0;?></td>
                    <td class="text-center"><?=$t_residual[1]['jml']+$t_residual[2]['jml']+$t_residual[3]['jml'];?></td>
                </tr>
                <tr>
                    <td>Target Risiko</td>
                    <td class="text-center"><?=$t_target[3]['jml'];?></td>
                    <td width="5"><?=($ttar>0)?number_format(($t_target[3]['jml']/$ttar)*100,2):0;?></td>
                    <td class="text-center"><?=$t_target[2]['jml'];?></td>
                    <td width="5"><?=($ttar>0)?number_format(($t_target[2]['jml']/$ttar)*100,2):0;?></td>
                    <td class="text-center"><?=$t_target[1]['jml'];?></td>
                    <td width="5"><?=($ttar>0)?number_format(($t_target[1]['jml']/$ttar)*100,2):0;?></td>
                    <td class="text-center"><?=$t_target[1]['jml']+$t_target[2]['jml']+$t_target[3]['jml'];?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<br/>
<div class='row'>
    <div class="col-lg-12">
        <strong>Progres Pelaksanaan Mitigasi Top 10 Risk Departemen</strong><br/>
        <table class="table table-bordered" border="1">
            <thead>
                <tr class="text-center">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Risiko</th>
                    <th rowspan="2">Mitigasi Risiko</th>
                    <th width="12%" rowspan="2">Due Date Mitigasi</th>
                    <th colspan="2" width="14%">Progress</th>
                    <th width="18%" rowspan="2">PIC</th>
                </tr>
                <tr class="text-center">
                    <th>Target</th>
                    <th>Aktual</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no=0;
                foreach($top as $row):?>
                    <tr>
                        <td><?=++$no;?></td>
                        <td><?=$row['penyebab_risiko'];?></td>
                        <?php
                        if ($row['detail']):?>
                            <td><?=$row['detail'][0]['mitigasi'];?></td>
                            <td><?=$row['detail'][0]['batas_waktu'];?></td>
                            <td><?=$row['detail'][0]['target'];?></td>
                            <td><?=$row['detail'][0]['aktual'];?></td>
                            <td><?=$row['detail'][0]['penanggung_jawab_detail'];?></td>
                        <?php
                        endif;?>
                    </tr>
                    <?php
                    if (count($row['detail'])>1):
                        foreach($row['detail'] as $key=>$row_top):
                            if ($key>0):?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><?=$row_top['mitigasi'];?></td>
                                <td><?=$row_top['batas_waktu'];?></td>
                                <td><?=$row_top['target'];?></td>
                                <td><?=$row_top['aktual'];?></td>
                                <td><?=$row_top['penanggung_jawab_detail'];?></td>
                            </tr>
                        <?php 
                            endif;
                        endforeach;
                    endif;
                endforeach;?>
            </tbody>
        </table>
        <br/>
        Note :<br/>
        1. Dilampirkan Laporan KPI & KRI pada per akhir bulan periode laporan<br/>
        2. Risiko-risiko yang harus dimitigasi dan dimonitoring pelaksanaannya adalah Top 10 Risk Departemen<br/>
    </div>
</div>