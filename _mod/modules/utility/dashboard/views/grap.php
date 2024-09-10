<div class="chart has-fixed-height" id="pie_basic"></div>
<?php 
$resultD   = [];
foreach($tasktonomi as $p){
    $resultD[] = [ 'value' => $p['id'], 'name' => $p['data'], 'type_chat' => 2, 'param_id' => 4, 'id'=>$p['id'] ];
    $x['warna'][] = $p['param_string'];
}


$x['data']  = $resultD;
$x['title'] = [
    'text'         => '',
    'subtext'      => 'Last updated: ' . date( 'M d, Y, H:i' ),
    'left'         => 'center',
    'textStyle'    => [
        'fontSize'   => 17,
        'fontWeight' => 500,
    ],
    'subtextStyle' => [
        'fontSize' => 12,
    ],
];
$hasil      = json_encode( $x );

?>

<script>
    var data = <?= $hasil; ?>;
    grafik_pie(data, 'pie_basic');
</script>