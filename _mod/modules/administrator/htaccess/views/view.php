<?php
	$string = file_get_contents(FCPATH . '.htaccess');
?>
<section class="content-header">
  <h1>
	HTACCESS
  </h1>
  <ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li class="active">Htaccess</li>
  </ol>
</section>

<section class="content">
	<div class="box">
		<div class="box-header with-border">
		  <h3 class="box-title">Edit Htaccess File</h3>
		  <div class="box-tools pull-right">
			<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
			<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
		  </div>
		</div>
		<div class="box-body">
		 <form action="<?php echo base_url('htaccess/save_data');?>" method="post">
            <textarea id="code" name="code"><?php echo $string;?></textarea>
			<center style="margin-top:15px;">
				<button type="submit" value="Save" class="btn btn-primary btn-flat"> S a v e </button>
			</center>
		</form>
		</div><!-- /.box-body -->
		<div class="box-footer">
		  <div class="row">
			<div class="col-xs-12">
				<p class="footer"><em><sup>Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  '| &nbsp;&nbsp;&nbsp;Engine : CodeIgniter version <strong>' . CI_VERSION . '</strong>' : '' ?>
				<span class="pull-right"><?php echo "Memory use  : " . $this->benchmark->memory_usage();?></span>
				</sup></em></p>
			</div>
		</div>
		</div><!-- /.box-footer-->
	</div><!-- /.box -->
</section><!-- /.content -->

<script>
	window.onload = function() {
		window.editor = CodeMirror.fromTextArea(code, {
			mode: "properties",
			theme: "mbo",
			lineNumbers: true,
			lineWrapping: true,
			gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
		});
	};
</script>