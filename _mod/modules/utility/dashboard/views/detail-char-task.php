<?php
if (!$mode):?>
<a class="btn btn-primary" href="<?=base_url(_MODULE_NAME_.'/cetak-task');?>" target="_blank"><i class="icon-file-excel"> Ms-Excel </i></a>
<br><br>
<?php endif;?>
<table class="table table-bordered table-striped table-hover datatable" id="datatable" border="1">
    <thead>
        <tr class="text-center">
            <th width="5%">No.</th>
            <th>Tahun</th>
            <th>Dir/Dept/Proyek</th>
            <th>Risiko Dept</th> 
            <th>Taksonomi </th> 
            <th>Tipe Risiko</th> 
        </tr>
    </thead>
    <tbody>
        <?php 
        $no=0;
        foreach($data as $key=>$row):
            $p= $this->db->select('data')->where('id', $row['period_id'])->get('il_combo')->row_array();
        ?>
        <tr>
            <td class="text-center"><?=++$no;?></td>
            <td class="text-center"><?= $p['data'];?></td>
            <td><?=$row['owner_name'];?></td>
            <td><?=$row['risiko_dept'];?></td>
            <td><?=$row['tasktonomi'];?></td>
            <td><?=$row['risk_type'];?></td> 
        </tr><?php endforeach;?>
    </tbody>
</table>