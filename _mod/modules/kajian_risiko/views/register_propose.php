<style>
    .rotate {
        -moz-transform: scale(-1, -1);
        -webkit-transform: scale(-1, -1);
        -o-transform: scale(-1, -1);
        -ms-transform: scale(-1, -1);
        transform: scale(-1, -1);
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-3 mb-3 border">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn bg-green btn-labeled btn-labeled-left"><b><i
                                class="icon-database-export"></i></b> Export</button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <table class="table table-bordered shadow-sm table-sm">
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
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card shadow-none border">
                        <div class="card-body ">
                            <ul class="nav nav-tabs nav-tabs-top">
                                <li class="nav-item">
                                    <a href="#content-tab-00" class="nav-link active show"
                                        data-toggle="tab">Inherent</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#content-tab-01" class="nav-link " data-toggle="tab">Residual</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="content-tab-00">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td rowspan="5" class="rotate text-center"
                                                            style='letter-spacing:5px;font-weight:400;font-size:12px;writing-mode:tb;'>
                                                            IMPACT
                                                        </td>
                                                        <td>5</td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td class="bg-danger"></td>
                                                        <td class="bg-danger"></td>
                                                        <td class="bg-danger"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td class="bg-success"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td class="bg-danger"></td>
                                                        <td class="bg-danger"></td>
                                                    </tr>
                                                    <tr>

                                                        <td>3</td>
                                                        <td class="bg-success"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td class="bg-danger"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td class="bg-success"></td>
                                                        <td class="bg-success"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td class="bg-success"></td>
                                                        <td class="bg-success"></td>
                                                        <td class="bg-success"></td>
                                                        <td class="bg-success"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" rowspan="2"></td>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-center"
                                                            style='letter-spacing:5px;font-weight:400;font-size:12px;'>
                                                            LIKELIHOOD</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-8">
                                            <table class="table table-bordered table-sm">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">Risiko</th>
                                                        <th colspan="3" class="text-center">Inherent Risk Level</th>
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
                                                                <td class="text-center"><?= $vRegister["inherent_risk_level"] ?>
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
                                <div class="tab-pane fade " id="content-tab-01">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td rowspan="5" class="rotate text-center"
                                                            style='letter-spacing:5px;font-weight:400;font-size:12px;writing-mode:tb;'>
                                                            IMPACT
                                                        </td>
                                                        <td>5</td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td class="bg-danger"></td>
                                                        <td class="bg-danger"></td>
                                                        <td class="bg-danger"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td class="bg-success"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td class="bg-danger"></td>
                                                        <td class="bg-danger"></td>
                                                    </tr>
                                                    <tr>

                                                        <td>3</td>
                                                        <td class="bg-success"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td class="bg-danger"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td class="bg-success"></td>
                                                        <td class="bg-success"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td class="bg-success"></td>
                                                        <td class="bg-success"></td>
                                                        <td class="bg-success"></td>
                                                        <td class="bg-success"></td>
                                                        <td style="background-color: #ffff55;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" rowspan="2"></td>
                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-center"
                                                            style='letter-spacing:5px;font-weight:400;font-size:12px;'>
                                                            LIKELIHOOD</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-8">
                                            <table class="table table-bordered table-sm">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">Risiko</th>
                                                        <th colspan="3" class="text-center">Residual Risk Level</th>
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
                                                                <td class="text-center">
                                                                    <?= $vRegister["residual_risk_level"] ?>
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
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>