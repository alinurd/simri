<div class="chart has-fixed-height-dashboard" id="bar_2"></div>
<script>
    var dataBarChart2 = JSON.parse('<?= $bar2 ?>');
    var chartBar2 = document.getElementById('bar_2');
    var myChartBar2 = echarts.init(chartBar2);
    var optionBar2;
    optionBar2 = {
        title: {
            text: 'Total Release Berdasarkan Bulan ' + "(<?= $labeltahun ?>)",
            // subtext: 'Fake Data',
            left: 'center',
        },
        xAxis: {
            type: 'category',
            data: dataBarChart2["label"],
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
                name: "tanggal_release",
                data: dataBarChart2["value"],
                type: 'bar',
                showBackground: true,
                color: "rgba(59, 95, 128)",
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
            },

        ]
    };

    var triggerChartResizeBar2 = function () {
        chartBar2 && myChartBar2.resize();
    };

    var resizeChartsBar2;
    window.addEventListener('resize', function () {
        clearTimeout(resizeChartsBar2);
        resizeChartsBar = setTimeout(function () {
            triggerChartResizeBar2();
        }, 200);
    });
    optionBar2 && myChartBar2.setOption(optionBar2);
    myChartBar2.on('click', getdataKajianDashboard);
</script>