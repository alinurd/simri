<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 border">
            <div class="card shadow-none border m-0">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm" border="1">
                                <thead class="bg-slate">
                                    <tr>
                                        <th rowspan="2" class="text-center">No</th>
                                        <th rowspan="2" class="text-center">Peristiwa Risiko</th>
                                        <th rowspan="2" class="text-center">Taksonomi BUMN</th>
                                        <th rowspan="2" class="text-center">Tipe Risiko</th>
                                        <th colspan="3" class="text-center">Current Risk Level</th>
                                        <th colspan="3" class="text-center">Residual Risk Level</th>
                                        <th rowspan="2">Mitigasi Risiko</th>
                                        <th rowspan="2">PIC</th>
                                        <th rowspan="2" class="text-center">Deadline</th>
                                        <th rowspan="2" class="text-center">Detail Progress</th>
                                        <th rowspan="2" class="text-center">Tanggal Update</th>
                                        <th rowspan="2" class="text-center">Status</th>

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
                                    <?php if( ! empty( $register ) )
                                    {
                                        $StatusMap = [
                                            "on-progress" => "On Progress",
                                            "not-started" => "Not Started",
                                            "closed"      => "Closed",
                                           ];
                                        foreach( $register as $kRegister => $vRegister )
                                        {
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $kRegister + 1 ?></td>
                                                <td><?= $vRegister["library"] ?></td>
                                                <td><?= $vRegister["taksonomi_name"] ?></td>
                                                <td><?= $vRegister["tipe_risiko_name"] ?></td>

                                                <td class="text-center"><?= $vRegister["residual_level_likelihood_text"] ?></td>
                                                <td class="text-center"><?= $vRegister["residual_level_impact_text"] ?></td>
                                                <td class="text-center p-1">
                                                    <div class="alert alert-sm border shadow-none m-0 p-1"
                                                        style="background-color:<?= $vRegister["residual_level_color"] ?>;color:<?= $vRegister["residual_text_level_color"] ?>">
                                                        <b><?= $vRegister["residual_level_name"] ?></b>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?= $vRegister["current_level_likelihood_text"] ?></td>
                                                <td class="text-center"><?= $vRegister["current_level_impact_text"] ?></td>
                                                <td class="text-center  p-1">
                                                    <div class="alert alert-sm border shadow-none m-0 p-1"
                                                        style="background-color:<?= $vRegister["current_level_color"] ?>;color:<?= $vRegister["current_text_level_color"] ?>">
                                                        <b><?= $vRegister["current_level_name"] ?></b>
                                                    </div>
                                                </td>
                                                <td><?= $vRegister["mitigasi"] ?></td>
                                                <td>
                                                    <?php if( ! empty( $vRegister["pic"] ) ) : ?>
                                                        <?php foreach( $vRegister["pic"] as $kEachPic => $vEachPic ) : ?>
                                                            <?= ( $kEachPic + 1 ) ?><b><?= '. ' . $vEachPic["owner_name"]; ?></b><br>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ! empty( $vRegister["deadline"] ) && $vRegister["deadline"] != "0000-00-00 00:00:00" ? date( "d-m-Y", strtotime( $vRegister["deadline"] ) ) : ""; ?>
                                                </td>
                                                <td><?= $vRegister['detail_progress'] ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ( ! empty( $vRegister['tanggal_update'] ) && $vRegister["tanggal_update"] != "0000-00-00" ) ? date( "Y-m-d", strtotime( $vRegister['tanggal_update'] ) ) : "" ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ( ! empty( $StatusMap[$vRegister['status']] ) ) ? $StatusMap[$vRegister["status"]] : "" ?>
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