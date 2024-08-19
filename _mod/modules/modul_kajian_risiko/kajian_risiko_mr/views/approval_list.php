<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 mb-3 border">
            <div class="row">
                <div class="col-md-6">
                    <div class="card m-0 shadow-none border">
                        <div class="card-header border-bottom text-center p-2 bg-slate">
                            <strong>Dokumen Self-Assessment</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="75" class="text-center">No</th>
                                        <th class="text-center"><strong><b>LINK</b></strong></th>
                                        <th width="75"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if( ! empty( $link_dokumen_kajian ) )
                                    {
                                        foreach( $link_dokumen_kajian as $kLinkDockajian => $vLinkDockajian )
                                        {
                                            if( ! empty( $vLinkDockajian ) )
                                            {
                                                ?>
                                                <tr>
                                                    <td><?= $kLinkDockajian ?></td>
                                                    <td><?= $vLinkDockajian ?></td>
                                                    <td>
                                                        <?php if( ! empty( $vLinkDockajian ) )
                                                        { ?>
                                                            <a class="btn btn-sm btn-outline-primary" target="_blank"
                                                                href="<?= ( ! empty( $vLinkDockajian ) ? $vLinkDockajian : '#' ) ?>"><i
                                                                    class="icon-link"></i></a>
                                                        <?php } ?>

                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            else
                                            { ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">Data Empty</td>
                                                </tr>
                                            <?php }
                                        }
                                    }
                                    else
                                    { ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Data Empty</td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                                <thead class="bg-light">
                                    <th width="75" class="text-center">No</th>
                                    <th class="text-center"><strong><b>FILE</b></strong></th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <?php if( ! empty( $file_assessmen ) )
                                    {
                                        foreach( $file_assessmen as $kLinkDocfileassesmen => $vLinkDocfileassesmen )
                                        { ?>
                                            <tr>
                                                <td><?= $kLinkDocfileassesmen + 1 ?></td>
                                                <td><strong><?= $vLinkDocfileassesmen["filename"] ?></strong></td>
                                                <td>
                                                    <?php if( ! empty( $vLinkDocfileassesmen["server_filename"] ) && file_exists( $vLinkDocfileassesmen["file_path"] . $vLinkDocfileassesmen["server_filename"] ) )
                                                    { ?>
                                                        <a class="btn btn-sm btn-outline-primary" target="_blank"
                                                            href="<?= ( ! empty( $vLinkDocfileassesmen["server_filename"] ) && file_exists( $vLinkDocfileassesmen["file_path"] . $vLinkDocfileassesmen["server_filename"] ) ? base_url( $vLinkDocfileassesmen["file_path"] . $vLinkDocfileassesmen["server_filename"] ) : '#' ) ?>"><i
                                                                class="icon-file-text2"></i></a>
                                                    <?php } ?>

                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    { ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Data Empty</td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card m-0 shadow-none border">
                        <div class="card-header border-bottom text-center p-2 bg-slate">
                            <strong>Dokumen Pendukung</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="75" class="text-center">No</th>
                                        <th class="text-center"><strong><b>LINK</b></strong></th>
                                        <th width="75"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php if( ! empty( $link_dokumen_pendukung ) )
                                        {
                                            foreach( $link_dokumen_pendukung as $kLinkDocpendukung => $vLinkDocPendukung )
                                            {
                                                if( ! empty( $vLinkDocPendukung ) )
                                                {
                                                    ?>
                                                <tr>
                                                    <td><?= $kLinkDocpendukung ?></td>
                                                    <td><?= $vLinkDocPendukung ?></td>
                                                    <td>
                                                        <?php if( ! empty( $vLinkDocPendukung ) )
                                                        { ?>
                                                            <a class="btn btn-sm btn-outline-primary" target="_blank"
                                                                href="<?= ( ! empty( $vLinkDocPendukung ) ? $vLinkDocPendukung : "#" ) ?>"><i
                                                                    class="icon-link"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                                else
                                                {
                                                    ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">Data Empty</td>
                                                </tr>
                                            <?php }
                                                ?>

                                            <?php
                                            }
                                        }
                                        else
                                        { ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Data Empty</td>
                                        </tr>
                                    <?php }
                                        ?>
                                    </tr>
                                </tbody>
                                <thead class="bg-light">
                                    <th width="75" class="text-center">No</th>
                                    <th class="text-center"><strong><b>FILE</b></strong></th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <?php if( ! empty( $file_pendukung ) )
                                    {
                                        foreach( $file_pendukung as $kLinkDocfilependukung => $vLinkDocfilependukung )
                                        { ?>

                                            <tr>
                                                <td><?= $kLinkDocfilependukung + 1 ?></td>
                                                <td><strong><?= $vLinkDocfilependukung["filename"] ?></strong></td>
                                                <td>
                                                    <?php if( ! empty( $vLinkDocfilependukung["server_filename"] ) && file_exists( $vLinkDocfilependukung["file_path"] . $vLinkDocfilependukung["server_filename"] ) )
                                                    { ?>
                                                        <a class="btn btn-sm btn-outline-primary" target="_blank"
                                                            href="<?= ( ! empty( $vLinkDocfilependukung["server_filename"] ) && file_exists( $vLinkDocfilependukung["file_path"] . $vLinkDocfilependukung["server_filename"] ) ? base_url( $vLinkDocfilependukung["file_path"] . $vLinkDocfilependukung["server_filename"] ) : '#' ) ?>"><i
                                                                class="icon-file-text2"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    { ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Data Empty</td>
                                        </tr>
                                    <?php }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card shadow-none m-0 border">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>