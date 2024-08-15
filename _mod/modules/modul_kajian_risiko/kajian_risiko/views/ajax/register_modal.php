<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 border">
            <div class="card shadow-none border m-0">
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
                            <table class="table table-bordered table-sm">
                                <thead class="bg-slate">
                                    <tr>
                                        <th rowspan="2" class="text-center">No</th>
                                        <th rowspan="2" class="text-center">Peristiwa Risiko</th>
                                        <th rowspan="2" class="text-center">Taksonomi BUMN</th>
                                        <th rowspan="2" class="text-center">Tipe Risiko</th>
                                        <th colspan="3" class="text-center">Inherent Risk Level</th>
                                        <th colspan="3" class="text-center">Residual Risk Level</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Likelihood</th>
                                        <th class="text-center">Impact</th>
                                        <th class="text-center">Risk Level</th>
                                        <th class="text-center">Likelihood</th>
                                        <th class="text-center">Impact</th>
                                        <th class="text-center">Risk Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if( ! empty( $register[0]["risiko"] ) )
                                    {
                                        foreach( $register as $kRegister => $vRegister )
                                        {
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $kRegister + 1 ?></td>
                                                <td><?= $vRegister["library"] ?></td>
                                                <td><?= $vRegister["taksonomi_name"] ?></td>
                                                <td><?= $vRegister["tipe_risiko_name"] ?></td>
                                                <td class="text-center"><?= $vRegister["inherent_level_likelihood_text"] ?></td>
                                                <td class="text-center"><?= $vRegister["inherent_level_impact_text"] ?></td>
                                                <td class="text-center p-1">
                                                    <div class="alert alert-sm border shadow-none m-0 p-1"
                                                        style="background-color:<?= $vRegister["inherent_level_color"] ?>;color:<?= $vRegister["inherent_text_level_color"] ?>">
                                                        <b><?= $vRegister["inherent_level_name"] ?></b>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?= $vRegister["residual_level_likelihood_text"] ?></td>
                                                <td class="text-center"><?= $vRegister["residual_level_impact_text"] ?></td>
                                                <td class="text-center  p-1">
                                                    <div class="alert alert-sm border shadow-none m-0 p-1"
                                                        style="background-color:<?= $vRegister["residual_level_color"] ?>;color:<?= $vRegister["residual_text_level_color"] ?>">
                                                        <b><?= $vRegister["residual_level_name"] ?></b>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    { ?>
                                        <tr class="text-center bg-light">
                                            <td colspan="10"><strong>Data is Empty</strong></td>
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