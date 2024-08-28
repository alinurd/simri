<div class="row">
    <div class="col-md-12">
        <div id="overdue">
            <h3>
                <a data-toggle="collapse" href="#collapseOverdue" role="button" aria-expanded="false"
                    aria-controls="collapseOverdue">
                    <legend class="text-uppercase font-size-lg text-danger font-weight-bold"><i class="icon-grid"></i>
                        Melewati Batas Waktu</legend>
                </a>
            </h3>
            <div class="collapse" id="collapseOverdue">
                <table class="table table-hover" id="tbl_list_mitigasi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th><?= _l( 'fld_owner_name' ); ?></th>
                            <th><?= _l( 'risiko_dept' ); ?></th>
                            <th><?= _l( 'fld_due_date' ); ?></th>
                            <th><?= _l( 'fld_tgl_update' ); ?></th>
                            <th><?= _l( 'pic' ); ?></th>
                            <th>Days Overdue</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach( $overdue as $q ) :
                            $arr_pic = json_decode( $q['penanggung_jawab_id'] );
                            $rows    = $this->db->where_in( 'owner_no', $arr_pic )->where( 'sts_owner', 1 )->get( _TBL_VIEW_OFFICER )->result_array();
                            $log     = $this->db->where( 'ref_id', $q['id'] )->get( "il_log_send_email" )->result_array();
                            $pic     = '';
                            if( ! empty( $rows ) )
                            {
                                foreach( $rows as $row )
                                {
                                    $pic .= $row['owner_name'] . ', ';
                                }
                                $pic = rtrim( $pic, ', ' );
                            }

                            $batas_waktu       = new DateTime( $q['batas_waktu'] );
                            $today             = new DateTime();
                            $interval          = $today->diff( $batas_waktu );
                            $days_overdue      = $interval->days;
                            $days_overdue_sign = $interval->invert ? '-' : '';
                            ?>
                            <tr class="<?= ( $days_overdue_sign == '-' && $days_overdue > 3 ) ? 'table-danger' : '' ?>">
                                <td><?= $no++ ?></td>
                                <td><b><?= $q['kode_dept'] ?></b><?= $q['owner_name'] ?></td>
                                <td><?= $q['risiko_dept'] ?></td>
                                <td><?= $q['batas_waktu'] ?></td>
                                <td><?= $q['updated_at'] ?></td>
                                <td><?= $pic ?></td>
                                <td><?= $days_overdue_sign . $days_overdue ?></td>
                                <td class="text-center">
                                    <span class="btn btn-light" id="cekLog" data-id="<?= $q['id'] ?>">Histori</span>
                                    <span class="btn btn-light" id="sendEmail" data-id="<?= $q['id'] ?>">Remider</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div id="upcoming">
            <h3>
                <a data-toggle="collapse" href="#collapseupcoming" role="button" aria-expanded="false"
                    aria-controls="collapseupcoming">
                    <legend class="text-uppercase font-size-lg text-info font-weight-bold"><i class="icon-grid"></i>
                        Mendekati Batas Waktu (h-7)</legend>
                </a>
            </h3>
            <div class="collapse" id="collapseupcoming">
                <table class="table table-hover" id="tbl_list_mitigasi">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th><?= _l( 'fld_owner_name' ); ?></th>
                            <th><?= _l( 'risiko_dept' ); ?></th>
                            <th><?= _l( 'fld_due_date' ); ?></th>
                            <th><?= _l( 'fld_tgl_update' ); ?></th>
                            <th><?= _l( 'pic' ); ?></th>
                            <th>Days Left</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach( $upcoming as $q ) :
                            $arr_pic = json_decode( $q['penanggung_jawab_id'] );
                            $rows    = $this->db->where_in( 'owner_no', $arr_pic )->where( 'sts_owner', 1 )->get( _TBL_VIEW_OFFICER )->result_array();
                            $pic     = '';
                            if( ! empty( $rows ) )
                            {
                                foreach( $rows as $row )
                                {
                                    $pic .= $row['owner_name'] . ', ';
                                }
                                $pic = rtrim( $pic, ', ' );
                            }

                            $batas_waktu    = new DateTime( $q['batas_waktu'] );
                            $today          = new DateTime();
                            $interval       = $today->diff( $batas_waktu );
                            $days_left      = $interval->days;
                            $days_left_sign = $interval->invert ? '-' : '';
                            ?>
                            <tr class="<?= ( $days_left_sign == '-' && $days_left > 3 ) ? 'table-danger' : '' ?>">
                                <td><?= $no++ ?></td>
                                <td><b><?= $q['kode_dept'] ?></b><?= $q['owner_name'] ?></td>
                                <td><?= $q['risiko_dept'] ?></td>
                                <td><?= $q['batas_waktu'] ?></td>
                                <td><?= $q['updated_at'] ?></td>
                                <td><?= $pic ?></td>
                                <td><?= $days_left_sign . $days_left ?></td>
                                <td class="text-center">
                                    <span class="btn btn-light" id="cekLog" data-id="<?= $q['id'] ?>">Histori</span>
                                    <span class="btn btn-light" id="sendEmail" data-id="<?= $q['id'] ?>">Remider</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div id="qa">
            <h3>
                <a data-toggle="collapse" href="#collapseQa" role="button" aria-expanded="true"
                    aria-controls="collapseQa">
                    <legend class="text-uppercase font-size-lg text-primary font-weight-bold"><i class="icon-grid"></i>
                        Frequently Asked Question</legend>
                </a>
            </h3>
            <div class="collapse.show" id="collapseQa">
                <table class="table table-hover" id="tbl_list_qa">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Id</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Created at</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if( ! empty( $faq ) )
                        {
                            foreach( $faq as $kFaq => $vFaq )
                            { ?>
                                <tr>
                                    <td><?= $kFaq + 1 ?></td>
                                    <td><?= $vFaq["id"] ?></td>
                                    <td><?= $vFaq["faq"] ?></td>
                                    <td><?= $vFaq["answer"] ?></td>
                                    <td>
                                        <?= ! empty( $vFaq["created_at"] ) ? date( "Y-m-d", strtotime( $vFaq["created_at"] ) ) : "" ?>
                                    </td>
                                    <td><a class='list-icons-item' data-action='collapse'></a></td>
                                </tr>
                                <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    <?php
    $setLabelDatatable = '<span class="text-info" style="font-style: italic;"><i class="fa fa-info-circle"></i> Untuk Pertanyaan Lebih Lanjut Silahkan Menghubungi Manajemen Risiko. </span>';
    ?>
    $(document).ready(function () {
        $('#tbl_list_mitigasi').DataTable({
            pageLength: 10,
            fixedHeader: true,
            language: {
                "decimal": '<?= lang( 'decimal' ); ?>',
                "emptyTable": '<?= lang( 'emptyTable' ); ?>',
                "info": '<?= lang( 'info' ); ?>',
                "infoEmpty": '<?= lang( 'infoEmpty' ); ?>',
                "infoFiltered": '<?= lang( 'infoFiltered' ); ?>',
                "infoPostFix": '<?= lang( 'infoPostFix' ); ?>',
                "thousands": '<?= lang( 'thousands' ); ?>',
                "lengthMenu": '<?= lang( 'lengthMenu' ); ?>',
                "loadingRecords": '<?= lang( 'loadingRecords' ); ?>',
                "processing": '<img src="<?= img_url( 'ajax-loader.gif' ) ?>">',
                "search": '<?= lang( 'search' ) . ' &nbsp; '; ?>',
                "zeroRecords": '<?= lang( 'zeroRecords' ); ?>',
                "paginate": {
                    "first": '<?= lang( 'first' ); ?>',
                    "last": '<?= lang( 'last' ); ?>',
                    "next": '<?= lang( 'next' ); ?>',
                    "previous": '<?= lang( 'previous' ); ?>',
                },
                "aria": {
                    "sortAscending": '<?= lang( 'sortAscending' ); ?>',
                    "sortDescending": '<?= lang( 'sortDescending' ); ?>',
                }
            },
            dom: "<'row'<'col-sm-5'i><'col-sm-7'p><'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        });

        var qaTable = $('#tbl_list_qa').DataTable({
            pageLength: 10,
            fixedHeader: true,
            columns: [
                { 'data': 'no' },
                { 'data': 'id', 'visible': false },
                { 'data': 'faq' },
                { 'data': 'answer', 'visible': false },
                { 'data': 'created_at' },
                {
                    'className': 'details-control',
                    'orderable': false,
                    'data': 'button',
                    'defaultContent': ''
                },
            ],
            language: {
                "decimal": '<?= lang( 'decimal' ); ?>',
                "emptyTable": '<?= lang( 'emptyTable' ); ?>',
                "info": '<?= lang( 'info' ); ?>' + '<br>' + '<br>' + '<?= $setLabelDatatable ?>',
                "infoEmpty": '<?= lang( 'infoEmpty' ); ?>',
                "infoFiltered": '<?= lang( 'infoFiltered' ); ?>',
                "infoPostFix": '<?= lang( 'infoPostFix' ); ?>',
                "thousands": '<?= lang( 'thousands' ); ?>',
                "lengthMenu": '<?= lang( 'lengthMenu' ); ?>',
                "loadingRecords": '<?= lang( 'loadingRecords' ); ?>',
                "processing": '<img src="<?= img_url( 'ajax-loader.gif' ) ?>">',
                "search": '<?= lang( 'search' ) . ' &nbsp; '; ?>',
                "zeroRecords": '<?= lang( 'zeroRecords' ); ?>',
                "paginate": {
                    "first": '<?= lang( 'first' ); ?>',
                    "last": '<?= lang( 'last' ); ?>',
                    "next": '<?= lang( 'next' ); ?>',
                    "previous": '<?= lang( 'previous' ); ?>',
                },
                "aria": {
                    "sortAscending": '<?= lang( 'sortAscending' ); ?>',
                    "sortDescending": '<?= lang( 'sortDescending' ); ?>',
                }
            },
            dom: "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        })

        $('#tbl_list_qa tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');

            var row = qaTable.row(tr);
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(format(row.data())).show();
                tr.addClass('shown');
                // tr.next().addClass('bg-light');
            }
        });
        function format(d) {
            return '<div class="jumbotron m-2 p-2 border shadow-sm"><div class="card collapse-card m-0 shadow-none border"><div class="card-body"><b>' + d.answer + '</b></div></div></div>';
        }
    });
</script>