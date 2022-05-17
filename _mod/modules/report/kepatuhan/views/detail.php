<?php
    $hide = '';
    $col = 8;
    $col_title = 12;
    if ($is_triwulan==1) {
        $hide = 'hide';
        $col = $col - 3;
        $col_title = $col_title - 3;
    }
    $tgl = (count($proyek_all)>0)?$proyek_all[0]['tgl']:'';
    $deadline = (count($proyek_all)>0)?$proyek_all[0]['deadline']:'';
    $tgl_before = (count($proyek_all)>0)?$proyek_all[0]['tgl_before']:'';
    // $tgl_loss = (count($proyek_all)>0)?$proyek_all[0]['tgl_loss']:'';
   function kepatuhan($nilai)
   {
        if ($nilai<=0) {
            $hasil = "100";
        }elseif($nilai==1){
            $hasil = "90";
        }elseif($nilai==2){
            $hasil = "80";
        }elseif($nilai==3){
            $hasil = "70";
        }elseif($nilai==4){
            $hasil = "60";
        }elseif($nilai==5){
            $hasil = "45";
        }elseif($nilai==6){
            $hasil = "30";
        }elseif($nilai == 7){
            $hasil = "15";
        }elseif($nilai > 7){
            $hasil = "0";
        }

        return $hasil;
   }
    // Doi::dump($proyek_all);
?>

