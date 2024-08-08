<style>
    tr.show {
        display: table-row !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-3 mb-3 border">
            <div class="card shadow-none border">
                <div class="card-body">
                    <div class="row border-bottom">
                        <div class="col-md-12">
                            <h6>Daftar Risiko</h6>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div id="setTableMonitoring">
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-slate">
                                        <tr>
                                            <th>No</th>
                                            <th>Risiko</th>
                                            <th>Taksonomi</th>
                                            <th>Tipe Risiko</th>
                                            <th>Inherent</th>
                                            <th>Residual</th>
                                            <th class="text-center">Mitigasi</th>
                                            <th class="text-center">Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if( ! empty( $register ) )
                                        {
                                            foreach( $register as $kReg => $vReg )
                                            { ?>
                                                <tr>
                                                    <td><?= $kReg + 1 ?></td>
                                                    <td><?= $vReg["risiko"] ?></td>
                                                    <td><?= $vReg["taksonomi"] ?></td>
                                                    <td><?= $vReg["tipe_risiko"] ?></td>
                                                    <td><?= $vReg["inherent_risk_level"] ?></td>
                                                    <td><?= $vReg["residual_risk_level"] ?></td>
                                                    <td class="text-center">
                                                        <button type="button" data-toggle="collapse"
                                                            data-target="#row<?= $vReg["id"] ?>" aria-expanded="false"
                                                            aria-controls="row<?= $vReg["id"] ?>"
                                                            class="btn bg-success btn-labeled btn-labeled-right button-action clickable btn-sm">
                                                            <b><i class="icon-arrow-down16"></i></b> Mitigasi
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" id="btn-reg-edit" data-id="<?= $vReg["id"] ?>"
                                                            data-url="<?= $btnEdit ?>"
                                                            class="btn btn-labeled button-action bg-primary btn-sm"><i
                                                                class="icon-pencil"></i></button>
                                                    </td>
                                                </tr>
                                                <tr class="collapse.show bg-light" id="row<?= $vReg["id"] ?>">
                                                    <td colspan="8" class="p-3 ">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card shadow-sm border m-0">
                                                                    <div class="card-body">
                                                                        <div id="setTableMonitoring">
                                                                            <table class="table table-sm table-bordered">
                                                                                <thead class="bg-success">
                                                                                    <tr>
                                                                                        <th>Mitigasi Risiko</th>
                                                                                        <th>PIC</th>
                                                                                        <th>Deadline</th>
                                                                                        <th class="text-center">Add Progress</th>
                                                                                        <th>Detail Progress</th>
                                                                                        <th>Tanggal Update</th>
                                                                                        <th class="text-center">Status</th>
                                                                                        <th class="text-center">Action Mitigasi
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php if( ! empty( $vReg["monitoring"] ) )
                                                                                    {
                                                                                        $spanCount = count( $vReg["monitoring"] );
                                                                                        foreach( $vReg["monitoring"] as $kMonitoring => $vMonitoring )
                                                                                        { ?>
                                                                                            <tr>
                                                                                                <?php if( $kMonitoring == 0 )
                                                                                                { ?>
                                                                                                    <td rowspan="<?= $spanCount; ?>">
                                                                                                        <?= $vMonitoring["mitigasi"]; ?>
                                                                                                    </td>
                                                                                                    <td rowspan="<?= $spanCount; ?>">
                                                                                                        <?= $vMonitoring["pic"]; ?>
                                                                                                    </td>
                                                                                                    <td rowspan="<?= $spanCount; ?>">
                                                                                                        <?= $vMonitoring["deadline"]; ?>
                                                                                                    </td>
                                                                                                    <td rowspan="<?= $spanCount; ?>" class="text-center">
                                                                                                    <button type="button" id="add-monitoring"
                                                                                                            data-url="<?= $btnAdd ?>"
                                                                                                            data-kajian-id="<?= $vReg['id_kajian_risiko'] ?>"
                                                                                                            data-mitigasi-id="<?=$vMonitoring['id_mitigasi']?>" class="btn btn-labeled button-action bg-success btn-sm"><i
                                                                                                            class="icon-plus-circle2"></i>
                                                                                                        </button>
                                                                                                    </td>
                                                                                                <?php } ?>
                                                                                                <td><?= $vMonitoring['detail_progress'] ?>
                                                                                                </td>
                                                                                                <td><?= $vMonitoring['tanggal_update'] ?>
                                                                                                </td>
                                                                                                <td><?= $vMonitoring['status'] ?></td>
                                                                                                <td class="text-center">
                                                                                                    <button type="button"
                                                                                                        id="btn-edit-monitoring"
                                                                                                        data-id="<?= $vMonitoring["id_monitoring"] ?>"
                                                                                                        data-url="<?= $btneditMonitoring ?>"
                                                                                                        data-kajian-id="<?= $vReg['id_kajian_risiko'] ?>"
                                                                                                        class="btn btn-labeled button-action bg-primary btn-sm"><i
                                                                                                            class="icon-pencil"></i></button>
                                                                                                    <button type="button"
                                                                                                        id="btn-delete-monitoring"
                                                                                                        data-kajian-id="<?= $vReg['id_kajian_risiko'] ?>"
                                                                                                        data-id="<?= $vMonitoring["id_monitoring"] ?>"
                                                                                                        data-url="<?= $btnDelete ?>"
                                                                                                        class="btn btn-labeled button-action bg-danger delete btn-sm"><i
                                                                                                            class="icon-bin"></i></button>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                          

                                                                                        }
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        ?>
                                                                                        <tr class="text-center">
                                                                                            <td colspan="7">Data Is Empty</td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>