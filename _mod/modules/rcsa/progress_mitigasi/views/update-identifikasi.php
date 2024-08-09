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
 

 
<?php
echo form_open_multipart( $this->uri->uri_string, array( 'id' => 'form_identifikasi', 'class' => 'form-horizontal' ), $hidden );
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-top">
                    <li class="nav-item">
                        <a href="#content-tab-00" class="nav-link active show"
                            data-toggle="tab"><?= _l( 'fld_identifikasi_risiko' ); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#content-tab-01" class="nav-link "
                            data-toggle="tab"><?= _l( 'fld_analisa_risiko' ); ?></a>
                    </li>
                    <?php if( $mode ) : ?>
                        <li class="nav-item">
                            <a href="#content-tab-02" class="nav-link "
                                data-toggle="tab"><?= _l( 'fld_evaluasi_risiko' ); ?></a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item <?= ( $rcsa_detail['sts_save_evaluasi'] ) ? '' : 'd-none'; ?>">
                        <a href="#content-tab-03" class="nav-link "
                            data-toggle="tab"><?= _l( 'fld_target_risiko' ); ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="content-tab-00"><?= $identifikasi; ?></div>
                    <div class="tab-pane fade" id="content-tab-01">
                        <?php
                        $help = ''; 
                        if( isset( $detail['tipe_analisa']['help'] ) )
                            $help = $detail['tipe_analisa']['help'];
                        ?>
                        <div class="form-group row">
                            <label
                                class="col-lg-3 col-form-label text-<?= $this->_preference_['align_label']; ?>"><?= $detail['tipe_analisa']['title'] . $help; ?></label>
                            <div class="col-lg-9">
                                <div class="form-group form-group-feedback form-group-feedback-right">
                                    <?= $detail['tipe_analisa']['isi']; ?>
                                </div>
                            </div>
                        </div>
                        <?= $analisa; ?>
                        <hr>
                        <div class="row">
                            <div class="col-lg-4">
                                <span <?= $no_edit; ?> class="btn bg-success-400 legitRipple" id="simpan_identifikasi"
                                    style="pointer-events:<?= $events; ?>"><b><i class="icon-floppy-disk "></i></b>
                                    <?= _l( 'fld_save' ); ?></span>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4">
                                <span class="btn btn-primary pointer btnNext pull-right" <?= $no_edit; ?>
                                    style="pointer-events:<?= $events; ?>"><?= _l( "fld_next_tab" ); ?></span>
                                <span
                                    class="btn btn-warning pointer btnPrevious pull-right mr-2"><?= _l( "fld_back_tab" ); ?></span>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="content-tab-02"><?= $evaluasi; ?></div>
                    <div class="tab-pane fade" id="content-tab-03"><?= $target; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close();
?>

<script>
    var cboperistiwa = '<?php echo addslashes( preg_replace( "/(\r\n)|(\n)/mi", "", $detail['peristiwa_cbo'] ) ); ?>';
    var cbodampak = '<?php echo addslashes( preg_replace( "/(\r\n)|(\n)/mi", "", $detail['dampak_cbo'] ) ); ?>';

    $(function () {
        $('.select').select2({
            allowClear: false
        });
        $('[data-popup="tooltip"]').tooltip();

        $('.summernote').summernote({
            height: 300,
            placeholder: 'type everyting',
        });
    })
</script>