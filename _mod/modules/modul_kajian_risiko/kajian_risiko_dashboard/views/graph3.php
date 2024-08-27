<div class="chart has-fixed-height-dashboard" id="pie_basic_3"></div>
<script>
    var chartDom3 = document.getElementById('pie_basic_3');
    var myChart3 = echarts.init(chartDom3);
    var option3;

    option3 = {
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
                data: <?= $graph3 ?>,
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
    var triggerChartResize3 = function () {
        chartDom3 && myChart3.resize();
    };

    var resizeCharts3;
    window.addEventListener('resize', function () {
        clearTimeout(resizeCharts3);
        resizeCharts3 = setTimeout(function () {
            triggerChartResize3();
        }, 200);
    });
    option3 && myChart3.setOption(option3);
</script>