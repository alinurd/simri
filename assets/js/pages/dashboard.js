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
    $("#maps").html(hasil.combo);
    $("#result_grap1").html(hasil.grap1);
    $("#result_grap2").html(hasil.data_grap1);
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

function show_detail_char(hasil) {
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function grafik_pie(data, target) {
    var pie_basic_element = document.getElementById(target);
    var myChart = echarts.init(pie_basic_element);
console.log(pie_basic_element)
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