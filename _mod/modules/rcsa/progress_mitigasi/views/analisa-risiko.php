<?php
$tipe = intval($detail['tipe_analisa_no']);
?>
<?php
$help = '';
if (isset($detail['tipe_analisa']['help']))
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
<div id="div_analisa_kualitatif" class="<?= ($tipe != 1) ? 'd-none' : ''; ?>">
    <?php
    foreach ($detail['analisa_kualitatif'] as $key => $row) :
        $add  = FALSE;
        $help = '';

        $mandatori = FALSE;
        if (isset($row['mandatori'])) {
            $mandatori = $row['mandatori'];
        }
        $required = '';
        if ($mandatori) {
            $required = '<sup class="text-danger">*)</sup>&nbsp;&nbsp;';
        }

        if (isset($row['add']))
            $add = $row['add'];
        if (isset($row['help']))
            $help = $row['help'];

    ?>
        <div class="form-group row">
            <label
                class="col-lg-3 col-form-label text-<?= $this->_preference_['align_label']; ?>"><?= $required . $row['title'] . $help; ?></label>
            <div class="col-lg-9">
                <div class="form-group form-group-feedback form-group-feedback-right">
                    <?= $row['isi']; ?>
                    <?php if ($add) :
                        echo form_input('txt_' . $key, '', 'class="form-control d-none" id="txt_' . $key . '" placeholder="' . $row['title'] . '"');
                    ?>
                        <div class="form-control-feedback text-primary form-control-feedback-lg pointer manual_combo"
                            data-id="0" data-key="<?= $key; ?>" id="add_<?= $key; ?>"><i class="icon-plus3"></i></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php
    endforeach; ?>
</div>
<div id="div_analisa_kuantitatif" class="<?= ($tipe != 2) ? 'd-none' : ''; ?>">
    <?php
    foreach ($detail['analisa_kuantitatif'] as $key => $row) :
        $add  = FALSE;
        $help = '';

        $mandatori = FALSE;
        if (isset($row['mandatori'])) {
            $mandatori = $row['mandatori'];
        }
        $required = '';
        if ($mandatori) {
            $required = '<sup class="text-danger">*)</sup>&nbsp;&nbsp;';
        }

        if (isset($row['add']))
            $add = $row['add'];
        if (isset($row['help']))
            $help = $row['help'];

        $help_popup = TRUE;
        if (isset($row['help_popup'])) {
            $help_popup = $row['help_popup'];
        }
        $br = '';
        if (! $help_popup) {
            $br = '<br/>';
        }
    ?>
        <div class="form-group row">
            <label
                class="col-lg-3 col-form-label text-<?= $this->_preference_['align_label']; ?>"><?= $required . $row['title'] . $br . $help; ?></label>
            <div class="col-lg-9">
                <div class="form-group form-group-feedback form-group-feedback-right">
                    <?= $row['isi']; ?>
                    <?php if ($add) :
                        echo form_input('txt_' . $key, '', 'class="form-control d-none" id="txt_' . $key . '" placeholder="' . $row['title'] . '"');
                    ?>
                        <div class="form-control-feedback text-primary form-control-feedback-lg pointer manual_combo"
                            data-id="0" data-key="<?= $key; ?>" id="add_<?= $key; ?>"><i class="icon-plus3"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php
    endforeach; ?>
</div>
<div id="div_analisa_semi" class="<?= ($tipe != 3) ? 'd-none' : ''; ?>">
    <?php
    foreach ($detail['analisa_semi'] as $key => $row) :
        $add       = FALSE;
        $help      = '';
        $mandatori = FALSE;
        if (isset($row['mandatori'])) {
            $mandatori = $row['mandatori'];
        }
        $required = '';
        if ($mandatori) {
            $required = '<sup class="text-danger">*)</sup>&nbsp;&nbsp;';
        }
        if (isset($row['add']))
            $add = $row['add'];
        if (isset($row['help']))
            $help = $row['help'];

    ?>
        <div class="form-group row">
            <label
                class="col-lg-3 col-form-label text-<?= $this->_preference_['align_label']; ?>"><?= $required . $row['title'] . $help; ?></label>
            <div class="col-lg-9">
                <div class="form-group form-group-feedback form-group-feedback-right">
                    <?= $row['isi']; ?>
                    <?php if ($add) :
                        echo form_input('txt_' . $key, '', 'class="form-control d-none" id="txt_' . $key . '" placeholder="' . $row['title'] . '"');
                    ?>
                        <div class="form-control-feedback text-primary form-control-feedback-lg pointer manual_combo"
                            data-id="0" data-key="<?= $key; ?>" id="add_<?= $key; ?>"><i class="icon-plus3"></i></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php
    endforeach; ?>
</div>

<?php
foreach ($detail['analisa2'] as $key => $row) :
    $add       = FALSE;
    $help      = '';
    $mandatori = FALSE;
    if (isset($row['mandatori'])) {
        $mandatori = $row['mandatori'];
    }
    $required = '';
    if ($mandatori) {
        $required = '<sup class="text-danger">*)</sup>&nbsp;&nbsp;';
    }
    if (isset($row['add']))
        $add = $row['add'];
    if (isset($row['help']))
        $help = $row['help'];

?>
    <div class="form-group row">
        <label
            class="col-lg-3 col-form-label text-<?= $this->_preference_['align_label']; ?>"><?= $required . $row['title'] . $help; ?></label>
        <div class="col-lg-9">
            <div class="form-group form-group-feedback form-group-feedback-right">
                <?= $row['isi']; ?>
                <?php if ($add) :
                    echo form_input('txt_' . $key, '', 'class="form-control d-none" id="txt_' . $key . '" placeholder="' . $row['title'] . '"');
                ?>
                    <div class="form-control-feedback text-primary form-control-feedback-lg pointer manual_combo" data-id="0"
                        data-key="<?= $key; ?>" id="add_<?= $key; ?>"><i class="icon-plus3"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
endforeach; ?>