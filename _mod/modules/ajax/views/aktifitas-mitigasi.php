<br /><br />
<legend class="text-uppercase font-size-lg text-warning font-weight-bold"><i class="icon-grid"></i> DETAIL MITIGASI
</legend>
<div class="row">
    <div class="col-xl-6">
        <table class="table table-bordered">
            <tr>
                <td width="30%"><em><?= _l( 'fld_mitigasi' ); ?></em></td>
                <td><strong><?= $parent['mitigasi']; ?></strong></td>
            </tr>
            <tr>
                <td><em><?= _l( 'fld_biaya' ); ?></em></td>
                <td><strong><?= $parent['biaya']; ?></strong></td>
            </tr>
            <tr>
                <td><em><?= _l( 'fld_pic' ); ?></em></td>
                <td><strong><?= $parent['penanggung_jawab']; ?></strong></td>
            </tr>
        </table>
    </div>
    <div class="col-xl-6">
        <table class="table table-bordered">
            <tr>
                <td><em><?= _l( 'fld_koordinator' ); ?></em></td>
                <td><strong><?= $parent['koordinator']; ?></strong></td>
            </tr>
            <tr>
                <td><em><?= _l( 'fld_due_date' ); ?></em></td>
                <td><strong><?= $parent['batas_waktu']; ?></strong></td>
            </tr>
        </table>
    </div>
</div>
<br />
<strong>LIST AKTIFITAS MITIGASI</strong><br />
<table class="table table-hover table-bordered" id="tbl_list_aktifitas_mitigasi">
    <thead>
        <tr class="bg-warning-300">
            <th width="5%">No</th>
            <th><?= _l( 'fld_aktifitas_mitigasi' ); ?></th>
            <th><?= _l( 'fld_pic' ); ?></th>
            <th><?= _l( 'fld_koordinator' ); ?></th>
            <th><?= _l( 'fld_due_date' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        if( ! empty( $aktifitas ) )
        {
            foreach( $aktifitas as $row ) : ?>
                <tr class="pointer detail-progres-mitigasi" data-id="<?= $row['id']; ?>">
                    <td><?= ++$no; ?></td>
                    <td><?= $row['aktifitas_mitigasi']; ?></td>
                    <td><?= $row['penanggung_jawab_detail']; ?></td>
                    <td><?= $row['koordinator_detail']; ?></td>
                    <td><?= date( 'd-m-Y', strtotime( $row['batas_waktu_detail'] ) ); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php }
        else
        { ?>
            <tr>
                <td colspan="5" class="text-center"><i>No Data Found</i></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div id="result_progres_aktifitas_mitigasi">

</div>