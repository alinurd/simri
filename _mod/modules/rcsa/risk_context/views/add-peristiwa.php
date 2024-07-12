<?php
echo form_open_multipart($this->uri->uri_string, ['id' => 'form_peristiwa_baru', 'class' => 'form-horizontal']);



foreach ($form as $key => $row) :
    $mandatori = $row['mandatori'] ?? false;
    $required = $mandatori ? '<sup class="text-danger">*)</sup>&nbsp;&nbsp;' : '';

    $help = $row['help'] ?? '';

    $help_popup = $row['help_popup'] ?? true;
    $br = !$help_popup ? '<br/>' : '';
?>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-<?= $this->_preference_['align_label']; ?>">
            <?= $required . $row['title'] . $br . $help; ?>
        </label>
        <div class="col-lg-9">
            <div class="form-group form-group-feedback form-group-feedback-right input-group">
                <?= $row['isi']; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<br />
<hr />
<span class="btn bg-warning-400 btn-labeled btn-labeled-left legitRipple" id="backListPeritwa">
    <b><i class="icon-arrow-left5"></i></b> <?= _l('fld_back_like_indi'); ?>
</span>
<span class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="savePeristiwa">
    <b><i class="icon-floppy-disk"></i></b> Simpan
</span>
<br />
<hr />
<?php echo form_close(); ?>

<script>
    $(function() {
        $('.select').select2({
            allowClear: false,
            dropdownParent: $("#modal_general")
        });
        $('[data-popup="tooltip"]').tooltip();
        $('.pickadate').pickadate({
            selectMonths: true,
            selectYears: true,
            formatSubmit: 'yyyy/mm/dd'
        });
        $('#form_like_indi input').keydown(function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
</div>