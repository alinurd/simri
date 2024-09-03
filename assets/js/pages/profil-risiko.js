$(function () {

    $('<input>').attr({
        type: 'hidden',
        id: 'idOfHiddenInput',
        name: 'idOfHiddenInput'
    }).appendTo('#datatable-list');

    $('<input>').attr({
        type: 'hidden',
        id: 'idOri',
        name: 'idOri'
    }).appendTo('#datatable-list');

    $('#datatable-list').on('init.dt', function () {
        readyCheckbox();
    }).DataTable().column(0).visible(false);

    $('#chk_list_parent').click(function (event) {
        if ($(this).is(":checked")) {
            // Iterate each checkbox
            var len = $('input[name="chk_list[]"]:checked').length;
            if (len > 0) {
                $('input[name="chk_list[]').each(function () {
                    $(this).prop('checked', true);
                });
                $('#btn_save_modul').removeClass('disabled');
            } else {
                $('input[name="chk_list[]').each(function () {
                    $(this).prop('checked', false);
                });
                setTimeout(function () {

                    readyCheckbox();

                }, 200)
                // $('#btn_save_modul').addClass('disabled');
                $("#idOfHiddenInput").val("");

            }

        } else {
            $('input[name="chk_list[]').each(function () {
                $(this).prop('checked', false);
            });
            // $('#btn_save_modul').addClass('disabled');
            // setTimeout(function () {

            //     readyCheckbox();

            // }, 200)

            $("#idOfHiddenInput").val("");
        }
    });


    $('#btn_save_modul').click(function(event) {
        var x=$(this);
        // var jml=0;
        var jml = $('input[type="checkbox"]:checked').length-1; 


        var data = $("#idOfHiddenInput").val();
        var dataOri = $("#idOri").val();
        // var period = $("#period").val();
        // var term = $("#term").val();
        var is_admin = $('input[name="is_admin"]').val();
        var owner = $('input[name="owner"]').val();
        if (data != "") {
            var dt = data;
        } else {
            var dt = "";
        }

        if (dataOri != "") {
            var dtOri = dataOri;
        } else {
            var dtOri = "";
        }

        var datax = {

            id:dt,
            dtori:dtOri,
            is_admin:is_admin, 
            owner:owner
        }; 
        // if (data!=""){
            var cek = cek_isian_identifikasi();
            // if (cek) {
                var notyConfirm = new Noty({
                    text: '<h6 class="mb-3">Konfirmasi</h6><label>Apa Anda yakin akan merubah '+jml+' data  tersebut pada Dashboard Profil Risiko ?</label>',
                    timeout: false,
                    modal: true,
                    layout: 'center',
                    theme: '  p-0 bg-white',
                    closeWith: 'button',
                    type: 'confirm',
                    buttons: [
                        Noty.button('Cancel', 'btn btn-link', function () {
                            notyConfirm.close();
                        }),
    
                        Noty.button('Ubah Data <i class="icon-paperplane ml-2"></i>', 'btn bg-blue ml-1', function () {
                                notyConfirm.close();
                                looding('light',x.parent().parent());
                                $.ajax({
                                    type:'post',
                                    url:x.data('url'),
                                    data:datax,
                                    dataType: "json",
                                    success:function(result){
                                        stopLooding(x.parent().parent());
                                        console.log(result);
                                        alert("data berhasil disimpan");
                                        // oTable.ajax.reload()
                                        oTable.ajax.url(modul_name+ "/list-data").load(function () {
                                            setTimeout(function () {
                                
                                                readyCheckbox();
                                              
                                            }, 200)
                                        } );
                                        // location.reload();
                                    },
                                    error:function(msg){
                                        stopLooding(x.parent().parent());
                                    },
                                    complate:function(){
                                    }
                                })
                            },
                            {id: 'button1', 'data-status': 'ok'}
                        )
                    ]
                }).show();
            // }else{
            //     alert(pesan);
            // }

             
        // if (data!=""){
        // var cek = cek_isian_identifikasi();
        // if (cek) {
        // var notyConfirm = new Noty({
        //     text: '<h6 class="mb-3">Konfirmasi</h6><label>Apa Anda yakin akan merubah ' + jml + ' data tersebut pada Dashboard Profil Risiko ?</label>',
        //     timeout: false,
        //     modal: true,
        //     layout: 'center',
        //     theme: '  p-0 bg-white',
        //     closeWith: 'button',
        //     type: 'confirm',
        //     buttons: [
        //         Noty.button('Cancel', 'btn btn-link', function () {
        //             notyConfirm.close();
        //         }),

        //         Noty.button('Ubah Data <i class="icon-paperplane ml-2"></i>', 'btn bg-blue ml-1', function () {
        //             notyConfirm.close();
        //             looding('light', x.parent().parent());
        //             $.ajax({
        //                 type: 'post',
        //                 url: x.data('url'),
        //                 data: datax,
        //                 dataType: "json",
        //                 success: function (result) {
        //                     stopLooding(x.parent().parent());
        //                     console.log(result);
        //                     alert("data berhasil disimpan");
        //                     // oTable.ajax.reload()
        //                     oTable.ajax.url(modul_name + "/list-data").load(function () {
        //                         setTimeout(function () {

        //                             readyCheckbox();

        //                         }, 200)
        //                     });
        //                     // location.reload();
        //                 },
        //                 error: function (msg) {
        //                     stopLooding(x.parent().parent());
        //                 },
        //                 complate: function () {
        //                 }
        //             })
        //         },
        //             { id: 'button1', 'data-status': 'ok' }
        //         )
        //     ]
        // }).show();
        // }else{
        //     alert(pesan);
        // }

        // }
    });

    $(document).on('click', 'input[name="chk_list[]"]', function (event) {
        var len = $('input[name="chk_list[]"]:checked').length;
        if (len > 0) {
            $('#btn_save_modul').removeClass('disabled');
            $('#chk_list_parent').prop('checked', true);
        } else {
            // $('#btn_save_modul').addClass('disabled');
            $('#chk_list_parent').prop('checked', false);
        }
        updateCheckboxes($(this));
    });


    $("#period_id").change(function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        // var target_combo = $("#term_id");
        // var url = "ajax/get-term";

        var target_combo = $("#bulan_id");

        // var url = "ajax/get-term";
        var url = "ajax/get-minggu-by-tahun";

        _ajax_("post", parent, data, target_combo, url);
    })

    $("#period").change(function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        // var target_combo = $(".term");
        var target_combo = $(".minggu");

        // var url = "ajax/get-term";
        var url = "ajax/get-minggu-by-tahun";
        _ajax_("post", parent, data, target_combo, url);
    })

    $("#term").change(function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        var target_combo = $(".minggu");
        var url = "ajax/get-minggu";
        _ajax_("post", parent, data, target_combo, url);
    })

    $("#term_id").change(function () {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        var target_combo = $("#minggu_id");
        var url = "ajax/get-minggu";
        _ajax_("post", parent, data, target_combo, url);
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
        var url = "profil-risiko/get-detail-map";
        _ajax_("post", parent, data, target_combo, url, 'list_map');
    })
    $(document).on('click', '.detail-rcsa', function () {
        var parent = $(this).parent().parent().parent();
        var id = $(this).data('id');

        var dampak = $(this).data('dampak');

        var data = { 'id': id, 'dampak_id': dampak };
        var target_combo = '';
        var url = "ajax/get-detail-rcsa";
        _ajax_("post", parent, data, target_combo, url, 'list_mitigasi');
    })

    $(document).on('click', '.col-prog', function (e) { e.stopPropagation() });

    $(document).on('click', '.progress', function (e) {
        e.stopPropagation()
        var parent = $(this).parent();
        var id = $(this).parent().parent().data('id');

        var owner = $("#owner").val();
        var period = $("#period").val();
        var type_ass = $("#type_ass").val();
        var term_mulai = $("#term_mulai").val();
        var term_akhir = $("#term_akhir").val();
        // var minggu = $("#minggu").val();
        var data = { 'id': id, 'period': period, 'owner': owner, 'type_ass': type_ass, 'term_mulai': term_mulai, 'term_akhir': term_akhir };
        var target_combo = '';
        var url = modul_name + "/get-progress";

        _ajax_("post", parent, data, target_combo, url, 'list_progress');
    })

    $(document).on('click', '.review-kpi', function (e) {
        e.stopPropagation()
        var parent = $(this).parent();
        var id = $(this).parent().parent().data('id');
        var rcsa = $(this).parent().parent().data('rcsa');

        var owner = $("#owner").val();
        var period = $("#period").val();
        var type_ass = $("#type_ass").val();
        var term_mulai = $("#term_mulai").val();
        var term_akhir = $("#term_akhir").val();
        // var minggu = $("#minggu").val();
        var data = { 'rcsa_id': rcsa, id: id, 'period': period, 'owner': owner, 'type_ass': type_ass, 'term_mulai': term_mulai, 'term_akhir': term_akhir };
        var target_combo = '';
        var url = modul_name + "/review-kpi";

        _ajax_("post", parent, data, target_combo, url, 'list_progress');
    })

    $(document).on('click', '.ketepatan', function (e) {
        e.stopPropagation()
        var parent = $(this).parent();
        var id = $(this).parent().parent().data('id');
        var rcsa = $(this).parent().parent().data('rcsa');

        var data = { 'id': id, 'rcsa': rcsa };
        var target_combo = '';

        var url = modul_name + "/get-ketepatan";

        _ajax_("post", parent, data, target_combo, url, 'get_chart');
    })

    $(document).on('click', '#back_list', function (e) {

        var parent = $(this).parent();
        var id = $(this).data('id');

        var owner = $("#owner").val();
        var period = $("#period").val();
        var type_ass = $("#type_ass").val();
        var term_mulai = $("#term_mulai").val();
        var term_akhir = $("#term_akhir").val();
        // var minggu = $("#minggu").val();
        var data = { 'id': id, 'period': period, 'owner': owner, 'type_ass': type_ass, 'term_mulai': term_mulai, 'term_akhir': term_akhir };
        var target_combo = '';
        var url = modul_name + "/get-progress";

        _ajax_("post", parent, data, target_combo, url, 'list_progress');
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
        var term_mulai = $("#term_mulai").val();
        var term_akhir = $("#term_akhir").val();
        var notif = 'Silahkan isi ';
        var n = false;
        if (term_mulai == 0) {
            notif += 'bulan awal ';
            n = true;
            var nn = true;
        }
        if (term_akhir == 0) {
            if (nn) {
                notif += 'dan ';
            }
            n = true;
            notif += 'bulan akhir ';
        }

        if (n) {
            alert(notif)
            return false
        }
        // var minggu = $("#minggu").val();
        var data = { 'period': period, 'owner': owner, 'type_ass': type_ass, 'term_mulai': term_mulai, 'term_akhir': term_akhir };
        var url = modul_name + "/get-map";
        _ajax_("post", parent, data, '', url, 'result_map');
    });

    $(document).on('click', '.detail-progress', function () {
        var parent = $(this).parent().parent().parent();
        var id = $(this).data('id');

        var rcsa = $(this).data('rcsa');

        var data = { 'id': id, 'rcsa': rcsa };
        var target_combo = '';
        var url = modul_name + "/get-monitoring";
        _ajax_("post", parent, data, target_combo, url, 'list_mitigasi');
    })

    $(document).on('click', '#proses_check', function () {
        var parent = $(this).parent().parent().parent();
        var period = $("#period").val();
        var term = $("#term").val();
        var data = { 'period': period, 'term': term };

        oTable.ajax.url(modul_name + "/list-data?period=" + period + "&term=" + term).load(function () {
            setTimeout(function () {

                readyCheckbox();

            }, 200)
        });

    });

    $(document).ready(function () {
        $("#period").trigger('change');
        // $("#period_id").trigger('change');
        // $("#term_id").trigger('change');
        // $("#proses_check").trigger('click');
    })

});

