<legend class="text-uppercase font-size-lg text-primary font-weight-bold"><i class="icon-grid"></i> <?=$title;?></legend>
<table class="table table-hover table-bordered">
    <thead>
        <tr class="bg-primary-300">
            <th width="5%">No.</th>
            <th>Departemen</th>
            <th>Periode</th>
            <th>Term</th>
            <th>Risiko Dept.</th>
            <th>Klasifikasi</th>
        </tr>
    </thead>
    <tboy>
    <?php
        $no=0;
        foreach($data as $row):?>
        <tr class="pointer detail-rcsa" data-id="<?=$row['id'];?>">
            <td><?=++$no;?></td>
            <td><?=$row['owner_name'];?></td>
            <td><?=$row['period_name'];?></td>
            <td><?=$row['term'];?></td>
            <td><?=$row['risiko_dept'];?></td>
            <td><?=$row['klasifikasi_risiko'].' | '.$row['tipe_risiko'];?></td>
        </tr>
        <?php endforeach; ?>
    </tboy>
</table>