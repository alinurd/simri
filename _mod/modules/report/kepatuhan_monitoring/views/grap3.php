<div class="chart has-fixed-height" id="pie_basic_2"></div>
<?php
    $resultD=[];
    $resultD[]=['value'=>$data['sudah'], 'name'=>'Tepat Waktu', 'type_chat'=>2, 'param_id'=>1];
    $resultD[]=['value'=>$data['belum'], 'name'=>'Terlambat', 'type_chat'=>2, 'param_id'=>0];
    foreach($data as $row){
    }
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
    $x['warna']=[$this->_preference_['warna_ketepatan_tepat'], $this->_preference_['warna_ketepatan_terlambat']];
    $hasil=json_encode($x);
?>
<script>
    var data=<?=$hasil;?>;
    grafik_pie(data, 'pie_basic_2');
</script>