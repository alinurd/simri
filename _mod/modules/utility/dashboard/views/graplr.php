<style>
    * {
        margin: 0;
        padding: 0;
    }

    #grapLin {
        position: relative;
        height: 300px;
        overflow: hidden;
    }

    #grapProgress {
        position: relative;
        height: 300px;
        overflow: hidden;
    }
    
</style>
<div class="card">
    <div class="card-header header-elements-sm-inline">
            <center> <h5>Level Risiko</h5></center>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="grapProgress"></div>
    </div>
</div>
<hr>

<div class="card">
    <div class="card-header header-elements-sm-inline">
            <center> <h5>Aktifitas Mitigasi</h5></center>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="grapLin"></div>
    </div>
</div>

<?php

$all_months = [
    ['month' => '1', 'monthName' => 'Januari', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '2', 'monthName' => 'Februari', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '3', 'monthName' => 'Maret', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '4', 'monthName' => 'April', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '5', 'monthName' => 'Mei', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '6', 'monthName' => 'Juni', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '7', 'monthName' => 'Juli', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '8', 'monthName' => 'Agustus', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '9', 'monthName' => 'September', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '10', 'monthName' => 'Oktober', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '11', 'monthName' => 'November', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
    ['month' => '12', 'monthName' => 'Desember', 'level_colors' => [], 'colors' => [], 'rcsa_detail_ids' => []],
];

foreach ($levelRisiko as $p) {
    foreach ($all_months as &$month) {
        if ($month['month'] == $p['month']) {
            $month['level_colors'][] = $p['level_color'];
            $month['colors'][] = $p['bg'];
            $month['rcsa_detail_ids'][] = $p['rcsa_detail_id'];
        }
    }
}

$months = [];
$colors = [];
$level_colors = [];

foreach ($all_months as $item) {
    $months[] = $item['monthName'] . ': ' . count($item['level_colors']);
    $colors[] = $item['colors'];
    $level_colors[] = $item['level_colors'];
    $rcsa_detail_ids[] = $item['rcsa_detail_ids'];
}

$chartData = [
    'months' => $months,
    'colors' => $colors,
    'level_colors' => $level_colors,
    'rcsa_detail_ids' => $rcsa_detail_ids,
];
$chartDataJson = json_encode($chartData);


?>

<script>
    $(document).ready(function() {
        var chartData = <?= $chartDataJson; ?>;
        grafik_mitigasi(chartData);
        grafik_aktifitas(chartData);
    });
</script>