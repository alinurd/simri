<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="<?=img_url('favicon.ico');?>" />
		<title><?=$this->template->_params['preference']['nama_kantor']. ' - '.$this->template->title;?></title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
		
		<?=$this->template->stylesheet;?>
		<?=$this->template->javascript;?>
	</head>
	<body class="layout-boxed-bg navbar-top <?=($this->template->_params['left_sidebar_mini'])?'sidebar-xs':''?> default">
	<div id="fb-root"></div>
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v5.0"></script>
		<?php echo $this->template->widget("Header", array('title' => 'Project name','params'=>$this->template->_params));?>
		<div class="page-content">
			<?php echo $this->template->widget("Left_Sidebar", array('title' => 'SideBar','params'=>$this->template->_params));?>
			<?php echo $this->template->second_sidebar;?>
			<div class="content-wrapper">
				<?php echo $this->template->header_content;?>
				<div class="content">
					<?php echo $this->template->widget("Tombol_Aksi", array('title' => 'Tombol','params'=>$this->template->_params));?>
					<?php echo $this->template->content;?>
				</div>
				<?php echo $this->template->widget("Footer", array('title' => 'Footer','params'=>$this->template->_params));?>
			</div>
			<?php echo $this->template->right_sidebar;?>
		</div>
		<div id="modal_general" class="modal fade" tabindex="-1">
			<div class="modal-dialog modal-full">
				<div class="modal-content">
					<div class="modal-header bg-teal-300">
						<h5 class="modal-title"><i class="icon-search4"></i> Title</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<?php echo form_open($this->uri->uri_string,array('id'=>'form_general', 'class'=>'form-horizontal','role'=>'form"'));?>
					<div class="modal-body">
						
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					</div>
					</form>
				</div>
			</div>
		</div>
	<script>
		
		var base_url = "<?php echo base_url();?>";
		var mode_aksi = "<?php echo _MODE_;?>";
		var modul_name = "<?php echo $this->router->fetch_module();?>";
		var csrf_token_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
		var csrf_cookie_name = '<?php echo $this->config->item('csrf_cookie_name'); ?>';

		$(function() {
			var object = {};
			object[csrf_token_name] = Cookies.get(csrf_cookie_name);

			$.ajaxSetup({
				data: object
			});
			$(document).ajaxComplete(function () {
				object[csrf_token_name] = Cookies.get(csrf_cookie_name);
				$('input[name="'+csrf_token_name+'"]').val(Cookies.get(csrf_cookie_name));
				$.ajaxSetup({
					data: object
				});
			});

			$('form').submit(function() {
				object[csrf_token_name] = Cookies.get(csrf_cookie_name);
				$('input[name="'+csrf_token_name+'"]').val(Cookies.get(csrf_cookie_name));
				return true;
			});
		});
	</script>
	</body>
</html>