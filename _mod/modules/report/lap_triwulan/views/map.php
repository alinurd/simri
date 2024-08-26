<div class="row">
    <div class="col-lg-6">
        <strong>Risiko Current</strong><br />
        <?= $map_residual; ?>
    </div>
    <div class="col-lg-6">
        <strong>Risiko Residual</strong><br />
        <?= $map_target; ?>
    </div>
</div>
<div class='row'>
    <div class="col-lg-12">
        <strong>Ringkasan Kategori Risiko</strong><br />
        <table class="table table-bordered" border="1">
            <thead>
                <tr class="text-center">
                    <th rowspan="2">Risiko</th>
                    <?php foreach ($level_risiko as $row): ?>
                        <th width="13%" colspan="2" style="background-color:<?= $row['color']; ?>;color:<?= $row['color_text']; ?>"><?= $row['level_color']; ?></th>
                        <?php endforeach; ?>
                        <th colspan="2">Total</th>
                 </tr>
                <tr class="text-center">
                    <?php
                    foreach ($level_risiko as $row): ?>
                        <th style="background-color:<?= $row['color']; ?>;color:<?= $row['color_text']; ?>">Jumlah</th>
                        <th style="background-color:<?= $row['color']; ?>;color:<?= $row['color_text']; ?>">%</th>
                    <?php endforeach; ?>
                    <th width="8%" >Jumlah</th>
                    <th width="8%" >%</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tresi = 0;
                $ttar = 0;
                $total_residual_percentage = 0;
                $total_target_percentage = 0;

                foreach ($level_risiko as $row) {
                    $tresi += $t_residual[$row['level_color']]['jml'];
                    $ttar += $t_target[$row['level_color']]['jml'];
                }
                ?>

                <tr>
                    <td>Risiko Current</td>
                    <?php foreach ($level_risiko as $row): ?>
                        <td class="text-center"><?= $t_residual[$row['level_color']]['jml']; ?></td>
                        <?php
                        $residual_percentage = ($tresi > 0) ? ($t_residual[$row['level_color']]['jml'] / $tresi) * 100 : 0;
                        $total_residual_percentage += $residual_percentage;
                        ?>
                        <td width="5"><?= number_format($residual_percentage, 2); ?>%</td>
                    <?php endforeach; ?>
                    <td class="text-center"><strong><?= $tresi; ?></strong></td>
                    <td width="5"><strong><?= number_format($total_residual_percentage, 2); ?>%</strong></td>
                </tr>
                <tr>
                    <td>Risiko Residual</td>
                    <?php foreach ($level_risiko as $row): ?>
                        <td class="text-center"><?= $t_target[$row['level_color']]['jml']; ?></td>
                        <?php
                        $target_percentage = ($ttar > 0) ? ($t_target[$row['level_color']]['jml'] / $ttar) * 100 : 0;
                        $total_target_percentage += $target_percentage;
                        ?>
                        <td width="5"><?= number_format($target_percentage, 2); ?>%</td>
                    <?php endforeach; ?>
                    <td class="text-center"><strong><?= $ttar; ?></strong></td>
                    <td width="5"><strong><?= number_format($total_target_percentage, 2); ?>%</strong></td>
                </tr>
            </tbody>

        </table>
    </div>
</div>
<br />
<div class='row'>
    <div class="col-lg-12">
        <strong>Progres Pelaksanaan Mitigasi Top 10 Risk Departemen</strong><br />
        <table class="table table-bordered" border="1">
            <thead>
                <tr class="text-center">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Risiko</th>
                    <th rowspan="2">Mitigasi Risiko</th>
                    <th width="12%" rowspan="2">Due Date Mitigasi</th>
                    <th colspan="2" width="14%">Progress</th>
                    <th width="18%" rowspan="2">PIC</th>
                </tr>
                <tr class="text-center">
                    <th>Target</th>
                    <th>Aktual</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                foreach ($top as $row): ?>
                    <tr>
                        <td><?= ++$no; ?></td>
                        <td><?= $row['penyebab_risiko']; ?></td>
                        <?php
                        if ($row['detail']): ?>
                            <td><?= $row['detail'][0]['mitigasi']; ?></td>
                            <td><?= $row['detail'][0]['batas_waktu']; ?></td>
                            <td><?= $row['detail'][0]['target']; ?></td>
                            <td><?= $row['detail'][0]['aktual']; ?></td>
                            <td><?= $row['detail'][0]['penanggung_jawab_detail']; ?></td>
                        <?php
                        endif; ?>
                    </tr>
                    <?php
                    if (count($row['detail']) > 1):
                        foreach ($row['detail'] as $key => $row_top):
                            if ($key > 0): ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><?= $row_top['mitigasi']; ?></td>
                                    <td><?= $row_top['batas_waktu']; ?></td>
                                    <td><?= $row_top['target']; ?></td>
                                    <td><?= $row_top['aktual']; ?></td>
                                    <td><?= $row_top['penanggung_jawab_detail']; ?></td>
                                </tr>
                <?php
                            endif;
                        endforeach;
                    endif;
                endforeach; ?>
            </tbody>
        </table>
        <br />
        Note :<br />
        1. Dilampirkan Laporan KPI & KRI pada per akhir bulan periode laporan<br />
        2. Risiko-risiko yang harus dimitigasi dan dimonitoring pelaksanaannya adalah Top 10 Risk Departemen<br />
    </div>
</div>