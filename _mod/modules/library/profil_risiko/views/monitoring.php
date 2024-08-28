<?php
$show = ' ';

if (isset($export)) {
    if (!$export) {
        $show = ' d-none ';
    }
}
?>
<style>
    .table-responsivey th {
        position: sticky;
        top: 0;
    }

    .table-responsivey .satu {
        position: sticky;
        left: 0;
    }

    .table-responsivey .dua {
        position: sticky;
        left: 45px;
    }

    .table-responsivey .tiga {
        position: sticky;
        left: 43px;
    }

    .table-responsivey .empat {
        position: sticky;
        left: 136px;
    }

    .table-responsivey .lima {
        position: sticky;
        left: 210px;
    }

    .table-responsivey .enam {
        position: sticky;
        left: 300px;
    }

    .table-responsivey .tujuh {
        position: sticky;
        left: 443px;
    }

    .table-responsivey td[scope="row"] {
        background-color: white;
        z-index: 10;
    }

    .table-responsivey th[scope="row"] {
        background-color: #2196f3 !important;
        z-index: 10;
    }
</style>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <?php if(isset($pos)):?>
                <a target="_blank" href="<?= base_url('/profil-risiko/cetak-register/' . $pos['period'] . '/' . $pos['owner'] . '/0/' . $pos['term_mulai'] . '/' . $pos['term_akhir']) ?>">
                    <h6 class="card-title"><span class="btn bg-primary pointer pull-right <?= $show; ?>" id="export_excel"> Export to Ms-Excel </span></h6>
                </a>
                <?php endif;?>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="20%">Nama Departemen</td>
                        <td>: <strong><?php echo isset($parent['owner_name']) ? $parent['owner_name'] : "-"; ?></strong></td>
                        </tr>
                    <tr>
                        <td><em>Sasaran Departmen</em></td>
                        <td>: <strong><?php echo isset($parent['sasaran_dept']) ? $parent['sasaran_dept'] : "-"; ?></strong></td>
                     </tr>
                    <tr>
                        <td><em>Periode</em></td>
                        <td>: <strong><?php echo isset($parent['period_name']) ? $parent['period_name'] : "-"; ?></strong></td>
                     </tr>
                </table>
                <div class="table-responsive table-responsivey">
                    <table class="table table-hover table-striped table-bordered" border="1">
                        <thead class="bg-primary">
                            <tr >
                                <th  rowspan="4" scope="row" class="text-center satu">No.</th>
                                <!-- <th  rowspan="4" scope="row" class="text-center dua"></th> -->
                                <th  rowspan="4" scope="row" class="text-center tiga">Nama Risiko</th>
                                <th  rowspan="4" scope="row" class="text-center empat">Mitigasi</th>
                                <th  rowspan="4" scope="row" class="text-center lima">Biaya</th>
                                <th  rowspan="4" scope="row" class="text-center enam">PIC</th>
                                <th  rowspan="4" scope="row" class="text-center tujuh">Koordinator</th>
                                <th  colspan="29">Monitoring Mitigasi Risiko</th>
                                <th rowspan="4">Status</th>
                            </tr>
                            <tr>
 
                                <th colspan="24">Progres Mitigasi (%)</th>
                            </tr>
                            <tr>
                                <?php foreach (range(1, 12) as $v) : ?>
                                    <th class="text-center" colspan="2"><?= date("M", mktime(0, 0, 0, $v, 10)); ?></th>

                                <?php endforeach ?>

                            </tr>
                            <tr>
                                <?php foreach (range(1, 12) as $v) : ?>
                                    <th>Target</th>
                                    <th>Aktual</th>

                                <?php endforeach ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            $identi_id = 0; 
                            $tmp = 0;
                            $tmp2 = 0;
                            $core = [];
                            $rcsa = [];
                            foreach ($rows as $row) :

                                if (!in_array($row['penyebab_id'], $core)) {

                                    $core[] = $row['penyebab_id'];
                                    $jml = 1; 
                                    $jml_miti = 1; 
                                    $jml_ident = 1;
                                    
                            ?>

                                    <tr class="pointer detail-progress" data-id="<?= $row['rcsa_detail_id']; ?>" data-rcsa="<?= $row['rcsa_id']; ?>">
                                        <td class="satu" scope="row" rowspan="<?= ($jml > 0) ? $jml : $jml_ident; ?>"><?= ++$no; ?></td>
                                        <!-- <td class="dua" scope="row" rowspan="<?= ($jml > 0) ? $jml : $jml_ident; ?>"><?= $row['kode_risiko']; ?></td> -->
                                        <td class="tiga" scope="row" rowspan="<?= ($jml > 0) ? $jml : $jml_ident; ?>"><?= $row['penyebab_risiko']; ?></td>
                                       
                                        <td class="empat" scope="row" rowspan="<?= ($jml > 0) ? $jml : $jml_miti; ?>"><?= $row['mitigasi']; ?></td>
                                        <td class="lima" scope="row" rowspan="<?= ($jml > 0) ? $jml : $jml_miti; ?>"><?= number_format($row['biaya']); ?></td>
                                        <td class="enam" class="text-left" scope="row" rowspan="<?= ($jml > 0) ? $jml : $jml_miti; ?>">
                                        <ol>
                                                <?php
                                                $a = json_decode($row['penanggung_jawab_detail_id']);
                                                if (is_array($a)) {
                                                    foreach ($a as $v) {
                                                        echo "<li>" . $picku[$v]['title'] . "</li>";
                                                    }
                                                } else {
                                                    echo "<li>" . $picku[$a]['title'] . "</li>";
                                                }
                                                ?>
                                            </ol>
                                        </td>
                                                                                <td class="tujuh" scope="row" rowspan="<?= ($jml > 0) ? $jml : $jml_miti; ?>"><?= $row['koordinator']; ?></td>
                                        <?php if (array_key_exists($row['penyebab_id'], $mitigasi)) : ?>
                                            <?php $bl = [];
                                            $blx = [];
                                            if ($mitigasi[$row['penyebab_id']]) : ?>
                                                <?php foreach ($mitigasi[$row['penyebab_id']] as $key => $mit) : ?>
                                                    <?php
                                                    if (isset($minggu[$mit['minggu_id']])) {
                                                        if (!in_array($minggu[$mit['minggu_id']], $bl)) {
                                                            $bl[$row['penyebab_id']][] = $minggu[$mit['minggu_id']];
                                                            $blx[$row['penyebab_id']][$minggu[$mit['minggu_id']]][] = $mit;
                                                        }
                                                    }
                                                    ?>
                                                <?php endforeach;; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php foreach (range(1, 12) as $v) :
                                            $kt = date("F", mktime(0, 0, 0, $v, 10));
                                        ?>
                                            <?php if (isset($blx[$row['penyebab_id']][$kt])) : ?>
                                                <?php
                                                $target = array_column($blx[$row['penyebab_id']][$kt], 'target');
                                                $targetpersen = array_sum($target) / count($target);

                                                $aktual = array_column($blx[$row['penyebab_id']][$kt], 'aktual');
                                                $aktualpersen = array_sum($aktual) / count($aktual);

                                                ?>
                                                <td><?= $targetpersen; ?></td>
                                                <td><?= $aktualpersen; ?></td>
                                            <?php else : ?>
                                                <td></td>
                                                <td></td>
                                            <?php endif; ?>

                                        <?php endforeach ?>



                                    </tr>
                                <?php } ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>