<div class="chart has-fixed-height-dashboard" id="bar_1"></div>
<script>
    var dataBarChart1 = JSON.parse('<?= $bar1 ?>');
    var chartBar = document.getElementById('bar_1');
    var myChartBar = echarts.init(chartBar);
    var optionBar;
    optionBar = {
        title: {
            text: 'Tanggal Tiket Terbit Berdasarkan Bulan ' + "(<?= $labeltahun ?>)",
            // subtext: 'Fake Data',
            left: 'center',
        },
        xAxis: {
            type: 'category',
            data: dataBarChart1["label"],
            axisLabel: {
                interval: 0,
                rotate: 30
            }

        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name: "tiket_terbit",
                data: dataBarChart1["value"],
                type: 'bar',
                showBackground: true,
                color: "rgba(110, 156, 151)",
                backgroundStyle: {
                    color: 'rgba(180, 180, 180, 0.2)'
                },
                label: {
                    show: true,
                    position: 'inside',
                    formatter: function (params) {
                        return params.value
                    },
                }
            }
        ]
    };

    var triggerChartResizeBar = function () {
        chartBar && myChartBar.resize();
    };

    var resizeChartsBar;
    window.addEventListener('resize', function () {
        clearTimeout(resizeChartsBar);
        resizeChartsBar = setTimeout(function () {
            triggerChartResizeBar();
        }, 200);
    });
    optionBar && myChartBar.setOption(optionBar);
    myChartBar.on('click', getdataKajianDashboard);
</script>