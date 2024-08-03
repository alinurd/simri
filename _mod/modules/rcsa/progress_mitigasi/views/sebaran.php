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
                                    <th>Peristiwa</th>
                                    <th>Jumlah Aktifitas</th>
                                    <th>Impact</th>
                                    <th>Residual</th>
                                    <th>Target</th>
                                    <th>Laporan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $records_per_page = 10;
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $offset = ($page - 1) * $records_per_page;

                                $total_records = $this->db->count_all_results("il_view_rcsa_detail");
                                $total_pages = ceil($total_records / $records_per_page);
                                $this->db->limit($records_per_page, $offset);
                                $this->db->order_by('owner_id');

                                $parentxxx = $this->db->get("il_view_rcsa_detail")->result_array();

                                $no = $offset + 1;

                                foreach ($parentxxx as $q) :
                                    $mit = $this->db->where('rcsa_detail_id', $q['id'])->get("il_view_rcsa_mitigasi")->result_array();
                                    $penyebab_grouped = [];

                                    foreach ($mit as $m) {
                                        $penyebab_grouped[$m['penyebab_id']][] = $m;
                                    }
                                ?>
                                    <tr data-toggle="collapse" href="#collapseupcoming-<?= $q['id'] ?>" role="button" aria-expanded="false" aria-controls="collapseupcoming-<?= $q['id'] ?>">
                                        <td><?= $no++ ?></td>
                                        <td><b><?= $q['kode_dept'] ?></b>-<?= $q['owner_name'] ?></td>
                                        <td><?= $q['risiko_dept'] ?></td>
                                        <td><?= count($mit) ?></td>
                                        <td>
                                            <span class="btn" style="background-color: <?= $q['color']; ?>; color: <?= $q['color_text']; ?>;">
                                                <?= $q['like_code']; ?> x <?= $q['impact_code']; ?> <br><?= $q['level_color']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="btn" style="background-color: <?= $q['color_residual']; ?>; color: <?= $q['color_text_residual']; ?>;">
                                                <?= $q['like_code_residual']; ?> x <?= $q['impact_code_residual']; ?> <br><?= $q['level_color_residual']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="btn" style="background-color: <?= $q['color_target']; ?>; color: <?= $q['color_text_target']; ?>;">
                                                <?= $q['like_code_target']; ?> x <?= $q['impact_code_target']; ?> <br><?= $q['level_color_target']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <i class="icon-menu6 pointer text-primary risk-monitoring" title="View Risk Register" data-id="<?= $q['rcsa_id'] ?>"></i>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="collapseupcoming-<?= $q['id'] ?>">
                                        <td colspan="8">
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Mitigasi</th>
                                                            <th width="150px">Aktifitas</th>
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
                                                        <?php foreach ($penyebab_grouped as $penyebabId => $items) : ?>
                                                            <?php foreach ($items as $index => $m) : ?>
                                                                <tr>
                                                                    <?php if ($index === 0) : ?>
                                                                        <td rowspan="<?= count($items) ?>"><?= $m['penyebab_risiko'] ?></td>
                                                                    <?php endif; ?>
                                                                    <td width="150px"><?= $m['mitigasi'] ?></td>
                                                                    <td>
                                                                        <span class="btn" style="background-color: <?= $m['color']; ?>; color: <?= $m['color_text']; ?>;">
                                                                            <?= $m['level_color']; ?>
                                                                        </span>
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
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
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
    <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <?php
                    $max_display_pages = 10;
                    $start_page = max(1, $page - floor($max_display_pages / 2));
                    $end_page = min($total_pages, $start_page + $max_display_pages - 1);

                    if ($end_page - $start_page + 1 < $max_display_pages) {
                        $start_page = max(1, $end_page - $max_display_pages + 1);
                    }

                    for ($i = $start_page; $i <= $end_page; $i++) : ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
</div>
<style>
    .table-responsive {
        overflow-x: auto;
    }

    .table {
        min-width: 1200px;
     }
</style>


<script>
    $(document).ready(function() {
        $('#datatables_library').DataTable();
    });
</script>