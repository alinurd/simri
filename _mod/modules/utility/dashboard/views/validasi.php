<table class="table table-bordered table-hover datatable-highlight dataTable no-footer">
    <thead>
        <tr>
            <th Width="5%">No.</th>
            <th>Petugas</th>
            <th>No. Lap</th>
            <th>Nama Lahan</th>
            <th Width="15%">Tanggal</th>
            <th Width="10%">Detail</th>
            <th Width="10%">Download KML</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $no=0;
        foreach($cek_lapangan as $row):?>
        <tr>
            <td><?=++$no;?></td>
            <td><?=$row['petugas'];?></td>
            <td><?=$row['lap_no'];?></td>
            <td><?=$row['class_tutupan'];?></td>
            <td><small><?=time_ago($row['created_at']);?></small></td>
            <td class="text-center pointer"><a href="<?=base_url("cek-lapangan/edit/".$row['id']);?>"><i class="fa fa-search"></i></a></td>
            <td class="text-center pointer"><a href="<?=base_url("cek-lapangan/edit/".$row['id']);?>"><i class="fa fa-download"></i></a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><?=count($cek_lapangan).' dari '.$cek_lapangan_num;?></td>
            <td colspan="5" style="text-align:right;"><small><a href="<?=base_url("cek-lapangan");?>">Lihat seluruh data yang belum divalidasi</a></small></td>
        </tr>
    </tfoot>
</table>