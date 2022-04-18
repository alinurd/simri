<section class="content-header">
  <h1>
	<?=$title;?> 
	
  </h1>
  <ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#">Pembelian</a></li>
	<li><a href="#">Report</a></li>
	<li class="active"><?=$title;?></li>
  </ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-success direct-chat direct-chat-success">
				<div class="box-header with-border">
					<h3 class="box-title">Backup</h3>
					<div class="box-tools pull-right">
						<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="box-body text-center">
					<a href="<?=base_url('backup/get-backup');?>" class="btn btn-primary btn-flat">Backup Database </a> &nbsp;&nbsp; <a href="<?=base_url('backup/get-backup-fileimage');?>" class="btn btn-success btn-flat">Backup Image Certificate</a> &nbsp;&nbsp; <a href="<?=base_url('backup/get-backup-barcode');?>" class="btn btn-warning btn-flat">Backup Image Barcode</a>
				</div>
				<div class="box-footer">
				
				</div>
			</div>
		</div>
	</div>
</section>