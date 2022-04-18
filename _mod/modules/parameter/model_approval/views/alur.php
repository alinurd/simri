<table class="table table-hover table-striped table-sm" id="tbl_alur">
    <thead>
        <tr>
            <th width="5%">Sort</th>
            <th width="5%">No.</th>
            <th>Level Approval</th>
            <th width="10%">Type</th>
            <th width="10%">Level</th>
            <th width="10%">Access Monitoring</th>
            <th width="10%">Send Notif Email</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $no=0;
        foreach($data as $row):
        $x=json_decode($row['param_text'], true);
        $edit_id = form_hidden('edit_id[]', $row['id']);
        $cbotype = form_dropdown('type_id[]', [0=>'Up Level', 1=>'Free Level'], intval($x['tipe_approval']), 'class="form-control select2" style="width:100% !important"');
        $cbolevel = form_dropdown('level_id[]', [1=>'Mengetahui', 2=>'Menyetujui', 3=>'Memvalidasi'], intval($x['level_approval']), 'class="form-control select2" style="width:100% !important"');
        $cbomonit = form_dropdown('sts_monit[]', [0=>'Tidak', 1=>'Ya'], intval($x['monit']), 'class="form-control select2" style="width:100% !important"');
        $cbonotif = form_dropdown('sts_notif[]', [0=>'Tidak', 1=>'Ya'], intval($x['notif_email']), 'class="form-control select2" style="width:100% !important"');
		
        ?>
        <tr>
            <td><i class="pointer text-danger icon-square-up" title=" Pindah posisi Keatas "></i><i class="pointer text-primary icon-square-down" title=" Pindah posisi Kebawah "></i> </td>
            <td><strong><?=++$no.$edit_id;?></strong></td>
            <td class="text-right"><?=form_dropdown('alur[]', $combo,$row['param_int'],'class="form-control"');?></td>
            <td><?=$cbotype;?></td>
            <td><?=$cbolevel;?></td>
            <td><?=$cbomonit;?></td>
            <td><?=$cbonotif;?></td>
            <td class="text-center">
                <i class="icon-file-plus pointer text-primary add-product-satu" title=" tambah data  "></i> | 
                <span class="text-danger" nilai="0" style="cursor:pointer;" onclick="remove_install(this,<?=$row['id'];?>,'combo')">
                    <i class="fa fa-cut" title="menghapus data"></i>
                </span>
            </td>
    <?php endforeach;?>
    </tbody>
</table>
<hr><span class="btn btn-info float-right pointer" id="add_alur"> Add Alur </span><br/>

<?php
    $edit = form_hidden('edit_id[]', 0);
    $alur = form_dropdown('alur[]', $combo, '',' class="form-control" style="width:100%;"');
    $type = form_dropdown('type_id[]', [0=>'Up Level', 1=>'Free Level'], 0,'class="form-control" style="width:100% !important"');
    $level = form_dropdown('level_id[]', [1=>'Mengetahui', 2=>'Menyetujui', 3=>'Memvalidasi'], 0,'class="form-control" style="width:100% !important"');
    $monit = form_dropdown('sts_monit[]', [0=>'Tidak', 1=>'Ya'], 0, 'class="form-control select2" style="width:100% !important"');
    $notif = form_dropdown('sts_notif[]', [0=>'Tidak', 1=>'Ya'], 0, 'class="form-control select2" style="width:100% !important"');
?>
<script type="text/javascript">
    var edit='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$edit));?>';
    var alur='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$alur));?>';
    var type='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$type));?>';
    var level='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$level));?>';
    var monit='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$monit));?>';
    var notif_email='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$notif));?>';
</script>