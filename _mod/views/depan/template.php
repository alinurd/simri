<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="<?=img_url('favicon.ico');?>" />
		<title>Material <?=$this->template->_params['preference']['nama_kantor']. ' - '.$this->template->title;?></title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
		
		<?=$this->template->stylesheet;?>
		<?=$this->template->javascript;?>
	</head>
	<body>
		<div class="page-content">
			<div class="content-wrapper">
				<div class="content">
					<?php echo $this->template->content;?>
				</div>
				<?php echo $this->template->widget("Footer", array('title' => 'Footer','params'=>$this->template->_params));?>
			</div>
			<?php echo $this->template->right_sidebar;?>
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