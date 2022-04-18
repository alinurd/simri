<?php
    $no_edit='';
    $events='auto';
    $no_edit_hide='';
    if(intval($parent['status_id'])>0){
        $no_edit_hide=' d-none ';
        $no_edit=' disabled="disabled" ';
        $events='none';
    }
?>
<div id="parent_risk">
    <?=$info_parent;?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <a href="<?=base_url(_MODULE_NAME_);?>" class="btn bg-success-300 btn-labeled btn-labeled-left legitRipple" ><b><i class="icon-list"></i></b> <?=_l('fld_list_progres_mitigasi');?></a>
                    <span  data-id="<?=$parent['id'];?>" class="btn bg-warning-400 btn-labeled btn-labeled-right legitRipple pull-right risk-register"  id="risk_register" style="margin-left:20px;"><b><i class="icon-file-presentation "></i></b> <?=_l('fld_risk_register');?></span>
                    <span  data-id="<?=$parent['id'];?>" class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right propose-mitigasi"  id="propose_mitigasi" style="margin-left:20px;"><b><i class="icon-file-presentation "></i></b> <?=_l('fld_propose_mitigasi');?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th>Risiko Dept.</th>
                                <th>Mitigasi</th>
                                <th>Aktifitas Mitigasi</th>
                                <th>Tgl Propose</th>
                                <th>Batas Waktu</th>
                                <th>Target</th>
                                <th>Aktual</th>
                                <th>Last Update</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tboy>
                        <?php
                            $no=0;
                            foreach($detail as $row):?>
                           <tr>
                                <td><?=++$no;?></td>
                                <td><?=$row['risiko_dept'];?></td>
                                <td><?=$row['mitigasi'];?></td>
                                <td><?=$row['aktifitas_mitigasi'];?></td>
                                <td><?=$row['tgl_propose'];?></td>
                                <td><?=date('d-M-Y', strtotime($row['batas_waktu_detail']));?></td>
                                <td class="text-center"><?=$row['target'];?>%</td>
                                <td class="text-center"><?=$row['aktual'];?>%</td>
                                <td><?=$row['updated_at'];?></td>
                                <td class="pointer text-center"><a href="<?=base_url(_MODULE_NAME_.'/update-progres/'.$row['id']);?>"><i class="icon-database-edit2  text-primary-400"></i></td>
                            </tr>
                            <?php endforeach; ?>
                        </tboy>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>