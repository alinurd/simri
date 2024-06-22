<legend class="text-uppercase font-size-lg text-primary font-weight-bold"><i class="icon-grid"></i> LIST IDENTIFIKASI</legend>
<table class="table table- table-bordered">
    <thead>
        <tr class="bg-primary-300">
            <th width="5%">No.</th>
            <th>Departemen</th>
            <th>Risiko Dept.</th>
            <th>Klasifikasi</th>
            <th>Risiko Inheren</th>
            <th>Risiko Current</th>
            <th>Risiko Residual</th>
            <th width="6%">Mitigasi</th>
            <th width="6%">Aktifitas Mitigasi</th>
            <th width="6%">Proges Mitigasi</th>
        </tr>
    </thead>
    <tboy>
        <?php
        $no = 0;
        foreach ($detail as $row) : ?>
            <tr class="pointer detail-rcsa" data-id="<?= $row['id']; ?>" data-dampak="<?= $row['impact_residual_id']; ?>">
                <td><?= ++$no; ?></td>
                <td><?= $row['owner_name']; ?></td>
                <td><?= $row['risiko_dept']; ?></td>
                <td><?= $row['klasifikasi_risiko'] . ' | ' . $row['tipe_risiko']; ?></td>
                <td class="text-center" style="background-color:<?= $row['color']; ?>;color:<?= $row['color_text']; ?>;"><?= $row['level_color']; ?><br /><small><?= $row['like_code'] . 'x' . $row['impact_code'] . ' : ' . $row['risiko_inherent_text']; ?></small></td>
                <td class="text-center" style="background-color:<?= $row['color_residual']; ?>;color:<?= $row['color_text_residual']; ?>;"><?= $row['level_color_residual']; ?><br /><small><?= $row['like_code_residual'] . 'x' . $row['impact_code_residual'] . ' : ' . $row['risiko_residual_text']; ?></small></td>
                <td class="text-center" style="background-color:<?= $row['color_target']; ?>;color:<?= $row['color_text_target']; ?>;"><?= $row['level_color_target']; ?><br /><small><?= $row['like_code_target'] . 'x' . $row['impact_code_target'] . ' : ' . $row['risiko_target_text']; ?></small></td>
                <td class="text-center"><span class="badge bg-orange-400 badge-pill align-self-center ml-auto"><?= $row['jml']; ?></span></td>
                <td class="text-center"><span class="badge bg-teal-400 badge-pill align-self-center ml-auto"><?= $row['jml2']; ?></span></td>
                <td class="text-center"><span class="badge bg-warning-400 badge-pill align-self-center ml-auto"><?= $row['jml3']; ?></span></td>
            </tr>
            <tr id="result_mitigasi_<?= $row['id']; ?>" class="result_mitigasi d-none">
                <td colspan="10" style="padding: 30px;">

                </td>
            </tr>
        <?php endforeach; ?>
    </tboy>
</table>

<!-- <div id="result_mitigasi">
    
</div> -->