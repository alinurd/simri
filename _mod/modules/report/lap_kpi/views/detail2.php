<br/>&nbsp;
<hr/>
<br/>Threshold:
<table class="table table-bordered table-striped table-hover" border="1">
    <thead>
        <tr  class="text-center">
            <th width="5%" rowspan="2">No.</th>
            <th rowspan="2">Parameter</th>
            <th rowspan="2" width="8%">Satuan</th>
            <th colspan="3" width="15%">Keterangan</th>
        </tr>
        <tr class="text-center">
            <th class="bg-dangerx-400" style="background-color:#e70808">Merah</th>
            <th class="bg-orangex-400" style="background-color:#f0ca0f">Orange</th>
            <th class="bg-successx-400" style="background-color:#edfd17">Kuning</th>
            <th class="bg-successx-400" style="background-color:#50ca4e">Hijau</th>
            <th class="bg-successx-400" style="background-color:#2c5b29">Hijau Tua</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no=0;
        $cek=[];

        foreach($lap2 as $key=>$row):?>
        <?php if(!in_array(trim($row['title']), $cek)):?>

        <tr>
            <td><?=++$no;?></td>
            <td><?=$row['title'];?></td>
            <td><?=$row['satuan'];?></td>
            <td><?=$row['p_3'].' ['.$row['s_3_min'].' &#8805; '.$row['s_3_max'].']';?></td>
            <td><?=$row['p_5'].' ['.$row['s_5_min'].' &#8805; '.$row['s_5_max'].']';?></td>
            <td><?=$row['p_2'].' ['.$row['s_2_min'].' &#8805; '.$row['s_2_max'].']';?></td>
            <td><?=$row['p_4'].' ['.$row['s_4_min'].' &#8805; '.$row['s_4_max'].']';?></td>
            <td><?=$row['p_1'].' ['.$row['s_1_min'].' &#8805; '.$row['s_1_max'].']';?></td>
        </tr>
            <?php
            $nod=-1;
            $alphabet = range('A', 'Z');
            foreach($row['detail'] as $row_det):
                $huruf=$alphabet[++$nod]; // returns D ?>
                <tr>
                <td>&nbsp;</td>
                <td><?=$huruf.'. '.$row_det['title'];?></td>
                <td><?=$row_det['satuan'];?></td>
                <td><?=$row_det['p_3'].' ['.$row_det['s_3_min'].' &#8805; '.$row_det['s_3_max'].']';?></td>
                <td><?=$row_det['p_5'].' ['.$row_det['s_5_min'].' &#8805; '.$row_det['s_5_max'].']';?></td>
                <td><?=$row_det['p_2'].' ['.$row_det['s_2_min'].' &#8805; '.$row_det['s_2_max'].']';?></td>
                <td><?=$row_det['p_4'].' ['.$row_det['s_4_min'].' &#8805; '.$row_det['s_4_max'].']';?></td>
                <td><?=$row_det['p_1'].' ['.$row_det['s_1_min'].' &#8805; '.$row_det['s_1_max'].']';?></td>
                </tr>
            <?php endforeach;
             $cek[] =  trim($row['title']);
            endif;
            ?>
        <?php endforeach;?>

    </tbody>
</table>