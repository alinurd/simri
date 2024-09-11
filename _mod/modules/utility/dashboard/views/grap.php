<div class="chart has-fixed-height" id="pie_basic"></div>
<!-- <center>Total: <?=count($detail)?></center> -->
 <?php 
$resultD   = [];
foreach ($tasktonomi as $key => $p) {
    // Periksa apakah $p['data'] ada di $detail sebelum diakses
    if (isset($detail[$p['data']])) {
        $resultD[] = [
            'value' => $p['id'], 
            'name' => $p['data'], 
            'type_chat' => 1, 
            'param_id' => 1, 
            'id' => $detail[$p['data']]
        ];
    } else {
        // Handle kasus jika data tidak ada
        // Misalnya: isi dengan nilai default atau abaikan
        $resultD[] = [
            'value' => $p['id'], 
            'name' => $p['data'], 
            'type_chat' => 1, 
            'param_id' => 1, 
            'id' => null // Atau tambahkan fallback nilai jika diperlukan
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
    grafik_pie_count(data, 'pie_basic');
</script>