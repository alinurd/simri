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

<div class="card card-body alpha-indigo border-indigo">
    <?php
    foreach( $d_evaluasi['info'] as $key => $row ) :
        $size = "";
        if( $key == 1 )
        {
            $size = "style=width:35%;";
        }
        ?>
        <div class="form-group row">
            <label
                class="col-lg-3 col-form-label text-<?= $this->_preference_['align_label']; ?>"><?= $row['title']; ?></label>
            <div class="col-lg-9">
                <div class="input-group" <?= $size; ?>>
                    <?= $row['isi']; ?>
                </div>
            </div>
        </div>
        <?php
    endforeach; ?>
</div>

<?php
foreach( $d_evaluasi['evaluasi'] as $key => $row ) :
    $add  = FALSE;
    $help = '';

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
            <div class="form-group form-group-feedback form-group-feedback-right">
                <?= $row['isi']; ?>
                <?php if( $add ) :
                    echo form_input( 'txt_' . $key, '', 'class="form-control d-none" id="txt_' . $key . '" placeholder="' . $row['title'] . '"' );
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
<br />
<div class="row">
    <div class="col-lg-4">
        <span <?= $no_edit; ?> class="btn bg-success-400 legitRipple" id="simpan_evaluasi"
            style="pointer-events:<?= $events; ?>"><b><i class="icon-floppy-disk "></i></b>
            <?= _l( 'fld_save_evaluasi' ); ?></span>
    </div>
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <span class="btn btn-primary pointer btnNext pull-right"><?= _l( "fld_next_tab" ); ?></span>
        <span class="btn btn-warning pointer btnPrevious pull-right mr-2"><?= _l( "fld_back_tab" ); ?></span>

    </div>
</div>
<hr />
<div id='list_mitigasi'>
    <?= $list_mitigasi; ?>
</div>