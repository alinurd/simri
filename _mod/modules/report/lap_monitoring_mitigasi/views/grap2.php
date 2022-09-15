<?php
$r[] = ['value' => $data['110'], 'name' => 'Sebelum Due Date (110%)', 'type_chat' => $data['110%']];
$r[] = ['value' => $data['100'], 'name' => 'On Schedule (100%)', 'type_chat' => $data['100%']];
$r[] = ['value' => $data['90'], 'name' => 'Delay 1 month (90%)', 'type_chat' => $data['90%']];
$r[] = ['value' => $data['75'], 'name' => 'Delay &#62; 1 month (75%)', 'type_chat' => $data['75%']];
$r[] = ['value' => $data['0'], 'name' => 'Tidak terlaksana (0%)', 'type_chat' => $data['0%']];

$warna = ['#23890f', '#1460d1', '#7e57c2', '#ff0000', '#009688'];

?>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th>Kategori Mitigasi</th>
            <th width="15%">Jumlah</th>
            <th width="15%">Persentase</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        $total = 0;
        foreach ($warna as $key => $value) : ?>
            <tr>
                <td><?= ++$no; ?></td>
                <td><?= $r[$key]['name'] ?></td>
                <td class="text-center">
                    <span class="badge badge-pill" style="background-color:<?= $value; ?>;color:#ffffff;padding:8px 10px;"><?= $r[$key]['value']; ?></span>
                </td>
                <td>
                    <?= $r[$key]['type_chat']; ?>%
                </td>
            </tr>
        <?php
            $total += $r[$key]['value'];
        endforeach; ?>
    <tfoot>
        <tr>
            <td colspan="2">Total</td>
            <td colspan="2"><?= $total ?></td>
        </tr>
    </tfoot>
    </tbody>

</table>