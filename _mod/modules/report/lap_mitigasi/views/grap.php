<div class="chart has-fixed-height" id="pie_basic"></div>

<?php
    $resultD=[];
    foreach($data as $key=>$row){
        $resultD[]=['value'=>$row['nilai'], 'name'=>$row['category'], 'type_chat'=>1, 'param_id'=>$key];
    }
    $x['data']=$resultD;

    $x['title']=[
                'text'=>'Chart Mitigasi',
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
    $x['warna']=[$this->_preference_['warna_mitigasi_selesai'],$this->_preference_['warna_mitigasi_belum_on_schedule'],$this->_preference_['warna_mitigasi_belum_terlambat'],$this->_preference_['warna_mitigasi_belum_dilaksanakan']];
    $hasil=json_encode($x);
?>
<script>
    var data=<?=$hasil;?>;
    grafik_pie(data, 'pie_basic');
</script>