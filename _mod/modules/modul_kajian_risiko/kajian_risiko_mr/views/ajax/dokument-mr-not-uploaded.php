<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 border">
            <div class="card shadow-none border m-0">
                <div class="card-body p-2">
                    <?php if( ! $export ) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <a href="<?= $btnExport ?>" class="btn bg-green btn-labeled btn-labeled-left"><b><i
                                            class="icon-database-export"></i></b> Export To Excel</a>
                            </div>
                        </div>
                        <hr>
                    <?php endif ?>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm" border="1">
                                <thead class="bg-slate">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Kajian Risiko</th>
                                        <th class="text-center">Tipe Kajian Risiko</th>
                                        <th class="text-center">Nama Owner</th>
                                        <th class="text-center">Tanggal Dibuat</th>
                                        <th class="text-center">Tanggal Submit</th>
                                        <th class="text-center">Tanggal Release</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if( ! empty( $data ) )
                                    {
                                        foreach( $data as $kdata => $vdata )
                                        {
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $kdata + 1 ?></td>
                                                <td><?= $vdata["name"] ?></td>
                                                <td><?= $vdata["tipe_kajian"] ?></td>
                                                <td><?= $vdata["owner_name"] ?></td>
                                                <td class="text-center">
                                                    <?= ( ! empty( $vdata["created_at"] ) && $vdata["created_at"] != "0000-00-00 00:00:00" ) ? date( "d-m-Y", strtotime( $vdata["created_at"] ) ) : "" ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ( ! empty( $vdata["date_submit"] ) && $vdata["date_submit"] != "0000-00-00 00:00:00" ) ? date( "d-m-Y", strtotime( $vdata["date_submit"] ) ) : "" ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ( ! empty( $vdata["release_date"] ) && $vdata["release_date"] != "0000-00-00 00:00:00" ) ? date( "d-m-Y", strtotime( $vdata["release_date"] ) ) : "" ?>
                                                </td>

                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    { ?>
                                        <tr class="text-center bg-light">
                                            <td colspan="7"><strong>Data Empty</strong></td>
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