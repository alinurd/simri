var tbl_mitigasi;
$(function(){

	$('<input>').attr({
        type: 'hidden',
        id: 'idOfHiddenInput',
        name: 'idOfHiddenInput'
    }).appendTo('#datatable-list');
    
    $('#datatable-list').on( 'init.dt', function () {
        readyCheckbox();
    } ).DataTable().column(0).visible(false);
    $('#chk_list_parent').click(function(event) {
        if(this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;
            });
            $('#btn_save_modul').removeClass('disabled');
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
			$("#idOfHiddenInput").val('');
            $('#btn_save_modul').addClass('disabled');
        }
    });

	$('#btn_lap').click(function(event) {
		event.preventDefault();
        var x=$(this);
        var jml=0;
        var data = $("#idOfHiddenInput").val();
		let triggerDelay = 100;
		let removeDelay = 1000;

		if (data!=""){
			looding('light',x.parent().parent());
			$.ajax({
				type:'post',
				url:x.data('url'),
				data:{id:data},
				dataType: "json",
				success:function(result){
					stopLooding(x.parent().parent());
					$.each(result, function (index, val) {
						_createIFrame(val, index * triggerDelay, removeDelay);
					})
				},
				error:function(msg){
					stopLooding(x.parent().parent());
				},
				complate:function(){
				}
			})
		}
	});

	$('#btn_kri').click(function(event) {
		event.preventDefault();
        var x=$(this);
        var jml=0;
        var data = $("#idOfHiddenInput").val();
		let triggerDelay = 100;
		let removeDelay = 1000;

		if (data!=""){
			looding('light',x.parent().parent());
			$.ajax({
				type:'post',
				url:x.data('url'),
				data:{id:data},
				dataType: "json",
				success:function(result){
					stopLooding(x.parent().parent());
					$.each(result, function (index, val) {
						_createIFrame(val, index * triggerDelay, removeDelay);
					})
				},
				error:function(msg){
					stopLooding(x.parent().parent());
				},
				complate:function(){
				}
			})
		}
	});

	$(document).on('click','input[name="chk_list[]"]', function (event) {
        
    
        var len = $('input[name="chk_list[]"]:checked').length;
        if (len>0){
            $('#btn_save_modul').removeClass('disabled');
            $('#chk_list_parent').prop('checked', true);
        }else{
            $('#btn_save_modul').addClass('disabled');
            $('#chk_list_parent').prop('checked', false);
        }
        updateCheckboxes($(this));
    });

	$(document).on("click","#btn_reset_one",function () {
		if(confirm("Anda akan membatalkan approval untuk progress mitigasi ini, \nYakin akan melanjutkan ?")){
			var parent = $(this).parent();
			var nilai = $(this).attr('data-id');
			var data = {'id': nilai};
			var url = modul_name+"/reset-approval";
			_ajax_("post", parent, data, '', url, 'reset_approval');
		}
	})

	$(document).on("click",".btnNext", function () {
		$('.nav-tabs').find('.active').closest('li').next('li').find('a').trigger('click');
	});

	$(document).on("click",".btnPrevious", function () {
		$('.nav-tabs').find('.active').closest('li').prev('li').find('a').trigger('click');
	});

	$(document).on('click','.delete-progres', function(){
        var objek = $(this);
        var notyConfirm = new Noty({
                    text: Globals.hapus,
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

                    Noty.button('Delete <i class="icon-paperplane ml-2"></i>', 'btn bg-blue ml-1', function () {
                            notyConfirm.close();
                            var parent = objek.parent().parent().parent();
                            var nilai = objek.data('id');
                            var mitigasi = objek.data('mitigasi');
                            var data = {
                                'id': nilai,
                                'mitigasi_id': mitigasi
                            };
                            var target_combo = '';
                            var url = modul_name + "/hapus-progres";
                            _ajax_("post", parent, data, target_combo, url, 'del_progres');
                        },
                        {id: 'button1', 'data-status': 'ok'}
                    )
                ]
                }).show();
	})

	$(document).on("click",".add-kri", function () {
		var parent = $(this).parent();
		// var kpi = $(this).data('parent');
		var id = $(this).data('id');
		var rcsa_id = $(this).data('parent');
		var minggu = $(this).data('minggu');
		var data={'kpi_id':id, 'minggu':minggu, 'rcsa_id':rcsa_id, 'edit_id':0};
		var url = modul_name+"/kri-add";
		_ajax_("post", parent, data, '', url, 'indikator_kri');
	})

	$(document).on("click",".edit-kri", function () {
		var parent = $(this).parent();
		var kpi = $(this).data('parent');
		var id = $(this).data('id');
		var rcsa_id = $(this).data('rcsa');
		var minggu = $(this).data('minggu');
		var data={'kpi_id':kpi, 'edit_id':id,'minggu':minggu, 'rcsa_id':rcsa_id};
		var target_combo = $(".entri_kri");
		var url = modul_name+"/kri-edit";
		// _ajax_("post", parent, data, target_combo, url);

		_ajax_("post", parent, data, '', url, 'indikator_kpi');
	})

	$(document).on("click","#add_kri", function () {
		var parent = $(this).parent();
		var kpi = $(this).data('parent');
		var id = $(this).data('id');
		var rcsa_id = $(this).data('rcsa');
		var minggu = $(this).data('minggu');
		var data={'kpi_id':kpi, 'edit_id':id,'minggu':minggu, 'rcsa_id':rcsa_id};
		var target_combo = $(".entri_kri");
		var url = modul_name+"/kri-edit";
		_ajax_("post", parent, data, '', url, 'indikator_kpi');

		// _ajax_("post", parent, data, target_combo, url);
	})

	$(document).on('click','#simpan_kri', function(){
		var parent = $(this).parent().parent().parent();
		
		var data = $("#form_kri").serialize();
		var target_combo = $("#parent_risk");
		// var target_combo = $("#list_kri");
		var url = modul_name + "/simpan-kri";
		
		_ajax_("post", parent, data, target_combo, url, 'indikator_kri');
	})

	$(document).on('click','#back_list_kri', function(){
		var parent = $(this).parent();
		var id = $(this).data('rcsa');
		var kpi_id = $("input[name='kpi_id']").data('rcsa');
		var minggu = $(this).data('minggu');

		var data={'id':id,'minggu':minggu, 'kpi_id':kpi_id};
		var url = modul_name + "/list-kpi";
		_ajax_("post", parent, data, '', url, 'indikator_kpi');
	})

	$(document).on("click",".delete-kri", function () {
		var parent = $(this).parent();
		var id = $(this).data('id');
		var parent_id = $(this).data('parent');
		var rcsa_id = $(this).data('rcsa');
		var minggu = $(this).data('minggu');
		var data={'kpi_id':parent_id, 'minggu':minggu, 'rcsa_id':rcsa_id, 'edit_id':id};
		var url = modul_name+"/kri-delete";
		_ajax_("post", parent, data, '', url, 'indikator_kri');
	})

	$(document).on("click","#add_kpi", function () {
		var parent = $(this).parent();
		var lap_id = $(this).data('parent');
		var minggu = $(this).data('minggu');
		var data={'minggu':minggu, 'rcsa_id':lap_id,'edit_id':0};
		var url = modul_name+"/kpi-add";
		_ajax_("post", parent, data, '', url, 'indikator_kpi');
	})
	
	$(document).on("click",".edit_kpi", function () {
		var parent = $(this).parent();
		var id = $(this).data('id');
		var lap_id = $(this).data('parent');
		var minggu = $(this).data('minggu');
		var data={'minggu':minggu, 'rcsa_id':lap_id,'edit_id':id};
		var url = modul_name+"/kpi-add";
		_ajax_("post", parent, data, '', url, 'indikator_kpi');
	})

	$(document).on("click",".delete_kpi", function () {
		var parent = $(this).parent();
		var id = $(this).data('id');
		var lap_id = $(this).data('parent');
		var minggu = $(this).data('minggu');
		var data={'minggu':minggu, 'rcsa_id':lap_id,'edit_id':id};
		var url = modul_name+"/kpi-delete";
		_ajax_("post", parent, data, '', url, 'indikator_kpi');
	})

	$(document).on("click","#back_list_kpi", function () {
		var parent = $(this).parent();
		var id = $(this).data('id');
		var minggu = $(this).data('minggu');

		var data={'id':id,'minggu':minggu};
		var url = modul_name + "/list-kpi";
		_ajax_("post", parent, data, '', url, 'indikator_kpi');
	})

	$(document).on('click','#input-kpi', function(){
		var parent = $(this).parent().parent().parent();
		var nilai = $(this).data('id');
		var minggu = $("#minggu").val();
		if (minggu.length>0){
			var data = {'id': nilai,'minggu':minggu};
			var url = modul_name + "/list-kpi";
			_ajax_("post", parent, data, '', url, 'indikator_kpi');
		}else{
			alert('Anda belum memilih data bulan pelaporan');
		}
	})

	$(document).on('click','#simpan_kpi', function(){
		var parent = $(this).parent().parent().parent();
		
		var data = $("#form_like_indi").serialize();
		var target_combo = $("#parent_risk");
		var url = modul_name + "/simpan-kpi";
		_ajax_("post", parent, data, target_combo, url, 'indikator_kpi');
	})

	$(document).on('click','#add_progres', function(){
		var parent = $(this).parent().parent().parent();
		var nilai = $(this).data('id');
		var data = {
			'mitigasi_id': nilai,
			'id': 0,
		};
		var target_combo = $("#entry_progres");
		var url = modul_name + "/add-progres";
		_ajax_("post", parent, data, target_combo, url);
	})

	$(document).on('click','.update-progres', function(){
		var parent = $(this).parent().parent().parent();
		var mitigasi = $(this).data('mitigasi');
		var id = $(this).data('id');
		var data = {
			'mitigasi_id': mitigasi,
			'id':id
		};
		var target_combo = $("#entry_progres");
		var url = modul_name + "/add-progres";
		_ajax_("post", parent, data, target_combo, url);
		
	})

	$(document).on('click','#simpan_progres', function(){
		var uraian=$("#uraian").val();
		var target=$("#target").val();
		var aktual=$("#aktual").val();
		if (uraian.length>0 && target.length>0 && aktual.length>0){
			var parent = $(this).parent().parent().parent();
			var data = $("#form_progres").serialize();
			var url = modul_name + "/simpan-progres";
			_ajax_("post", parent, data, '', url, 'result_progres');
		}else{
			alert("Target, Aktual dan Uraian wajib diisi!");
			$("#uraian").focus();
		}
	})

	// $(document).on('click','#proses_propose_mitigasi', function(){
	// 	var parent = $(this).parent().parent().parent();
	// 	// var data = $("#form_general").serialize();
	// 	var form = $('#form_general').get(0);
	// 	var data = new FormData(form);
	// 	var url = modul_name + "/proses-propose-mitigasi";
	// 	_ajax_file_("post", parent, data, '', url, 'result_progres');
	// })

	$(document).on('click','#view-kpi', function(){
		var parent = $(this).parent().parent().parent();
		var id = $(this).data('id');
		var minggu = $('#minggu').val();
		var data = {'rcsa_id':id, 'minggu':minggu};
		var url = modul_name + "/view-kpi";
		_ajax_file_("post", parent, data, '', url, 'view_kpi');
	})

	$(document).on('click','.review-kpi', function(){
		var parent = $(this).parent().parent().parent();
		var id = $(this).data('id');
		var minggu = 0;
		var data = {'rcsa_id':id, 'minggu':minggu};
		var url = modul_name + "/review-kpi";
		_ajax_("post", parent, data, '', url, 'view_kpi');
	})

	$(document).on('click','.propose-mitigasi', function(){
		var parent = $(this).parent().parent().parent();
		var id = $(this).data('id');
		var data = {
			'id':id
		};
		var url = "/progress-mitigasi/propose/"+id;
		
		var notyConfirm = new Noty({
			text: '<h6 class="mb-3">Please confirm your action</h6><label>Anada akan mengajukan propose Mitigasi, yakin akan melanjutkan ?</label>',
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

				Noty.button('Propose <i class="icon-paperplane ml-2"></i>', 'btn bg-blue ml-1', function () {
						notyConfirm.close();
						// _ajax_("post", parent, data, '', url, 'propose_mitigasi');
						window.location = url;

					},
					{id: 'button1', 'data-status': 'ok'}
				)
			]
		}).show();
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

	$("#period_id").change(function() {
        var parent = $(this).parent();
        var nilai = $(this).val();
        var data = {
            'id': nilai
        };
        var target_combo = $("#term_id");
        var url = "ajax/get-term";
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
});


var checkboxes = [];
function readyCheckbox() {
    $('input[name="chk_list[]"]:checked').each(function(){
        id = $(this).val();
        var arrPos = checkboxes.indexOf(id);
        if(arrPos == -1){
            checkboxes.push(id);
        }
    });

    setTimeout(function () {
        $('input[name="chk_list[]"]').each(function(index){
            idx = $(this).val();
            var arrPosx = checkboxes.indexOf(idx);
            if(!$(this).is(":checked")){
                if(arrPosx > -1){
                    checkboxes.splice(arrPosx,1);
                }
            }
        });
        $("#idOfHiddenInput").val(checkboxes);
    }, 200)

    // $("#idOfHiddenInput").val(checkboxes);

}

function _createIFrame(url, triggerDelay, removeDelay) {
    //Add iframe dynamically, set SRC, and delete
    setTimeout(function() {
        var frame = $('<iframe style="display: none;" class="multi-download"></iframe>');
        frame.attr('src', url);
        $(document.body).after(frame);
        setTimeout(function() {
            frame.remove();
        }, removeDelay);
    }, triggerDelay);
}

function updateCheckboxes(checkbox){
    //Get the row id
    var id = checkbox.val();

    //Check the array for the id
    var arrPos = checkboxes.indexOf(id);

    //If it exists and we unchecked it, remove it
    if(arrPos > -1 && !checkbox.checked){
        checkboxes.splice(arrPos,1);
    }
    //Else it doesn't exist and we checked it
    else 
    {
        checkboxes.push(id);
    }

    setTimeout(function () {
        $('input[name="chk_list[]"]').each(function(index){
            idx = $(this).val();
            var arrPosx = checkboxes.indexOf(idx);
            if(!$(this).is(":checked")){
                if(arrPosx > -1){
                    checkboxes.splice(arrPosx,1);
                }
            }
        });
      
        $("#idOfHiddenInput").val(checkboxes);
    }, 200)
}

function del_progres(hasil){
    $("#list_progres").html(hasil.list_progres);
    alert(hasil.combo);
}

function list_progres(hasil){
	$("#entry_progres").html(hasil.update);
	$("#list_progres").html(hasil.list_progres);
}

function propose_mitigasi(hasil){
	$("#modal_general").find(".modal-title").html('Propose Mitigasi');
	$("#modal_general").find(".modal-body").html(hasil.combo);
	$("#modal_general").find(".modal-footer").addClass('d-none');
	$("#modal_general").modal("show");
}

function result_progres(hasil){
	location.reload();
}

function indikator_kpi(hasil){
	$("#modal_general").find(".modal-title").html('Daftar KPI');
	$("#modal_general").find(".modal-body").html(hasil.combo);
	$("#modal_general").find(".modal-footer").addClass('d-none');
	$("#modal_general").modal("show");
}

function indikator_kri(hasil){
	$("#modal_general").find(".modal-title").html('Daftar KRI');
	$("#modal_general").find(".modal-body").html(hasil.combo);
	$("#modal_general").find(".modal-footer").addClass('d-none');
	$("#modal_general").modal("show");
}

function view_kpi(hasil){
	$("#modal_general").find(".modal-title").html('Preview KRI');
	$("#modal_general").find(".modal-body").html(hasil.combo);
	$("#modal_general").find(".modal-footer").removeClass('d-none');
	$("#modal_general").modal("show");
}

function reset_approval(hasil){
	location.reload();
}