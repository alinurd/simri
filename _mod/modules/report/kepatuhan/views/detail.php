<?php
    $hide = '';
    $col = 8;
    $col_title = 12;
    if ($is_triwulan==1) {
        $hide = 'hide';
        $col = $col - 3;
        $col_title = $col_title - 9;
    }
    $tgl = (count($proyek_all)>0)?$proyek_all[0]['tgl']:'';
    $deadline = (count($proyek_all)>0)?$proyek_all[0]['deadline']:'';
    // $tgl_before = (count($proyek_all)>0)?$proyek_all[0]['tgl_before']:'';
    // $tgl_loss = (count($proyek_all)>0)?$proyek_all[0]['tgl_loss']:'';
   function kepatuhan($nilai)
   {
        if ($nilai<0) {
            $hasil = "110";
        }elseif($nilai==0){
            $hasil = "100";
        }elseif($nilai==1){
            $hasil = "90";
        }elseif($nilai==2){
            $hasil = "90";
        }elseif($nilai>=3){
            $hasil = "75";
        }

        return $hasil;
   }
?>

<strong>Kepatuhan Pelaporan Assesment Manajement Risiko Korporasi</strong><br>
<strong>Deadline:</strong><br>
<strong>Tanggal 5 Setiap Bulan</strong><br>
<table class="table table-bordered table-striped" border="1" width="85%" cellpadding="3" cellspacing="4">
    <thead>
        <tr>
            <th rowspan="3" width="5%">No.</th>
            <th rowspan="3">Department</th>
            <th colspan="<?=intval($col_title*count($minggu))?>" width="40%" class="text-center">Laporan <?= $title; ?></th>
        </tr>
        
        <tr>
            <?php foreach($minggu as $m):?>
            <th colspan="3" class="text-center"><?= $m ?></th>
            <?php endforeach;?>
        </tr>
        
        <tr>
            <?php foreach($minggu as $m):?>
            <th width="4%" class="text-center">Kelengkapan</th>
            <th width="8%" class="text-center">Tanggal Approved</th>
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
                
                if ($row['bkx'] > 0) {
                    if ($row[0]['id']>0) {
                        $created='<i class="fa fa-check-circle text-primary"></i>';
                        $total1 += 1;
                    }else{
                        $created='<i class="fa fa-minus-circle text-danger"></i>';
                    }
                    
                } elseif ($row['bkx'] == 0) {
                    $created='<i class="fa fa-minus-circle text-danger"></i>';
                } 

                $bk1 = '';
                $tgl_approved = '';
                $diff = '';
                if ($row['bk1'] == '1') {
                    $bk1='<i class="fa fa-check-circle text-primary"></i>';
                    if (isset($row[0]['tgl_propose'])) {
                        if ($divisi==-1) {
                            $co = $this->data->get_data_lap_by_div($row[0]['period_no'], $row[0]['term_no'], 0, $row[0]['owner_no'], $term_t);
                            $total1x = 0;
                            $kepatuhanx = 0;

                            if(count($co['proyek_all'])>0){
                                foreach ($co['proyek_all'] as $p => $q) {
                                    if ($q['bkx'] > 0) {
                                        if ($q[0]['id']>0) {
                                            $total1x += 1;
                                        }
                                    }
                                    
                                    $bk3x = '';
                                    $tgl_approvedx = '';
                                    $diffx = '';
                                    if ($q['bk1'] == '1') {
                                        if (isset($q[0]['tgl_propose'])) {

                                            $tgl_approvedx = $q[0]['tgl_propose'];
                                            $date1x=date_create($tgl_approvedx);
                                            $date2x=date_create($deadline);
                                            $diff0x=date_diff($date2x,$date1x);
                                            $nilai_diffx=intval($diff0x->format("%R%a"));
                                            $diffx = kepatuhan($nilai_diffx)."%";
                                            $kepatuhanx += kepatuhan($nilai_diffx);
                                        }
                                        
                                    }
                                }
                                $patuh = ($total1x>0)?($kepatuhanx/$total1x):0;
                                $kepatuhan += $patuh;
                                $diff = number_format($patuh, 2).'%';
                                
                            }

                            $tgl_approved = $row[0]['tgl_propose'];

                        } else {
                            $tgl_approved = $row[0]['tgl_propose'];
                            $date1=date_create($tgl_approved);
                            $date2=date_create($deadline);
                            $diffo=date_diff($date2,$date1);
                            $nilai_diff=intval($diffo->format("%R%a"));
                            $diff = kepatuhan($nilai_diff)."%";
                            $kepatuhan += kepatuhan($nilai_diff); 
                        }
                    }
                    $total2 += 1;

                } elseif ($row['bk1'] == '0') {
                    $bk1='<i class="fa fa-minus-circle text-danger"></i>';
                } 

                $perUnit = '';
                $satu = (isset($nilai_diff))?intval(kepatuhan($nilai_diff)):0;
                $totalPerUnit = $satu;
                $perUnit = number_format($totalPerUnit,2).'%';
                
            ?>
            <tr>
                <td class="text-center"><?=++$no;?></td>
                <td class="text-left"><?=$row[0]['owner_name'];?> (<?=$row[0]['kode_dept'];?>)</td>

                <?php foreach($minggu as $x => $m):?>

                <td class="text-center"><?=$created?></td>
                <td class="text-center"><?=($tgl_approved!='')?date('d-m-Y', strtotime($tgl_approved)):'';?></td>
                <td class="text-center"><?=$diff?></td>
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
            <td class="text-center" rowspan="2"><?=number_format($total1,0)?></td>
            <td class="text-center" rowspan="2"><?=number_format($total2,0)?></td>
            <td class="text-center"></td>
        </tr>
        <tr class="bg-warning"></tr>
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" class="text-right">Nilai (%)</td>
            <?php
                $nilai = ($total1>0)?($total2/$total1)*100:0;
                $kelengkapan = $nilai;
                $kepatuhan_total = ($total1>0)?($kepatuhan/$total1):0;
                $kepatuhan_total_all = $kepatuhan_total;
            ?>
            <td class="text-center"><?=number_format($nilai,2)?>%</td>
            <td class="text-center"></td>
            <td class="text-center"><?=number_format($kepatuhan_total,2)?>%</td>

        </tr>
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" class="text-right">Kelengkapan Dokumen</td>
            <td class="text-left" colspan="<?=$col?>"><?=number_format($kelengkapan, 2)?>%</td>
        </tr>
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" class="text-right">Ketepatan Waktu</td>
            <td class="text-left" colspan="<?=$col?>"><?=number_format($kepatuhan_total_all, 2)?>%</td>
        </tr>
    </tfoot>
</table> 
