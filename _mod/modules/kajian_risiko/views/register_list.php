<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered shadow-sm">
            <thead class="bg-slate">
                <tr>
                    <th>No</th>
                    <th>Risiko</th>
                    <th>Taksonomi BUMN</th>
                    <th>Tipe Risiko</th>
                    <th class="text-center">Inherent</th>
                    <th class="text-center">Residual</th>
                    <th class="text-center">Created At</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if( ! empty( $register[0]["risiko"] ) )
                {
                    foreach( $register as $kRegister => $vRegister )
                    {
                        ?>
                        <tr>
                            <td><?= $kRegister + 1 ?></td>
                            <td><?= $vRegister["risiko"] ?></td>
                            <td><?= $vRegister["taksonomi"] ?></td>
                            <td><?= $vRegister["tipe_risiko"] ?></td>
                            <td class="text-center"><?= $vRegister["inherent_risk_level"] ?></td>
                            <td class="text-center"><?= $vRegister["residual_risk_level"] ?></td>
                            <td class="text-center"><?= $vRegister["created_at"] ?></td>
                            <td class="text-center">
                                <?php if( ! empty( $disabledSubmit ) )
                                { ?>
                                    <button type="button"
                                        class="btn btn-labeled button-action bg-success btn-sm <?= $disabledSubmit ?>"><i
                                            class="icon-checkmark-circle">&nbsp;Submitted</i></button>
                                <?php }
                                else
                                { ?>
                                    <a href="<?= $btnEdit . $vRegister["id"] ?>"
                                        class="btn btn-labeled button-action bg-primary btn-sm"><i
                                            class="icon-pencil">&nbsp;Edit</i></a>
                                    <a href="<?= $btnDelete . $vRegister["id"] ?>"
                                        class="btn btn-labeled button-action bg-danger delete btn-sm"><i
                                            class="icon-bin">&nbsp;Hapus</i></a>

                                <?php } ?>
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