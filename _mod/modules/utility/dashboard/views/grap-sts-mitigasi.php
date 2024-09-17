<div class="chart has-fixed-height" id="pie_basic_2"></div>
<?php
 
$statuses = [
    'Porgress' => 0,  // For sts_mon = 1
    'Done' => 0,  // For sts_mon = 1
    'Not Yet' => 0  // For sts_mon = 0
];

// Count occurrences of each sts_mon value
foreach ($detail as $item) {
    if ($item['sts_mon'] == "1") {
        $statuses['Porgress']++;
    }
   else if ($item['sts_mon'] == "2") {
        $statuses['Done']++;
    } else {
        $statuses['Not Yet']++;
    }
}

// Prepare data for the chart
$resultD = [];
foreach ($statuses as $name => $value) {
    $resultD[] = [
        'value' => $value,
        'name' => $name,
        'type_chat' => 2,
        'id' => $name,
        'param_id' => 4
    ];
}
 
$x['data'] = $resultD;
$x['title'] = [
    'text' => 'Mitigation Status',
    'subtext' => 'Based on Monitoring Status',
    'left' => 'center',
    'textStyle' => [
        'fontSize' => 14,
        'fontWeight' => 'bold',
    ],
    'subtextStyle' => [
        'fontSize' => 12,
        'fontWeight' => 'normal',
    ],
];
 $x['warna']=['#0189cd','#00a50d','#ee0000'];

$hasil = json_encode($x);
 ?>


<script>
    var data=<?=$hasil;?>;
    grafik_pie_sts_mon(data, 'pie_basic_2');
</script>