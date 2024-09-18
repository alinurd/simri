$(function () {
    $(document).on("keypress", "#search_text", function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        console.log(keycode);
        if (keycode == '13') {
            $("#btnSearch").trigger("click");
        }
    })

    $("#period").change(function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        var target_combo = $("#term");
        var url = "ajax/get-term";
        _ajax_("post", parent, data, target_combo, url);
        $("#term").trigger('change')
    })

    $("#term").change(function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        var target_combo = $("#minggu");
        var url = "ajax/get-minggu";
        _ajax_("post", parent, data, target_combo, url);
    })

    $("#btnSearch").click(function () {
        var text = $("#search_text").val();

        var parent = $(this).parent();
        var data = { 'text': text, 'kel': 1 };
        var url = modul_name + "/get-search";
        _ajax_("post", parent, data, '', url, 'show_result');
    })

    $(".detail").click(function () {
        var text = $(this).data('barcode');

        var parent = $(this).parent();
        var data = { 'text': text, 'kel': 2 };
        var url = modul_name + "/get-search";
        _ajax_("post", parent, data, '', url, 'show_result');
    })

    $(document).on("keyup", "#nama", function () {
        $('#detail_visitor').val($(this).val());
    })

    $(document).on('click', '.detail-peta', function () {
        var parent = $(this).parent().parent().parent();
        var owner = $("#owner").val();
        var period = $("#period").val();
        var type_ass = $("#type_ass").val();
        var term = $("#term").val();
        var minggu = $("#minggu").val();
        var id = $(this).data('id');
        var level = $(this).data('level');
        var data = { 'id': id, 'level': level, 'period': period, 'owner': owner, 'type_ass': type_ass, 'term': term, 'minggu': minggu };
        var target_combo = '';
        var url = "ajax/get-detail-map";
        _ajax_("post", parent, data, target_combo, url, 'list_map');
    })

    $(document).on('click', '.detail-peta-current', function () {
        var parent = $(this).parent().parent().parent();
        var owner = $("#owner").val();
        var period = $("#period").val();
        var type_ass = $("#type_ass").val();
        var term = $("#term").val();
        var minggu = $("#minggu").val();
        var id = $(this).data('id');
        var monid = $(this).data('monid');
        console.log(monid)
        var level = $(this).data('level');
        var data = { 'monid': monid,'id': id, 'level': level, 'period': period, 'owner': owner, 'type_ass': type_ass, 'term': term, 'minggu': minggu };
        var target_combo = '';
        var url = "ajax/get-detail-map-current";
        console.log(monid

        )
        _ajax_("post", parent, data, target_combo, url, 'list_map');
    })

    $(document).on('click', '.detail-rcsa', function () {
        var parent = $(this).parent().parent().parent();
        var id = $(this).data('id');
        var dampak = $(this).data('dampak');

        var data = { 'id': id, 'dampak_id': dampak };
        var target_combo = $("#result_mitigasi_" + id + " td");
        var url = "ajax/get-detail-rcsa";
        $(".result_mitigasi").addClass('d-none');
        $(".result_mitigasi td").html('');
        $("#result_mitigasi_" + id).removeClass('d-none');
        _ajax_("post", parent, data, target_combo, url);
    })

    $(document).on('click', '.detail-mitigasi', function () {
        var parent = $(this).parent().parent().parent();
        var id = $(this).data('id');
        var data = { 'id': id };
        var target_combo = '';
        var url = "ajax/get-detail-mitigasi";
        _ajax_("post", parent, data, target_combo, url, 'list_aktifitas_mitigasi');
    })
    $(document).on('click', '.detail-progres-mitigasi', function () {
        var parent = $(this).parent().parent().parent();
        var id = $(this).data('id');
        var owner = $("#owner").val();
        var period = $("#period").val();
        var type_ass = $("#type_ass").val();
        var data = { 'id': id, 'period': period, 'owner': owner, 'type_ass': type_ass };
        var target_combo = '';
        var url = "ajax/get-detail-progres-mitigasi";
        _ajax_("post", parent, data, target_combo, url, 'list_progres_aktifitas_mitigasi');
    })

    $(document).on('click', '#proses', function () {
        var parent = $(this).parent().parent().parent();
        var owner = $("#owner").val();
        var period = $("#period").val();
        var type_ass = $("#type_ass").val();
        var term = $("#term").val();
        var minggu = $("#minggu").val();
        var data = { 'period': period, 'owner': owner, 'type_ass': type_ass, 'term': term, 'minggu': minggu };
        var url = modul_name + "/get-map";
        _ajax_("post", parent, data, '', url, 'result_map');
    });
});

