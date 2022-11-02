<a target="_blank" href="<?= base_url('/laporan-loss-event/cetak-register/' . $period_id . '/' . $owner_no) ?>">
	<h6 class="card-title"><span class="btn bg-primary pointer pull-right" id="export_excel"> Export to Ms-Excel </span></h6>
</a>
<br>
<br>
<br>
<div class="table-responsive">
	<table class="table" border="1">
		<thead>
			<tr>
				<th colspan="4" rowspan="2" style="text-align: center;"><img src="<?= img_url('logo.png'); ?>" width="100"></th>
				<th colspan="6" rowspan="2" style="text-align: center;">PT. INDONESIA ASAHAN ALUMINIUM (Persero)</th>
				<th colspan="4" style="text-align: center;">No. Dokumen/Revisi</th>
			</tr>
			<tr>
				<th colspan="4" style="text-align: center;">1</th>
			</tr>
			<tr>
				<th colspan="14" style="text-align: center;border:none;">LAPORAN LOSS EVENT</th>
			</tr>

			<tr>
				<th colspan="2" style="border:none;">Dept./Seksi</th>

				<th colspan="12" style="border:none;">: <?= (isset($data_owner)) ? $data_owner : "INALUM"; ?></th>
			</tr>
			<tr>
				<th colspan="2" style="border:none;">Periode</th>
				<th colspan="12" style="border:none;">: <?= (isset($data_period)) ? $data_period : "2020"; ?></th>
			</tr>
			<tr>
				<th rowspan="2" style="text-align:center;">No.</th>
				<th rowspan="2" style="text-align:center;">Kode Risiko Departemen </th>
				<th rowspan="2" style="text-align:center;">Risiko Departemen </th>
				<th rowspan="2" style="text-align:center;">Sumber / Tempat Kejadian </th>
				<th rowspan="2" style="text-align:center;">Waktu Kejadian </th>
				<th rowspan="2" style="text-align:center;">Penyebab Kejadian </th>
				<th colspan="2" style="text-align:center;">Dampak Kejadian </th>
				<th rowspan="2" style="text-align:center;">Tindakan Perbaikan </th>
				<th rowspan="2" style="text-align:center;">Jenis Tindakan Perbaikan </th>
				<th rowspan="2" style="text-align:center;">Pelaksana / PIC </th>
				<th rowspan="2" style="text-align:center;">Koordinator </th>
				<th rowspan="2" style="text-align:center;">Due Date </th>
				<th rowspan="2" style="text-align:center;">Anggaran (Rp.) </th>
			</tr>
			<tr>
				<th style="text-align:center;">Financial </th>
				<th style="text-align:center;">Non Financial </th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			foreach ($data['rows'] as $key => $row) : ?>

				<tr>
					<td><?= $no++; ?></td>
					<td><?= $row['owner_code']; ?></td>
					<td><?= $row['risiko_dept']; ?></td>
					<td><?= $row['tempat_kejadian']; ?></td>
					<td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
					<td><?= $row['penyebab']; ?></td>
					<td><?= $row['dampak_kerugian']; ?></td>
					<td><?= $row['dampak_non_uang']; ?></td>
					<td><?= $row['tindakan']; ?></td>
					<td><?= $row['keterangan']; ?></td>
					<td><?= format_list($row['pic'], ","); ?></td>
					<td><?= format_list($row['kid'], ","); ?></td>
					<td><?= date('d-m-Y', strtotime($row['due_date'])); ?></td>
					<td><?= number_format(floatval($row['anggaran'])); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="14"></th>
			</tr>
			<tr>
				<th colspan="10"></th>
				<th></th>
				<th>Disusun Oleh</th>
				<th>Diperiksa Oleh</th>
				<th>Disahkan Oleh</th>
			</tr>
			<tr>
				<th colspan="10"></th>
				<th>Nama</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<th colspan="10"></th>
				<th>Jabatan</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<th colspan="10"></th>
				<th>Tanda Tangan</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
			<tr>
				<th colspan="10"></th>
				<th>Tanggal</th>
				<th></th>
				<th></th>
				<th></th>
			</tr>

		</tfoot>
	</table>
</div>