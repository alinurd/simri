<style>
    tr.show {
        display: table-row !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 border">
            <div class="card shadow-none border m-0">
                <div class="card-body p-2" id="content-monitoring">
                    <div class="row border-bottom">
                        <div class="col-md-12">
                            <h6>Daftar Risiko</h6>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div id="setTableMonitoring">
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-slate">
                                        <tr>
                                            <th>No</th>
                                            <th class="text-center">Peristiwa Risiko</th>
                                            <th class="text-center">Taksonomi BUMN</th>
                                            <th class="text-center">Tipe Risiko</th>
                                            <th class="text-center">Inherent Risk Level</th>
                                            <th class="text-center">Residual Risk Level</th>
                                            <th class="text-center">Mitigasi</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if( ! empty( $register ) )
                                        {
                                            foreach( $register as $kReg => $vReg )
                                            { ?>
                                                <tr>
                                                    <td><?= $kReg + 1 ?></td>
                                                    <td><?= $vReg["library"] ?></td>
                                                    <td><?= $vReg["taksonomi_name"] ?></td>
                                                    <td><?= $vReg["tipe_risiko_name"] ?></td>
                                                    <td><div class="alert alert-sm border shadow-none m-0 p-1 text-center"
                                                            style="background-color:<?= $vReg["inherent_level_color"] ?>;color:<?= $vReg["inherent_text_level_color"] ?>">
                                                            <b><?= $vReg["inherent_level_name"] ?></b>
                                                        </div>  </td>
                                                    <td> <div class="alert alert-sm border shadow-none m-0 p-1 text-center"
                                                style="background-color:<?= $vReg["residual_level_color"] ?>;color:<?= $vReg["residual_text_level_color"] ?>">
                                                <b><?= $vReg["residual_level_name"] ?></b>
                                            </div>    </td>
                                                    <td class="text-center">
                                                        <a href="#" data-toggle="collapse"
                                                            data-target="#row<?= $vReg["id"] ?>" aria-expanded="false"
                                                            aria-controls="row<?= $vReg["id"] ?>"
                                                            class="btn bg-success btn-sm">
                                                            <b><i class="icon-arrow-down16"></i></b>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" id="btn-reg-edit" data-id="<?= $vReg["id"] ?>"
                                                            data-url="<?= $btnEdit ?>"
                                                            class="btn btn-labeled button-action bg-primary btn-sm"><i
                                                                class="icon-pencil"></i></button>
                                                    </td>
                                                </tr>
                                                <tr class="collapse.show bg-light" id="row<?= $vReg["id"] ?>">
                                                    <td colspan="8" class="p-2 ">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card shadow-none border m-0">
                                                                    <div class="card-body p-2">
                                                                        <div id="setTableMonitoring">
                                                                            <table class="table table-sm table-bordered">
                                                                                <thead class="bg-success">
                                                                                    <tr>
                                                                                        <th>Mitigasi Risiko</th>
                                                                                        <th>PIC</th>
                                                                                        <th class="text-center">Deadline</th>
                                                                                        <th class="text-center">Add Progress</th>
                                                                                        <th class="text-center">Detail Progress</th>
                                                                                        <th class="text-center">Tanggal Update</th>
                                                                                        <th class="text-center">Dokumen</th>
                                                                                        <th class="text-center">Status</th>
                                                                                        <th class="text-center">Action Monitoring
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php if (!empty( $vReg["mitigasi"])) {
                                                                                $n=0;
                                                                                foreach ($vReg["mitigasi"] as $kMit => $vMit) {?>
                                                                                    <?php if( ! empty( $vReg["monitoring"] ) )
                                                                                    {
                                                                                        $spanCount = count( $vReg["monitoring"][$vMit['id']] );
                                                                                        
                                                                                        foreach( $vReg["monitoring"][$vMit['id']] as $kMonitoring => $vMonitoring )
                                                                                        {?>
                                                                                            <tr>
                                                                                                <?php if( $n == 0 )
                                                                                                { ?>
                                                                                                    <td rowspan="<?= $spanCount; ?>">
                                                                                                        <?= $vMonitoring["mitigasi"]; ?>
                                                                                                    </td>
                                                                                                    <td rowspan="<?= $spanCount; ?>">
                                                                                                    <?php if (!empty($vMonitoring["pic"])):?>
                                                                                                            <?php foreach ($vMonitoring["pic"] as $kEachPic => $vEachPic):?>
                                                                                                                <?=($kEachPic+1)?><b><?='. '.$vEachPic["owner_name"];?></b><br>
                                                                                                                <?php endforeach; ?>
                                                                                                        <?php endif;?>
                                                                                                    </td>
                                                                                                    <td rowspan="<?= $spanCount; ?>" class="text-center">
                                                                                                        <?= !empty($vMonitoring["deadline"])&& $vMonitoring["deadline"] != "0000-00-00"?date("d-m-Y",strtotime($vMonitoring["deadline"])):""; ?>
                                                                                                    </td>
                                                                                                    <td rowspan="<?= $spanCount; ?>" class="text-center">
                                                                                                    <button type="button" id="add-monitoring"
                                                                                                            data-url="<?= $btnAdd ?>"
                                                                                                            data-kajian-id="<?= $vReg['id_kajian_risiko'] ?>"
                                                                                                            data-mitigasi-id="<?=$vMit['id']?>" class="btn btn-labeled button-action bg-success btn-sm add-monitoring"><i
                                                                                                            class="icon-plus-circle2"></i>
                                                                                                        </button>
                                                                                                    </td>
                                                                                                <?php } ?>
                                                                                                <td><?= $vMonitoring['detail_progress'] ?>
                                                                                                </td>
                                                                                                <td class="text-center"><?= (!empty($vMonitoring['tanggal_update'])&& $vMonitoring["tanggal_update"] != "0000-00-00")?date("Y-m-d",strtotime($vMonitoring['tanggal_update'])):"" ?>
                                                                                                </td>
                                                                                                <td class="text-center">
                                                                                                  <?php if (!empty($vMonitoring["dokumen_pendukung"])&&file_exists("./files/kajian_risiko_monitoring/{$vMonitoring["dokumen_pendukung"]}")) :?>
                                                                                                    <a href="<?=base_url("files/kajian_risiko_monitoring/{$vMonitoring["dokumen_pendukung"]}")?>" target="_blank"><i class="icon-file-text"></i></a>
                                                                                                    <?php endif;?>
                                                                                                </td>
                                                                                                <td class="text-center"><?= $vMonitoring['status'] ?></td>
                                                                                                <td class="text-center">
                                                                                                    <?php if (!empty($vMonitoring["id_monitoring"])):?>
                                                                                                    <button type="button"
                                                                                                        id="btn-edit-monitoring"
                                                                                                        data-id="<?= $vMonitoring["id_monitoring"] ?>"
                                                                                                        data-url="<?= $btneditMonitoring ?>"
                                                                                                        data-kajian-id="<?= $vReg['id_kajian_risiko'] ?>"
                                                                                                        class="btn btn-labeled button-action bg-primary btn-sm"><i
                                                                                                            class="icon-pencil"></i>
                                                                                                    </button>
                                                                                                    <?php endif;?>
                                                                                                    <?php if (!empty($vMonitoring["id_monitoring"])):?>
                                                                                                    <button type="button"
                                                                                                        id="btn-delete-monitoring"
                                                                                                        data-kajian-id="<?= $vReg['id_kajian_risiko'] ?>"
                                                                                                        data-id="<?= $vMonitoring["id_monitoring"] ?>"
                                                                                                        data-url="<?= $btnDelete ?>"
                                                                                                        class="btn btn-labeled button-action bg-danger delete btn-sm"><i
                                                                                                            class="icon-bin"></i>
                                                                                                        </button>
                                                                                                        <?php endif;?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php

                                                                                          if ($kMonitoring==$spanCount) {  
                                                                                            }else{
                                                                                                $n++;
                                                                                            }
                                                                                        }
                                                                                        $n=0;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        ?>
                                                                                        <tr class="text-center">
                                                                                            <td colspan="7"><strong>Data Empty</strong></td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                    <?php 
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }else{?>
                                                                                <tr class="text-center">
                                                                                            <td colspan="8"><strong>Data Empty</strong></td>
                                                                                        </tr>
                                        <?php 
                                        }
                                        ?>

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