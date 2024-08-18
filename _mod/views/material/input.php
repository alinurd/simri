<?php
if( ! $tabs )
{
    $fld = [];
    foreach( $fields as $row )
    {
        $fld[] = $row['field'];
    }
    $tabs[] = array( 'title' => $this->lang->line( _MODULE_NAME_REAL_ . '_title' ), 'id' => 'tab-01', 'icon' => '<i class="icon-grid3"></i>', 'field' => $fld );
}

$pesan = '';
$error = $this->session->flashdata( 'message_crud_error' );
if( empty( $error ) )
    $pesan = $this->session->flashdata( 'message_crud' );

$color = "primary";
if( validation_errors() )
{
    $error = validation_errors();
    $color = 'danger';
}
elseif( $error )
{
    $error = '- ' . implode( '<br/>- ', $error );
    $color = 'danger';
}

if( $color == 'danger' ) : ?>
    <div class="alert alert-danger border-0 alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        <span class="font-weight-semibold">Error!<br /></span> <?= $error; ?>
    </div>
    <?php
endif;

echo '<ul class="nav nav-tabs nav-tabs-top">';
$required = '<sup class="text-danger">*)</sup> ';
// dump($tabs);
$awal = TRUE;
$hide = [];
foreach( $fields as $row )
{
    if( ! $row['show'] && $row['type'] !== 'free' )
    {
        $hide[$row['field']] = $row['isi'];
    }
}
echo form_hidden( $hide );
foreach( $tabs as $row ) : ?>
    <li class="nav-item">
        <a href="#content-<?= $row['id']; ?>" class="nav-link <?= ( $awal ) ? 'active show' : ''; ?>" data-toggle="tab">
            <?= $row['title']; ?>
        </a>
    </li>
    <?php $awal = FALSE;
endforeach; ?>
</ul>
<div class="tab-content">
    <?php
    $awal = TRUE;
    foreach( $tabs as $row ) :
        ?>
        <div class="tab-pane fade <?= ( $awal ) ? 'active show' : ''; ?>" id="content-<?= $row['id']; ?>">
            <?php
            if( array_key_exists( 'cols', $row ) )
            {
                echo '<div class="row">';
                $grid = 12;
                if( count( $row['cols'] ) >= 2 )
                    $grid = 6;
                foreach( $row['cols'] as $cols ) :
                    echo '<div class="col-md-' . $grid . '">';
                    foreach( $cols as $col ) :
                        if( isset( $fields[$col]['box'] ) ) :
                            $span_help = '';
                            $help      = '';
                            if( $params['help_tool'] )
                            {
                                $help = lang( 'help_' . $fields[$col]['field'] );
                                if( $params['help_popup'] )
                                {
                                    if( ! empty( $help ) )
                                    {
                                        $span_help = '<span class="float-right pointer" data-popup="tooltip" data-html="true" title="' . $help . '"><i class="icon-help text-info"></i></span>';
                                    }
                                    $help = '';
                                }
                            }
                            ?>
                            <div class="form-group row">
                                <?php
                                if( $fields[$col]['line'] ) : ?>
                                    <legend class="text-uppercase font-size-lg text-primary font-weight-bold"><i
                                            class="<?= $fields[$col]['line-icon']; ?>"></i>
                                        <?= $fields[$col]['line-text']; ?></legend>
                                <?php endif; ?>
                                <label class="col-form-label col-lg-2 text-<?= $params['align_label']; ?>">
                                    <?= ( $fields[$col]['required'] ) ? $required : '' ?>
                                    <?= $fields[$col]['title']; ?>
                                    <?= $span_help; ?>
                                </label>
                                <div class="col-lg-10">
                                    <?php
                                    if( ! empty( $fields[$col]['box'] ) )
                                    {
                                        $box = $fields[$col]['box'];
                                    }
                                    else
                                    {
                                        $box = '<span id="content_' . $fld . '"></span>';
                                    }
                                    echo $box;
                                    ?>
                                    <span class=" text-muted" id="help_<?= $fld; ?>">
                                        <?= $help; ?></span>
                                    <span class="text-muted" id="info_<?= $fld; ?>">&nbsp;</span>
                                </div>
                            </div>
                        <?php endif;
                    endforeach;
                    echo '</div>';
                endforeach;
                echo '</div>';
            }
            else
            {
                foreach( $row['field'] as $fld ) :
                    if( isset( $fields[$fld]['box'] ) ) :
                        $x         = $fields[$fld];
                        $span_help = '';
                        $help      = '';
                        if( $params['help_tool'] )
                        {
                            $help = lang( 'help_' . $x['field'] );
                            if( $params['help_popup'] )
                            {
                                if( ! empty( $help ) )
                                {
                                    $span_help = '<span class="float-right pointer icon-help text-primary" data-popup="tooltip" data-html="true" title="' . $help . '" style="padding-left:15px;"></span>';
                                }
                                $help = '';
                            }
                        }
                        ?>
                        <?php
                        if( $x['line'] ) : ?>
                            <legend class=" text-uppercase font-size-lg text-primary font-weight-bold"><i
                                    class="<?= $x['line-icon']; ?>"></i> <?= $x['line-text']; ?></legend>
                        <?php endif; ?>
                        <div class="form-group row">
                            <?php
                            if( $x['mode'] == 'o' ) : ?>
                                <label class="col-form-label col-lg-2 text-<?= $params['align_label']; ?>">
                                    <?= ( $x['required'] ) ? $required : '' ?>
                                    <?= $x['title']; ?>
                                    <?= $span_help; ?>
                                </label>
                                <?php $col2 = 10;
                            else : ?>
                                <label class="col-form-label col-lg-12 text-left"><?= ( $x['required'] ) ? $required : '' ?>
                                    <?= $x['title']; ?>
                                    <?= $span_help; ?>
                                </label>
                                <br />
                                <?php $col2 = 12; endif; ?>

                            <div class="col-lg-<?= $col2; ?>">
                                <?php
                                if( ! empty( $x['box'] ) )
                                {
                                    $box = $x['box'];
                                }
                                else
                                {
                                    $box = '<span id="content_' . $fld . '"></span>';
                                }
                                echo $box;
                                ?>
                                <span class=" text-muted" id="help_<?= $fld; ?>">
                                    <?= $help; ?>
                                </span>
                                <span class="text-muted" id="info_<?= $fld; ?>">&nbsp;</span>
                            </div>
                        </div>
                    <?php endif;
                endforeach;
            }
            $awal = FALSE;
            ?>
        </div>
    <?php endforeach; ?>
</div>
<?php

//$pesan =$this->session->flashdata('message_crud'); ?>


<script>
    var pesan = '<?= $pesan; ?>';
    var color = '<?= $color; ?>';
    if (pesan.length > 0)
        notif();

    function notif() {

        new Noty({
            layout: 'top',
            text: pesan,
            timeout: 5000,
            theme: ' p-0 bg-' + color + '-300 text-center',
            type: 'info'
        }).show();
    }
</script>