<?php
$bulan = [];
foreach ($detail as $row) {
    if (!in_array($row['minggu_id'], $bulan)) {
        $bulan[] = $row['minggu_id'];
    }
}

?>
<ul class="nav nav-tabs nav-tabs-top">
    <?php foreach ($bulan as $b) : ?>
        <?php
        $bg = '';
        $active = '';
        if ($b == reset($bulan)) {
            $bg = 'bg-primary';
            $active = 'active show';
        } elseif ($b == end($bulan)) {
            $bg = 'bg-info';
        }
        ?>
        <li class="nav-item">
            <a href="#content-det-<?= $b ?>" class="nav-link <?= $bg ?> <?= $active ?>" data-toggle="tab"><?= (isset($minggu[$b])) ? $minggu[$b] : ''; ?> </a>
        </li>
    <?php endforeach; ?>
</ul>
<div class="tab-content">
    <?php foreach ($bulan as $b) : ?>
        <?php
        $active = '';
        if ($b == reset($bulan)) {
            $active = 'active show';
        }
        ?>
        <div class="tab-pane fade <?= $active ?>" id="content-det-<?= $b ?>">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="bg-primary-300">
                        <th width="5%">No.</th>
                        <th>Kode Risk</th>
                        <th>Departemen</th>
                        <th>Risiko Dept.</th>
                        <th>Klasifikasi</th>
                        <th>Risiko Inheren</th>
                        <th>Risiko Residual</th>
                        <th>Risiko Target</th>
                        <th width="6%">Mitigasi</th>
                        <th width="6%">Aktifitas Mitigasi</th>
                        <th width="6%">Proges Mitigasi</th>
                        <th width="6%">Ketepatan Mitigasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    foreach ($detail as $row) : ?>
                        <?php //if($post['term_mulai']>0):
                        ?>
                        <?php if ($b == $row['minggu_id']) : ?>

                            <tr class="pointer detail-rcsa" data-id="<?= $row['id']; ?>" data-rcsa="<?= $row['rcsa_id']; ?>" data-dampak="<?= $row['impact_residual_id']; ?>">
                                <td><?= ++$no; ?></td>
                                <td><?= $row['kode_risk']; ?></td>
                                <td><?= $row['owner_name']; ?></td>
                                <td><?= $row['risiko_dept']; ?></td>
                                <td><?= $row['klasifikasi_risiko'] . ' | ' . $row['tipe_risiko']; ?></td>
                                <td class="text-center" style="background-color:<?= $row['color']; ?>;color:<?= $row['color_text']; ?>;"><?= $row['level_color']; ?><br /><small><?= $row['like_code'] . 'x' . $row['impact_code'] . ' : ' . $row['risiko_inherent_text']; ?></small></td>
                                <td class="text-center" style="background-color:<?= $row['color_residual']; ?>;color:<?= $row['color_text_residual']; ?>;"><?= $row['level_color_residual']; ?><br /><small><?= $row['like_code_residual'] . 'x' . $row['impact_code_residual'] . ' : ' . $row['risiko_residual_text']; ?></small></td>
                                <td class="text-center" style="background-color:<?= $row['color_target']; ?>;color:<?= $row['color_text_target']; ?>;"><?= $row['level_color_target']; ?><br /><small><?= $row['like_code_target'] . 'x' . $row['impact_code_target'] . ' : ' . $row['risiko_target_text']; ?></small></td>
                                <td class="text-center"><span class="badge bg-orange-400 badge-pill align-self-center ml-auto"><?= $row['jml']; ?></span></td>
                                <td class="text-center"><span class="badge bg-teal-400 badge-pill align-self-center ml-auto"><?= $row['jml2']; ?></span></td>
                                <td class="text-center col-prog" style="cursor: default;"><?php  //$row['jml3']; 
                                                                                            ?><br><span class="badge bg-warning-400 badge-pill align-self-center ml-auto pointer progress">Lihat Detail</span></td>

                                <td class="text-center col-prog" style="cursor: default;"><?= $row['avg2'] ?>% <br><span class="badge bg-info-400 badge-pill align-self-center ml-auto pointer ketepatan">Lihat Chart</span></td>
                            </tr>

                        <?php endif; ?>
                        <?php //endif; 
                        ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endforeach; ?>

</div>