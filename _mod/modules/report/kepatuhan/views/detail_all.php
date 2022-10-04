<?php
$hide = '';
$col = 8;
$col_title = 12;
if ($is_triwulan == 1) {
  $hide = 'hide';
  $col = $col - 3;
  $col_title = $col_title - 9;
}
$tgl = (count($proyek_all) > 0) ? $proyek_all[0]['tgl'] : '';
$deadline = (count($proyek_all) > 0) ? $proyek_all[0]['deadline'] : '';
// $tgl_before = (count($proyek_all)>0)?$proyek_all[0]['tgl_before']:'';
// $tgl_loss = (count($proyek_all)>0)?$proyek_all[0]['tgl_loss']:'';
function kepatuhan($nilai)
{
  if ($nilai < 0) {
    $hasil = "110";
  } elseif ($nilai == 0) {
    $hasil = "100";
  } elseif ($nilai == 1) {
    $hasil = "90";
  } elseif ($nilai == 2) {
    $hasil = "90";
  } elseif ($nilai >= 3) {
    $hasil = "75";
  }

  return $hasil;
}

//    Doi::dump($minggu);
?>

<strong>Kepatuhan Pelaporan Assesment Manajement Risiko Korporasi</strong><br>
<strong>Deadline:</strong><br>
<strong>Tanggal 5 Setiap Bulan</strong><br>
<table class="table table-bordered table-striped" border="1" width="85%" cellpadding="3" cellspacing="4">
  <thead>
    <tr>
      <th rowspan="3" width="5%">No.</th>
      <th rowspan="3">Department</th>
      <?php foreach ($all_triwulan as $k => $tw) : ?>
        <th colspan="<?= count($tw) * 3 ?>" width="40%" class="text-center">Laporan <?= $k ?> - <?= $tahun ?></th>
      <?php endforeach; ?>
    </tr>

    <tr>
      <?php foreach ($all_triwulan as $tw) : ?>
        <?php foreach ($tw as $m) : ?>
          <th colspan="3" class="text-center"><?= $m ?></th>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </tr>

    <tr>
      <?php foreach ($all_triwulan as $tw) : ?>
        <?php foreach ($tw as $m) : ?>
          <th width="4%" class="text-center">Kelengkapan<br>(deadline)</th>
          <th width="8%" class="text-center">Tanggal Approved</th>
          <th width="8%" class="text-center">Ketepatan Waktu</th>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php
    $total1 = 0;
    $total2 = 0;
    $kepatuhan = 0;
    ?>
    <?php if (count($proyek_all) > 0) : ?>

      <?php $no = 0; ?>
      <?php foreach ($proyek_all as $row) : ?>
        <?php
        $created = [];
        $total1 = [];
        if ($row['bkx'] > 0) {
          foreach ($all_triwulan as $tw) {
            foreach ($tw as $x => $m) {
              if (isset($row[0][$x])) {
                $created[$x] = '<i class="fa fa-check-circle text-primary"></i><br><small><b>' . date('d-m-Y', strtotime($row[0][$x]['deadline'])) . '</b></b></small>';
                // $total1[$x] += 1;
                // if (($row[0][$x])>0) {
                // }else{
                //     $created[$x]='<i class="fa fa-minus-circle text-danger"></i>';
                // }

              } else {
                $created[0] = '<i class="fa fa-minus-circle text-danger"></i>';
              }
            }
          }
        } elseif ($row['bkx'] == 0) {
          $created[0] = '<i class="fa fa-minus-circle text-danger"></i>';
        }

        $bk1 = [];
        $tgl_approved = [];
        $diff = [];

        foreach ($all_triwulan as $tw) {
          foreach ($tw as $x => $m) {
            if (isset($row[0][$x])) {
              if ($row[0][$x]['bk1'] == '1') {
                $bk1[0] = '<i class="fa fa-check-circle text-primary"></i>';
                $tgl_approved[$x] = $row[0][$x]['tgl_propose'] == '0000-00-00 00:00:00' ? '' : $row[0][$x]['tgl_propose'];
                $date1 = date_create($tgl_approved[$x]);
                $date2 = date_create($row[0][$x]['deadline']);
                $diffo[$x] = date_diff($date2, $date1);
                $nilai_diff[$x] = intval($diffo[$x]->format("%R%a"));
                $diff[$x] = $tgl_approved[$x] ? kepatuhan($nilai_diff[$x])."%" : '';
                // $diff[$x] = kepatuhan($nilai_diff[$x]) . "%";
                // $diff[$x] = $nilai_diff[$x];
                // $kepatuhan += kepatuhan($nilai_diff); 
                // $total2 += 1;

              } elseif ($row[0][$x]['bk1'] == '0') {
                $bk1[0] = '<i class="fa fa-minus-circle text-danger"></i>';
                $tgl_approved[0] = '';
                $diff[0] = '';
              }
            } else {
              $bk1[0] = '<i class="fa fa-minus-circle text-danger"></i>';
              $tgl_approved[0] = '';
              $diff[0] = '';
            }
          }
        }


        // $perUnit = '';
        // $satu = (isset($nilai_diff))?intval(kepatuhan($nilai_diff)):0;
        // $totalPerUnit = $satu;
        // $perUnit = number_format($totalPerUnit,2).'%';
        // dumps($tgl_approved);

        ?>
        <tr>
          <td class="text-center"><?= ++$no; ?></td>
          <td class="text-left">
          <?= $row[0]['owner_name']; ?> (<?= $row[0]['kode_dept']; ?>)</td>
          <?php foreach ($all_triwulan as $tw) : ?>
            <?php foreach ($tw as $x => $m) : ?>

              <td class="text-center p-0"><?= (isset($created[$x])) ? $created[$x] : $created[0] ?></td>
              <td class="text-center p-0">
                <?php
                if (isset($tgl_approved[$x])) {
                  $c =  ($tgl_approved[$x] != '') ? date('d-m-Y', strtotime($tgl_approved[$x])) : '';
                  echo '<small><b>' . $c . '</b></small>';
                } else {
                  echo ($tgl_approved[0] != '') ? date('d-m-Y', strtotime($tgl_approved[0])) : '';
                }
                ?>
              </td>
              <td class="text-center">
                <?php
                if (isset($diff[$x])) {
                  echo $diff[$x];
                } else {
                  echo $diff[0];
                }
                ?>
              </td>
            <?php endforeach; ?>
          <?php endforeach; ?>

        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="13" class="text-center">Tidak Ada Data</td>
      </tr>
    <?php endif; ?>
  </tbody>
  <tfoot class="d-none">
    <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
      <td colspan="2" rowspan="2" class="text-right">Total</td>
      <td class="text-center" rowspan="2"><?php //number_format($total1,0)
                                          ?></td>
      <td class="text-center" rowspan="2"><?php //number_format($total2,0)
                                          ?></td>
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
      <td class="text-center"><?php //number_format($nilai,2)
                              ?>%</td>
      <td class="text-center"></td>
      <td class="text-center"><?php //number_format($kepatuhan_total,2)
                              ?>%</td>

    </tr>
    <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
      <td colspan="2" class="text-right">Kelengkapan Dokumen</td>
      <td class="text-left" colspan="<?= $col ?>"><?php //number_format($kelengkapan, 2)
                                                  ?>%</td>
    </tr>
    <tr class="bg-warning" style="font-weight:bold;font-size:16px;">
      <td colspan="2" class="text-right">Ketepatan Waktu</td>
      <td class="text-left" colspan="<?= $col ?>"><?php //number_format($kepatuhan_total_all, 2)
                                                  ?>%</td>
    </tr>
  </tfoot>
</table>