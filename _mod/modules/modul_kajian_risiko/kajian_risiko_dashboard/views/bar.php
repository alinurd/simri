<div class="chart has-fixed-height-dashboard" id="bar_1"></div>
<script>
    var dataBarChart = JSON.parse('<?= $databar ?>');
    var chartBar = document.getElementById('bar_1');
    var myChartBar = echarts.init(chartBar);
    var optionBar;
    optionBar = {
        xAxis: {
            type: 'category',
            data: dataBarChart["label"]
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                data: dataBarChart["value"],
                type: 'bar',
                showBackground: true,
                color: "rgba(101, 181, 162)",
                backgroundStyle: {
                    color: 'rgba(180, 180, 180, 0.2)'
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
</script>