<?php
if (!$mode):?>
<a class="btn btn-primary" href="<?=base_url(_MODULE_NAME_.'/cetak');?>" target="_blank"><i class="icon-file-excel"> Ms-Excel </i></a>
<?php endif;?>
<center>
PELAPORAN KEY RISK INDICATOR<br/>
DEPARTEMEN <strong><?=strtoupper($owner_name);?></strong>
</center>
<br/>&nbsp;
Sasaran Departemen :
<table class="table table-bordered table-striped table-hover" border="1">
    <thead>
        <tr>
            <th width="5%" rowspan="2">No.</th>
            <th rowspan="2">Parameter</th>
            <th rowspan="2" width="8%">Satuan</th>
            <?php
            for($x=$bulan[0];$x<=$bulan[1];++$x):
                $monthNum = $x;
                $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
            ?>
            <th colspan="3" width="15%"><?=$monthName;?></th>
            <?php endfor;?>
        </tr>
        <tr>
            <?php
            for($x=$bulan[0];$x<=$bulan[1];++$x):?>
            <th>Std</th>
            <th>Act</th>
            <th>Sta</th>
            <?php endfor;?>
        </tr>
    </thead>
    <tbody>
        <?php
        $no=0;
        $cek=[];
        // dumps($data);
        foreach($data as $key=>$row):?>
        
        <?php if(!in_array($row['title'], $cek)):?>
        <tr>
            <td><?=++$no;?></td>
            <td><?=$row['title'];?></td>
            <td><?=$row['satuan'];?></td>
            <?php
            for($x=$bulan[0];$x<=$bulan[1];++$x):
                $warna='bg-default';
                // if ($row['indikator']==1){
                //     $warna='bg-success-400';
                // }elseif ($row['indikator']==2){
                //     $warna='bg-orange-400';
                // }elseif ($row['indikator']==3){
                //     $warna='bg-danger-400';
                // }
              

                $int=intval($row['indikator']);
                if ($int<1 || $int>3){
                    $int=1;
                }
                
                if (array_key_exists($x, $row['bulan'])):?>

<?php

                        if ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_1_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_1_max']){
                            $warna='bg-success-400';
                            $int = 1;
                        }elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_2_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_2_max']){
                            $warna='bg-orange-400';
                            $int = 2;
                        }elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_3_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_3_max']){
                            $warna='bg-danger-400';
                            $int = 3;
                        }
                    ?>
                    <td><?=$row['bulan'][$x]['p_1'].' '.$row['bulan'][$x]['s_1_min'].'-'.$row['bulan'][$x]['s_1_max'];?></td>
                    <td><?=$row['bulan'][$x]['score'];?></td>
                    <td class="<?=$warna;?>"></td>
                <?php else:?>
                    <td></td>
                    <td></td>
                    <td></td>
                <?php endif;
            endfor; ?>
        </tr>
        <?php
            // dumps($row);
            $nod=-1;
            $alphabet = range('A', 'Z');
            foreach($row['detail'] as $row_det):
                // dumps($row_det);
            $huruf=$alphabet[++$nod]; // returns D
            ?>
            <tr>
                <td></td>
                <td><?=$huruf.'. '.$row_det['title'];?></td>
                <td><?=$row_det['satuan'];?></td>
                <?php
                for($x=$bulan[0];$x<=$bulan[1];++$x):
                    $warna='bg-default';
                    // if ($row_det['indikator']==1){
                    //     $warna='bg-success-400';
                    // }elseif ($row_det['indikator']==2){
                    //     $warna='bg-orange-400';
                    // }elseif ($row_det['indikator']==3){
                    //     $warna='bg-danger-400';
                    // }
                    

                    $int=intval($row_det['indikator']);
                    if ($int<1 || $int>3){
                        $int=1;
                    }
                    // dumps($row_det);
                    if (array_key_exists($x, $row_det['bulan'])):?>
                    
                        <?php 
                            if ($row_det['bulan'][$x]['score'] >= $row_det['bulan'][$x]['s_1_min'] && $row_det['bulan'][$x]['score'] <= $row_det['bulan'][$x]['s_1_max']){
                                $warna='bg-success-400';
                                $int = 1;
                            }elseif ($row_det['bulan'][$x]['score'] >= $row_det['bulan'][$x]['s_2_min'] && $row_det['bulan'][$x]['score'] <= $row_det['bulan'][$x]['s_2_max']){
                                $warna='bg-orange-400';
                                $int = 2;
                            }elseif ($row_det['bulan'][$x]['score'] >= $row_det['bulan'][$x]['s_3_min'] && $row_det['bulan'][$x]['score'] <= $row_det['bulan'][$x]['s_3_max']){
                                $warna='bg-danger-400';
                                $int = 3;
                            }
                        ?>
                        <td><?=$row_det['bulan'][$x]['p_1'].' '.$row_det['bulan'][$x]['s_1_min'].'-'.$row_det['bulan'][$x]['s_1_max'];?></td>
                        <td><?=$row_det['bulan'][$x]['score'];?></td>
                        <td class="<?=$warna;?>"></td>
                    <?php else:?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php endif;
                endfor; ?>
            </tr>
            <?php endforeach;
            

            $cek[] =  $row['title'];
            endif;
        endforeach;?>
    </tbody>
</table>