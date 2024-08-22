             <div class="card-header header-elements-sm-inline">
                <!-- <a href="<?= base_url('/risk-context/cetak-register/' . $id) ?>">
                    <h6 class="card-title"><span class="btn bg-primary pointer pull-right <?= $show; ?>" id="export_excel"> Export to Ms-Excel </span></h6>
                </a> -->
            </div>
                 <table class="table table-borderless">
                    <!-- <tr>
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
                    </tr> -->
                </table>
                <!-- <div class="table-responsive "> -->
                <div class="double-scroll">
                    <table class="table table-hover table-striped table-bordered" border="1">
                        <thead class="bg-primary">
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">Direktorat</th>
                                <th rowspan="2">Departemen</th>
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
                                <th colspan="6" class="text-center" style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Risiko Inheren</th>
                                <th rowspan="2" style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Jenis/Nama Kontrol</th>
                                <th rowspan="2" style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Efek L/D Kontrol</th>
                                <th colspan="4" class="text-center" style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">Risiko Current</th>
                                <th rowspan="2" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Respon Risiko</th>
                                <th rowspan="2" style="background:<?= $this->_preference_['warna_target']; ?> !important;color:#ffffff;">Efek L/D Mitigasi</th>
                                <th colspan="4" class="text-center" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Risiko Residual</th>
                                <th rowspan="2" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Mitigasi</th>
                                <th rowspan="2" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Kordinator</th>
                                <th rowspan="2" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">PIC</th>
                                <th rowspan="2" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Due Date</th>
                                <th rowspan="2" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Aktifitas & Progres Mitigasi</th>
                            </tr>
                            <tr>
                                <th style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Risk Indikator Likelihood</th>
                                <th style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Risk Indikator Dampak</th>
                                <th style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Likelihood Inheren</th>
                                <th style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Inheren Impact</th>
                                <th style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Inheren Risk</th>
                                <th style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Risk Level Inheren</th>

                                <th style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">Likelihood Current</th>
                                <th style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">Current Impact</th>
                                <th style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">Current Risk</th>
                                <th style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">Current Risk Level</th>

                                <th style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Likelihood Residual</th>
                                <th style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Residual Impact</th>
                                <th style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Residual Risk</th>
                                <th style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Residual Risk Level</th>
                            </tr>
                        </thead>
                        <body>
                        <tbody>
                            <?php
                            $no = 1;
                             foreach ($data['parent'] as $row) :
                                $ownerName=$row["owner_name"];
                                $sasaranDept=$row["sasaran_dept"];
                                $id = $row['id'];
                                $jml = 1;
                                $mitigasi[$id]=$this->db->where('rcsa_id', $id)->get(_TBL_VIEW_RCSA_MITIGASI)->result_array();
                                
                                $rows=$this->db->where('rcsa_id', $id)->order_by('kode_dept')->order_by('kode_aktifitas')->get(_TBL_VIEW_REGISTER)->result_array();
                                $rows=$this->db->where('rcsa_id', $id)->get(_TBL_VIEW_RCSA_DET_LIKE_INDI)->result_array();
		$like=[];
		foreach($rows as $row){
			$like[$row['rcsa_detail_id']][$row['bk_tipe']][]=$row;
		}
		$hasil['like_indi']=$like;
		$rows=$this->db->where('rcsa_id', $id)->get(_TBL_VIEW_RCSA_DET_DAMPAK_INDI)->result_array();
		$dampak=[];
		foreach($rows as $row){
			$dampak[$row['rcsa_detail_id']][$row['bk_tipe']][]=$row;
		}
		$hasil['dampak_indi']=$dampak;
        $rows=$this->db->where('rcsa_id', $id)->order_by('kode_dept')->order_by('kode_aktifitas')->get(_TBL_VIEW_REGISTER)->result_array();
		foreach($rows as &$row){
			$idx=explode(',', $row['peristiwa_id']);
			$libs=$this->db->where_in('id', $idx)->get(_TBL_LIBRARY)->result_array();
			$x=[];
			foreach($libs as $lib){
				$x[]=$lib['library'];
			}
			$row['peristiwa']=implode('###',$x);

			$idx=explode(',', $row['dampak_id']);
			$libs=$this->db->where_in('id', $idx)->get(_TBL_LIBRARY)->result_array();
			$x=[];
			foreach($libs as $lib){
				$x[]=$lib['library'];
			}
			$row['dampak']=implode('###',$x);
			if(!empty($row['nama_kontrol_note']) && !empty($row['nama_kontrol'])){
				$row['nama_kontrol'].='###'.$row['nama_kontrol_note'];
			}else{
				$row['nama_kontrol'].=$row['nama_kontrol_note'];
			}
		}
		unset($row);
		$hasil['rows']=$rows;

        
                                if (array_key_exists($id, $mitigasi)) {
                                    $jml = count($mitigasi[$id]);
                                }
                                $like1 = [];
                                $like2 = [];
                                $like3 = [];
                                ?>
                                <tr> 
                                   <td  rowspan="<?= $jml; ?>" ><?=$no++?></td>
                                <td  rowspan="<?= $jml; ?>" >Direktorat</td>
                                <td  rowspan="<?= $jml; ?>" ><?=$ownerName?></td>
                                <td  rowspan="<?= $jml; ?>" ><?=$sasaranDept?></td>
                                 <td  rowspan="<?= $jml; ?>" >Kode Aktivitas</td>
                                <td  rowspan="<?= $jml; ?>" >Aktivitas</td>
                                <td  rowspan="<?= $jml; ?>" >Sasaran Aktivitas</td>
                                <td  rowspan="<?= $jml; ?>" >Tahapan Proses</td>
                                <td  rowspan="<?= $jml; ?>" >Klasifikasi Risiko</td>
                                <td  rowspan="<?= $jml; ?>" >Tipe Risiko</td>
                                <td  rowspan="<?= $jml; ?>" >Fraud Risk</td>
                                <td  rowspan="<?= $jml; ?>" >SMAP</td>
                                <td  rowspan="<?= $jml; ?>" >ESG Risk</td>
                                <td  rowspan="<?= $jml; ?>" >Kode Risiko Departemen</td>
                                <td  rowspan="<?= $jml; ?>" >Risiko Departemen</td>
                                <td  rowspan="<?= $jml; ?>" >Penyebab Risiko</td>
                                <td  rowspan="<?= $jml; ?>" >Peristiwa Risiko</td>
                                <td  rowspan="<?= $jml; ?>" >Dampak Risiko</td>
                                 <td  rowspan="<?= $jml; ?>"  style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Jenis/Nama Kontrol</td>
                                <td  rowspan="<?= $jml; ?>"  style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Efek L/D Kontrol</td>
                                 <td  rowspan="<?= $jml; ?>"  style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Respon Risiko</td>
                                <td  rowspan="<?= $jml; ?>"  style="background:<?= $this->_preference_['warna_target']; ?> !important;color:#ffffff;">Efek L/D Mitigasi</td>
                                 <td  rowspan="<?= $jml; ?>"  style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Mitigasi</td>
                                <td  rowspan="<?= $jml; ?>"  style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Kordinator</td>
                                <td  rowspan="<?= $jml; ?>"  style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">PIC</td>
                                <td  rowspan="<?= $jml; ?>"  style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Due Date</td>
                                <td  rowspan="<?= $jml; ?>"  style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Aktifitas & Progres Mitigasi</td>
                            
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Risk Indikator Likelihood</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Risk Indikator Dampak</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Likelihood Inheren</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Inheren Impact</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Inheren Risk</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_inherent']; ?> !important;color:#ffffff;">Risk Level Inheren</td>

                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">Likelihood Current</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">Current Impact</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">Current Risk</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_residual']; ?> !important;;color:#ffffff;">Current Risk Level</td>

                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Likelihood Residual</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Residual Impact</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Residual Risk</td>
                                <td  rowspan="<?= $jml; ?>" style="background:<?= $this->_preference_['warna_target']; ?> !important;;color:#ffffff;">Residual Risk Level</td>
                            </tr>
                            <?php endforeach;
                            ?>
                            
                        </body>
                    </table>
                </div>
         