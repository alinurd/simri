<div class="chart has-fixed-height" id="pie_basic_3"></div>
<?php
    $resultD=[];
    $resultD[]=['value'=>$data['data'][2]['nilai'], 'name'=>$data['data'][2]['category'], 'type_chat'=>3, 'param_id'=>2];
    $resultD[]=['value'=>$data['data'][1]['nilai'], 'name'=>$data['data'][1]['category'], 'type_chat'=>3, 'param_id'=>1];
    $resultD[]=['value'=>$data['data'][0]['nilai'], 'name'=>$data['data'][0]['category'], 'type_chat'=>3, 'param_id'=>0];
    // foreach($data as $row){
    // }
    $x['data']=$resultD;

    $x['title']=[
                'text'=>'Komitmen Pelaksanaan Manajemen Risiko',
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
    $x['warna']=[$this->_preference_['warna_komitmen_lengkap'], $this->_preference_['warna_komitmen_tidak_lengkap'], $this->_preference_['warna_komitmen_tidak_dibicarakan']];
    $hasil=json_encode($x);
?>
<script>
    var data=<?=$hasil;?>;
    grafik_pie(data, 'pie_basic_3');
</script>