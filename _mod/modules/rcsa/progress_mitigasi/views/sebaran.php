
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="content-tab-00">
                    <table class="table table-hover" id="datatables_library">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Owner Name</th>
                                    <th>Kode Dept</th>
                                    <th>Stakeholder</th>
                                    <th>Tipe Ass</th>
                                    <th>Tahun</th>
                                    <th class="text-center">Periode</th>
                                    <th>Status Mitigasi</th>
                                    <th>Tgl Propose Mitigasi</th>
                                    <th>Laporan</th>
                                 </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($parent as $q) : ?>
                                    <tr data-toggle="collapse" href="#collapseupcoming-<?= $q['id'] ?>" role="button" aria-expanded="false" aria-controls="collapseupcoming-<?= $q['id'] ?>">
                                        <td><?= $no++ ?></td>
                                        <td><?= $q['owner_name'] ?></td>
                                        <td><?= $q['kode_dept'] ?></td>
                                        <td><?= $q['stakeholder_id'] ?></td>
                                        <td><?= $q['type_ass'] ?></td>
                                        <td><?= $q['period_name'] ?></td>
                                        <td class="text-center"><?= $q['term'] ?> <br> (<?= $q['tgl_mulai_term'] ?> - <?= $q['tgl_selesai_term'] ?>)</td>
                                        <td><?= $q['status_id_mitigasi'] ?></td>
                                        <td><?= $q['tgl_propose'] ?></td>
                                        <td>
                                            <i class="icon-menu6 pointer text-primary risk-monitoring" title="View Risk Register" data-id="<?= $q['id'] ?>"></i>
                                        </td> 
                                    </tr>
                                    <tr class="collapse" id="collapseupcoming-<?= $q['id'] ?>">
                                        <td colspan="11">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Januari</th>
                                                        <th>Februari</th>
                                                        <th>Maret</th>
                                                        <th>April</th>
                                                        <th>Mei</th>
                                                        <th>Juni</th>
                                                        <th>Juli</th>
                                                        <th>Agustus</th>
                                                        <th>September</th>
                                                        <th>Oktober</th>
                                                        <th>November</th>
                                                        <th>Desember</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    		$m = $this->db->where('rcsa_detail_id', 15354)->get("il_view_monitoring")->row_array();
                                                             ?>
                                                    <tr>
                                                        <td>
                                                            <span class="btn" style="background-color: <?=$m['color'];?>; color: <?=$m['color_text'];?>;"> <?=$m['level_color'];?></span>
                                                        </td>
                                                        <td>Content for Februari</td>
                                                        <td>Content for Maret</td>
                                                        <td>Content for April</td>
                                                        <td>Content for Mei</td>
                                                        <td>Content for Juni</td>
                                                        <td>Content for Juli</td>
                                                        <td>Content for Agustus</td>
                                                        <td>Content for September</td>
                                                        <td>Content for Oktober</td>
                                                        <td>Content for November</td>
                                                        <td>Content for Desember</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#datatables_library').DataTable();
});
</script> 
