<!-- 
<ul class="nav nav-tabs nav-tabs-top">
    <li class="nav-item">
        <a href="#content-tab-00" class="nav-link active show" data-toggle="tab">Inheren <?= $jml_inherent; ?> </a>
    </li>
    <li class="nav-item">
        <a href="#content-tab-01" class="nav-link " data-toggle="tab">Current <?= $jml_residual; ?> </a>
    </li>
    <li class="nav-item">
        <a href="#content-tab-02" class="nav-link " data-toggle="tab">Residual <?= $jml_target; ?> </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active show" id="content-tab-00"><?= $map_inherent; ?><?= $jml_inherent_status; ?></div>
    <div class="tab-pane fade" id="content-tab-01"><?= $map_residual; ?><?= $jml_residual_status; ?></div>
    <div class="tab-pane fade" id="content-tab-02"><?= $map_target; ?><?= $jml_target_status; ?></div>
</div> -->
<style>
    .table-profil-dashboard {
        table-layout: fixed;
        width: 100%;
    }

    .table-profil-dashboard,
    .table-profil-dashboard td {
        /* border: 1px solid black; */
        border-collapse: collapse;
    }

    .table-profil-dashboard tr,
    td {
        width: 25px !important;
        height: 35px !important;
    }


    /* Table Status*/
    .table-status {
        table-layout: fixed;
        width: 100%;
    }

    .table-status,
    .table-status td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    .table-status tr,
    td {
        width: 25px !important;
        height: 10px !important;
    }

    /**END TABLE STATUS */

    /**TABLE LEGEND */
    .table-legend {
        table-layout: fixed;
        width: 100%;
    }

    .table-legend,
    .table-legend td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    .table-legend tr,
    td {
        width: 20px !important;
        height: 10px !important;
    }

    /**END TABLE LEGEND */


    .top-border {
        border-style: solid;
        border-color: white;
        border-width: 10px;
    }

    .remove-border {
        border-style: hidden !important;
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
    <div class="col-md-4 text-center">
        <div class="card">
            <div class="card-body bg-light pb-0 pl-0">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="font-weight-bold">Inherent <?= ! empty( $jml_inherent ) ? $jml_inherent : 0; ?>
                        </h6>
                        <?= $map_inherent ?>
                    </div>
                </div>
            </div>
            <div class="card-footer pb-0">
                <div class="row justify-content-center mt-3">
                    <div class="col-md-8">
                        <?= $jml_inherent_status; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-center">
        <div class="card">
            <div class="card-body bg-light pb-0 pl-0">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="font-weight-bold">Current <?= ! empty( $jml_residual ) ? $jml_residual : 0; ?>
                        </h6>
                        <?= $map_residual ?>
                    </div>
                </div>
            </div>
            <div class="card-footer pb-0">
                <div class="row justify-content-center mt-3">
                    <div class="col-md-8">
                        <?= $jml_residual_status; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-center">
        <div class="card">
            <div class="card-body bg-light pb-0 pl-0">
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="font-weight-bold">Residual <?= ! empty( $jml_target ) ? $jml_target : 0; ?>
                        </h6>
                        <?= $map_target ?>
                    </div>
                </div>
            </div>
            <div class="card-footer pb-0">
                <div class="row justify-content-center mt-3">
                    <div class="col-md-8">
                        <?= $jml_target_status; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body bg-light">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <table class="table table-sm table-legend">
                            <tbody>
                                <tr>
                                    <td>
                                        <h6 class="text-center font-weight-bold m-0">LIKELIHOOD</h6>
                                    </td>
                                </tr>
                                <?php foreach( $legendLikelihoodMatrix as $keyLegendLikelihood => $vLegendLikelihood )
                                { ?>
                                    <tr class="pr-3">
                                        <td class="">
                                            <?= "&nbsp;&nbsp;&nbsp;" . $keyLegendLikelihood . ".&nbsp;&nbsp;" . $vLegendLikelihood ?>
                                        </td>
                                    </tr>
                                    <?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-legend">
                            <tbody>
                                <tr>
                                    <td>
                                        <h6 class="text-center font-weight-bold m-0">IMPACT</h6>
                                    </td>
                                </tr>
                                <?php foreach( $legendImpactMatrix as $keyLegendimpact => $vLegendImpact )
                                { ?>
                                    <tr class="pr-3">
                                        <td class="">
                                            <?= "&nbsp;&nbsp;&nbsp;" . $keyLegendimpact . ".&nbsp;&nbsp;" . $vLegendImpact ?>
                                        </td>
                                    </tr>
                                    <?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>