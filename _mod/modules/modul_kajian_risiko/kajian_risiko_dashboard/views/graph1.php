<div class="chart has-fixed-height-dashboard" id="pie_basic_1"></div>
<script>
    var chartDom1 = document.getElementById('pie_basic_1');
    var myChart1 = echarts.init(chartDom1);
    var option1;

    option1 = {
        title: {
            text: 'Referer of a Website',
            // subtext: 'Fake Data',
            left: 'center',
        },
        tooltip: {
            trigger: 'item'
        },
        legend: {
            show: false,
            fontSize: 8,
            orient: 'horizontal',
            bottom: 0,
        },
        series: [
            {
                name: 'Access From',
                type: 'pie',
                radius: '70%',
                center: ['50%', '55%'],
                itemStyle: {
                    normal: {
                        borderWidth: 1,
                        borderColor: '#fff'
                    }
                },
                label: {
                    position: 'inside',
                    formatter: '{d}%',
                },
                data: <?= $graph1 ?>,
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };
    var triggerChartResize1 = function () {
        chartDom1 && myChart1.resize();
    };

    var resizeCharts1;
    window.addEventListener('resize', function () {
        clearTimeout(resizeCharts1);
        resizeCharts1 = setTimeout(function () {
            triggerChartResize1();
        }, 200);
    });
    option1 && myChart1.setOption(option1);
</script>