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
                    <th>No</th>
                    <th>Risiko</th>
                    <th>Taksonomi BUMN</th>
                    <th>Tipe Risiko</th>
                    <th class="text-center">Inherent Risiko</th>
                    <th class="text-center">Residual Risiko</th>
                    <th class="text-center">Mitigasi Risiko</th>
                    <th class="text-center">Progress Update</th>
                    <th class="text-center">Deadline</th>
                    <th class="text-center">PIC</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if( ! empty( $monitoring ) )
                {
                    $StatusMap = [
                        "on-progress" => "On Progress",
                        "not-started" => "Not Started",
                        "closed"      => "Closed",
                       ];
                    foreach( $monitoring as $kmonitoring => $vmonitoring )
                    {
                        ?>
                        <tr>
                            <td><?= $kmonitoring + 1 ?></td>
                            <td><?= $vmonitoring["risiko"] ?></td>
                            <td><?= $vmonitoring["taksonomi"] ?></td>
                            <td><?= $vmonitoring["tipe_risiko"] ?></td>
                            <td class="text-center"><?= $vmonitoring["inherent_risk_level"] ?></td>
                            <td class="text-center"><?= $vmonitoring["residual_risk_level"] ?></td>
                            <td class="text-center"><?= $vmonitoring["mitigasi"] ?></td>
                            <td class="text-center"><?= $vmonitoring["detail_progress"] ?></td>
                            <td class="text-center"><?= $vmonitoring["deadline"] ?></td>
                            <td class="text-center"><?= $vmonitoring["pic"] ?></td>
                            <td class="text-center"><?= $StatusMap[$vmonitoring["status"]] ?></td>
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