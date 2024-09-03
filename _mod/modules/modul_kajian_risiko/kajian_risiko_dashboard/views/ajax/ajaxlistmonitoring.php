<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 mb-3 border">
            <div class="card shadow-none border m-0">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-sm" border="1">
                                <thead class="bg-slate">
                                    <tr>
                                        <th>No</th>
                                        <th>Peristiwa Risiko</th>
                                        <th>Taksonomi BUMN</th>
                                        <th>Tipe Risiko</th>
                                        <th class="text-center">Inherent Risiko</th>
                                        <th class="text-center">Current Risiko</th>
                                        <th class="text-center">Mitigasi Risiko</th>
                                        <th class="text-center">Progress Update</th>
                                        <th class="text-center">Deadline</th>
                                        <th class="text-center">PIC</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if( ! empty( $data ) )
                                    {
                                        $StatusMap = [
                                            "on-progress" => "On Progress",
                                            "not-started" => "Not Started",
                                            "closed"      => "Closed",
                                           ];
                                        foreach( $data as $kmonitoring => $vmonitoring )
                                        { ?>
                                            <tr>
                                                <td><?= $kmonitoring + 1 ?></td>
                                                <td><?= $vmonitoring["library"] ?></td>
                                                <td><?= $vmonitoring["taksonomi_name"] ?></td>
                                                <td><?= $vmonitoring["tipe_risiko_name"] ?></td>
                                                <td class="text-center">
                                                    <div class="alert alert-sm border shadow-none m-0 p-1"
                                                        style="background-color:<?= $vmonitoring["inherent_level_color"] ?>;color:<?= $vmonitoring["inherent_text_level_color"] ?>">
                                                        <b><?= $vmonitoring["inherent_level_name"] ?></b>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="alert alert-sm border shadow-none m-0 p-1"
                                                        style="background-color:<?= $vmonitoring["current_level_color"] ?>;color:<?= $vmonitoring["current_text_level_color"] ?>">
                                                        <b><?= $vmonitoring["current_level_name"] ?></b>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?= $vmonitoring["mitigasi"] ?></td>
                                                <td class="text-center"><?= $vmonitoring["detail_progress"] ?></td>
                                                <td class="text-center">
                                                    <?= ! empty( $vmonitoring["deadline"] ) && $vmonitoring["deadline"] != "0000-00-00" ? date( "d-m-Y", strtotime( $vmonitoring["deadline"] ) ) : ""; ?>
                                                </td>
                                                <td class="">
                                                    <?php if( ! empty( $vmonitoring["pic"] ) ) : ?>
                                                        <?php foreach( $vmonitoring["pic"] as $kEachPic => $vEachPic ) : ?>
                                                            <?= ( $kEachPic + 1 ) ?><b><?= '. ' . $vEachPic["owner_name"]; ?></b><br>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= ( ! empty( $StatusMap[$vmonitoring["status"]] ) ) ? $StatusMap[$vmonitoring["status"]] : "" ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    { ?>
                                        <tr class="text-center bg-light">
                                            <td colspan="20"><strong>Data Empty</strong></td>
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