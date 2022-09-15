    


<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th>Kategori Mitigasi</th>
            <th width="15%">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no=0;

        foreach($data as $key=>$row):
            $warna=$this->_preference_['warna_mitigasi_belum_dilaksanakan'];
            if ($key==1){
                $warna=$this->_preference_['warna_mitigasi_selesai'];
            }elseif ($key==2){
                $warna=$this->_preference_['warna_mitigasi_belum_on_schedule'];
            }elseif ($key==3){
                $warna=$this->_preference_['warna_mitigasi_belum_terlambat'];
            }elseif ($key==4){
                $warna=$this->_preference_['warna_mitigasi_belum_dilaksanakan'];
            }
        ?>
        <tr>
            <td><?=++$no;?></td>
            <td><?=$row['category'];?></td>
            <td class="text-center"><span class="badge badge-pill" style="background-color:<?=$warna;?>;color:#ffffff;padding:8px 10px;"><?=$row['nilai'];?></span></td>
        </tr><?php endforeach;?>
    </tbody>

</table>