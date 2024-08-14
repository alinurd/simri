<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title"><span class="btn bg-primary-300 pointer pull-right" id="export_excel"> Export to
                        Ms-Excel </span></h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="20%">Owner Name</td>
                        <td><strong><?= $parent['owner_name']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em>Sasaran Departmen</em></td>
                        <td><strong><?= $parent['sasaran_dept']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em>Period</em></td>
                        <td><strong><?= $parent['period_name'] . ' - ' . $parent['term']; ?></strong></td>
                    </tr>
                </table>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="bg-primary">
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">Perusahaan</th>
                                <th rowspan="2">Direktorat / Departemen</th>
                                <th rowspan="2">Sasaran Departemen</th>
                                <th rowspan="2">Kode Aktivitas</th>
                                <th rowspan="2">Aktivitas</th>
                                <th rowspan="2">Sasaran Aktivitas</th>
                                <th rowspan="2">Tahapan Proses</th>
                                <th rowspan="2">Klasifikasi Risiko</th>
                                <th rowspan="2">Tipe Risiko</th>
                                <th rowspan="2">Fraud Risk</th>
                                <th rowspan="2">SMAP</th>
                                <th rowspan="2">ESG Risk</th>
                                <th rowspan="2">Kode Risiko Departemen</th>
                                <th rowspan="2">Risiko Departemen</th>
                                <th rowspan="2">Penyebab Risiko</th>
                                <th rowspan="2">Peristiwa Risiko</th>
                                <th rowspan="2">Dampak Risiko</th>
                                <th colspan="6" class="text-center"
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Risiko Inheren</th>
                                <th rowspan="2">Kontrol Yang Sudah Ada</th>
                                <th rowspan="2">Efek L/D Kontrol</th>
                                <th colspan="6" class="text-center"
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Risiko Residual</th>
                                <th rowspan="2">Respon Risiko</th>
                                <th colspan="6" class="text-center"
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Risiko Target</th>
                                <th rowspan="2">Mitigasi</th>
                                <th rowspan="2">Kordinator</th>
                                <th rowspan="2">PIC</th>
                                <th rowspan="2">Due Date</th>
                                <th rowspan="2">Aktifitas Mitigasi</th>
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
                                    Dampak Inheren</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Nilai Risiko Inheren</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">
                                    Level Risiko Inheren</th>

                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Risk Indikator Likelihood</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Risk Indikator Dampak</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Likelihood Residual</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Dampak Residual</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Nilai Risiko Residual</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">
                                    Level Risiko Residual</th>

                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Risk Indikator Likelihood</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Risk Indikator Dampak</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Likelihood Target</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Dampak Target</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Nilai Risiko Target</th>
                                <th
                                    style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">
                                    Level Risiko Target</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
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
                                $urut = str_pad( ++$no, 3, STR_PAD_LEFT );
                                ?>
                                <tr>
                                    <td rowspan="<?= $jml; ?>"><?= $no; ?></td>
                                    <td rowspan="<?= $jml; ?>">Inalum</td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['owner_name']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['sasaran_dept']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['kode_risiko_dept'] . '-' . $urut; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['aktifitas']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['sasaran']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['tahapan']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['klasifikasi_risiko']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['tipe_risiko']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['fraud_risk'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['smap'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['esg_risk'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['kode_risiko_dept'] . '-' . $urut . '-' . $urut; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['risiko_dept']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['penyebab_risiko']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= format_list( $row['peristiwa'], '###' ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= format_list( $row['dampak'], '###' ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= implode( '<br/>', $like1 ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= implode( '<br/>', $dampak1 ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['like_inherent']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['impact_inherent']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['risiko_inherent_text']; ?></td>
                                    <td rowspan="<?= $jml; ?>"
                                        style="background:<?= $row['color']; ?>;color:<?= $row['color_text']; ?>;">
                                        <?= $row['level_color']; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>"><?= format_list( $row['nama_kontrol'], '###' ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['efek_kontrol_text']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= implode( '<br/>', $like2 ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= implode( '<br/>', $dampak2 ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['like_residual']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['impact_residual']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['risiko_residual_text']; ?></td>
                                    <td rowspan="<?= $jml; ?>"
                                        style="background:<?= $row['color_residual']; ?>;color:<?= $row['color_text_residual']; ?>;">
                                        <?= $row['level_color_residual']; ?>
                                    </td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['treatment']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= implode( '<br/>', $like3 ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= implode( '<br/>', $dampak3 ); ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['like_target']; ?></td>
                                    <td rowspan="<?= $jml; ?>"><?= $row['impact_target']; ?></td>
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
                                                <td><?= $mit['jml']; ?></td>
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