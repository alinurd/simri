<?php 
$badge=['primary', 'danger', 'success', 'info', 'warning', 'secondary', 'light'];
$no=0;
$filter='';
foreach ($search as $key=>$row):
    if ($no>=7)
        $no=0;
    $filter .="<span class='badge badge-".$badge[$no++]."'>".$row['field'].' : '.$row['value']."</span> ";
endforeach;
if ($search)
    echo 'Search by : ' . $filter . ' <a href="'.base_url(_MODULE_NAME_).'?cs=1"> <i class="icon-close2 pointer text-danger"></i></a>';
?>
<table class="table table-hover table-striped " id='datatable-list'>
    <thead>
        <tr>
            <th width='5%' class='text-center' style="padding-left:2px;"><input type="checkbox" class="form-check-input pointer" name="chk_list_parent" id="chk_list_parent"  style="padding:0;margin:0;"></th>
            <th width='5%' class='text-center'>#</th>
            <?php
            foreach($title as $row):?>
            <th class='text-<?=$row[4];?> <?=$row[5];?>' width='<?=$row[3];?>%'><?=$row[2];?></th>
            <?php endforeach;?> 
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<?php $pesan =$this->session->flashdata('message_crud');?>

<?php if($params['modal_box_search']):?>
<div id="modal_form_search" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-300">
                <h5 class="modal-title"><i class="icon-search4"></i> Search</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?php echo form_open($this->uri->uri_string,array('id'=>'form_input_search', 'class'=>'form-horizontal','role'=>'form"'));?>
                <div class="modal-body">
                    <?php
                    echo form_hidden(['sts_query'=>'1']);
                    foreach ($fields as $row):
                        if ($row['search']):
                    ?>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3"><?=$row['title'];?></label>
                        <div class="col-sm-9">
                            <?=$row['box'];?>
                        </div>
                    </div>
                        <?php endif; endforeach;?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-primary">Submit form</button>
                </div>
            </form>
        </div>
    </div>
</div>
                        <?php endif;?>
<script>
    var pesan = '<?=$pesan;?>';
    if (pesan.length>0)
        notif();
    var DatatableAdvanced = function() {
    //
    // Setup module components
    //

    // Basic Datatable examples
    var _componentDatatableAdvanced = function() {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [ 5 ]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
            }
        });

        // DOM positioning
        $('.datatable-dom-position').DataTable({
            dom: '<"datatable-header length-left"lp><"datatable-scroll"t><"datatable-footer info-right"fi>',
        });

        // Highlighting rows and columns on mouseover
        var lastIdx = null;
        this.oTable = $('#datatable-list').DataTable({
            lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
            pageLength:5,
            fixedColumns: {
        leftColumns: 2
    },
            columnDefs: [ 
                {
                    targets: [0,1,'no-sort'],
                    orderable: false
                }
            ],
            "fnDrawCallback": function( oSettings ) {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover();
                
                var len = $('input[name="chk_list[]"]:checked').length;
                if (len>0){
                    $('#btn_save_modul').removeClass('disabled');
                    $('#chk_list_parent').prop('checked', true);
                }else{
                    $('#btn_save_modul').addClass('disabled');
                    $('#chk_list_parent').prop('checked', false);
                }
            },
            ajax: {
                url: '<?php echo _MODULE_NAME_; ?>' + "/list-data",
                beforeSend: function() {
                    var col = $("#datatable-list > thead").find("tr:first th").length;
                    // Here, manually add the loading message.
                    $('#datatable-list > tbody').html(
                        '<tr class="odd">' +
                        '<td valign="top" colspan="' + col + '" class="dataTables_empty"><img src="<?= img_url('ajax-loader.gif') ?>"></td>' +
                        '</tr>'
                    );
                }
            },
            processing: true,
            bServerSide: true,
            language:{
                "decimal":        '<?=lang('decimal');?>',
                "emptyTable":     '<?=lang('emptyTable');?>',
                "info":           '<?=lang('info');?>',
                "infoEmpty":      '<?=lang('infoEmpty');?>',
                "infoFiltered":   '<?=lang('infoFiltered');?>',
                "infoPostFix":    '<?=lang('infoPostFix');?>',
                "thousands":      '<?=lang('thousands');?>',
                "lengthMenu":     '<?=lang('lengthMenu');?>',
                "loadingRecords": '<?=lang('loadingRecords');?>',
                "processing":     '<?=lang('processing');?>',
                "search":         '<?=lang('search');?>',
                "zeroRecords":    '<?=lang('zeroRecords');?>',
                "paginate": {
                    "first":      '<?=lang('first');?>',
                    "last":       '<?=lang('last');?>',
                    "next":       '<?=lang('next');?>',
                    "previous":   '<?=lang('previous');?>',
                },
                "aria": {
                    "sortAscending":  '<?=lang('sortAscending');?>',
                    "sortDescending": '<?=lang('sortDescending');?>',
                }
            }
        });
        // console.log(table);
        // return table;
    };

    // Select2 for length menu styling
    var _componentSelect2 = function() {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth: true,
            width: 'auto'
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentDatatableAdvanced();
            _componentSelect2();
        }
    }
    }();


    // Initialize module
    // ------------------------------

    document.addEventListener('DOMContentLoaded', function() {
    DatatableAdvanced.init();
});

$(function(){
    $('#chk_list_parent').click(function(event) {
        if(this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;
            });
            $('#btn_delete').removeClass('disabled');
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
            $('#btn_delete').addClass('disabled');
        }
    });

    $('#btn_delete').click(function(event) {
        var x=$(this);
        var data=[];
        var jml=0;
        $('input[name="chk_list[]"]:checked').each(function(){
            id = $(this).val();
            data.push(id);
            jml++;
        })

        if (jml>0){
        var notyConfirm = new Noty({
                text: '<h6 class="mb-3">Please confirm your action</h6><label>are you sure you want to permanently delete this '+jml+' data ?</label>',
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
                            looding('light',x.parent().parent());
                            $.ajax({
                                type:'post',
                                url:x.data('url'),
                                data:{id:data},
                                dataType: "json",
                                success:function(result){
                                    stopLooding(x.parent().parent());
                                    location.reload();
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
        }
    });

    $(document).on('click','input[name="chk_list[]"]', function(event) {
        var len = $('input[name="chk_list[]"]:checked').length;
        if (len>0){
            $('#btn_delete').removeClass('disabled');
            $('#chk_list_parent').prop('checked', true);
        }else{
            $('#btn_delete').addClass('disabled');
            $('#chk_list_parent').prop('checked', false);
        }
    });

    $(document).on('click', '#btn_search_card', function(){
        $('#box_search').toggle();
    })
});

function notif(){
    
    new Noty({
        layout: 'top',
        text: pesan,
        timeout: 5000,
        theme: ' p-0 bg-primary-300 text-center',
        type: 'info'
    }).show();
}
</script>