<style>
    .table-map {
        table-layout: fixed;
        width: 100%;
    }

    .table-map,
    .table-map td {
        /* border: 1px solid black; */
        border-collapse: collapse;
    }

    .table-map tr,
    td {
        width: 25px !important;
        height: 35px !important;
    }

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
        <div class="jumbotron p-2 border">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-none border mb-2">
                        <div class="card-body bg-light p-2">
                            <table class="table table-bordered table-sm">
                                <thead class="bg-slate">
                                    <tr>
                                        <th rowspan="2" class="text-center">No</th>
                                        <th rowspan="2" class="text-center">Peristiwa Risiko</th>
                                        <th rowspan="2" class="text-center">Taksonomi BUMN</th>
                                        <th rowspan="2" class="text-center">Tipe Risiko</th>
                                        <th colspan="3" class="text-center">Current Risk Level</th>
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
                                    <?php if( ! empty( $register[0]["id"] ) )
                                    {
                                        foreach( $register as $kRegister => $vRegister )
                                        {
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $kRegister + 1 ?></td>
                                                <td><?= $vRegister["library"] ?></td>
                                                <td><?= $vRegister["taksonomi_name"] ?></td>
                                                <td><?= $vRegister["tipe_risiko_name"] ?></td>
                                                
                                                <td class="text-center"><?= $vRegister["current_level_likelihood_text"] ?></td>
                                                <td class="text-center"><?= $vRegister["current_level_impact_text"] ?></td>
                                                <td class="text-center  p-1">
                                                    <div class="alert alert-sm border shadow-none m-0 p-1"
                                                        style="background-color:<?= $vRegister["current_level_color"] ?>;color:<?= $vRegister["current_text_level_color"] ?>">
                                                        <b><?= $vRegister["current_level_name"] ?></b>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?= $vRegister["residual_level_likelihood_text"] ?></td>
                                                <td class="text-center"><?= $vRegister["residual_level_impact_text"] ?></td>
                                                <td class="text-center p-1">
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
                                            <td colspan="10"><strong>Data Empty</strong></td>
                                        </tr>
                                    <?php } ?>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-none border m-0">
                        <div class="card-body p-2">
                            <ul class="nav nav-tabs nav-tabs-top">
                                <li class="nav-item">
                                    <a href="#content-tab-00" class="nav-link active show" data-toggle="tab">Current</a>
                                </li>
                                <li class="nav-item"><a href="#content-tab-01" class="nav-link" data-toggle="tab">Residual</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="content-tab-00">
                                <div class="row">
                                        <div class="col-md-4">
                                            <div class="card shadow-sm border">
                                                <div class="card-body bg-light p-2">
                                                    <table class="table table-bordered table-sm table-map">
                                                        <tbody>
                                                        <?php if( ! empty( $mapData["current"] ) ) : ?>
                                                                <?php foreach( $mapData["current"] as $kcurrentRow => $vcurrentRow ) : ?>
                                                                    <tr>
                                                                        <?php if( $kcurrentRow == 0 ) : ?>
                                                                            <td rowspan="5" class="rotate text-center p-1"
                                                                                style='letter-spacing:5px;font-weight:400;font-size:12px;writing-mode:tb;'>
                                                                                LIKELIHOOD
                                                                            </td>
                                                                        <?php endif; ?>
                                                                        <td class="text-center"><?= $vcurrentRow["code"] ?></td>
                                                                        <?php if( ! empty( $vcurrentRow["column"] ) ) : ?>
                                                                            <?php foreach( $vcurrentRow["column"] as $kcurrentCol => $vcurrentcol ) : ?>
                                                                                <td
                                                                                    style="background-color: <?= $vcurrentcol["color"] ?>;color:<?= $vcurrentcol["color_text"]?>" class="text-center"><b><?= $vcurrentcol["countregister"] ?></b>
                                                                                    
                                                                                </td>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                            <tr>
                                                                <td colspan="2" rowspan="2"></td>
                                                                <td class="text-center">1</td>
                                                                <td class="text-center">2</td>
                                                                <td class="text-center">3</td>
                                                                <td class="text-center">4</td>
                                                                <td class="text-center">5</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" class="text-center p-1"
                                                                    style='letter-spacing:5px;font-weight:400;font-size:12px;'>
                                                                    IMPACT</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="jumbotron p-2 border">
                                                <div class="card shadow-none border m-0">
                                                    <div class="card-body p-2">
                                                        <table class="table table-bordered table-sm">
                                                            <thead class="bg-slate">
                                                                <tr>
                                                                    <th rowspan="2">No</th>
                                                                    <th rowspan="2" class="text-center">Peristiwa
                                                                        Risiko</th>
                                                                    <th colspan="3" class="text-center">Current Risk
                                                                        Level</th>
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
                                                                            <td class="text-center">
                                                                                <div class="alert alert-sm border shadow-none m-0 p-1"
                                                                                    style="background-color:<?= $vRegister["current_level_color"] ?>;color:<?= $vRegister["current_text_level_color"] ?>">
                                                                                    <b><?= $vRegister["current_level_name"] ?></b>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                }
                                                                else
                                                                { ?>
                                                                    <tr class="text-center bg-light">
                                                                        <td colspan="8"><strong>Data Empty</strong></td>
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
                                <div class="tab-pane fade " id="content-tab-01">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card shadow-sm border">
                                                <div class="card-body bg-light p-2">
                                                    <table class="table table-bordered table-sm table-map">
                                                        <tbody>
                                                            <?php if( ! empty( $mapData["residual"] ) ) : ?>
                                                                <?php foreach( $mapData["residual"] as $kResidualRow => $vResidualRow ) : ?>
                                                                    <tr>
                                                                        <?php if( $kResidualRow == 0 ) : ?>
                                                                            <td rowspan="5" class="rotate text-center p-1"
                                                                                style='letter-spacing:5px;font-weight:400;font-size:12px;writing-mode:tb;'>
                                                                                LIKELIHOOD
                                                                            </td>
                                                                        <?php endif; ?>
                                                                        <td class="text-center"><?= $vResidualRow["code"] ?></td>
                                                                        <?php if( ! empty( $vResidualRow["column"] ) ) : ?>
                                                                            <?php foreach( $vResidualRow["column"] as $kResidualCol => $vResidualcol ) : ?>
                                                                                <td
                                                                                    style="background-color: <?= $vResidualcol["color"] ?>;color:<?= $vResidualcol["color_text"]?>" class="text-center"><b><?= $vResidualcol["countregister"] ?></b>
                                                                                    
                                                                                </td>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                            <tr>
                                                                <td colspan="2" rowspan="2"></td>
                                                                <td class="text-center">1</td>
                                                                <td class="text-center">2</td>
                                                                <td class="text-center">3</td>
                                                                <td class="text-center">4</td>
                                                                <td class="text-center">5</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" class="text-center p-1"
                                                                    style='letter-spacing:5px;font-weight:400;font-size:12px;'>
                                                                    IMPACT</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="jumbotron p-2 border">
                                                <div class="card shadow-none border m-0">
                                                    <div class="card-body p-2">
                                                        <table class="table table-bordered table-sm">
                                                            <thead class="bg-slate">
                                                                <tr>
                                                                    <th rowspan="2">No</th>
                                                                    <th rowspan="2" class="text-center">Peristiwa
                                                                        Risiko</th>
                                                                    <th colspan="3" class="text-center">Residual Risk
                                                                        Level</th>
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
                                                                            <td class="text-center">
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
                                                                        <td colspan="8"><strong>Data Empty</strong></td>
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
        </div>
    </div>
</div>