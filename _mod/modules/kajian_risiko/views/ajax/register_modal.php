<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-3 mb-3 border">
            <div class="card shadow-none border">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="<?= $btnExport ?>" class="btn bg-green btn-labeled btn-labeled-left"><b><i
                                        class="icon-database-export"></i></b> Export To Excel</a>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead class="bg-slate">
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Risiko</th>
                                        <th rowspan="2">Taksonomi BUMN</th>
                                        <th rowspan="2">Tipe Risiko</th>
                                        <th colspan="3" class="text-center">Inherent Risk</th>
                                        <th colspan="3" class="text-center">Residual Risk</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">L</th>
                                        <th class="text-center">I</th>
                                        <th class="text-center">RL</th>
                                        <th class="text-center">L</th>
                                        <th class="text-center">I</th>
                                        <th class="text-center">RL</th>
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
                                                <td class="text-center"><?= $vRegister["likelihood_inherent_level"] ?></td>
                                                <td class="text-center"><?= $vRegister["impact_inherent_level"] ?></td>
                                                <td class="text-center"><?= $vRegister["inherent_risk_level"] ?></td>
                                                <td class="text-center"><?= $vRegister["likelihood_residual_level"] ?></td>
                                                <td class="text-center"><?= $vRegister["impact_residual_level"] ?></td>
                                                <td class="text-center"><?= $vRegister["residual_risk_level"] ?></td>
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
    </div>
</div>