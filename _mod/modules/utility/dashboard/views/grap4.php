<span class="badge bg-blue"> Tepat Waktu : <?=$data['sudah'];?> [ <?=$data['sudah_persen'];?> ]</span>&nbsp;&nbsp;&nbsp;&nbsp;
<span class="badge bg-danger pull-right"> Terlambat : <?=$data['belum'];?> [ <?=$data['belum_persen'];?> ]</span><br/><br/>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th width="15%">Kode</th>
            <th>Departement</th>
            <th width="15%">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no=0;
        foreach($data['owner'] as $row):
            $icon='<i class="icon-stack-cancel text-danger">';
            $text='text-danger';
            $font='normal';
            if(intval($row['status'])){
                $icon='<i class="icon-stack-check text-primary">';
                $text='text-primary';
                $font='bold';
            }
        ?>
        <tr class="<?=$text;?> pointer detail-peta" data-level="9" data-id="<?=$row['id']?>" style="font-weight:<?=$font;?>">
            <td><?=++$no;?></td>
            <td class="text-center"><?=$row['owner_code'];?></td>
            <td><?=$row['owner_name'];?></td>
            <td class="text-center"><?=$icon;?></td>
        </tr><?php endforeach;?>
    </tbody>

</table>