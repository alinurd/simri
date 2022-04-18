<?php
	$huruf=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
?>

<section class="content-header">
  <h1>
	Dashboard
  </h1>
  <ol class="breadcrumb">
	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	<li><a href="#">Dashboard</a></li>
  </ol>
</section>
<?php echo form_open('sub-bahasa/save_data');?>
<section class="content">
	<section class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<strong><?=lang('msg_field_module_list');?></strong><br/>
					<?=form_dropdown('cboModul', $cbo_modul, $nm_modul,"id='cboModul' class='form-control' style='width:100% !important;' ");?>					
				</div>
				<div class="col-md-6">
					<strong><?=lang('msg_field_active_language');?></strong><br/>
					<?=form_dropdown('cboBahasa', $cbo_bahasa, $bahasa,"id='cboBahasa' class='form-control' style='width:100% !important;' ");?>					
				</div>
			</div>
		</div>
	</section>
	<section class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12" id="content_bahasa">
					<section class="panel" id='isi_event_detail' style="border:1px solid #2A3542;">
						<textarea id="code" name="code" rows="20" cols="50"><?php echo $string;?></textarea>
						<input type="hidden" name="modul" id="modul" value="<?=$nm_modul;?>">
						<center style="margin-top:15px;">
							<button type="submit" value="Save" class="btn btn-primary"> S a v e </button>
						</center>
					</section>
				</div>
			</div>
		</div>
	</section>
</section>
<?php echo form_close();?>
		
<script>
	var modul_tmp='<?=$nm_modul;?>';
	var bahasa_tmp='<?=$bahasa;?>';
	window.onload = function() {
		window.editor = CodeMirror.fromTextArea(code, {
			mode: "properties",
			theme: "mbo",
			lineNumbers: true,
			lineWrapping: true,
			gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
		});
	};
	
	$("#cboModul").change(function(){
		modul_tmp=$(this).val();
		get_content_bahasa();
	})
	
	$("#cboBahasa").change(function(){
		bahasa_tmp=$(this).val();
		get_content_bahasa();
	})
	
	function get_content_bahasa(){
		var modul=modul_tmp;
		var bahasa=bahasa_tmp;
		console.log(modul);
		console.log(bahasa);
		
		if (modul.length==0 || bahasa.length==0){
			return false;
		}
		
		var dark_4 = $("#content_bahasa");
		// looding('dark',dark_4);
		
		var url='<?php echo base_url("sub-bahasa");?>';
		window.location.href=url + "/" + modul + "/" + bahasa;
	}
	
	$(".icon-circle-down2").click(function(){
		var no_id=$(this).data('hidden');
		$(".child-"+no_id).toggle();
	})
</script>