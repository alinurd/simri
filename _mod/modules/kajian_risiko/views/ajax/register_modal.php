<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn bg-green btn-labeled btn-labeled-left"><b><i
                    class="icon-database-export"></i></b> Export</button>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12">
        <table class="table table-bordered shadow-sm">
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