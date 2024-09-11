<div class="chart has-fixed-height" id="pie_basic"></div>
  <?php 
$resultD   = [];
foreach ($tasktonomi as $key => $p) { 
    if (isset($detail[$p['data']])) {
        $resultD[] = [
            'value' => $detail[$p['data']], 
            'name' =>$p['data'], 
            'type_chat' => 4, 
            'param_id' => 1, 
            'id' => $p['id']
        ];
    } else { 
        $resultD[] = [
            'value' => 0, 
            'name' =>$p['data'], 
            'type_chat' => 4, 
            'param_id' => 1, 
            'id' => $p['id']
        ];
    }
    if (isset($p['param_string'])) {
        $x['warna'][] = $p['param_string'];
    }
}

$x['data']  = $resultD;
$x['title'] = [
    'text'         => '',
    'subtext'      => '',
    // 'text'         => 'Data Tasktonomi & Tipe Risiko Yang Sudah Termapping',
    // 'subtext'      => 'Total:'.$total,
    'left'         => 'center',
    'textStyle'    => [
        'fontSize'   => 12,
        'fontWeight' => 10,
    ],
    'subtextStyle' => [
        'fontSize' => 12,
        'fontWeight' => 5,
    ],
];
$hasil      = json_encode( $x );

?>

<script>
    var data = <?= $hasil; ?>;
    grafik_pie_taksonomi(data, 'pie_basic');
</script>