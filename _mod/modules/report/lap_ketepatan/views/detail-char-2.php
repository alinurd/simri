<?php
if (!$mode):?>
<a class="btn btn-primary" href="<?=base_url(_MODULE_NAME_.'/cetak');?>" target="_blank"><i class="icon-file-excel"> Ms-Excel </i></a>
<?php endif;?>
<table class="table table-bordered table-striped table-hover" border="1">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th>Kode</th>
            <th>Dir/Dept/Proyek</th>
            <th>Tanggal Pelaporan</th>
            <th width="8%">Target</th>
            <th width="8%">Aktual</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no=0;
        foreach($data as $key=>$row):?>
        <tr>
            <td><?=++$no;?></td>
            <td><?=$row['owner_code'];?></td>
            <td><?=$row['owner_name'];?></td>
            <td><?=$row['tgl_propose'];?></td>
            <td class="text-center"><?=number_format($row['target'],2);?></td>
            <td class="text-center"><?=number_format($row['aktual'],2);?></td>
        </tr><?php endforeach;?>
    </tbody>

</table>