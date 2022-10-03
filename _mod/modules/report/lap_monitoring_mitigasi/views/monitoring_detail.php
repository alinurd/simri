<?php
$show = '';
$rc = '';
$ur = 'progress-mitigasi';
if (isset($export)) {
    if (!$export) {
        $show = ' d-none ';
    }
}
if (isset($rcsa)) {
    $rc = '/' . $rcsa;
}
if (isset($url)) {
    $ur = $url;
}
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <?php if (isset($back)) : ?>
                    <span class="btn bg-warning-400 btn-labeled btn-labeled-left legitRipple" data-id="<?= $id ?>" id="back_list"><b><i class="icon-arrow-left5"></i></b> Kembali</span>
                <?php endif; ?>


                <a target="_blank" href="<?= base_url('/lap-monitoring-mitigasi/cetak-register/' . $pos['period'] . '/' . $pos['owner'] . '/' . $pos['type_ass']) ?>">
                    <h6 class="card-title"><span class="btn bg-primary pointer pull-right <?= $show; ?>" id="export_excel"> Export to Ms-Excel </span></h6>
                </a>


            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="20%">Nama Departemen</td>
                        <td><strong><?= $parent['owner_name']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em>Sasaran Departmen</em></td>
                        <td><strong><?= $parent['sasaran_dept']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em>Periode</em></td>
                        <td><strong><?= $parent['period_name'] . ' - ' . $parent['term']; ?></strong></td>
                    </tr>
                </table>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="bg-primary">
                            <tr>
                                <th rowspan="3">No.</th>
                                <th rowspan="3">Kode Risiko</th>
                                <th rowspan="3">Nama Risiko</th>
                                <th colspan="14">Monitoring Mitigasi Risiko</th>
                                <th rowspan="3">Keterangan</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Risk Indikator Inheren</th>
                                <th rowspan="2">Mitigasi</th>
                                <th rowspan="2">Biaya</th>
                                <th rowspan="2">PIC</th>
                                <th rowspan="2">Koordinator</th>
                                <th colspan="9">Progres</th>
                            </tr>
                            <tr>
                                <th>Aktivitas Mitigasi</th>
                                <th>Due Date</th>
                                <th>Bulan</th>
                                <th>Target</th>
                                <th>Aktual</th>
                                <th>Uraian Progres</th>
                                <th>Kendala Pelaksanaan</th>
                                <th>"Tindak Lanjut/Dukungan<br /> Yang Diperlukan<br />(Jika ada kendala)</th>
                                <th>Due Date Tindak Lanjut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $identi_id = 0;
                            $tmp = 0;
                            $tmp2 = 0;
                            ?>
                            <?php foreach ($rows as $row) : ?>
                                <?php //if (!in_array($row['penyebab_id'], $core)) : ?>
                                    <?php
                                    $jml = 1;
                                    if (array_key_exists($row['id'], $aktif)) {
                                        $jml = count($aktif[$row['id']]);
                                    }
                                    $jml_miti = 1;
                                    if (array_key_exists($row['mitigasi_id'], $miti)) {
                                        $jml_miti = count($miti[$row['mitigasi_id']]);
                                    }
                                    $jml_ident = 1;
                                    if (array_key_exists($row['rcsa_detail_id'], $identi)) {
                                        $jml_ident = count($identi[$row['rcsa_detail_id']]);
                                    }
                                    
                                    ?>
                                    <tr>
                                        <td rowspan="<?= ($jml > 0) ? $jml : $jml_ident; ?>"><?= ++$no; ?></td>
                                        <td rowspan="<?= ($jml > 0) ? $jml : $jml_ident; ?>"><?= $row['kode_risiko']; ?></td>
                                        <td rowspan="<?= ($jml > 0) ? $jml : $jml_ident; ?>"><?= $row['penyebab_risiko']; ?></td>
                                        <td rowspan="<?= ($jml > 0) ? $jml : $jml_ident; ?>" style="background-color:<?= $row['color']; ?>;color:<?= $row['color_text']; ?>;"><?= $row['level_color']; ?></td>
                                        <td rowspan="<?= ($jml > 0) ? $jml : $jml_miti; ?>"><?= $row['mitigasi']; ?></td>
                                        <td rowspan="<?= ($jml > 0) ? $jml : $jml_miti; ?>"><?= number_format($row['biaya']); ?></td>
                                        <td rowspan="<?= ($jml > 0) ? $jml : $jml_miti; ?>"><?= $row['penanggung_jawab']; ?></td>
                                        <td rowspan="<?= ($jml > 0) ? $jml : $jml_miti; ?>"><?= $row['koordinator']; ?></td>
                                        <td rowspan="<?= $jml; ?>"><?= $row['aktifitas_mitigasi'] ?></td>
                                        <td rowspan="<?= $jml; ?>"><?= $row['batas_waktu_detail']; ?></td>
                                          <?php
                                    // dumps(array_key_exists($row['id'], $mitigasi));
                                if (array_key_exists($row['id'], $mitigasi)){
                                    if($mitigasi[$row['id']]){
                                        foreach($mitigasi[$row['id']] as $key=>$mit):
                                            if ($key>0):?>
                                            <tr>
                                            <?php endif;?>
                                            <td><?=$minggu[$mit['minggu_id']];?></td>
                                            <td><?=$mit['target'];?></td>
                                            <td><?=$mit['aktual'];?></td>
                                            <td><?=$mit['uraian'];?></td>
                                            <td><?=$mit['kendala'];?></td>
                                            <td><?=$mit['tindak_lanjut'];?></td>
                                            <td><?=($mit['batas_waktu_tindak_lanjut']!='1970-01-01')?$mit['batas_waktu_tindak_lanjut']:"-";?></td>
                                            <?php
                                             if ($key==0):?>
                                                <td>-</td>
                                             <?php endif;
                                             echo '</tr>';
                                        endforeach;
                                    }
                                }else{
                                    echo '<td>-</td><td></td><td></td><td></td><td></td><td></td><td></td>';
                                }
                                ?>
                        

                                    </tr>
                                <?php //endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>