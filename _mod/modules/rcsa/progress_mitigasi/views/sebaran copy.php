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
                                $records_per_page = 10; // Number of records per page
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page or set to 1
                                $offset = ($page - 1) * $records_per_page; // Calculate the offset

                                // Get the total number of records
                                $total_records = $this->db->count_all_results(_TBL_VIEW_RCSA);
                                $total_pages = ceil($total_records / $records_per_page);

                                // Fetch records for the current page
                                $this->db->limit($records_per_page, $offset);
                                $parentxxx = $this->db->get(_TBL_VIEW_RCSA)->result_array();

                                $no = $offset + 1;

                                $no = 1;
                                foreach ($parentxxx as $q) : ?>
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
                                                            <span class="btn" style="background-color: <?= $m['color']; ?>; color: <?= $m['color_text']; ?>;"> <?= $m['level_color']; ?></span>
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
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#datatables_library').DataTable();
    });
</script>