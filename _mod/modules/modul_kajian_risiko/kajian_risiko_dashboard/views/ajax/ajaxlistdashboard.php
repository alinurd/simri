<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 border">
            <div class="card shadow-none border m-0">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm" border="1">
                                <thead class="bg-slate">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Kajian Risiko</th>
                                        <th class="text-center">Tipe Kajian</th>
                                        <th class="text-center">Latar Belakang</th>
                                        <th class="text-center">Nama Owner</th>
                                        <!-- <th class="text-center">Request Date</th> -->
                                        <th class="text-center">Release Date</th>
                                        <th class="text-center">Date Submit</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Status Approval</th>
                                        <th class="text-center">Date Approval</th>
                                        <th class="text-center">Tiket Terbit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if( ! empty( $data ) )
                                    {
                                        foreach( $data as $kData => $vData )
                                        {
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $kData + 1 ?></td>
                                                <td><?= $vData["name"] ?></td>
                                                <td><?= $vData["tipe_kajian"] ?></td>
                                                <td><?= $vData["tipe_kajian"] ?></td>
                                                <td class="text-center"><?= $vData["tipe_kajian"] ?></td>
                                                <!-- <td class="text-center">
                                                    <?= ! empty( $vData["request_date"] ) && $vData["request_date"] != "0000-00-00 00:00:00" ? date( "d-m-Y", strtotime( $vData["request_date"] ) ) : "" ?>
                                                </td> -->
                                                <td class="text-center">
                                                    <?= ! empty( $vData["release_date"] ) && $vData["release_date"] != "0000-00-00" ? date( "d-m-Y", strtotime( $vData["release_date"] ) ) : "" ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ! empty( $vData["date_submit"] ) && $vData["date_submit"] != "0000-00-00 00:00:00" ? date( "d-m-Y", strtotime( $vData["date_submit"] ) ) : "" ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if( ! empty( $vData["status"] ) )
                                                    {
                                                        switch( $vData["status"] )
                                                        {
                                                            case 0: ?>
                                                                <span
                                                                    class="btn btn-sm disabled btn-block btn-danger"><strong>DRAFT</strong></span>
                                                                <?php break; ?>
                                                            <?php case 1: ?>
                                                                <span
                                                                    class="btn btn-sm disabled btn-block btn-success"><strong>SUBMITTED</strong></span>
                                                                <?php break; ?>
                                                            <?php case 2: ?>
                                                                <span
                                                                    class="btn btn-sm disabled btn-block btn-warning"><strong>REVISION</strong></span>
                                                                <?php break; ?>
                                                            <?php default:

                                                                break;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if( ! empty( $vData["status_approval"] ) )
                                                    {
                                                        switch( $vData["status_approval"] )
                                                        {
                                                            case "review": ?>
                                                                <span
                                                                    class="btn btn-sm disabled btn-block btn-warning"><strong>REVIEW</strong></span>
                                                                <?php break; ?>
                                                            <?php case "rejected": ?>
                                                                <span
                                                                    class="btn btn-sm disabled btn-block btn-danger"><strong>REJECTED</strong></span>
                                                                <?php break; ?>
                                                            <?php case "approved": ?>
                                                                <span
                                                                    class="btn btn-sm disabled btn-block btn-success"><strong>APPROVED</strong></span>
                                                                <?php break; ?>
                                                            <?php default:

                                                                break;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ! empty( $vData["date_approval"] ) && $vData["date_approval"] != "0000-00-00 00:00:00" ? date( "d-m-Y", strtotime( $vData["date_approval"] ) ) : "" ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ! empty( $vData["tiket_terbit"] ) && $vData["tiket_terbit"] != "0000-00-00 00:00:00" ? date( "d-m-Y", strtotime( $vData["tiket_terbit"] ) ) : "" ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    { ?>
                                        <tr class="text-center bg-light">
                                            <td colspan="12"><strong>Data is Empty</strong></td>
                                        </tr>
                                    <?php } ?>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>