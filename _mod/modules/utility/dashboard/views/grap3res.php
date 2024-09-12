<?php

// Ambil data dari database
$r = $this->db->select('id, month, level_color, color_text, color as bg, rcsa_detail_id')
              ->get("il_update_residual")->result_array();

// Inisialisasi array untuk semua bulan dari Januari hingga Desember
$all_months = [
    ['id' => null, 'month' => '1', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '2', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '3', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '4', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '5', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '6', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '7', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '8', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '9', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '10', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '11', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
    ['id' => null, 'month' => '12', 'level_color' => 'No Data', 'color_text' => '#ffffff', 'bg' => '#cccccc', 'rcsa_detail_id' => null],
];

// Gabungkan data dari database ke bulan-bulan default
foreach ($r as $p) {
    // Temukan bulan yang sesuai dalam $all_months dan perbarui datanya
    foreach ($all_months as &$month) {
        if ($month['month'] == $p['month']) {
            $month['id'] = $p['id'];
            $month['level_color'] = $p['level_color'];
            $month['color_text'] = $p['color_text'];
            $month['bg'] = $p['bg'];
            $month['rcsa_detail_id'] = $p['rcsa_detail_id'];
        }
    }
}

// Data untuk diagram batang
$months = [];
$colors = [];
$level_colors = [];

foreach ($all_months as $item) {
    $months[] = 'Month ' . $item['month'];  // Menyimpan nama bulan
    $colors[] = $item['bg'];  // Menyimpan warna latar belakang
    $level_colors[] = $item['level_color'];  // Menyimpan level risiko
}

$chartData = [
    'months' => $months,
    'colors' => $colors,
    'level_colors' => $level_colors,
];

// Encode data ke format JSON
$chartDataJson = json_encode($chartData);
?>
<div class="chart has-fixed-height" id="bar_chart"></div>

<script>
    // Mengambil data dari PHP
    var chartData = <?=$chartDataJson;?>;

    // Membuat diagram bar menggunakan ECharts
    var chartElement = document.getElementById('bar_chart');
    var myChart = echarts.init(chartElement);

    var option = {
        title: {
            text: 'Risk Level per Month',
            left: 'center'
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            },
            formatter: function (params) {
                var level = chartData.level_colors[params[0].dataIndex];
                return params[0].name + '<br/>' + level + ': ' + params[0].value;
            }
        },
        legend: {
            data: ['High', 'Moderate', 'Low'],
            bottom: 0,
            selectedMode: false, // Disable interaction for the legend
            textStyle: {
                color: '#000',
            }
        },
        xAxis: {
            type: 'category',
            data: chartData.months,
            axisLabel: {
                rotate: 45,  // Agar label bulan mudah dibaca
            }
        },
        yAxis: {
            type: 'value'
        },
        series: [{
            name: 'Risk Level',
            type: 'bar',
            data: chartData.level_colors.map(function (level, index) {
                return {
                    value: level === 'High' ? 3 : (level === 'Moderate' ? 2 : (level === 'Low' ? 1 : 0)),  // Menentukan nilai berdasarkan level
                    itemStyle: {
                        color: chartData.colors[index],  // Menggunakan warna sesuai data
                    }
                };
            }),
            label: {
                show: true,
                position: 'top',
                formatter: function (params) {
                    return chartData.level_colors[params.dataIndex];  // Menampilkan label level_color di atas bar
                }
            }
        }]
    };

    myChart.setOption(option);
</script>
