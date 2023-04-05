<?= $info_parent;
$disabled = '';
$events = 'auto';
if ($lanjut) :
?>
    <div class="alert alert-primary alert-dismissible"><?= $hidden['ket']; ?></div>
<?php else :
    $events = 'none';
    $disabled = ' disabled="disabled" '; ?>
    <div class="alert alert-danger alert-dismissible"><?= $hidden['ket']; ?></div>
<?php endif;

echo form_open_multipart(base_url(_MODULE_NAME_ . '/proses-propose-mitigasi/' . $parent['id']), array('id' => 'form_propose', 'class' => 'form-horizontal'), $hidden);
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <!-- <span class="btn bg-primary-400 btn-labeled btn-labeled-left legitRipple" data-dismiss="modal"><b><i class="icon-list"></i></b> </span> -->
                <a href="<?= base_url(_MODULE_NAME_); ?>" class="btn bg-primary-400 btn-labeled btn-labeled-left legitRipple"><b><i class="icon-list"></i></b> <?= _l('fld_list_mitigasi'); ?></a>
                <button type="submit" class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="proses_propose_mitigasixxxx" style="pointer-events:<?= $events; ?>"><b><i class="icon-floppy-disk "></i></b> <?= _l('fld_proses_propose'); ?></button>
                <span data-id="<?= $parent['id']; ?>" class="btn bg-info-400 btn-labeled btn-labeled-right legitRipple pull-right d-none" id="view-kpi" style="pointer-events:<?= $events; ?>;margin-right:10px;"><b><i class="icon-list"></i></b> <?= _l('fld_show_kpi'); ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Tahun</td>
                        <td><?php echo form_dropdown('period', $period, $parent['period_id'], 'class="form-control select" style="width:100%;"  id="period"'); ?></td>
                        <td>Periode</td>
                        <!-- <td> <?php //echo form_dropdown('term', $term, _TERM_ID_, 'class="form-control select" style="width:100%;"  id="term"'); ?></td> -->
                        <td> <?php echo form_dropdown('term', $term, $parent['term_id'], 'class="form-control select" style="width:100%;"  id="term"'); ?></td>
                    </tr>
                    <tr>
                        <!-- <td>Minggu</td> -->
                        <td>Bulan</td>
                        <td><?php echo form_dropdown('minggu', $minggu, $parent['minggu_id'], 'class="form-control select" style="width:100%;"  id="minggu" data-value="'. $parent['minggu_id'].'"'); ?></td>
                        <td>Attacment</td>
                        <td><?php echo form_upload('attr'); ?></td>
                    </tr>
                </table><br />
                <span class="btn btn-warning pointer" id="input-kpi" data-id="<?= $parent['id']; ?>"> Input KPI & KRI </span>
                <br /><?= $note_propose; ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>