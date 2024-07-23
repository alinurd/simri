<div id="entri_mitigasi">
    <?php
    foreach( $mitigasi as $key => $row ) :
        $add       = FALSE;
        $help      = '';
        $mandatori = FALSE;
        if( isset( $row['mandatori'] ) )
        {
            $mandatori = $row['mandatori'];
        }
        $required = '';
        if( $mandatori )
        {
            $required = '<sup class="text-danger">*)</sup>&nbsp;&nbsp;';
        }

        if( isset( $row['add'] ) )
            $add = $row['add'];
        if( isset( $row['help'] ) )
            $help = $row['help'];

        $help_popup = TRUE;
        if( isset( $row['help_popup'] ) )
        {
            $help_popup = $row['help_popup'];
        }
        $br = '';
        if( ! $help_popup )
        {
            $br = '<br/>';
        }

        ?>
        <div class="form-group row">
            <label
                class="col-lg-3 col-form-label text-<?= $this->_preference_['align_label']; ?>"><?= $required . $row['title'] . $br . $help; ?></label>
            <div class="col-lg-9">
                <div class="form-group form-group-feedback form-group-feedback-right input-group">
                    <?= $row['isi']; ?>
                </div>
            </div>
        </div>
        <?php
    endforeach; ?>
    <span class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="simpan_mitigasi"><b><i
                class="icon-floppy-disk "></i></b> <?= _l( 'fld_save_mitigasi' ); ?></span>
    <br />
    <hr />
</div>
<script>
    $(function () {
        $('.select').select2({
            allowClear: false,
            dropdownParent: $("#modal_general")
        });
        $('.rupiah').number(true);
        $('[data-popup="tooltip"]').tooltip();
        $('.pickadateaddmitigasi').pickadate({
            selectMonths: false,
            selectYears: false,
            formatSubmit: 'yyyy/mm/dd',
            min: 1
        });
    })
</script>