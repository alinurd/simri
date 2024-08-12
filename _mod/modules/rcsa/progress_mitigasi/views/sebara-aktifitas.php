<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="content-tab-00">
                        <!-- Dropdown for Limit Selection -->
                        <div class="dropdown mb-3">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <?php
                                $records_per_page = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $offset = ($page - 1) * $records_per_page;
                                ?>
                                Tampilkan <?= $records_per_page ?>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="?page=<?= $page ?>&limit=10">10</a>
                                <a class="dropdown-item" href="?page=<?= $page ?>&limit=20">20</a>
                                <a class="dropdown-item" href="?page=<?= $page ?>&limit=35">35</a>
                                <a class="dropdown-item" href="?page=<?= $page ?>&limit=50">50</a>
                                <a class="dropdown-item" href="?page=<?= $page ?>&limit=100">100</a>
                                <a class="dropdown-item" href="?page=<?= $page ?>&limit=500">500</a>
                            </div>
                        </div>

                        <?php
                        $this->db->where('rcsa_id', 389);
                        $total_records = $this->db->count_all_results("il_view_rcsa_detail");
                        $total_pages = ceil($total_records / $records_per_page);
                        $this->db->limit($records_per_page, $offset);
                        $this->db->order_by('owner_id');

                        // Fetch and group records by owner_id

                        $this->db->where('rcsa_id', 389);
                        $parent_records = $this->db->get("il_view_rcsa_detail")->result_array();
                        $grouped_by_owner = [];

                        foreach ($parent_records as $record) {
                            $grouped_by_owner[$record['owner_id']][] = $record;
                        }

                        $no = $offset + 1;
                        ?>

                        <table class="table table-hover" id="datatables_library">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Owner Name</th>
                                    <th>Peristiwa</th>
                                    <th class="text-center">Jumlah Aktifitas</th>
                                    <th class="text-center">Impact</th>
                                    <th class="text-center">Residual</th>
                                    <th class="text-center">Target</th>
                                    <th class="text-center">Laporan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grouped_by_owner as $owner_id => $records) : ?>
                                    <?php
                                    $first_record = true;
                                    foreach ($records as $q) :
                                        $mit = $this->db->where('rcsa_detail_id', $q['id'])->get("il_view_rcsa_mitigasi_detail")->result_array();
                                        $penyebab_grouped = [];

                                        foreach ($mit as $m) {
                                            $penyebab_grouped[$m['penyebab_id']][] = $m;
                                        }
                                    ?>
                                        <!-- Owner Row -->
                                        <?php if ($first_record) : ?>
                                            <tr data-toggle="collapse" href="#ownerRow-<?= $q['kode_dept'] ?>" role="button" aria-expanded="false" aria-controls="ownerRow-<?= $q['kode_dept'] ?>">
                                                <td colspan="7" class="font-weight-bold"><?= $q['kode_dept'] ?> - <?= $q['owner_name'] ?></td>
                                                <td>
                                                    <i class="icon-menu6 pointer text-primary risk-monitoring" title="View Risk Register" data-id="<?= $q['rcsa_id'] ?>"></i>
                                                </td>
                                            </tr>
                                            <?php $first_record = false; ?>
                                        <?php endif; ?>

                                        <!-- Detail Row -->
                                        <tr data-toggle="collapse" href="#collapseupcoming-<?= $q['id'] ?>" role="button" aria-expanded="false" aria-controls="collapseupcoming-<?= $q['id'] ?>" id="ownerRow-<?= $q['kode_dept'] ?>">
                                            <td><?= $no++ ?></td>
                                            <td><b><?= $q['kode_dept'] ?></b> - <?= $q['owner_name'] ?></td>
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
                                        </tr>
                                        <!-- Collapsible Detail -->
                                        <tr class="collapse" id="collapseupcoming-<?= $q['id'] ?>">
                                            <td colspan="8">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Mitigasi</th>
                                                                <th width="150px">Aktifitas</th>
                                                                <th class="text-center">Januari</th>
                                                                <th class="text-center">Februari</th>
                                                                <th class="text-center">Maret</th>
                                                                <th class="text-center">April</th>
                                                                <th class="text-center">Mei</th>
                                                                <th class="text-center">Juni</th>
                                                                <th class="text-center">Juli</th>
                                                                <th class="text-center">Agustus</th>
                                                                <th class="text-center">September</th>
                                                                <th class="text-center">Oktober</th>
                                                                <th class="text-center">November</th>
                                                                <th class="text-center">Desember</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($penyebab_grouped as $penyebabId => $items) : ?>
                                                                <?php foreach ($items as $index => $m) :
                                                                    $getProgress = $this->db->where('rcsa_mitigasi_detail_id', $m['id'])->get("il_view_rcsa_mitigasi_progres")->result_array();

                                                                    $progress_by_month = [];
                                                                    foreach ($getProgress as $progress) {
                                                                        $getMinggu = $this->db->where('id', $progress['minggu_id'])->get("il_view_minggu")->row_array();
                                                                        $month = intval($getMinggu['bulan_int']);
                                                                        $progress_by_month[$month][] = $progress;
                                                                    }
                                                                ?>
                                                                    <tr>
                                                                        <?php if ($index === 0) : ?>
                                                                            <td rowspan="<?= count($items) ?>"><?= $m['penyebab_risiko'] ?></td>
                                                                        <?php endif; ?>
                                                                        <td width="150px"><?= $m['mitigasi'] ?></td>
                                                                        <?php for ($month = 1; $month <= 12; $month++) : ?>
                                                                            <td>
                                                                                <?php if (isset($progress_by_month[$month])) : ?>
                                                                                    <?php foreach ($progress_by_month[$month] as $progress) : ?>
                                                                                        <span>Target: <?= number_format($progress['target']) ?></span> 
                                                                                        <span>Aktual: <?= number_format($progress['aktual']) ?></span> 
                                                                                     <?php endforeach; ?>
                                                                                <?php else : ?>
                                                                                    No Data
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        <?php endfor; ?>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $records_per_page ?>" aria-label="Previous">
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
                        <a class="page-link" href="?page=<?= $i ?>&limit=<?= $records_per_page ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $records_per_page ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</div>

<style>
    .table-responsive {
        overflow-x: auto;
    }

    .table {
        min-width: 1100px;
    }
</style>