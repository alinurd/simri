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
            $text='text-primary';
            $font='normal';
            $warna=$this->_preference_['warna_komitmen_tidak_dibicarakan'];
            if (intval($row['status'])==2){
                $warna=$this->_preference_['warna_komitmen_lengkap'];
            }elseif (intval($row['status'])==1){
                $warna=$this->_preference_['warna_komitmen_tidak_lengkap'];
            }

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
            <td class="text-center"><span class="badge badge-pill" style="background-color:<?=$warna;?>;color:#ffffff;padding:8px 10px;"><?=$row['status'];?></span></td>
        </tr><?php endforeach;?>
    </tbody>

</table>