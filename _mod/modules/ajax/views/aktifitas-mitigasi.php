<br /><br />
<legend class="text-uppercase font-size-lg text-warning font-weight-bold"><i class="icon-grid"></i> DETAIL MITIGASI
</legend>
<div class="row">
    <div class="col-xl-6">
        <table class="table table-bordered">
            <tr>
                <td width="30%"><em><?= _l('fld_mitigasi'); ?></em></td>
                <td><strong><?= $parent['mitigasi']; ?></strong></td>
            </tr>
            <tr>
                <td><em><?= _l('fld_biaya'); ?></em></td>
                <td><strong><?= $parent['biaya']; ?></strong></td>
            </tr>
            <tr>
                <td><em><?= _l('fld_pic'); ?></em></td>
                <td>
                    <ol>
                        <?php
                        $a = json_decode($parent['penanggung_jawab_id']);

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
            </tr>
        </table>
    </div>
    <div class="col-xl-6">
        <table class="table table-bordered">
            <tr>
                <td><em><?= _l('fld_koordinator'); ?></em></td>
                <td><strong><?= $parent['koordinator']; ?></strong></td>
            </tr>
            <tr>
                <td><em><?= _l('fld_due_date'); ?></em></td>
                <td><strong><?= $parent['batas_waktu']; ?></strong></td>
            </tr>
        </table>
    </div>
</div>
<br />
<strong>LIST AKTIFITAS MITIGASI</strong><br />
<table class="table table-hover table-bordered" id="tbl_list_aktifitas_mitigasi">
    <thead>
        <tr class="bg-warning-300">
            <th width="5%">No</th>
            <th><?= _l('fld_aktifitas_mitigasi'); ?></th>
            <th><?= _l('fld_pic'); ?></th>
            <th><?= _l('fld_koordinator'); ?></th>
            <th><?= _l('fld_due_date'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $no = 0;
    $grandTotalTarget = 0;  // Inisialisasi total target global
    $grandTotalAktual = 0;  // Inisialisasi total aktual global
    $totalActivities = 0;    // Menghitung jumlah aktivitas

    if (!empty($aktifitas)) {
        foreach ($aktifitas as $row) :
            // Ambil detail berdasarkan rcsa_mitigasi_detail_id
            $detail = $this->db->where('rcsa_mitigasi_detail_id', $row['id'])->get(_TBL_VIEW_RCSA_MITIGASI_PROGRES)->result_array();
            $jumlahDetail = count($detail); // Hitung jumlah detail yang terkait
            
            // Inisialisasi total untuk aktivitas ini
            $totalTarget = 0;
            $totalAktual = 0;

            // Tampilkan baris untuk aktifitas mitigasi
            ?>
            <tr class="pointer detail-progres-mitigasi" data-id="<?= $row['id']; ?>">
                <td rowspan="<?= $jumlahDetail + 3; ?>"><?= ++$no; ?></td>
                <td rowspan="<?= $jumlahDetail + 3; ?>"><?= $row['aktifitas_mitigasi']; ?></td>
                <td rowspan="<?= $jumlahDetail + 3; ?>">
                    <ol>
                        <?php
                        $a = json_decode($row['penanggung_jawab_id']);
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
                <td rowspan="<?= $jumlahDetail + 3; ?>"><?= $row['koordinator_detail']; ?></td>
                <td rowspan="<?= $jumlahDetail + 3; ?>"><?= date('d-m-Y', strtotime($row['batas_waktu_detail'])); ?></td>
            </tr>

            <!-- Tampilkan header detail mitigasi terkait -->
            <tr class="pointer detail-progres-mitigasi" data-id="<?= $row['id']; ?>">
                 <td>ID</td>
                 <td>TARGET</td>
                 <td>AKTUAL</td>
            </tr>
            
            <!-- Tampilkan detail mitigasi terkait -->
            <?php foreach ($detail as $d) : 
                // Tambahkan target dan aktual ke total
                $totalTarget += $d['target'];
                $totalAktual += $d['aktual'];
                $grandTotalTarget += $d['target']; // Tambah ke total global
                $grandTotalAktual += $d['aktual']; // Tambah ke total global
                ?>
                <tr class="pointer detail-progres-mitigasi" data-id="<?= $row['id']; ?>">
                     <td><?= $d['id']; ?></td>
                     <td><?= $d['target']; ?></td>
                     <td><?= $d['aktual']; ?></td> 
                </tr>
            <?php endforeach; ?>
            
            <!-- Tampilkan total target dan aktual untuk aktivitas ini -->
            <tr class="pointer detail-progres-mitigasi" data-id="<?= $row['id']; ?>">
                 <td><strong>Total:</strong></td> 
                <td><?= $totalTarget; ?></td> 
                <td><?= $totalAktual; ?> (hasil sum)</td>
            </tr>

        <?php endforeach; ?>

        <!-- Tampilkan rata-rata untuk semua aktivitas -->
        <tr>
            <td colspan="6" style="text-align: end;"><strong>Rata-rata:</strong></td>
            <td><?= ($no > 0) ? round($grandTotalTarget / $no, 2) : 0; ?></td>
            <td><?= ($no > 0) ? round($grandTotalAktual / $no, 2) : 0; ?><br> (hasil sum / jumlah data mitigasi)</td> 
        </tr>
          
    <?php } else { ?>
        <tr>
            <td colspan="5" class="text-center"><i>No Data Found</i></td>
        </tr>
    <?php } ?>
</tbody>

</table>


</table>


<div id="result_progres_aktifitas_mitigasi">

</div>