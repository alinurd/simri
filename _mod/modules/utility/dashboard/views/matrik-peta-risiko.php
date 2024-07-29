<style>
    .table-dashboard {
        table-layout: fixed;
        width: 100%;
    }

    .table-dashboard,
    .table-dashboard td {
        /* border: 1px solid black; */
        border-collapse: collapse;
    }

    .table-dashboard tr,
    td {
        width: 25px !important;
        height: 40px !important;
    }


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
        <div class="row">
            <div class="col-md-12">
                <h6>Inherent
                    <?= ! empty( $jml_inherent ) ? $jml_inherent : ""; ?>
                </h6>
                <?= $map_inherent ?>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-center">
        <div class="row">
            <div class="col-md-12">
                <h6>Current<?= ! empty( $jml_residual ) ? $jml_residual : ""; ?>
                </h6>
                <?= $map_residual ?>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-center">
        <div class="row">
            <div class="col-md-12">
                <h6>Residual<?= ! empty( $jml_target ) ? $jml_target : ""; ?>
                </h6>
                <?= $map_target ?>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-3 mb-3">
    <div class="col-md-3">
        <table class="table-legend">
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
    <div class="col-md-3">
        <table class="table-legend">
            <tbody>
                <tr>
                    <td>
                        <h6 class="text-center font-weight-bold m-0">IMPACT</h6>
                    </td>
                </tr>
                <?php foreach( $legendImpactMatrix as $keyLegendImpact => $vLegendImpact )
                { ?>
                    <tr class="pr-3">
                        <td class=""><?= "&nbsp;&nbsp;&nbsp;" . $keyLegendImpact . ".&nbsp;&nbsp;" . $vLegendImpact ?></td>
                    </tr>
                    <?php
                } ?>
            </tbody>
        </table>
    </div>
</div>