function list_map(hasil) {
    // $('#result').html(hasil.combo);
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function result_map(hasil) {
    console.log(hasil)
    $("#maps").html(hasil.combo);
    $("#result_grap1").html(hasil.grap1);
    // $("#result_grap2").html(hasil.grap3);
    $("#grap3").html(hasil.grap3);
    $("#result_grap3").html(hasil.grap2);
    $("#result_grap4").html(hasil.data_grap2);
}

function list_mitigasi(hasil) {
    $('#result_mitigasi').html(hasil.combo);
}

function list_aktifitas_mitigasi(hasil) {
    $('#result_aktifitas_mitigasi').html(hasil.combo);
}
function list_progres_aktifitas_mitigasi(hasil) {
    $('#result_progres_aktifitas_mitigasi').html(hasil.combo);
}

function show_result(hasil) {
    $("#modal_general_title").find(".modal-body").html(hasil.combo);
    $("#modal_general_title").modal("show");
    var elems = Array.prototype.slice.call(document.querySelectorAll('.form-switchery-primary'));
    elems.forEach(function (html) {
        var switchery = new Switchery(html, { color: '#2196F3' });
    });
}

function jumlah_change() {
    $('#jml').trigger('change');
}

function eConsole(param) {
    if (typeof param.seriesIndex != 'undefined') {
        // console.log(param);
        // console.log(param.data);
        // console.log(param.data.type_chat);

        var parent = $(this).parent().parent().parent();

        var owner = $("#owner").val();
        var period = $("#period").val();
        var type_ass = $("#type_ass").val();
        var term = $("#term").val();
        var minggu = $("#minggu").val();
        var data = { 'period': period, 'owner': owner, 'type_ass': type_ass, 'term': term, 'minggu': minggu, 'data': param.data };

        // var data={'id':id, 'data':param.data};

        var target_combo = '';
        var url = modul_name + "/get-detail-char";
        _ajax_("post", parent, data, target_combo, url, 'show_detail_char');
    }
}

function eConsoleTask(param) {
    if (typeof param.seriesIndex != 'undefined') { 

        var parent = $(this).parent().parent().parent();

        var owner = $("#owner").val();
        var period = $("#period").val(); 
        var data = { 'id': param.data.id, 'period': period, 'owner': owner, 'param_id': param.data.param_id };
 
         var target_combo = '';
        var url = modul_name + "/get-detail-char-task";
        _ajax_("post", parent, data, target_combo, url, 'show_detail_char');
    }
}

function eConsoleStsMon(param) {
    if (typeof param.seriesIndex != 'undefined') { 

        var parent = $(this).parent().parent().parent();

        var owner = $("#owner").val();
        var period = $("#period").val(); 
        var data = { 'id': param.data.id, 'period': period, 'owner': owner, 'param_id': param.data.param_id };
 
         var target_combo = '';
        var url = modul_name + "/get-detail-char-stsmon";
        _ajax_("post", parent, data, target_combo, url, 'show_detail_char');
    }
}

function show_detail_char(hasil) {
    var title = hasil.title;
    if(!hasil.title){
        title = "";
    }
    $("#modal_general").find(".modal-title").html(title);
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function grafik_pie(data, target) {
    var pie_basic_element = document.getElementById(target);
    var myChart = echarts.init(pie_basic_element); 
    // specify chart configuration item and data
    var option = {

        // Colors
        color: data.warna,

        // Global text styles
        textStyle: {
            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
            fontSize: 13
        },

        // Add title
        title: data.title,

        // Add tooltip
        tooltip: {
            trigger: 'item',
            backgroundColor: 'rgba(0,0,0,0.75)',
            padding: [10, 15],
            textStyle: {
                fontSize: 13,
                fontFamily: 'Roboto, sans-serif'
            },
            formatter: "{b}: {c} ({d}%)"
        },

        // Add legend
        // legend: {
        //     // type: 'scroll',
        //     // orient: 'horizontal',
        //     // right: 10,
        //     // top: 20,
        //     bottom: 0,
        //     top: 'bottom',
        //     left: 'center',
        //     padding: [-20, 10],
        //     selected: data.selected
        // },

        // Add series
        series: [{
            name: '',
            type: 'pie',
            radius: '75%',
            center: ['50%', '57.5%'],
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
            data: data.data
        }]
    };

    // use configuration item and data specified to show chart
    myChart.setOption(option);

    var triggerChartResize = function () {
        pie_basic_element && myChart.resize();
    };

    // On window resize
    var resizeCharts;
    window.addEventListener('resize', function () {
        clearTimeout(resizeCharts);
        resizeCharts = setTimeout(function () {
            triggerChartResize();
        }, 200);
    });

    myChart.on('click', eConsole);
}

function grafik_pie_taksonomi(data, target) {
    var pie_basic_element = document.getElementById(target);
    var myChart = echarts.init(pie_basic_element); 
    // specify chart configuration item and data
    var option = {

        // Colors
        color: data.warna,

        // Global text styles
        textStyle: {
            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
            fontSize: 13
        },

        // Add title
        title: data.title,

        // Add tooltip
        tooltip: {
            trigger: '',
            backgroundColor: 'rgba(0,0,0,0.75)',
            padding: [10, 15],
            textStyle: {
                fontSize: 13,
                fontFamily: 'Roboto, sans-serif'
            },
            formatter: '{b}: {c} ({d}%)',
        },

        legend: {
            type: 'scroll',
            orient: 'horizontal',
            left: 'center',
            top: 'bottom',
            padding: [5, 5],
            selected: data.selected
        },
        

        series: [{
            name: '',
            type: 'pie',
            radius: '55%',
            center: ['50%', '46.5%'],
            
            itemStyle: {
                normal: {
                    borderWidth: 1,
                    borderColor: '#fff'
                }
            },
            label: {
                 position: 'outside',
                formatter: '{b}: {c} ({d}%)',
            },
            data: data.data
        }]
        
    };

     myChart.setOption(option);

    var triggerChartResize = function () {
        pie_basic_element && myChart.resize();
    };

    // On window resize
    var resizeCharts;
    window.addEventListener('resize', function () {
        clearTimeout(resizeCharts);
        resizeCharts = setTimeout(function () {
            triggerChartResize();
        }, 200);
    });

    myChart.on('click', eConsoleTask);
}

function grafik_pie_sts_mon(data, target) {
    var pie_basic_element = document.getElementById(target);
    var myChart = echarts.init(pie_basic_element); 
    // specify chart configuration item and data
    var option = {

        // Colors
        color: data.warna,

        // Global text styles
        textStyle: {
            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
            fontSize: 13
        },

        // Add title
        title: data.title,

        // Add tooltip
        tooltip: {
            trigger: 'item',
            backgroundColor: 'rgba(0,0,0,0.75)',
            padding: [10, 15],
            textStyle: {
                fontSize: 13,
                fontFamily: 'Roboto, sans-serif'
            },
            formatter: "{b}: {c} ({d}%)"
        },

        // Add legend
        // legend: {
        //     // type: 'scroll',
        //     // orient: 'horizontal',
        //     // right: 10,
        //     // top: 20,
        //     bottom: 0,
        //     top: 'bottom',
        //     left: 'center',
        //     padding: [-20, 10],
        //     selected: data.selected
        // },

        // Add series
        series: [{
            name: '',
            type: 'pie',
            radius: '75%',
            center: ['50%', '57.5%'],
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
            data: data.data
        }]
    };

    // use configuration item and data specified to show chart
    myChart.setOption(option);

    var triggerChartResize = function () {
        pie_basic_element && myChart.resize();
    };

       // On window resize
       var resizeCharts;
       window.addEventListener('resize', function () {
           clearTimeout(resizeCharts);
           resizeCharts = setTimeout(function () {
               triggerChartResize();
           }, 200);
       });
   
       myChart.on('click', eConsoleStsMon);
}

function grafik_pie_sts_mon_(data, target) {
    var pie_basic_element = document.getElementById(target);
    var myChart = echarts.init(pie_basic_element); 
    // specify chart configuration item and data
    var option = {

        // Colors
        color: data.warna,

        // Global text styles
        textStyle: {
            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
            fontSize: 13
        },

        // Add title
        title: data.title,

        // Add tooltip
        tooltip: {
            trigger: '',
            backgroundColor: 'rgba(0,0,0,0.75)',
            padding: [10, 15],
            textStyle: {
                fontSize: 13,
                fontFamily: 'Roboto, sans-serif'
            },
            formatter: '{b}: {c} ({d}%)',
        },

        legend: {
            type: 'scroll',
            orient: 'horizontal',
            left: 'center',
            top: 'bottom',
            padding: [5, 5],
            selected: data.selected
        },
        

        series: [{
            name: '',
            type: 'pie',
            radius: '55%',
            center: ['50%', '46.5%'],
            
            itemStyle: {
                normal: {
                    borderWidth: 1,
                    borderColor: '#fff'
                }
            },
            label: {
                 position: 'outside',
                formatter: '{b}: {c} ({d}%)',
            },
            data: data.data
        }]
        
    };

     myChart.setOption(option);

    var triggerChartResize = function () {
        pie_basic_element && myChart.resize();
    };

    // On window resize
    var resizeCharts;
    window.addEventListener('resize', function () {
        clearTimeout(resizeCharts);
        resizeCharts = setTimeout(function () {
            triggerChartResize();
        }, 200);
    });

    myChart.on('click', eConsoleStsMon);
}

function grafik_aktifitas(data) {
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
    myChart.on('click', eConsoleMitigasi)(grapProgress, "Aktifitas Monitoring");
}

function grafik_mitigasi(data) {
    var domx = document.getElementById('grapProgress');
    var myChartx = echarts.init(domx, null, {
        renderer: 'canvas',
        useDirtyRect: false
    });
    const xAxisData = data.months; 
    const levelColorsPerMonth = data.level_colors;

    const legendData = ['Low', 'Low to Moderate', 'Moderate', 'Moderate to High', 'High'];
    const legendDataColor = ['#4fad59', '#9fcf62', '#ffff55', '#f5c444', '#e93423'];

    let seriesData = []; 

     legendData.forEach((legend, index) => {
        let levelData = [];
        let lineLevelData = [];

        levelColorsPerMonth.forEach((levelArray, monthIndex) => {
            let count = levelArray.filter(level => level === legend).length;
            levelData.push(count);  
            lineLevelData.push(count);  
        });

        
        seriesData.push({
            type: 'bar',
            name: legend,
            itemStyle: { color: legendDataColor[index] },
            data: levelData
        });

        seriesData.push({
            type: 'line',
            name: `${legend} Trend`,
            data: lineLevelData,
            itemStyle: {
                color: legendDataColor[index],  
            },
            lineStyle: {
                width: 2,  
            },
            symbol: 'circle',  
            symbolSize: 8,  
        });
    });

    var option = {
        title: { text: '' },
        tooltip: { trigger: 'axis' },
        legend: {
            data: [...legendData, ...legendData.map(level => `${level} Trend`)],
            selected: legendData.reduce((acc, level) => {
                acc[level] = true; 
                acc[`${level} Trend`] = false; 
                return acc;
            }, {})
        },
        xAxis: { 
            data: xAxisData 
        },
        yAxis: { type: 'value' },
        series: seriesData,  
        dataZoom: [
            {
                type: 'slider',  
                start: 0,       
                end: 100,         
            },
            {
                type: 'inside',  
                start: 10,       
                end: 50,         
            }
        ]
    };

    var resizeCharts;
    window.addEventListener('resize', function () {
        clearTimeout(resizeCharts);
        resizeCharts = setTimeout(function () {
            triggerChartResize();
        }, 200);
    });

    if (option && typeof option === 'object') {
        myChartx.setOption(option);
    }
    window.addEventListener('resize', myChartx.resize);
 }

function eConsoleMitigasi(param, grapProgress, title) {
    if (typeof param.seriesIndex !== 'undefined') {
        var owner = $("#owner").val();
        var period = $("#period").val();
        var data = {
            'id': 1,
            'period': period,
            'owner': owner,
            'title':title,
            'param_id': 3
        };

        var target_combo = '';
        var url = modul_name + "/get-detail-char-progress";
        _ajax_("post", $('#'+grapProgress), data, target_combo, url, 'show_detail_char');
    }
}

 
