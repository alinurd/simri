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
                                        <th class="text-center">Nama Kajian Risiko</th>
                                        <th class="text-center">Nama Owner</th>
                                        <th class="text-center">Tanggal Permintaan</th>
                                        <th class="text-center">Tanggal Release</th>
                                        <th class="text-center">Tanggal Submit</th>
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
                                                <td><?= $vdata["owner_name"] ?></td>
                                                <td class="text-center">
                                                    <?= ( ! empty( $vdata["request_date"] ) && $vdata["request_date"] != "0000-00-00" ) ? date( "d-m-Y", strtotime( $vdata["request_date"] ) ) : "" ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ( ! empty( $vdata["release_date"] ) && $vdata["release_date"] != "0000-00-00" ) ? date( "d-m-Y", strtotime( $vdata["release_date"] ) ) : "" ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ( ! empty( $vdata["date_submit"] ) && $vdata["date_submit"] != "0000-00-00" ) ? date( "d-m-Y", strtotime( $vdata["date_submit"] ) ) : "" ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    { ?>
                                        <tr class="text-center bg-light">
                                            <td colspan="6"><strong>Data Empty</strong></td>
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