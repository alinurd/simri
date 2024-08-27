<div class="chart has-fixed-height-dashboard" id="pie_basic_2"></div>
<script>
    var chartDom2 = document.getElementById('pie_basic_2');
    var myChart2 = echarts.init(chartDom2);
    var option2;

    option2 = {
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
                data: <?= $graph2 ?>,
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
    var triggerChartResize2 = function () {
        chartDom2 && myChart2.resize();
    };

    var resizeCharts2;
    window.addEventListener('resize', function () {
        clearTimeout(resizeCharts2);
        resizeCharts2 = setTimeout(function () {
            triggerChartResize2();
        }, 200);
    });
    option2 && myChart2.setOption(option2);
</script>