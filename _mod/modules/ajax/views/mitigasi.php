<div class="card">
    <div class="card-header">
        <legend class="text-uppercase font-size-lg text-success font-weight-bold"><i class="icon-grid"></i> DETAIL
            IDENTIFIKASI
        </legend>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xl-6">
                <table class="table table-bordered">
                    <tr>
                        <td width="30%"><em><?= _l( 'fld_aktifitas' ); ?></em></td>
                        <td><strong><?= $parent['aktifitas']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_sasaran_aktifitas' ); ?></em></td>
                        <td><strong><?= $parent['sasaran']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_tahapan_proses' ); ?></em></td>
                        <td><strong><?= $parent['tahapan']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_klasifikasi_risiko' ); ?></em></td>
                        <td><strong><?= $parent['klasifikasi_risiko']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_tipe_risiko' ); ?></em></td>
                        <td><strong><?= $parent['tipe_risiko']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'Fraud Risk' ); ?></em></td>
                        <td><strong><?= $parent['fraud_risk'] == 1 ? 'Ya' : 'Tidak'; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'SMAP' ); ?></em></td>
                        <td><strong><?= $parent['smap'] == 1 ? 'Ya' : 'Tidak'; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'ESG Risk' ); ?></em></td>
                        <td><strong><?= $parent['esg_risk'] == 1 ? 'Ya' : 'Tidak'; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_penyebab_risiko' ); ?></em></td>
                        <td><strong><?= $parent['penyebab_risiko']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_analisa_risiko' ); ?></em></td>
                        <td><strong>
                                <?php
                                if( $parent['tipe_analisa_no'] == 1 )
                                {
                                    echo "Kualitatif";
                                }
                                elseif( $parent['tipe_analisa_no'] == 2 )
                                {
                                    echo "Kuantitatif";
                                }
                                elseif( $parent['tipe_analisa_no'] == 3 )
                                {
                                    echo "Semi Kuantitatif";
                                }
                                ?></strong></td>
                    </tr>
                </table>
            </div>
            <div class="col-xl-6">
                <table class="table table-bordered">

                    <?php if( $parent['tipe_analisa_no'] == 1 || $parent['tipe_analisa_no'] == 3 ) : ?>
                        <tr>
                            <td><em><?= _l( 'fld_indi_likelihood' ); ?></em></td>
                            <td><strong><?= $parent['like_text']; ?></strong></td>
                        </tr>
                    <?php endif ?>
                    <tr>
                        <td><em><?= _l( 'fld_likelihood' ); ?></em></td>
                        <td><strong><?= $parent['like_inherent']; ?></strong></td>
                    </tr>
                    <tr>
                        <td width="30%"><em><?= _l( 'fld_peristiwa_risiko' ); ?></em></td>
                        <td><strong><?= $parent['peristiwa']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_dampak_risiko' ); ?></em></td>
                        <td><strong><?= $parent['dampak']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_risiko_dept' ); ?></em></td>
                        <td><strong><?= $parent['risiko_dept']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_nama_control' ); ?></em></td>
                        <td><strong><?= format_list( $parent['nama_kontrol'], '###' ); ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_efek_kontrol' ); ?></em></td>
                        <td><strong><?= $parent['efek_kontrol_text']; ?></strong></td>
                    </tr>
                    <tr>
                        <td><em><?= _l( 'fld_treatment' ); ?></em></td>
                        <td><strong><?= $parent['treatment']; ?></strong></td>
                    </tr>

                    <tr>
                        <td><em><?= _l( 'fld_impact' ); ?></em></td>
                        <td><strong><?= $parent['impact_inherent']; ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php if( $parent['tipe_analisa_no'] == 2 || $parent['tipe_analisa_no'] == 3 ) : ?>
            <div class="row mt-3">
                <div class="col-xl-12">
                    <strong><?= strtoupper( _l( 'fld_indi_likelihood' ) ); ?></strong><br />
                    <?= $kpi ?>
                    <br>
                    <strong><?= strtoupper( _l( 'fld_indi_dampak' ) ); ?></strong><br />
                    <table class="table table-hover table-bordered" id="tbl_list_mitigasi">
                        <thead>
                            <tr class="bg-success-300">
                                <th>No</th>
                                <th><?= _l( 'fld_jenis_kri' ); ?></th>
                                <th><?= _l( 'fld_kri' ); ?></th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            if( ! empty( $dampak ) )
                            {
                                foreach( $dampak as $row ) : ?>
                                    <tr>
                                        <td><?= ++$no; ?></td>
                                        <td><?= $row['jenis_kri']; ?></td>
                                        <td><?= $row['kri']; ?></td>
                                        <td><?= $row['detail']; ?></td>
                                    </tr>
                                <?php endforeach ?>
                            <?php }
                            else
                            { ?>
                                <tr>
                                    <td colspan="4" class="text-center"><i>No Data Found</i></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif ?>
        <div class="row mt-3">
            <div class="col-xl-12">
                <strong>LIST MITIGASI</strong><br />
                <table class="table table-hover table-bordered" id="tbl_list_mitigasi">
                    <thead>
                        <tr class="bg-success-300">
                            <th>No</th>
                            <th><?= _l( 'fld_mitigasi' ); ?></th>
                            <th class="text-right"><?= _l( 'fld_biaya' ); ?></th>
                            <th><?= _l( 'fld_pic' ); ?></th>
                            <th><?= _l( 'fld_koordinator' ); ?></th>
                            <th><?= _l( 'fld_jml_aktifitas' ); ?></th>
                            <th><?= _l( 'fld_due_date' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 0;
                        if( ! empty( $mitigasi ) )
                        {
                            foreach( $mitigasi as $row ) : ?>
                                <tr class="pointer detail-mitigasi" data-id="<?= $row['id']; ?>">
                                    <td><?= ++$no; ?></td>
                                    <td><?= $row['mitigasi']; ?></td>
                                    <td class="text-right"><?= number_format( $row['biaya'] ); ?></td>
                                    <td><?= $row['penanggung_jawab']; ?></td>
                                    <td><?= $row['koordinator']; ?></td>
                                    <td><?= $row['jml']; ?></td>
                                    <td><?= date( 'd-m-Y', strtotime( $row['batas_waktu'] ) ); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php }
                        else
                        { ?>
                            <tr>
                                <td colspan="7" class="text-center"><i>No Data Found</i></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="result_aktifitas_mitigasi">
        </div>
    </div>
</div>