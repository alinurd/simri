<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title"><?= _l('fld_title'); ?></h6>
                <div class="header-elements">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <span class="label"><?= (!empty($mode)) ? '<span class="badge bg-blue-400"> ' . $mode_text . ' </span>' : ''; ?></span>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="20%"><em><?= _l('fld_owner_id'); ?></em></td>
                        <td><strong><?= $parent['owner_name']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l('fld_sasaran_dept'); ?></em></td>
                        <td><strong><?= $parent['sasaran_dept']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l('fld_term_id'); ?></em></td>
                        <td><strong><?= $parent['period_name'] ?></strong></td>
                    </tr>
                    <tr>
                        <td><em>Bulan </em></td>
                        <td><strong><?= $month['param_string'] ?></strong></td>
                    </tr>
                    <tr>
                        <td><em>Risiko Dept </em></td>
                        <td><strong><?= $detail['risiko_dept'] ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="<?= base_url(_MODULE_NAME_); ?>" class="btn bg-primary-400 btn-labeled btn-labeled-left legitRipple" id="back_identifikasi"><b><i class="icon-list"></i></b> Kembali ke List </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-top">
                    <li class="nav-item">
                        <a href="#content-tab-00" class="nav-link " data-toggle="tab"><?= _l('fld_identifikasi_risiko'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-01" class="nav-link " data-toggle="tab"><?= _l('fld_analisa_risiko'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-02" class="nav-link " data-toggle="tab"><?= _l('fld_evaluasi_risiko'); ?></a>
                    </li>
                    <li class="nav-item ">
                        <a href="#content-tab-03" class="nav-link " data-toggle="tab"><?= _l('fld_target_risiko'); ?></a>
                    </li>
                    <li class="nav-item ">
                        <a href="#content-tab-04" class="nav-link active show" data-toggle="tab">Update Monitoring</a>
                    </li>

                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade " id="content-tab-00">
                    <?= $identifikasi; ?>
                </div>
                <div class="tab-pane fade" id="content-tab-01">
                    <?= $analisa; ?>

                </div>
                <div class="tab-pane fade" id="content-tab-02">
                    <?= $evaluasi; ?>
                </div>
                <div class="tab-pane fade" id="content-tab-03">
                    <?= $target; ?>

                </div>
                <div class="tab-pane fade active show" id="content-tab-04">

                    <div class="card" style="background-color: #ffffffb2;">
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <td width="130px">Residual Likelihood</td>
                                    <td width="5px">:</td>
                                    <td><?= $dampak ?></td>
                                </tr>
                                <tr>
                                    <td width="130px">Residual Impact</td>
                                    <td width="5px">:</td>
                                    <td><?= $impact ?></td>
                                </tr>
                                <tr>
                                    <td width="130px">Residual Risk Level</td>
                                    <td width="5px">:</td>

                                    <td><?= $level ?></td>

                                </tr>
                                <tr>
                                    <td width="130px"></td>
                                    <td width="5px"></td>
                                    <td>
                                        <span class="btn btn-success<?= isset($mit) ? '' : ' disabled' ?>" id="simpanResidual">Simpan</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover" id="datatables_library">
                                <thead>
                                    <tr>
                                        <th>Mitigasi</th>
                                        <th width="150px">Aktifitas</th>
                                        <th>Target</th>
                                        <th>Aktual</th>
                                        <th>Uraian</th>
                                        <th>Kendala</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($penyebab_grouped)) : ?>
                                        <?php foreach ($penyebab_grouped as $penyebabId => $items) : ?>
                                            <?php foreach ($items as $index => $m) :
                                            // doi::dump($m);
                                                $getProgress = $this->db->where('rcsa_mitigasi_detail_id', $m['id'])->get("il_view_rcsa_mitigasi_progres")->result_array();

                                                $progress_by_month = [];
                                                foreach ($getProgress as $progress) {
                                                    $getMinggu = $this->db->where('id', $progress['minggu_id'])->get("il_view_minggu")->row_array();
                                                    $monthProgress = intval($getMinggu['bulan_int']);
                                                    $progress_by_month[$monthProgress][] = $progress;
                                                }
                                            ?> 
                                                <tr>
                                                    <td width="20%"><?= $m['penyebab_risiko'] ?></td>
                                                    <td width="20%"><?= $m['mitigasi'] ?></td>
                                                    <?php if (isset($progress_by_month[$monthParam][0])) : ?>
                                                        <td><?= number_format($progress_by_month[$monthParam][0]['target']) ?> %</td>
                                                        <td><?= number_format($progress_by_month[$monthParam][0]['aktual']) ?> %</td>
                                                        <td><?= $progress_by_month[$monthParam][0]['uraian'] ?></td>
                                                        <td><?= $progress_by_month[$monthParam][0]['kendala'] ?></td>
                                                        <td>
                                                            <span id="updateAktifitas"
                                                                data-id="<?= $progress_by_month[$monthParam][0]['id'] ?>"
                                                                data-rcsadetail="<?= $progress_by_month[$monthParam][0]['rcsa_detail_id'] ?>"
                                                                data-mitigasi="<?= $progress_by_month[$monthParam][0]['rcsa_mitigasi_id'] ?>"
                                                                data-mitdetail="<?= $progress_by_month[$monthParam][0]['rcsa_mitigasi_detail_id'] ?>"
                                                                data-periode="<?= $progress_by_month[$monthParam][0]['period_id'] ?>"
                                                                data-bln="<?= $monthParam ?>"
                                                                class="btn btn-primary" data-edit="0">
                                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                            </span>
                                                        </td>
                                                    <?php else : ?>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>

                                                        <td>
                                                            <span id="updateAktifitas"
                                                                data-id="<?= $m['id'] ?>"
                                                                data-rcsadetail="<?= $m['rcsa_detail_id'] ?>"
                                                                data-mitigasi="<?= $m['rcsa_mitigasi_id'] ?>"
                                                                data-mitdetail="<?= $m['id'] ?>"
                                                                data-periode="<?= $m['period_id'] ?>"
                                                                data-bln="<?= $monthParam ?>"
                                                                class="btn btn-primary" data-edit="0">
                                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                                            </span>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7">
                                                <center>Data not found</center>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-10"> </div>
            <div class="col-lg-1">
                <span class="btn btn-primary pointer btnNext pull-right"><?= _l("fld_next_tab"); ?></span>
            </div>
            <div class="col-lg-1">
                <span class="btn btn-warning pointer btnPrevious pull-right"><?= _l("fld_back_tab"); ?></span>&nbsp;&nbsp;
            </div>
        </div>
    </div>
</div>