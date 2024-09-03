<?php if (!$mode) : ?>
    <a class="btn btn-primary d-nonex" href="<?= base_url('/profil-risiko/cetak-kri/' . $pos['period'] . '/' . $pos['owner'] . '/0/' . $pos['term_mulai'] . '/' . $pos['term_akhir']) ?>" target=" _blank"><i class="icon-file-excel"> Ms-Excel </i></a>
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
                <!-- <th rowspan="2" scope="row" width="8%" class="limax">Target</th> -->
                <?php for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) : ?>
                    <?php
                    $monthNum = $x;
                    $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
                    ?>
                    <th colspan="3" width="15%"><?= $monthName; ?></th>
                <?php endfor; ?>
            </tr>
            <tr>
                <?php for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) : ?>
                    <th>Std</th>
                    <th>Act</th>
                    <th>Sta</th>
                    <!-- <th>Nilai</th> -->
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            $cek = [];
            $det = [];
            foreach ($data as $key => $row) {
                $parent = $this->data->slugify($row['title']);
                $det[$parent]['title'] = $row['title'];
                $det[$parent]['name'] = $row['name'];
                $det[$parent]['satuan'] = $row['satuan'];
                $det[$parent]['indikator'] = $row['indikator'];
                $det[$parent]['target'] = '';
                $det[$parent]['bulan'] = $row['bulan'];
                if (count($row['detail']) > 0) {
                    foreach ($row['detail'] as $kd => $row_det) {
                        $title = $this->data->slugify($row_det['title']);
                        $det[$parent]['detail'][$title]['title'] = $row_det['title'];
                        $det[$parent]['detail'][$title]['name'] = $row_det['name'];
                        $det[$parent]['detail'][$title]['satuan'] = $row_det['satuan'];
                        $det[$parent]['detail'][$title]['indikator'] = $row_det['indikator'];
                        $det[$parent]['detail'][$title]['target'] = '';
                        foreach ($row_det['bulan'] as $keyx => $value) {
                            $det[$parent]['detail'][$title]['bulan'][$keyx] = $value;
                        }
                    }
                }
            }
            
            ?>
            <?php foreach ($det as $key => $row) :
            

                 ?>
                <tr>
                    <td scope="row" class="satux"><?= ++$no; ?></td>
                    <td scope="row" class="duax"><?= $row['name']; ?></td>
                    <td scope="row" class="tigax"><?= $row['title']; ?></td>
                    <td scope="row" class="empatx"><?= $row['satuan']; ?></td>
                    <!-- <td scope="row" class="limax"></td> -->
                    <?php for ($x = $bulan[0]; $x <= $bulan[1]; ++$x) : ?>
                        <?php
                        // doi::dump($row['bulan']);
                        $nilai=0;
                        if(isset( $row['bulan'][$x])){
                            $det=$this->db->where('rcsa_id', $row['bulan'][$x]['rcsa_id'])->where('kri_id', $row['bulan'][$x]['kpi_id'])->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();

                            $nilai   = ( $row['indikator'] / 100 ) * ( $det[0]['pembobotan'] * count( $det ) );
                            // doi::dump(count($det));
                            // doi::dump($nilai);

                        }

                        $warna = 'bg-default';
                        $int = intval($row['indikator']);
                        if ($int < 1 || $int > 5) {
                            $int = 1;
                        }
                        if (array_key_exists($x, $row['bulan'])) : ?>

                            <?php if ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_1_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_1_max']) {
                                $warna = 'bg-success-400';
                                $bg = "background-color: #2c5b29";
                                $p='p_1';
                                $smin='s_1_min';
                                $smax='s_1_max';
                                $int = 1;
                            } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_4_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_4_max']) {
                                $warna = 'bg-orange-400';
                                $bg = "background-color: #50ca4e";
                                $p='p_4';
                                $smin='s_4_min';
                                $smax='s_4_max';
                                $int = 2;
                            } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_2_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_2_max']) {
                                $warna = 'bg-danger-400';
                                $bg = "background-color: #edfd17";
                                $p='p_2';
                                $smin='s_2_min';
                                $smax='s_2_max';
                                $int = 3;
                            } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_5_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_5_max']) {
                                $warna = 'bg-danger-400';
                                $bg = "background-color: #f0ca0f";
                                $p='p_5';
                                $smin='s_5_min';
                                $smax='s_5_max';
                                $int = 4;
                            } elseif ($row['bulan'][$x]['score'] >= $row['bulan'][$x]['s_3_min'] && $row['bulan'][$x]['score'] <= $row['bulan'][$x]['s_3_max']) {
                                $warna = 'bg-danger-400';
                                $bg = "background-color: #e70808";
                                $p='p_3';
                                $smin='s_3_min';
                                $smax='s_3_max';
                                $int = 5;
                            }
                            ?>
                            <td><?= $row['bulan'][$x][$p] . ' ' . $row['bulan'][$x][$smin] . '-' . $row['bulan'][$x][$smax]; ?></td>
                            <td><?= $row['bulan'][$x]['score']; ?></td>
                            <td class="<?= $warna; ?>" style="<?= $bg ?>"></td>
                            <!-- <td></td> -->

                        <?php else : ?>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php endif; ?>
                    <?php endfor; ?>
                </tr>
                <?php 
                if(isset($row['detail'])){
                foreach ($row['detail'] as $kdx => $row_det) : ?>
                    <tr>
                        <td scope="row" class="satux"></td>
                        <td scope="row" class="duax"></td>
                        <td scope="row" class="tigas"><?= $row_det['title']; ?></td>
                        <td scope="row" class="empatx"><?= $row_det['satuan']; ?></td>
                        <td scope="row" class="limax"></td>
                        <?php for ($y = $bulan[0]; $y <= $bulan[1]; ++$y) : ?>
                            <?php
                            $warna = 'bg-default';
                            $int = intval($row_det['indikator']);
                            if ($int < 1 || $int > 5) {
                                $int = 1;
                            }
                            if (array_key_exists($y, $row_det['bulan'])) : ?>
                                <?php
                                if ($row_det['bulan'][$y]['score'] >= $row_det['bulan'][$y]['s_1_min'] && $row_det['bulan'][$y]['score'] <= $row_det['bulan'][$y]['s_1_max']) {
                                    $warna = 'bg-success-400';
                                    $bg = "background-color: #2c5b29";

                                    $int = 1;
                                } elseif ($row_det['bulan'][$y]['score'] >= $row_det['bulan'][$y]['s_4_min'] && $row_det['bulan'][$y]['score'] <= $row_det['bulan'][$y]['s_4_max']) {
                                    $warna = 'bg-orange-400';
                                    $bg = "background-color: #50ca4e";

                                    $int = 2;
                                } elseif ($row_det['bulan'][$y]['score'] >= $row_det['bulan'][$y]['s_2_min'] && $row_det['bulan'][$y]['score'] <= $row_det['bulan'][$y]['s_2_max']) {
                                    $warna = 'bg-danger-400';
                                    $bg = "background-color: #edfd17";

                                    $int = 3;
                                } elseif ($row_det['bulan'][$y]['score'] >= $row_det['bulan'][$y]['s_5_min'] && $row_det['bulan'][$y]['score'] <= $row_det['bulan'][$y]['s_5_max']) {
                                    $warna = 'bg-danger-400';
                                    $bg = "background-color: #f0ca0f";

                                    $int = 4;
                                } elseif ($row_det['bulan'][$y]['score'] >= $row_det['bulan'][$y]['s_3_min'] && $row_det['bulan'][$y]['score'] <= $row_det['bulan'][$y]['s_3_max']) {
                                    $warna = 'bg-danger-400';
                                    $bg = "background-color: #e70808";

                                    $int = 5;
                                }
                                ?>

                                <td><?= $row_det['bulan'][$y]['p_1'] . ' ' . $row_det['bulan'][$y]['s_1_min'] . '-' . $row_det['bulan'][$y]['s_1_max']; ?></td>
                                <td><?= $row_det['bulan'][$y]['score']; ?></td>
                                <td class="<?= $warna; ?>" style="<?= $bg ?>"></td>
                                <td></td>
                            <?php else : ?>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            <?php endif; ?>
                        <?php endfor; ?>

                    </tr>

                <?php endforeach; }?>

            <?php endforeach; ?>
        </tbody>
    </table>
</div>