<?php

if (!$mode) : ?>
    <a class="btn btn-primary d-none" href="<?= base_url(_MODULE_NAME_ . '/cetak'); ?>" target="_blank">
        <i class="icon-file-excel"> Ms-Excel </i></a>
<?php endif; ?>
<center>
    PELAPORAN KEY RISK INDICATOR<br />
    DEPARTEMEN <strong><?= strtoupper($owner_name); ?></strong>
</center>
<br />&nbsp;
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
                <?php for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) : ?>
                    <?php
                    $monthNum = $x;
                    $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                    ?>
                    <th colspan="4" width="15%"><?= $monthName; ?></th>
                <?php endfor; ?>
            </tr>
            <tr>
                <?php for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) : ?>
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
            ?>

            <?php foreach ($detail as $data) : ?>
                <?php foreach ($data['data'] as $key => $row) : ?>
                    <?php
                    $cek = [];
                    ?>
                    <?php if (!in_array(trim($row['title']), $cek)) : ?>
                        <tr>
                            <td scope="row" class="satux"><?= ++$no; ?></td>
                            <td scope="row" class="duax"><?= $data['owner_name']; ?></td>
                            <td scope="row" class="tigax"><?= $row['title']; ?></td>
                            <td scope="row" class="empatx"><?= $row['satuan']; ?></td>
                            <td scope="row" class="limax"><?= $data['ttl']; ?></td>
                            <?php for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) : ?>
                                <?php
                                $warna = 'bg-default';
                                $int = intval($row['indikator']);
                                if ($int < 1 || $int > 5) {
                                    $int = 1;
                                }
                                ?>
                                <?php if (array_key_exists($x, $row['bulan'])) : ?>
                                    <?php

                                    if ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_1_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_1_max']) {
                                        $warna = 'bg-success-400';
                                        $bg = "background-color: #2c5b29;";
                                        $int = 1;
                                    } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_4_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_4_max']) {
                                        $warna = 'bg-orange-400';
                                        $bg = "background-color: #50ca4e;";

                                        $int = 2;
                                    } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_2_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_2_max']) {
                                        $warna = 'bg-danger-400';
                                        $bg = "background-color: #edfd17;";

                                        $int = 3;
                                    } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_5_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_5_max']) {
                                        $warna = 'bg-danger-400';
                                        $bg = "background-color: #f0ca0f;";

                                        $int = 4;
                                    } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_3_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_3_max']) {
                                        $warna = 'bg-danger-400';
                                        $bg = "background-color: #e70808;";

                                        $int = 5;
                                    }
                                    ?>
                                    <td><?= $row['bulan'][$x]['p_1'] . ' ' . $row['bulan'][$x]['s_1_min'] . '-' . $row['bulan'][$x]['s_1_max']; ?></td>
                                    <td><?= $row['bulan'][$x]['score']; ?></td>
                                    <td class="<?= $warna; ?>" style="<?= $bg ?>"></td>
                                    <?php
                                    $nilai = 0;
                                    if ($data['ttl'] > 0) {
                                        $nilai = (floatval($row['bulan'][$x]['score']) / $data['ttl']) * 100;
                                    }
                                    ?>
                                    <td><?= $this->data->kepatuhan2($nilai) . "%" ?></td>

                                <?php else : ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </tr>
                        <?php
                        $nod = -1;
                        $alphabet = range('A', 'Z');
                        ?>
                        <?php foreach ($row['detail'] as $row_det) :

                            $huruf = $alphabet[++$nod]; // returns D
                        ?>
                            <tr>
                                <td scope="row" class="satux"></td>
                                <td scope="row" class="duax"><?= $data['owner_name'] ?></td>
                                <td scope="row" class="tigax"><?= $huruf . '. ' . $row_det['title']; ?></td>
                                <td scope="row" class="empatx"><?= $row_det['satuan']; ?></td>
                                <td scope="row" class="limax"><?= $data['ttl']; ?></td>
                                <?php for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) : ?>
                                    <?php
                                    $warna = 'bg-default';
                                    $int = intval($row_det['indikator']);
                                    if ($int < 1 || $int > 5) {
                                        $int = 1;
                                    }
                                    ?>

                                    <?php if (array_key_exists($x, $row_det['bulan'])) : ?>

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
                                        <?php
                                        $nilaix = 0;
                                        if ($data['ttl'] > 0) {
                                            $nilaix = (floatval($row_det['bulan'][$x]['score']) / $data['ttl']) * 100;
                                        }
                                        ?>
                                        <td><?= $this->data->kepatuhan2($nilaix) . "%" ?></td>

                                    <?php else : ?>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; ?>
                        <?php $cek[] =  trim($row['title']); ?>
                    <?php endif; ?>
                <?php endforeach; ?>

            <?php endforeach; ?>
        </tbody>
</div>