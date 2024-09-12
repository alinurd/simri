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
 <div id="grapProgress"></div>
 <hr>
 <div id="grapLin"></div>

 <script>
     $(document).ready(function() {
         aktifitas();
         mitigasi();
     });

     function aktifitas() {
         var dom = document.getElementById('grapLin');
         var myChart = echarts.init(dom, null, {
             renderer: 'canvas',
             useDirtyRect: false
         });

         var option = {
            //  title: {
            //      text: 'Progress Aktifitas'
            //  },
             tooltip: {
                 trigger: 'axis'
             },
             legend: {
                 data: ['Target', 'Aktual']
             },
             grid: {
                 left: '3%',
                 right: '4%',
                 bottom: '3%',
                 containLabel: true
             },
             toolbox: {
                 feature: {
                     saveAsImage: {}
                 }
             },
             xAxis: {
                 type: 'category',
                 boundaryGap: false,
                 data: ['','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
             },
             yAxis: {
                 type: 'value'
             },
             series: [{
                     name: 'Target',
                     type: 'line',
                     smooth: true,
                     symbol: 'circle',
                     symbolSize: 8,
                     sampling: 'average',
                     itemStyle: {
                         color: '#0770FF'
                     },
                     stack: 'total',
                     areaStyle: {
                         color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                 offset: 0,
                                 color: 'rgba(58,77,233,0.8)'
                             },
                             {
                                 offset: 1,
                                 color: 'rgba(58,77,233,0.3)'
                             }
                         ])
                     },
                     data: [0,0, 10, 31, 37, 46, 42, 21, 61, 13, 82, 94, 100]
                 },
                 {
                     name: 'Aktual',
                     type: 'line',
                     stack: 'Total',
                     data: [0,10, 18, 29, 37, 46, 30, 66, 61, 46, 75, 82, 100]
                 }
             ]
         };

         if (option && typeof option === 'object') {
             myChart.setOption(option);
         }
         window.addEventListener('resize', myChart.resize);
         myChart.on('click', eConsoleTask);
     }

     function mitigasi() {
         var domx = document.getElementById('grapProgress');
         var myChartx = echarts.init(domx, null, {
             renderer: 'canvas',
             useDirtyRect: false
         });

         const levelCunt = 5;
         const categoryCount = 12;
         const xAxisData = [];
         const customData = [];
         const dataList = [];
 
         const legendData = ['Low', 'Low to Moderate', 'Moderate', 'Moderate to High', 'High'];
         const legendDataColor = ['#4fad59', '#9fcf62', '#ffff55', '#f5c444', '#e93423'];  
 
         xAxisData.push('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
         const encodeY = [];

        
         for (var i = 0; i < levelCunt; i++) {
             dataList.push([]);
             encodeY.push(1 + i);  
         }
 
         for (var i = 0; i < categoryCount; i++) {
             var val = Math.random() * 25;
             var customVal = [i];
             customData.push(customVal);
 
             for (var j = 0; j < dataList.length; j++) {
                 var value = j === 0 ? echarts.number.round(val, 2) :
                     echarts.number.round(Math.max(0, dataList[j - 1][i] + (Math.random() - 0.5) * 25), 2);
                 dataList[j].push(value);
                 customVal.push(value);
             }
         }

         var option = {
            title: {
                 text: ''
             },
             tooltip: {
                 trigger: 'axis'
             },
             legend: {
                 data: legendData
             },
             dataZoom: [{
                     type: 'slider',
                     start: 50,
                     end: 70
                 },
                 {
                     type: 'inside',
                     start: 50,
                     end: 70
                 }
             ],
             xAxis: {
                 data: xAxisData
             },
             yAxis: {},
             series: [{
                     type: 'custom', 
                     renderItem: function(params, api) {
                         var xValue = api.value(0);
                         var currentSeriesIndices = api.currentSeriesIndices();
                         var barLayout = api.barLayout({
                             barGap: '30%',
                             barCategoryGap: '20%',
                             count: currentSeriesIndices.length - 1
                         });
                         var points = [];
                         for (var i = 0; i < currentSeriesIndices.length; i++) {
                             var seriesIndex = currentSeriesIndices[i];
                             if (seriesIndex !== params.seriesIndex) {
                                 var point = api.coord([xValue, api.value(seriesIndex)]);
                                 point[0] += barLayout[i - 1].offsetCenter;
                                 point[1] -= 20;
                                 points.push(point);
                             }
                         }
                         var style = api.style({
                             stroke: '#000000',
                             fill: 'none'
                         });
                         return {
                             type: 'polyline',
                             shape: {
                                 points: points
                             },
                             style: style
                         };
                     },
                     itemStyle: {
                         borderWidth: 2
                     },
                     encode: {
                         x: 0,
                         y: encodeY
                     },
                     data: customData,
                     z: 100
                 },
                 ...dataList.map(function(data, index) {
                     return {
                         type: 'bar',
                         animation: true,
                         name: legendData[index],
                         itemStyle: {
                             color: legendDataColor[index]
                            },
                            data: data
                        };
                        console.log(data)
                 })
             ]
         };

         if (option && typeof option === 'object') {
             myChartx.setOption(option);
         }
         window.addEventListener('resize', myChartx.resize);
         myChart.on('click', eConsoleTask);
     }

     function eConsoleTask(param) {
         if (typeof param.seriesIndex !== 'undefined') {
             var owner = $("#owner").val();
             var period = $("#period").val();
             var data = {
                 'id': 1,
                 'period': period,
                 'owner': owner,
                 'param_id': 3
             };

             var target_combo = '';
             var url = modul_name + "/get-detail-char-progress";
             _ajax_("post", $('#grapLin'), data, target_combo, url, 'show_detail_char');
         }
     }
 </script>