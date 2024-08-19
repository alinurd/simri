<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 border">
            <div class="card m-0">
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-slate">
                            <tr>
                                <th>No</th>
                                <th class="text-center">Peristiwa Risiko</th>
                                <th>Taksonomi BUMN</th>
                                <th>Tipe Risiko</th>
                                <th class="text-center">Inherent Risk Level</th>
                                <th class="text-center">Residual Risk Level</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if( ! empty( $register[0]["id"] ) )
                            {
                                foreach( $register as $kRegister => $vRegister )
                                {
                                    ?>
                                    <tr>
                                        <td><?= $kRegister + 1 ?></td>
                                        <td><?= $vRegister["library"] ?></td>
                                        <td><?= $vRegister["taksonomi_name"] ?></td>
                                        <td><?= $vRegister["tipe_risiko_name"] ?></td>
                                        <td class="text-center">
                                            <div class="alert alert-sm border shadow-none m-0 p-1"
                                                style="background-color:<?= $vRegister["inherent_level_color"] ?>;color:<?= $vRegister["inherent_text_level_color"] ?>">
                                                <b><?= $vRegister["inherent_level_name"] ?></b>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="alert alert-sm border shadow-none m-0 p-1"
                                                style="background-color:<?= $vRegister["residual_level_color"] ?>;color:<?= $vRegister["residual_text_level_color"] ?>">
                                                <b><?= $vRegister["residual_level_name"] ?></b>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= $vRegister["created_at"] ?></td>
                                        <td class="text-center">
                                            <!-- <?php if( ! empty( $disabledSubmit ) )
                                            { ?>
                                                <button type="button"
                                                    class="btn btn-labeled button-action bg-success btn-sm <?= $disabledSubmit ?>"><i
                                                        class="icon-checkmark-circle">&nbsp;Submitted</i></button>
                                            <?php }
                                            else
                                            { ?>
                                                <a href="<?= $btnEdit . $vRegister["id"] ?>"
                                                    class="btn btn-labeled button-action bg-primary btn-sm"><i
                                                        class="icon-pencil"></i></a>
                                                <a href="<?= $btnDelete . $vRegister["id"] ?>"
                                                    class="btn btn-labeled button-action bg-danger delete btn-sm"><i
                                                        class="icon-bin"></i></a>

                                            <?php } ?> -->
                                            <a href="<?= $btnEdit . $vRegister["id"] ?>"
                                                class="btn btn-labeled button-action bg-primary btn-sm"><i
                                                    class="icon-pencil"></i></a>
                                            <a href="<?= $btnDelete . $vRegister["id"] ?>"
                                                class="btn btn-labeled button-action bg-danger delete btn-sm"><i
                                                    class="icon-bin"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else
                            { ?>
                                <tr class="text-center bg-light">
                                    <td colspan="8"><strong>Data is Empty</strong></td>
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
<?php $this->load->view( "register_upload_dokumen_mr", $register ) ?>
