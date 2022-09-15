$(function(){
    $("#period").change(function () {
		var parent = $(this).parent();
		var nilai = $(this).val();
		var data = {
			'id': nilai
		};
		var target_combo = $("#term");
		var url = "ajax/get-term";
		_ajax_("post", parent, data, target_combo, url);
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

    $(document).on('click','#proses', function() {
        var parent = $(this).parent().parent().parent();
		var owner = $("#owner").val();
		var period = $("#period").val();
		var type_ass = $("#type_ass").val();
		var term = $("#term").val();
		var minggu = $("#minggu").val();
        var data = { 'period': period, 'owner': owner, 'type_ass': type_ass, 'type': type_ass, 'term':term,'minggu':minggu};
		var url = modul_name+"/get-map";
		_ajax_("post", parent, data, '', url,'result_map');
    });
});

function list_map(hasil){
    // $('#result').html(hasil.combo);
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function result_map(hasil){
    // console.log(hasil);
    // $("#result_grap1").html(hasil.grap1);
    // $("#result_grap2").html(hasil.data_grap1);
    $("#kompi").html(hasil.kompi);
}

function show_result(hasil){
    $("#modal_general_title").find(".modal-body").html(hasil.combo);
    $("#modal_general_title").modal("show");
    var elems = Array.prototype.slice.call(document.querySelectorAll('.form-switchery-primary'));
    elems.forEach(function(html) {
    var switchery = new Switchery(html, { color: '#2196F3' });
    });
}

function jumlah_change(){
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
        var data={'period':period,'owner':owner,'type_ass':type_ass, 'term':term,'minggu':minggu, 'data':param.data};
        
        // var data={'id':id, 'data':param.data};
        
        var target_combo = '';
        var url = modul_name+"/get-detail-char";
        _ajax_("post", parent, data, target_combo, url, 'show_detail_char');
     }
 }

 function show_detail_char(hasil){
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
 }

function grafik_pie(data, target){
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

    var triggerChartResize = function() {
        pie_basic_element && myChart.resize();
    };

    // On window resize
    var resizeCharts;
    window.addEventListener('resize', function() {
        clearTimeout(resizeCharts);
        resizeCharts = setTimeout(function () {
            triggerChartResize();
        }, 200);
    });

    myChart.on('click', eConsole);
}