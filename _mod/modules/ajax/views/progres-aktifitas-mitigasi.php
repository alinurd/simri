<br/><br/><legend class="text-uppercase font-size-lg text-slate font-weight-bold"><i class="icon-grid"></i> DETAIL MITIGASI</legend>
<div class="row alpha-slate">
    <div class="col-xl-6">
        <table class="table table-borderless">
            <tr><td width="30%"><em><?=_l('fld_aktifitas_mitigasi');?></em></td><td><strong><?=$parent['aktifitas_mitigasi'];?></strong></td></tr>
            <tr><td><em><?=_l('fld_pic');?></em></td><td><strong><?=$parent['penanggung_jawab_detail'];?></strong></td></tr>
            <tr><td><em><?=_l('fld_koordinator');?></em></td><td><strong><?=$parent['koordinator_detail'];?></strong></td></tr>
            <tr><td><em><?=_l('fld_due_date');?></em></td><td><strong><?=$parent['batas_waktu_detail'];?></strong></td></tr>
        </table>
    </div>
</div>
<br/>
<strong>LIST AKTIFITAS MITIGAS</strong><br/>
<table class="table table-hover" id="tbl_list_mitigasi">
    <thead>
    <tr class="bg-slate-300">
            <th>No</th>
            <th><?=_l('fld_target');?></th>
            <th><?=_l('fld_aktual');?></th>
            <th><?=_l('fld_uraian');?></th>
            <th ><?=_l('fld_tgl_update');?></th>
            <th><?=_l('fld_kendala');?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no=0;
        foreach($progres as $row):?>
        <tr>
            <td><?=++$no;?></td>
            <td><?=$row['target'];?></td>
            <td><?=$row['aktual'];?></td>
            <td><?=$row['uraian'];?></td>
            <td><?=date('d-m-Y', strtotime($row['created_at']));?></td>
            <td><?=$row['kendala'];?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>