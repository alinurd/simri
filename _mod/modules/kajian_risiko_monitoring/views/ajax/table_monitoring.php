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
                                                    <td>
                                                    <div class="alert alert-sm border shadow-none m-0 text-center"
                                                            style="background-color:<?= $vReg["inherent_level_color"] ?>;color:<?= $vReg["inherent_text_level_color"] ?>">
                                                            <b><?= $vReg["inherent_level_name"] ?></b>
                                                        </div>     
                                                   </td>
                                                    <td>
                                                    <div class="alert alert-sm border shadow-none m-0 text-center"
                                                style="background-color:<?= $vReg["residual_level_color"] ?>;color:<?= $vReg["residual_text_level_color"] ?>">
                                                <b><?= $vReg["residual_level_name"] ?></b>
                                            </div>    
                                                   </td>
                                                    <td class="text-center">
                                                        <button type="button" data-toggle="collapse"
                                                            data-target="#row<?= $vReg["id"] ?>" aria-expanded="false"
                                                            aria-controls="row<?= $vReg["id"] ?>"
                                                            class="btn bg-success btn-labeled btn-labeled-right button-action clickable btn-sm">
                                                            <b><i class="icon-arrow-down16"></i></b> Mitigasi
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" id="btn-reg-edit" data-id="<?= $vReg["id"] ?>"
                                                            data-url="<?= $btnEdit ?>"
                                                            class="btn btn-labeled button-action bg-primary btn-sm"><i
                                                                class="icon-pencil"></i></button>
                                                    </td>
                                                </tr>
                                                <tr class="collapse.show bg-light" id="row<?= $vReg["id"] ?>">
                                                    <td colspan="8" class="p-3 ">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card shadow-sm border m-0">
                                                                    <div class="card-body">
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
                                                                                        { ?>
                                                                                            <tr>
                                                                                                <?php if( $n == 0 )
                                                                                                { ?>
                                                                                                    <td rowspan="<?= $spanCount; ?>">
                                                                                                        <?= $vMonitoring["mitigasi"]; ?>
                                                                                                    </td>
                                                                                                    <td rowspan="<?= $spanCount; ?>">
                                                                                                        <?= $vMonitoring["pic"]; ?>
                                                                                                    </td>
                                                                                                    <td rowspan="<?= $spanCount; ?>" class="text-center">
                                                                                                        <?= $vMonitoring["deadline"]; ?>
                                                                                                    </td>
                                                                                                    <td rowspan="<?= $spanCount; ?>" class="text-center">
                                                                                                    <button type="button" id="add-monitoring"
                                                                                                            data-url="<?= $btnAdd ?>"
                                                                                                            data-kajian-id="<?= $vReg['id_kajian_risiko'] ?>"
                                                                                                            data-mitigasi-id="<?=$vMonitoring['id_mitigasi']?>" class="btn btn-labeled button-action bg-success btn-sm add-monitoring"><i
                                                                                                            class="icon-plus-circle2"></i>
                                                                                                        </button>
                                                                                                    </td>
                                                                                                <?php } ?>
                                                                                                <td><?= $vMonitoring['detail_progress'] ?>
                                                                                                </td>
                                                                                                <td class="text-center"><?= (!empty($vMonitoring['tanggal_update']))?date("Y-m-d",strtotime($vMonitoring['tanggal_update'])):"" ?>
                                                                                                </td>
                                                                                                <td class="text-center">
                                                                                                  <?php if (!empty($vMonitoring["dokumen_pendukung"])&&file_exists("./files/kajian_risiko_monitoring/{$vMonitoring["dokumen_pendukung"]}")) :?>
                                                                                                    <a href="<?=base_url("files/kajian_risiko_monitoring/{$vMonitoring["dokumen_pendukung"]}")?>" target="_blank"><i class="icon-file-text"></i></a>
                                                                                                    <?php endif;?>
                                                                                                </td>
                                                                                                <td><?= $vMonitoring['status'] ?></td>
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
                                                                                            <td colspan="7">Data Is Empty</td>
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
                                        }
                                        ?>

                                    </tbody>
                                </table>