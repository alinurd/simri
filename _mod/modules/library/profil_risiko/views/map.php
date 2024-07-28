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
    table {
        table-layout: fixed;
        width: 100%;
    }

    table,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }


    tr,
    td {
        width: 25px !important;
        height: 40px !important;
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
                <h6>Inherent <?= ! empty( $jml_inherent ) ? $jml_inherent : 0; ?>
                </h6>
                <?= $map_inherent ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="alert alert-secondary">
                    <table>
                        <?php foreach( $legendMatrix as $keyLegend => $vLegend )
                        { ?>
                            <tr>
                                <td><?= $keyLegend ?></td>
                                <td><?= $vLegend ?></td>
                            </tr>
                            <?php
                        } ?>
                    </table>
                </div>

            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <?= $jml_inherent_status; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-center">
        <div class="row">
            <div class="col-md-12">
                <h6>Current <?= ! empty( $jml_residual ) ? $jml_residual : 0; ?>
                </h6>
                <?= $map_residual ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="alert alert-secondary">
                    <table>
                        <?php foreach( $legendMatrix as $keyLegend => $vLegend )
                        { ?>
                            <tr>
                                <td><?= $keyLegend ?></td>
                                <td><?= $vLegend ?></td>
                            </tr>
                            <?php
                        } ?>
                    </table>
                </div>

            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <?= $jml_residual_status; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4 text-center">
        <div class="row">
            <div class="col-md-12">
                <h6>Residual <?= ! empty( $jml_target ) ? $jml_target : 0; ?>
                </h6>
                <?= $map_target ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="alert alert-secondary">
                    <table>
                        <?php foreach( $legendMatrix as $keyLegend => $vLegend )
                        { ?>
                            <tr>
                                <td><?= $keyLegend ?></td>
                                <td><?= $vLegend ?></td>
                            </tr>
                            <?php
                        } ?>
                    </table>
                </div>

            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <?= $jml_target_status; ?>
            </div>
        </div>

    </div>
</div>