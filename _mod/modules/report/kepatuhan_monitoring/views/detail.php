<?php
    $hide = '';
    $col = 8;
    $col_title = 12;
    if ($is_triwulan==1) {
        $hide = 'hide';
        $col = $col - 3;
        $col_title = $col_title - 9;
    }
   
?>

<strong>Kepatuhan Pelaporan Mitigasi Manajement Risiko Korporasi</strong><br>
<strong>Deadline:</strong><br>
<strong>Pada masing-masing mitigasi</strong><br>
<table class="table table-bordered table-striped" border="1" width="85%" cellpadding="3" cellspacing="4">
    <thead>
        <tr>
            <th rowspan="3" width="5%">No.</th>
            <th rowspan="3">Department</th>
            <th colspan="<?=intval($col_title*count($minggu))?>" width="40%" class="text-center">Laporan <?= $title; ?></th>
        </tr>
        
        <tr>
            <?php foreach($minggu as $m):?>
            <th class="text-center"><?= $m ?></th>
            <?php endforeach;?>
        </tr>
        
        <tr>
            <?php foreach($minggu as $m):?>
            <th width="8%" class="text-center">Ketepatan Waktu</th> 
            <?php endforeach;?>
        </tr>
    </thead>
    <tbody>
    <?php
        $total1 = 0;
        $total2 = 0;
        $kepatuhan = 0;
    ?>
    <?php if(count($proyek_all)>0):?>

        <?php $no=0; ?>
        <?php foreach($proyek_all as $row):?>
                <?php 
                $created = [];
                $total1 = [];
                if ($row['bkx'] > 0) {
                    foreach($minggu as $x => $m){
                        if (isset($row[0][$x])) {
                            foreach ($row[0][$x]['mitigasi'] as $km => $vm) {
                                if (isset($row[0][$x]['mitigasi'][$km])) {

                                    $created[$x] = $row[0][$x]['mitigasi'][$km];
                                }
                            }

                        }else{
                            $created[0]='0%';
                        }
                    }
                    
                } elseif ($row['bkx'] == 0) {
                    $created[0]='0%';
                } 
            ?>
            <tr>
                <td class="text-center"><?=++$no;?></td>
                <td class="text-left"><?=$row[0]['owner_name'];?> (<?=$row[0]['kode_dept'];?>)</td>

                <?php foreach($minggu as $x => $m):?>
                <td class="text-center p-0"><?= (isset($created[$x])>0)?number_format(floatval($created[$x]),2):"0"?>%</td>
   
                
                <?php endforeach;?>

            </tr>
        <?php endforeach;?>
    <?php else:?>
        <tr>
            <td colspan="13" class="text-center">Tidak Ada Data</td>
        </tr>
    <?php endif;?>
    </tbody>
    <tfoot class="d-none">
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" rowspan="2" class="text-right">Total</td>
            <td class="text-center" rowspan="2"><?php //number_format($total1,0)?></td>
            <td class="text-center" rowspan="2"><?php //number_format($total2,0)?></td>
            <td class="text-center"></td>
        </tr>
        <tr class="bg-warning"></tr>
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" class="text-right">Nilai (%)</td>
            <?php
                // $nilai = ($total1>0)?($total2/$total1)*100:0;
                // $kelengkapan = $nilai;
                // $kepatuhan_total = ($total1>0)?($kepatuhan/$total1):0;
                // $kepatuhan_total_all = $kepatuhan_total;
            ?>
            <td class="text-center"><?php //number_format($nilai,2)?>%</td>
            <td class="text-center"></td>
            <td class="text-center"><?php //number_format($kepatuhan_total,2)?>%</td>

        </tr>
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" class="text-right">Kelengkapan Dokumen</td>
            <td class="text-left" colspan="<?=$col?>"><?php //number_format($kelengkapan, 2)?>%</td>
        </tr>
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" class="text-right">Ketepatan Waktu</td>
            <td class="text-left" colspan="<?=$col?>"><?php //number_format($kepatuhan_total_all, 2)?>%</td>
        </tr>
    </tfoot>
</table> 
