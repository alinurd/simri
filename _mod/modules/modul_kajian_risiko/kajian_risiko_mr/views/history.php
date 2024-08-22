<?php $this->load->view( "register_btn_history", $dataview ); ?>
<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 border">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-none border m-0">
                        <div class="card-body p-2">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-slate">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Kajian Risiko</th>
                                        <th class="text-center">Status kajian</th>
                                        <th class="text-center">Status Approval</th>
                                        <th class="text-center">Note</th>
                                        <th class="text-center">Tanggal Update</th>
                                        <th class="text-center">Updated by</th>
                                        <th class="text-center">Tiket Terbit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if( ! empty( $dataview ) )
                                    { ?>
                                        <?php foreach( $dataview as $kView => $vView )
                                        { ?>
                                            <tr>
                                                <td class="text-center"><?= $kView + 1 ?></td>
                                                <td class="text-center"><?= $vView["name"] ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    switch( $vView["status_kajian"] )
                                                    {
                                                        case 1: ?>
                                                            <span
                                                                class="btn btn-sm disabled btn-block btn-success"><strong>SUBMITTED</strong></span>
                                                            <?php break;

                                                        case 2: ?>
                                                            <span
                                                                class="btn btn-sm disabled btn-block btn-warning"><strong>REVISI</strong></span>
                                                            <?php break;

                                                        default: ?>
                                                            <span
                                                                class="btn btn-sm disabled btn-block btn-danger"><strong>DRAFT</strong></span>
                                                            <?php
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center"><?= $vView["status_approval"] ?></td>
                                                <td class="text-center"><?= $vView["note"] ?></td>
                                                <td class="text-center">
                                                    <?= ! empty( $vView["updated_at"] ) && $vView["updated_at"] != "0000-00-00" ? date( "d-m-Y", strtotime( $vView["updated_at"] ) ) : "" ?>
                                                </td>
                                                <td class="text-center"><?= $vView["updated_by"] ?></td>
                                                <td class="text-center">
                                                    <?= ! empty( $vView["tiket_terbit"] ) && $vView["tiket_terbit"] != "0000-00-00" ? date( "d-m-Y", strtotime( $vView["tiket_terbit"] ) ) : "" ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php }
                                    else
                                    { ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Data Empty</td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>