<strong>Kepatuhan Pelaporan Manajement Risiko Korporasi</strong><br>
<strong>Deadline:</strong><br>
<strong>Tanggal 5 Setiap Bulan</strong><br>
<table class="table table-bordered table-striped" border="1" width="85%" cellpadding="3" cellspacing="4">
    <thead>
        <tr>
            <th rowspan="4" width="5%">No.</th>
            <!-- <th rowspan="4" width="10%" class="text-center">Kode</th> -->
            <th rowspan="4">Divisi/Proyek</th>
            <th colspan="<?=$col_title?>" width="40%" class="text-center">Laporan <?= $title; ?></th>
            <!-- <th rowspan="4" width="5%" class="text-center">Aksi</th> -->
        </tr>

        <tr>
            
            <th width="8%" colspan="2" class="text-center <?=$hide?>"><?=date('M',strtotime($tgl_before))?></th>
            <th rowspan="2" width="8%" class="text-center <?=$hide?>">Tanggal Approved</th>
            <th rowspan="2" width="8%" class="text-center <?=$hide?>">Ketepatan Waktu</th>

            <th width="8%" colspan="2" class="text-center"><?=date('M',strtotime($tgl))?></th>
                <th rowspan="2" width="8%" class="text-center">Tanggal Approved</th>
            <th rowspan="2" width="8%" class="text-center">Ketepatan Waktu</th> 
            <!-- <th width="8%" colspan="3" class="text-center">Loss Event Report</th> -->
            <th rowspan="2" width="8%" class="text-center">Total (%)<br>per Unit</th> 


        </tr>
        <tr>
            <th width="4%" class="text-center <?=$hide?>">BK1/2</th>
            <th width="4%" class="text-center <?=$hide?>">BK3</th>

            <th width="4%" class="text-center">BK1/2</th>
            <th width="4%" class="text-center">BK3</th>
            <!-- <th width="4%" class="text-center">Kelengkapan</th>
            <th width="4%" class="text-center">Tanggal Pengiriman</th>
            <th width="4%" class="text-center">Ketepatan Waktu</th> -->
        </tr>
    </thead>
    <tbody>
    <?php
        $total1 = 0;
        $total2 = 0;
        $kepatuhan = 0;
        $total1_before = 0;
        $total2_before = 0;
        $kepatuhan_before = 0;
        // $total1_loss = 0;
        // $total2_loss = 0;
        // $kepatuhan_loss = 0;
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

                if ($row['bkx_before'] > 0) {
                    if ($row[0]['id']>0) {
                        $created_before='<i class="fa fa-check-circle text-primary"></i>';
                        $total1_before += 1;
                    }else{
                        $created_before='<i class="fa fa-minus-circle text-danger"></i>';
                    }
                    
                } elseif ($row['bkx'] == 0) {
                    $created_before='<i class="fa fa-minus-circle text-danger"></i>';
                } 

                $bk3_before = '';
                $tgl_approved_before = '';
                $diff_before = '';
                
                
                if ($row['bk3_before'] == '1') {
                    $bk3_before='<i class="fa fa-check-circle text-primary"></i>';
                    
                    if (isset($row[1]['tgl_propose_bk3'])) {
                        
                        if ($divisi==-1) {
                            $co_before = $this->data->get_data_lap_by_div($row[1]['period_no'], $row[1]['term_no'], 0, $row[1]['owner_no'], $term_t);
                            $total1_beforex = 0;
                            $kepatuhan_beforex = 0;

                            if(count($co_before['proyek_all'])>0){
                                foreach ($co_before['proyek_all'] as $pb => $qb) {
                                    if ($qb['bkx_before'] > 0) {
                                        if ($qb[0]['id']>0) {
                                            $total1_beforex += 1;
                                        }
                                    }
                                    
                                    $bk3_beforex = '';
                                    $tgl_approved_beforex = '';
                                    $diff_beforex = '';
                                    if ($qb['bk3_before'] == '1') {
                                        if (isset($qb[1]['tgl_propose_bk3'])) {

                                            $tgl_approved_beforex = $qb[1]['tgl_propose_bk3'];
                                            $date1_beforex=date_create($tgl_approved_beforex);
                                            $date2_beforex=date_create($deadline);
                                            $diff0_beforex=date_diff($date2_beforex,$date1_beforex);
                                            $nilai_diff_beforex=intval($diff0_beforex->format("%R%a"));
                                            $diff_beforex = kepatuhan($nilai_diff_beforex)."%";
                                            $kepatuhan_beforex += kepatuhan($nilai_diff_beforex);
                                        }
                                        
                                    }
                                }
                                $patuh_before = ($total1_beforex>0)?($kepatuhan_beforex/$total1_beforex):0;
                                $kepatuhan_before += $patuh_before;
                                $diff_before = number_format($patuh_before, 2).'%';
                                
                            }

                            $tgl_approved_before = $row[1]['tgl_propose_bk3'];

                        } else {
                            $tgl_approved_before = $row[1]['tgl_propose_bk3'];
                            $date1_before=date_create($tgl_approved_before);
                            $date2_before=date_create($deadline);
                            $diff0_before=date_diff($date2_before,$date1_before);
                            $nilai_diff_before=intval($diff0_before->format("%R%a"));
                            $diff_before = kepatuhan($nilai_diff_before)."%";
                            $kepatuhan_before += kepatuhan($nilai_diff_before);
                        }
                        
                        

                    }
                    $total2_before += 1;

                } elseif ($row['bk3_before'] == '0') {
                    $bk3_before='<i class="fa fa-minus-circle text-danger"></i>';
                } 

                
                // if ($row['bkx_loss'] > 0) {
                //     if ($row[2]['id']>0) {
                //         $created_loss='<i class="fa fa-check-circle text-primary"></i>';
                //         $total1_loss += 1;
                //     }else{
                //         $created_loss='<i class="fa fa-minus-circle text-danger"></i>';
                //     }

                // } elseif ($row['bkx_loss'] == 0) {
                //     $created_loss='<i class="fa fa-minus-circle text-danger"></i>';
                // } 
                
                // $bk1_loss = '';
                // $tgl_approved_loss = '';
                // $diff_loss = '';
                
                // if ($row['bk1_loss'] == '1') {
                //     $bk1_before='<i class="fa fa-check-circle text-primary"></i>';
                    
                //     if (isset($row[2]['tgl_propose'])) {
                //         if ($divisi==-1) {
                //             $co_loss = $this->data->get_data_lap_by_div($row[2]['period_no'], $row[2]['term_no'], 0, $row[2]['owner_no'], $term_t);
                //             $total1_lossx = 0;
                //             $kepatuhan_lossx = 0;

                //             if(count($co_loss['proyek_all'])>0){
                //                 foreach ($co_loss['proyek_all'] as $pl => $ql) {
                //                     if ($ql['bkx_loss'] > 0) {
                //                         if ($ql[2]['id']>0) {
                //                             $total1_lossx += 1;
                //                         }
                //                     }
                                    
                //                     $bk3_lossx = '';
                //                     $tgl_approved_lossx = '';
                //                     $diff_lossx = '';
                //                     if ($ql['bk1_loss'] == '1') {
                //                         if (isset($ql[2]['tgl_propose'])) {

                //                             $tgl_approved_lossx = $ql[2]['tgl_propose'];
                //                             $date1_lossx=date_create($tgl_approved_lossx);
                //                             $date2_lossx=date_create($deadline);
                //                             $diff0_lossx=date_diff($date2_lossx,$date1_lossx);
                //                             $nilai_diff_lossx=intval($diff0_lossx->format("%R%a"));
                //                             $diff_lossx = kepatuhan($nilai_diff_lossx)."%";
                //                             $kepatuhan_lossx += kepatuhan($nilai_diff_lossx);
                //                         }
                                        
                //                     }
                //                 }
                                

                //                 $patuh_loss = ($total1_lossx>0)?($kepatuhan_lossx/$total1_lossx):0;
                //                 $kepatuhan_loss += $patuh_loss;
                //                 $diff_loss = number_format($patuh_loss, 2).'%';
                                
                //             }

                //             $tgl_approved_loss = $row[2]['tgl_propose'];

                //         } else{

                //             $tgl_approved_loss = $row[2]['tgl_propose'];
                //             $date1_loss=date_create($tgl_approved_loss);
                //             $date2_loss=date_create($deadline);
                //             $diff0_loss=date_diff($date2_loss,$date1_loss);
                //             $nilai_diff_loss=intval($diff0_loss->format("%R%a"));
                //             $diff_loss = kepatuhan($nilai_diff_loss)."%";
                //             $kepatuhan_loss += kepatuhan($nilai_diff_loss); 
                //         }

                //     }
                //     $total2_loss += 1;

                // } elseif ($row['bk1_loss'] == '0') {
                //     $bk3_before='<i class="fa fa-minus-circle text-danger"></i>';
                // } 
                $perUnit = '';
                // if ($row['bkx_loss'] > 0 && $row[0]['id']>0) {
                    $satu = (isset($nilai_diff))?intval(kepatuhan($nilai_diff)):0;
                    $dua = (isset($nilai_diff_before))?intval(kepatuhan($nilai_diff_before)):0;
                    // $tiga = (isset($nilai_diff_loss))?intval(kepatuhan($nilai_diff_loss)):0;
                    $totalPerUnit = ($satu+$dua)/3;
                    $perUnit = number_format($totalPerUnit,2).'%';

                // }
                
            ?>
            <tr>
                <td class="text-center"><?=++$no;?></td>
                <td class="text-left"><?=$row[0]['owner_name'];?></td>
                <td class="bg-warning"></td>
                <td class="text-center"><?=$created_before?> </td>
                <td class="text-center"><?=($tgl_approved_before!='')?date('d-m-Y', strtotime($tgl_approved_before)):'';?></td>
                <td class="text-center"><?=$diff_before?> </td>
                <td class="text-center"><?=$created?></td>
                <td class="bg-warning"></td>
                <td class="text-center"><?=($tgl_approved!='')?date('d-m-Y', strtotime($tgl_approved)):'';?></td>
                <td class="text-center"><?=$diff?></td>
                <!-- <td class="text-center"><?php //$created_loss?></td> -->
                <!-- <td class="text-center"><?php //($tgl_approved_loss!='')?date('d-m-Y', strtotime($tgl_approved_loss)):'';?></td> -->
                <!-- <td class="text-center"><?php //$diff_loss?></td> -->
                <td class="text-center"><?=$perUnit?></td>

            </tr>
        <?php endforeach;?>
    <?php else:?>
        <tr>
            <td colspan="13" class="text-center">Tidak Ada Data</td>
        </tr>
    <?php endif;?>
    </tbody>
    <tfoot class="">
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" rowspan="2" class="text-right">Total</td>
            <td class="text-center <?=$hide?>"></td>
            <td class="text-center <?=$hide?>"><?=number_format($total1_before,0)?></td>
            <td class="text-center <?=$hide?>"><?=number_format($total2_before,0)?></td>
            <td class="text-center <?=$hide?>"></td>
            <td class="text-center"><?=number_format($total1,0)?></td>
            <td class="text-center"></td>
            <td class="text-center"><?=number_format($total2,0)?></td>
            <td class="text-center"></td>
            <!-- <td class="text-center"><?php //number_format($total1_loss,0)?></td> -->
            <!-- <td class="text-center"><?php //number_format($total2_loss,0)?></td> -->
            <td class="text-center"></td>
            <!-- <td class="text-center"></td> -->
        </tr>
        <tr class="bg-warning" > 
            <td class="text-center <?=$hide?>"></td>
            <td class="text-center <?=$hide?>"></td>
            <td class="text-center <?=$hide?>"></td>
            <td class="text-center <?=$hide?>"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <!-- <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td> -->
        </tr>
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" class="text-right">Nilai (%)</td>
            <?php
                $nilai = ($total1>0)?($total2/$total1)*100:0;
                $nilai_before = ($total1_before>0)?($total2_before/$total1_before)*100:0;
                // $nilai_loss = ($total1_loss>0)?($total2_loss/$total1_loss)*100:0;
                $kelengkapan = ($nilai+$nilai_before)/2;
                $kepatuhan_total = ($total1>0)?($kepatuhan/$total1):0;
                $kepatuhan_total_before = ($total1_before>0)?($kepatuhan_before/$total1_before):0;
                $kepatuhan_total_all = ($kepatuhan_total+$kepatuhan_total_before)/2;

                // $kepatuhan_total_loss = ($total1_loss>0)?($kepatuhan_loss/$total1_loss):0;
            

            ?>
            <td class="text-center <?=$hide?>"></td>
            <td class="text-center <?=$hide?>"><?=number_format($nilai_before,2)?>%</td>
            <td class="text-center <?=$hide?>"></td>
            <td class="text-center <?=$hide?>"><?=number_format($kepatuhan_total_before,2)?>%</td>
            <td class="text-center"><?=number_format($nilai,2)?>%</td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"><?=number_format($kepatuhan_total,2)?>%</td>
            <!-- <td class="text-center"><?php //number_format($nilai_loss,2)?>%</td> -->
            <td class="text-center"></td>
            <!-- <td class="text-center"><?php //number_format($kepatuhan_total_loss,2)?>%</td> -->
            <!-- <td class="text-center"></td> -->

        </tr>
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" class="text-right">Kelengkapan Dokumen</td>
            <td class="text-left" colspan="<?=$col?>"><?=number_format($kelengkapan, 2)?>%</td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
        </tr>
        <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
            <td colspan="2" class="text-right">Ketepatan Waktu</td>
            <td class="text-left" colspan="<?=$col?>"><?=number_format($kepatuhan_total_all, 2)?>%</td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
        </tr>
    </tfoot>
</table> 


<script>
   
</script>