var checkboxes = [];

function readyCheckbox() {
    var lens = checkboxes.length;

    $('input[name="chk_list[]"]:checked').each(function () {
        id = $(this).val();
        var arrPos = checkboxes.indexOf(id);
        if (arrPos == -1) {
            checkboxes.push(id);
        }
    });
    // if (lens>0) {
    setTimeout(function () {
        $('input[name="chk_list[]"]').each(function (index) {
            idx = $(this).val();
            var arrPosx = checkboxes.indexOf(idx);
            if (!$(this).is(":checked")) {
                if (arrPosx > -1) {
                    checkboxes.splice(arrPosx, 1);
                }
            }
        });
        // $("#idOfHiddenInput").val(checkboxes);
        // $("#idOri").val(checkboxes);
    }, 200)
    // }
    // let checkboxesX = checkboxes.filter((c, index) => {
    //     return checkboxes.indexOf(c) === index;
    // });
    $("#idOfHiddenInput").val(checkboxes);
    $("#idOri").val(checkboxes);

    // console.log($("#idOfHiddenInput").val())

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
            formatter: function (params) {
                return params.name;
            }
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

function updateCheckboxes(checkbox) {
    //Get the row id
    var id = checkbox.val();
    var lens = checkboxes.length;
    // if(lens == 0){
    //     checkboxes = [];
    // }
    //Check the array for the id
    var arrPos = checkboxes.indexOf(id);


    //If it exists and we unchecked it, remove it
    if (arrPos > -1 && !checkbox.is(":checked")) {
        checkboxes.splice(arrPos, 1);
    }
    //Else it doesn't exist and we checked it
    else {
        checkboxes.push(id);
    }

    let checkboxesX = checkboxes.filter((c, index) => {
        return checkboxes.indexOf(c) === index;
    });

    $("#idOfHiddenInput").val(checkboxesX);

}

function list_map(hasil) {
    // $('#result').html(hasil.combo);
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function list_mitigasi(hasil) {
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function list_progress(hasil) {
    $("#modal_general").find(".modal-body").html(hasil.combo);
    $("#modal_general").modal("show");
}

function get_chart(hasil) {
    $("#modal_general").find(".modal-body").html(hasil.grap2);
    $("#modal_general").modal("show");
}

function list_aktifitas_mitigasi(hasil) {
    $('#result_aktifitas_mitigasi').html(hasil.combo);
}
function list_progres_aktifitas_mitigasi(hasil) {
    $('#result_progres_aktifitas_mitigasi').html(hasil.combo);
}

function result_map(hasil) {
    $("#maps").html(hasil.combo);
    $(".range").html(hasil.range);
    $("#detail_list").html(hasil.detail_list);
    $("#kpi").html(hasil.kpi);
    $("#progress").html(hasil.progress);
    // $("#result_grap1").html(hasil.grap1);
    // $("#result_grap2").html(hasil.data_grap1);
}

function cek_isian_identifikasi(awal = false) {
    var hasil = true;
    pesan = 'data dibawah ini wajib diisi:\n';
    if (isNaN(parseFloat($('#period').val()))) {
        hasil = false;
        pesan += '- Tahun\n';
    }

    if (isNaN(parseFloat($('#term').val()))) {
        hasil = false;
        pesan += '- Periode\n';
    }

    return hasil;
}