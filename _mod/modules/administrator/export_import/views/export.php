<?php echo form_open_multipart(base_url('export_import'),array('menthod'=>'POST','id'=>'simpan_','class'=>'form-horizontal','role'=>'form'));
$modul=array('Siswa', 'Karyawan', 'Agen');
$type=array('Import', 'Export');
?>
<section class="content-header">
  <h1>Export Import</h1>
  <ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#">Export Import</a></li>
  </ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-success direct-chat direct-chat-success">
				<div class="box-header with-border">
					<h3 class="box-title">Export</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<table class="table">
						<tr>
							<td width="15%">Type : <?php echo form_dropdown('tipe', $type,0, 'class="form-control"');?></td>
							<td width="15%">Modul : <?php echo form_dropdown('modul', $modul,0, 'class="form-control"');?></td>
							<td width="15%">Pilih File : <?php echo form_upload('export');;?></td>
							<td valign="middle" align="center" style="vertical-align:middle;">
								<button class="btn btn-primary btn-flat" type="submit" id="proses_lap">Proses</button>
							</td>
						</tr>
						</table>
				</div>
				<div class="box-footer">
				
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>