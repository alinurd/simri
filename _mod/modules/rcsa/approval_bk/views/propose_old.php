<?=$info_parent;
$disabled='';
if ($lanjut || $sts_final):?>
    <div class="alert alert-primary alert-dismissible"><?=$hidden['ket'];?></div>
<?php else:
    $disabled=' disabled="disabled" ';?>
    <div class="alert alert-danger alert-dismissible"><?=$hidden['ket'];?></div>
<?php endif;?>

<?php
echo form_open_multipart(base_url(_MODULE_NAME_.'/simpan-propose/'.$id), array('id'=>'form_propose','class'=>'form-horizontal'), $hidden);
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="<?=base_url(_MODULE_NAME_);?>" class="btn bg-primary-400 btn-labeled btn-labeled-left legitRipple" ><b><i class="icon-list"></i></b> <?=_l('fld_back_risk_register');?></a>
                <span data-id="<?=$parent['id'];?>" class="btn bg-danger-400 btn-labeled btn-labeled-right legitRipple pull-right" id="revisi" style="margin-left:15px;"><b><i class="icon-stack-cancel"></i></b> <?=_l('fld_revisi_risk_register');?></span>&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" data-id="<?=$parent['id'];?>" class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="propose" <?=$disabled;?>><b><i class="icon-floppy-disk "></i></b> <?=_l('fld_proses_propose');?></button>&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <?=$note_propose;?>
            </div>
        </div>
    </div>
</div>
<?=$regis;?>

<div class="row d-none">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title"><?=_l('fld_title');?></h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th><?=_l('fld_note');?></th>
                            <th><?=_l('fld_aktifitas');?></th>
                            <th><?=_l('fld_sasaran_aktifitas');?></th>
                            <th><?=_l('fld_tahapan_proses');?></th>
                            <th><?=_l('fld_klasifikasi_risiko');?></th>
                            <th><?=_l('fld_tipe_risiko');?></th>
                            <th><?=_l('fld_penyebab_risiko');?></th>
                            <th><?=_l('fld_risiko_dept');?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no=0;
                    $alur=json_decode($hidden['alur'], true);
                    $note_arr=[];
                    foreach($alur as $key=>$row){
                        $note_arr[$row['level_approval_id']]=$row;
                    }

                    foreach($detail as $row):
                        $x = json_decode($row['note_approval'], true);
                        $cat='';
                        if ($x){
                            foreach($x as $key=>$y){
                                $note[$row['id'].'_'.$key]=$y['note'];
                                $cat.='<strong>'.$y['name'] . '</strong> : <br/><em>'.$y['note'].'</em><br/><br/>';
                            }
                        }else{
                            foreach($note_arr as $key=>$y){
                                $note[$row['id'].'_'.$key]='-';
                            }
                        }

                        $hide = form_hidden(['rcsa_detail_id[]'=>$row['id']]);
                        $hide1 = form_hidden($note);
                        ?>
                        <tr>
                            <td><?=++$no.$hide.$hide1;?></td>
                            <td class="text-center pointer notes note-<?=$row['id'];?>" data-id="<?=$row['id'];?>" data-owner="<?=$poin_start['level_approval_id'];?>" data-popup="popover" title="" data-html="true" data-trigger="hover" data-content="<?=$cat;?>" data-original-title="Catatan :" data-placement="left"><i class="icon-notebook"></i></td>
                            <td><?=$row['aktifitas'];?></td>
                            <td><?=$row['sasaran'];?></td>
                            <td><?=$row['tahapan'];?></td>
                            <td><?=$row['klasifikasi_risiko'];?></td>
                            <td><?=$row['tipe_risiko'];?></td>
                            <td><?=$row['penyebab_risiko'];?></td>
                            <td><?=$row['risiko_dept'];?></td>
                        </tr>
                    <?php endforeach;
                    ?>
                </table>
                <!-- <hr> -->
                <br/>&nbsp;
                <strong>PETA RISIKO<br/></strong>
                <ul class="nav nav-tabs nav-tabs-top">
                    <li class="nav-item">
                        <a href="#content-tab-00" class="nav-link active show" data-toggle="tab">Inheren</a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-01" class="nav-link " data-toggle="tab">Residual</a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-03" class="nav-link " data-toggle="tab">Target</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="content-tab-00"><?=$map_inherent;?></div>
                    <div class="tab-pane fade" id="content-tab-01"><?=$map_residual;?></div>
                    <div class="tab-pane fade" id="content-tab-01"><?=$map_target;?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?=$info_alur;?>
<?php echo form_close();?>
<div class="text-center d-none" id="propose">
    <a href="<?=base_url(_MODULE_NAME_.'/proses-propose/'.$parent['id']);?>" class="btn btn-primary pointer"> Propose Data </a>
</div>