<div class='table-responsive'>
    <?=_l('fld_list_progres_mitigasi');?>
    <span class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right d-none" data-id="<?=$aktifitas_mitigas['id'];?>" id="add_progres" id="add_mitigasi"><b><i class="icon-file-plus "></i></b> <?=_l('fld_add_progres_mitigasi');?></span>
    <br/>&nbsp;<br/>&nbsp;
    <table class="table table-hover" id="tbl_list_mitigasi">
        <thead>
            <tr>
                <th>No</th>
                <th><?=_l('fld_target');?></th>
                <th><?=_l('fld_aktual');?></th>
                <th><?=_l('fld_uraian');?></th>
                <th >Bulan Progress</th>

                <th ><?=_l('fld_tgl_update');?></th>
                <th><?=_l('fld_kendala');?></th>
                <th width="15%" class="text-center  d-none">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=0;
            foreach($detail_progres as $row):?>
            <tr>
                <td><?=++$no;?></td>
                <td><?=$row['target'];?></td>
                <td><?=$row['aktual'];?></td>
                <td><?=$row['uraian'];?></td>
                <td><?=(isset($minggu[$row['minggu_id']]))?$minggu[$row['minggu_id']]:'belum ditentukan';?></td>

                <td><?=date('d-m-Y', strtotime($row['created_at']));?></td>
                <td><?=$row['kendala'];?></td>
                <td class="pointer text-center  d-none">
                    <i class="icon-database-edit2  text-primary-400 update-progres" data-mitigasi="<?=$aktifitas_mitigas['id'];?>" data-id="<?=$row['id'];?>" data-popup="tooltip" data-html="true" title=" Update Progres Mitigasi "></i> | 
                    <i class="icon-database-remove  text-danger-400 delete-progres" data-mitigasi="<?=$aktifitas_mitigas['id'];?>" data-id="<?=$row['id'];?>" data-popup="tooltip" data-html="true" title=" Hapus data Progres Mitigasi "></i> </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>