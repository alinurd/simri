<?php
$show = '';
if( isset( $export ) )
{
    if( ! $export )
    {
        $show = ' d-none ';
    }
}

?>
<style>
    .double-scroll {
        width: 100%;
    }
</style>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <a href="<?= base_url( '/risk-context/cetak-register/' . $id ) ?>">
                    <h6 class="card-title"><span class="btn bg-primary pointer pull-right <?= $show; ?>"
                            id="export_excel"> Export to Ms-Excel </span></h6>
                </a>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="20%">Nama Departemen</td>
                        <td><strong><?= $parent['owner_name']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em>Sasaran Departmen</em></td>
                        <td><strong><?= $parent['sasaran_dept']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em>Periode</em></td>
                        <td><strong><?= $parent['period_name'] . ' - ' . $parent['bulan']; ?></strong></td>
                    </tr>
                </table>
                <!-- <div class="table-responsive "> -->
                <div class="double-scroll">
                    <table class="table table-hover table-striped table-bordered" border="1">
                        <thead class="bg-primary">
                            <tr>
                                <th rowspan="2">No.</th>
                                <?php if( isset( $notes ) ) : ?>
                                    <?php if( $notes ) : ?>
                                        <th rowspan="2"><?= _l( 'fld_note' ); ?></th>
                                    <?php endif ?>
                                <?php endif ?>
                                <th rowspan="2">Direktorat</th>
                                <th rowspan="2">Departemen</th>
                                <th rowspan="2">Seksi</th>
                                <th rowspan="2">Sasaran Departemen</th>
                                <th rowspan="2">Kode Aktivitas</th>
                                <th rowspan="2">Aktivitas</th>
                                <th rowspan="2">Sasaran Aktivitas</th>
                                <th rowspan="2">Tahapan Proses</th>
                                <th rowspan="2">Peristiwa Risiko</th>
                                <th rowspan="2">Taksonomi Risiko</th>
                                <th rowspan="2">Tipe Risiko</th>
                                <th rowspan="2">Fraud Risk</th>
                                <th rowspan="2">SMAP</th>
                                <th rowspan="2">ESG Risk</th>
                                <th rowspan="2">Kode Risiko Departemen</th>
                                <th rowspan="2">Risiko Departemen</th>
                                <th rowspan="2">Penyebab Risiko</th>
                                <th rowspan="2">Dampak Risiko</th>
                                <th colspan="6" class="text-center"
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Risiko Inheren</th>
                                <th rowspan="2"
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Jenis/Nama Kontrol</th>
                                <th rowspan="2"
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Efek L/D Kontrol</th>
                                <th colspan="4" class="text-center"
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Risiko Current</th>
                                <th rowspan="2"
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Respon Risiko</th>
                                <th rowspan="2"
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;color:#ffffff;">
                                    Efek L/D Mitigasi</th>
                                <th colspan="4" class="text-center"
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Risiko Residual</th>
                                <th rowspan="2"
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Mitigasi</th>
                                <th rowspan="2"
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Kordinator</th>
                                <th rowspan="2"
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    PIC</th>
                                <th rowspan="2"
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Due Date</th>
                                <th rowspan="2"
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Aktifitas & Progres Mitigasi</th>
                            </tr>
                            <tr>
                                <th
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Risk Indikator Likelihood</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Risk Indikator Dampak</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Likelihood Inheren</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Inheren Impact</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Inheren Risk</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Risk Level Inheren</th>

                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Likelihood Current</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Current Impact</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Current Risk</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Current Risk Level</th>

                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Likelihood Residual</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Residual Impact</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Residual Risk</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Residual Risk Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;

                            if( isset( $notes ) )
                            {
                                if( $notes )
                                {
                                    $alur     = json_decode( $hidden['alur'], TRUE );
                                    $note_arr = [];
                                    foreach( $alur as $key => $row )
                                    {
                                        $note_arr[$row['level_approval_id']] = $row;
                                    }
                                }
                            }

                            foreach( $rows as $row ) :
                                $jml = 1;
                                if( array_key_exists( $row['id'], $mitigasi ) )
                                {
                                    $jml = count( $mitigasi[$row['id']] );
                                }
                                $like1 = [];
                                $like2 = [];
                                $like3 = [];
                                if( array_key_exists( $row['id'], $like_indi ) )
                                {
                                    if( array_key_exists( 1, $like_indi[$row['id']] ) )
                                    {
                                        foreach( $like_indi[$row['id']][1] as $r )
                                        {
                                            $like1[] = $r['kri'];
                                        }
                                    }
                                    if( array_key_exists( 2, $like_indi[$row['id']] ) )
                                    {
                                        foreach( $like_indi[$row['id']][2] as $r )
                                        {
                                            $like2[] = $r['kri'];
                                        }
                                    }
                                    if( array_key_exists( 3, $like_indi[$row['id']] ) )
                                    {
                                        foreach( $like_indi[$row['id']][3] as $r )
                                        {
                                            $like3[] = $r['kri'];
                                        }
                                    }
                                }
                                else
                                {
                                    $like1[] = $row['like_text'];
                                }
                                ;
                                $dampak1 = [];
                                $dampak2 = [];
                                $dampak3 = [];
                                if( array_key_exists( $row['id'], $dampak_indi ) )
                                {
                                    if( array_key_exists( 1, $dampak_indi[$row['id']] ) )
                                    {
                                        foreach( $dampak_indi[$row['id']][1] as $r )
                                        {
                                            $dampak1[] = $r['kri'];
                                        }
                                    }
                                    if( array_key_exists( 2, $dampak_indi[$row['id']] ) )
                                    {
                                        foreach( $dampak_indi[$row['id']][2] as $r )
                                        {
                                            $dampak2[] = $r['kri'];
                                        }
                                    }
                                    if( array_key_exists( 3, $dampak_indi[$row['id']] ) )
                                    {
                                        foreach( $dampak_indi[$row['id']][3] as $r )
                                        {
                                            $dampak3[] = $r['kri'];
                                        }
                                    }
                                }
                                else
                                {
                                    $dampak1[] = $row['impact_text'];
                                }
                                $urut = str_pad( $row['kode_risiko_dept'], 3, 0, STR_PAD_LEFT );

                                if( isset( $notes ) )
                                {
                                    if( $notes )
                                    {
                                        $x   = json_decode( $row['note_approval'], TRUE );
                                        $cat = '';
                                        if( $x )
                                        {
                                            foreach( $x as $key => $y )
                                            {
                                                $note[$row['id'] . '_' . $key] = $y['note'];
                                                $cat .= '<strong>' . $y['name'] . '</strong> : <br/><em>' . $y['note'] . '</em><br/><br/>';
                                            }
                                        }
                                        else
                                        {
                                            foreach( $note_arr as $key => $y )
                                            {
                                                $note[$row['id'] . '_' . $key] = '-';
                                            }
                                        }

                                        $hide  = form_hidden( [ 'rcsa_detail_id[]' => $row['id'] ] );
                                        $hide1 = form_hidden( $note );
                                    }
                                }
                                ?>
                                <tr>
                                    <td rowspan="<?= $jml; ?>"><?= ++$no; ?></td>
                                    <?php if( isset( $notes ) ) : ?>
                                        <?php if( $notes ) : ?>
                                            <?= $hide . $hide1; ?>
                                            <td rowspan="<?= $jml; ?>" class="text-center pointer notes note-<?= $row['id']; ?>"
                                                data-id="<?= $row['id']; ?>" data-owner="<?= $poin_start['level_approval_id']; ?>"
                                                data-popup="popover" title="" data-html="true" data-trigger="hover"
                                                data-content="<?= $cat; ?>" data-original-title="Catatan :" data-placement="left"><i
                                                    class="icon-notebook"></i></td>
                                        <?php endif ?>
                                    <?php endif ?>
                                    <td rowspan="<?= $jml; ?>"><?= $row['lv_1_name'] ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['owner_name']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['seksi']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['sasaran_dept']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['kode_dept'] . '-' . $row['kode_aktifitas']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['aktifitas']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['sasaran']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['tahapan']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= format_list( $row['peristiwa'], '###' ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['klasifikasi_risiko']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['tipe_risiko']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['fraud_risk'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['smap'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['esg_risk'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                                    <td rowspan="<?= $jml; ?>">
                                        <?= $row['kode_dept'] . '-' . $row['kode_aktifitas'] . '-' . $urut; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['risiko_dept']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['penyebab_risiko']; ?></td>

                                    <td rowspan="<?= $jml; ?>"><?= format_list( $row['dampak'], '###' ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= implode( '<br/><br/> ', $like1 ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= implode( '<br/><br/>', $dampak1 ); ?></td>
                                    <td rowspan="<?= $jml; ?>">
                                        <?= ( ! empty( $row["like_inherent"] ) ) ? $row['like_inherent'][0] : ""; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>">
                                        <?= ( ! empty( $row["impact_inherent"] ) ) ? $row['impact_inherent'][0] : ""; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['risiko_inherent_text']; ?></td>
                                    <td rowspan="<?= $jml; ?>"
                                        style="background:<?= $row['color']; ?>;color:<?= $row['color_text']; ?>;">
                                        <?= $row['level_color']; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>">
                                        <?php echo strip_tags( $row['nama_kontrol_note'], '<p><span><br>' ); ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['efek_kontrol_text']; ?></td>
                                    <td rowspan="<?= $jml; ?>">
                                        <?= ( ! empty( $row["like_residual"] ) ) ? $row['like_residual'][0] : ""; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>">
                                        <?= ( ! empty( $row["risiko_residual_text"] ) ) ? $row['risiko_residual_text'][0] : ""; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['risiko_residual_text']; ?></td>
                                    <td rowspan="<?= $jml; ?>"
                                        style="background:<?= $row['color_residual']; ?>;color:<?= $row['color_text_residual']; ?>;">
                                        <?= $row['level_color_residual']; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['treatment']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['efek_mitigasi_text']; ?></td>
                                    <td rowspan="<?= $jml; ?>">
                                        <?= ( ! empty( $row["like_target"] ) ) ? $row['like_target'][0] : ""; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>">
                                        <?= ( ! empty( $row["impact_target"] ) ) ? $row['impact_target'][0] : ""; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['risiko_target_text']; ?></td>
                                    <td rowspan="<?= $jml; ?>"
                                        style="background:<?= $row['color_target']; ?>;color:<?= $row['color_text_target']; ?>;">
                                        <?= $row['level_color_target']; ?>
                                    </td>
                                    <?php
                                    if( array_key_exists( $row['id'], $mitigasi ) )
                                    {
                                        if( $mitigasi[$row['id']] )
                                        {
                                            foreach( $mitigasi[$row['id']] as $key => $mit ) :
                                                if( $key > 0 ) : ?>
                                                <tr>
                                                <?php endif; ?>
                                                <td><?= $mit['mitigasi']; ?></td>
                                                <td><?= $mit['koordinator']; ?></td>
                                                <td><?= $mit['penanggung_jawab']; ?></td>
                                                <td><?= $mit['batas_waktu']; ?></td>
                                                <td>
                                                    <ul>
                                                        <li>Aktifitas: <?= $mit['jml']; ?></li>
                                                        <li>Progres : <?= $mit['progres']; ?>%</li>
                                                    </ul>

                                                </td>
                                            </tr>
                                            <?php
                                            endforeach;
                                        }
                                    }
                                    else
                                    {
                                        echo '<td></td><td></td><td></td><td></td><td></td>
                                    </tr>';
                                    }
                                    ;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>