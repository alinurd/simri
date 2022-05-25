<?php 
    $r[]=['value'=>$data['110'], 'name'=>'Sebelum Due Date (110%)', 'type_chat'=>$data['110%']];
    $r[]=['value'=>$data['100'], 'name'=>'On Schedule (100%)', 'type_chat'=>$data['100%']];
    $r[]=['value'=>$data['90'], 'name'=>'H+1-2 Due Date (90%)', 'type_chat'=>$data['90%']];
    $r[]=['value'=>$data['75'], 'name'=>'&#8805; H+3 Due Date (75%)', 'type_chat'=>$data['75%']];
    $r[]=['value'=>$data['0'], 'name'=>'Tidak Menyampaikan (0%)', 'type_chat'=>$data['0%']];

    $warna=['#23890f', '#1460d1', '#7e57c2', '#ff0000', '#009688'];

?>

<?php foreach($warna as $key => $value):?>
    <span class="badge text-white " style="background: <?=$value?>;margin-right: 15%;"> 
        <?= $r[$key]['name']?> : <?=$r[$key]['value'];?> [ <?=$r[$key]['type_chat'];?>% ]
    </span>
<?php endforeach;?>
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

            if ($row['nilai']=="110%") {
				$bg = $warna[0];
			}elseif($row['nilai']=="100%"){
				$bg = $warna[1];
			}elseif($row['nilai']=="90%"){
				$bg = $warna[2];
			}elseif($row['nilai']=="75%"){
				$bg = $warna[3];
			}elseif($row['nilai']=="0%"){
				$bg = $warna[4];
			}
        ?>
        <tr class="pointer" style="font-weight:bold;color:<?=$bg?>">
            <td><?=++$no;?></td>
            <td class="text-center"><?=$row['owner_code'];?></td>
            <td><?=$row['owner_name'];?></td>
            <td class="text-center"><?=$row['nilai'];?></td>
        </tr><?php endforeach;?>
    </tbody>

</table>
