
<?php
    $resultD=[];
    $resultD[]=['value'=>$data['110'], 'name'=>'Sebelum Due Date (110%)', 'type_chat'=>2, 'param_id'=>4];
    $resultD[]=['value'=>$data['100'], 'name'=>'On Schedule (100%)', 'type_chat'=>2, 'param_id'=>3];
    $resultD[]=['value'=>$data['90'], 'name'=>'H+1-2 Due Date (90%)', 'type_chat'=>2, 'param_id'=>2];
    $resultD[]=['value'=>$data['75'], 'name'=>'&#8805; H+3 Due Date (75%)', 'type_chat'=>2, 'param_id'=>1];
    $resultD[]=['value'=>$data['0'], 'name'=>'Tidak Menyampaikan (0%)', 'type_chat'=>2, 'param_id'=>0];

   
    $x['data']=$resultD;
    $x['title']=[
                'text'=>'Komitment Pelaksanaan Manajemen Risiko',
                'subtext'=>'Last updated: '.date('M d, Y, H:i'),
                'left'=>'center',
                'textStyle'=>[
                    'fontSize'=>17,
                    'fontWeight'=>500
                ],
                'subtextStyle'=> [
                    'fontSize'=>12
                ]
            ];
    $x['warna']=['#23890f', '#1460d1', '#7e57c2', '#ff0000', '#009688'];
    $hasil=json_encode($x);

?>
<div class="chart has-fixed-height" id="pie_basic_2"></div>

<script>
    var data=<?=$hasil;?>;
    grafik_pie(data, 'pie_basic_2');
</script>