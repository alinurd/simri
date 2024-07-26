<?php
$no_edit      = '';
$events       = 'auto';
$no_edit_hide = '';
if( intval( $parent['status_id'] ) > 0 )
{
    $no_edit_hide = ' d-none ';
    $no_edit      = ' disabled="disabled" ';
    $events       = 'none';
}
?>
<div id="parent_risk">
    <?= $info_parent; ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <a href="<?= base_url( _MODULE_NAME_ ); ?>" class="btn bg-success-300 btn-labeled btn-labeled-left legitRipple" ><b><i class="icon-list"></i></b> <?= _l( 'fld_back_risk_register' ); ?></a>
                    <span  data-id="<?= $parent['id']; ?>" class="btn bg-warning-400 btn-labeled btn-labeled-right legitRipple pull-right risk-register"  id="risk_register" style="margin-left:20px;"><b><i class="icon-file-presentation "></i></b> <?= _l( 'fld_risk_register' ); ?></span>
                    <span <?= $no_edit; ?> data-id="<?= $parent['id']; ?>" class="btn bg-primary-400 btn-labeled btn-labeled-right pull-right legitRipple" id="add_identifikasi" style="pointer-events:<?= $events; ?>"><b><i class="icon-database-add"></i></b> <?= _l( 'fld_add_identifikasi' ); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-hover" id="datatable-listx">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th>Kode Risiko Dept.</th>
                                <th>Risiko Dept.</th>
                                <th>Klasifikasi</th>
                                <th>Risiko Inheren</th>
                                <th>Efek Kontrol</th>
                                <th>Risiko Current</th>
                                <th>Respon</th>
                                <th>Risiko Residual</th>
                                <th width="6%">Mitigasi</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tboy>
                        <?php
                        $no = 0;
                        foreach( $detail as $row ) :
                            $del = '';
                            if( intval( $row['jml'] ) == 0 && intval( $parent['status_id'] ) == 0 )
                            {
                                $del = ' | <i class="icon-database-remove  text-danger-400 delete-identifikasi"  data-rcsa="' . $parent['id'] . '" data-id="' . $row['id'] . '"></i> ';
                            }
                            $urut = str_pad( $row['kode_risiko_dept'], 3, 0, STR_PAD_LEFT );
                            ?>
                               <tr>
                                    <td><?= ++$no; ?></td>
                                    <td><?= $row['kode_dept'] . '-' . $row['kode_aktifitas'] . '-' . $urut; ?></td>
                                    <td><?= $row['risiko_dept']; ?></td>
                                    <td><?= $row['klasifikasi_risiko'] . ' | ' . $row['tipe_risiko']; ?></td>
                                    <td class="text-center" style="background-color:<?= $row['color']; ?>;color:<?= $row['color_text']; ?>;"><?= $row['level_color']; ?><br/><small><?= $row['like_code'] . 'x' . $row['impact_code'] . ' : ' . $row['risiko_inherent_text']; ?></small></td>
                                    <td><?= $row['efek_kontrol_text']; ?></td>
                                    <td class="text-center" style="background-color:<?= $row['color_residual']; ?>;color:<?= $row['color_text_residual']; ?>;"><?= $row['level_color_residual']; ?><br/><small><?= $row['like_code_residual'] . 'x' . $row['impact_code_residual'] . ' : ' . $row['risiko_residual_text']; ?></small></td>
                                    <td><?= $row['treatment']; ?></td>
                                    <td class="text-center" style="background-color:<?= $row['color_target']; ?>;color:<?= $row['color_text_target']; ?>;"><?= $row['level_color_target']; ?><br/><small><?= $row['like_code_target'] . 'x' . $row['impact_code_target'] . ' : ' . $row['risiko_target_text']; ?></small></td>
                                    <td class="text-center"><span class="badge bg-teal-400 badge-pill align-self-center ml-auto"><?= $row['jml']; ?></span></td>
                                    <td class="pointer text-center"><i class="icon-database-edit2  text-primary-400 update-identifikasi" data-rcsa="<?= $parent['id']; ?>" data-id="<?= $row['id']; ?>"></i><?= $del; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tboy>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#datatable-listx').DataTable({
            pageLength:50,
            language:{
                "decimal":        '<?= lang( 'decimal' ); ?>',
                "emptyTable":     '<?= lang( 'emptyTable' ); ?>',
                "info":           '<?= lang( 'info' ); ?>',
                "infoEmpty":      '<?= lang( 'infoEmpty' ); ?>',
                "infoFiltered":   '<?= lang( 'infoFiltered' ); ?>',
                "infoPostFix":    '<?= lang( 'infoPostFix' ); ?>',
                "thousands":      '<?= lang( 'thousands' ); ?>',
                "lengthMenu":     '<?= lang( 'lengthMenu' ); ?>',
                "loadingRecords": '<?= lang( 'loadingRecords' ); ?>',
                "processing":      '<img src="<?= img_url( 'ajax-loader.gif' ) ?>">',
                "search":         '<?= lang( 'search' ) . ' &nbsp; '; ?>',
                "zeroRecords":    '<?= lang( 'zeroRecords' ); ?>',
                "paginate": {
                    "first":      '<?= lang( 'first' ); ?>',
                    "last":       '<?= lang( 'last' ); ?>',
                    "next":       '<?= lang( 'next' ); ?>',
                    "previous":   '<?= lang( 'previous' ); ?>',
                },
                "aria": {
                    "sortAscending":  '<?= lang( 'sortAscending' ); ?>',
                    "sortDescending": '<?= lang( 'sortDescending' ); ?>',
                }
            },
            dom: "<'row'<'col-sm-5'i><'col-sm-7'p><'col-sm-6'l><'col-sm-6'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        });
    })
</script>