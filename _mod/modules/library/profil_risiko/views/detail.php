<?php
if (!$mode) : ?>
    <a class="btn btn-primary d-none" href="<?= base_url(_MODULE_NAME_ . '/cetak'); ?>" target="_blank"><i class="icon-file-excel"> Ms-Excel </i></a>
<?php endif; ?>
<style>
    .table-responsivex th {
        position: sticky;
        top: 0;
    }

    .table-responsivex .satux {
        position: sticky;
        left: 0;
    }

    .table-responsivex .duax {
        position: sticky;
        left: 45px;
    }

    .table-responsivex .tigax {
        position: sticky;
        left: 100px;
    }

    .table-responsivex .empatx {
        position: sticky;
        left: 160px;
    }

    .table-responsivex .limax {
        position: sticky;
        left: 220px;
    }

    .table-responsivex th[scope="row"] {
        background-color: white;
        z-index: 10;
    }

    .table-responsivex td[scope="row"] {
        background-color: white;
        z-index: 10;
    }
</style>
<center>
    PELAPORAN KEY RISK INDICATOR<br />
    DEPARTEMEN <strong><?= strtoupper($owner_name); ?></strong>
</center>
<br />&nbsp;
Sasaran Departemen :
<div class="table-responsive table-responsivex">
    <table class="table table-bordered table-striped table-hover" border="1">
        <thead>
            <tr>
                <th width="5%" scope="row" rowspan="2" class="satux">No.</th>
                <th rowspan="2" scope="row" class="duax">Owner</th>
                <th rowspan="2" scope="row" class="tigax">Parameter</th>
                <th rowspan="2" scope="row" width="8%" class="empatx">Satuan</th>
                <th rowspan="2" scope="row" width="8%" class="limax">Target</th>
                <?php
                for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) :
                    $monthNum = $x;
                    $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                ?>
                    <th colspan="4" width="15%"><?= $monthName; ?></th>
                <?php endfor; ?>
            </tr>
            <tr>
                <?php
                for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) : ?>
                    <th>Std</th>
                    <th>Act</th>
                    <th>Sta</th>
                    <th>Nilai</th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            $cek = [];
            // dumps($data);
            foreach ($data as $key => $row) : ?>

                <!-- !in_array(trim($row['title']), $cek) &&  -->
                <?php if (!in_array(trim($row['title']), $cek) && count($row['detail']) > 0) : ?>
                    <tr>
                        <td scope="row" class="satux"><?= ++$no; ?></td>
                        <td scope="row" class="duax"><?= $row['name']; ?></td>
                        <td scope="row" class="tigax"><?= $row['title']; ?></td>
                        <td scope="row" class="empatx"><?= $row['satuan']; ?></td>
                        <td scope="row" class="limax"><?= $row['satuan']; ?></td>
                        <?php

                        for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) :
                            $warna = 'bg-default';
                            // if ($row['indikator']==1){
                            //     $warna='bg-success-400';
                            // }elseif ($row['indikator']==2){
                            //     $warna='bg-orange-400';
                            // }elseif ($row['indikator']==3){
                            //     $warna='bg-danger-400';
                            // }


                            $int = intval($row['indikator']);
                            if ($int < 1 || $int > 5) {
                                $int = 1;
                            }


                            if (array_key_exists($x, $row['bulan'])) : ?>

                                <?php

                                if ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_1_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_1_max']) {
                                    $warna = 'bg-success-400';
                                    $bg = "background-color: #2c5b29";

                                    $int = 1;
                                } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_4_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_4_max']) {
                                    $warna = 'bg-orange-400';
                                    $bg = "background-color: #50ca4e";

                                    $int = 2;
                                } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_2_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_2_max']) {
                                    $warna = 'bg-danger-400';
                                    $bg = "background-color: #edfd17";

                                    $int = 3;
                                } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_5_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_5_max']) {
                                    $warna = 'bg-danger-400';
                                    $bg = "background-color: #f0ca0f";

                                    $int = 4;
                                } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_3_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_3_max']) {
                                    $warna = 'bg-danger-400';
                                    $bg = "background-color: #e70808";

                                    $int = 5;
                                }
                                ?>
                                <td><?= $row['bulan'][$x]['p_1'] . ' ' . $row['bulan'][$x]['s_1_min'] . '-' . $row['bulan'][$x]['s_1_max']; ?></td>
                                <td><?= $row['bulan'][$x]['score']; ?></td>
                                <td class="<?= $warna; ?>" style="<?= $bg ?>"></td>
                                <td></td>

                            <?php else : ?>
                                <?php //dumps($row['bulan'][10]);
                                ?>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                        <?php endif;
                        endfor; ?>
                    </tr>
                    <?php
                    $nod = -1;
                    $alphabet = range('A', 'Z');
                    foreach ($row['detail'] as $row_det) :
                        // dumps($row_det);
                        $huruf = $alphabet[++$nod]; // returns D
                    ?>
                        <tr>
                            <td scope="row" class="satux"></td>
                            <td scope="row" class="duax"></td>
                            <td scope="row" class="tigas"><?= $huruf . '. ' . $row_det['title']; ?></td>
                            <td scope="row" class="empatx"><?= $row_det['satuan']; ?></td>
                            <td scope="row" class="limax"></td>
                            <?php
                            for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) :
                                $warna = 'bg-default';
                                // if ($row_det['indikator']==1){
                                //     $warna='bg-success-400';
                                // }elseif ($row_det['indikator']==2){
                                //     $warna='bg-orange-400';
                                // }elseif ($row_det['indikator']==3){
                                //     $warna='bg-danger-400';
                                // }


                                $int = intval($row_det['indikator']);
                                if ($int < 1 || $int > 5) {
                                    $int = 1;
                                }
                                // dumps($row_det);
                                if (array_key_exists($x, $row_det['bulan'])) : ?>

                                    <?php
                                    if ($row_det['bulan'][$x]['score'] >= $row_det['bulan'][$x]['s_1_min'] && $row_det['bulan'][$x]['score'] <= $row_det['bulan'][$x]['s_1_max']) {
                                        $warna = 'bg-success-400';
                                        $bg = "background-color: #2c5b29";

                                        $int = 1;
                                    } elseif ($row_det['bulan'][$x]['score'] >= $row_det['bulan'][$x]['s_4_min'] && $row_det['bulan'][$x]['score'] <= $row_det['bulan'][$x]['s_4_max']) {
                                        $warna = 'bg-orange-400';
                                        $bg = "background-color: #50ca4e";

                                        $int = 2;
                                    } elseif ($row_det['bulan'][$x]['score'] >= $row_det['bulan'][$x]['s_2_min'] && $row_det['bulan'][$x]['score'] <= $row_det['bulan'][$x]['s_2_max']) {
                                        $warna = 'bg-danger-400';
                                        $bg = "background-color: #edfd17";

                                        $int = 3;
                                    } elseif ($row_det['bulan'][$x]['score'] >= $row_det['bulan'][$x]['s_5_min'] && $row_det['bulan'][$x]['score'] <= $row_det['bulan'][$x]['s_5_max']) {
                                        $warna = 'bg-danger-400';
                                        $bg = "background-color: #f0ca0f";

                                        $int = 4;
                                    } elseif ($row_det['bulan'][$x]['score'] >= $row_det['bulan'][$x]['s_3_min'] && $row_det['bulan'][$x]['score'] <= $row_det['bulan'][$x]['s_3_max']) {
                                        $warna = 'bg-danger-400';
                                        $bg = "background-color: #e70808";

                                        $int = 5;
                                    }
                                    ?>
                                    <td><?= $row_det['bulan'][$x]['p_1'] . ' ' . $row_det['bulan'][$x]['s_1_min'] . '-' . $row_det['bulan'][$x]['s_1_max']; ?></td>
                                    <td><?= $row_det['bulan'][$x]['score']; ?></td>
                                    <td class="<?= $warna; ?>" style="<?= $bg ?>"></td>
                                    <td></td>
                                <?php else : ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                            <?php endif;
                            endfor; ?>
                        </tr>
            <?php endforeach;


                    $cek[] =  trim($row['title']);
                endif;
            endforeach; ?>
        </tbody>
    </table>
</div>