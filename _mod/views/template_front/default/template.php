<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- <meta name="keywords" content="HTML5 Template" />
		<meta name="description" content="SEOhub - SEO, Marketing. Social Media, Multipurpose HTML5 Template" />
		<meta name="author" content="potenzaglobalsolutions.com" /> -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		
		<link rel="shortcut icon" href="<?=img_url('favicon.ico');?>" />
		<title><?=$this->template->title;?></title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
		
		<?=$this->template->meta;?>
		<?=$this->template->stylesheet;?>
		<?=$this->template->javascript;?>
	</head>
	<body>
		<!--================================= loading -->
		<div id="loading">
			<div id="loading-center">
				<img src="<?=img_url('loader.gif');?>" alt="">
			</div>
		</div>
		<!--================================= loading -->

		<?php echo $this->template->widget("Header", array('title' => 'Header', 'params'=>$this->template->_params));?>
		<?php echo $this->template->content;?>
		<?php echo $this->template->widget("Footer", array('title' => 'Footer', 'params'=>$this->template->_params));?>
		
		<!-- Small modal -->
		<div id="modal_notif" class="modal fade" tabindex="-1">
			<div class="modal-dialog" style="max-width:90%;">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Notif</h5>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body">
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /small modal -->

		<script>
			var base_url = "<?php echo base_url();?>";
			var mode_aksi = "<?php echo _MODE_;?>";
			var modul_name = "<?php echo $this->router->fetch_module();?>";
			var csrf_token_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
			var csrf_cookie_name = '<?php echo $this->config->item('csrf_cookie_name'); ?>';
			var wa = '<?php echo $this->template->_params['preference']['sos_wa']; ?>';
			var email = '<?php echo $this->template->_params['preference']['email_kantor']; ?>';
			var postion = '<?php echo $this->template->_params['preference']['message_location']; ?>';
		</script>

	<!-- GetButton.io widget -->
	<script type="text/javascript">
		(function () {
			var options = {
				whatsapp: '', // WhatsApp number
				email: '', // Email
				call_to_action: "Message us", // Call to action
				button_color: "#932C8B", // Color of button
				position: "left", // Position may be 'right' or 'left'
				order: "whatsapp,email", // Order of buttons
			};

			options['whatsapp']=wa;
			options['email']=email;
			options['postion']=postion;
			console.log(options);
			var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
			var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
			s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
			var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
		})();
	</script>

	<!--Start of Tawk.to Script-->
	<script type="text/javascript">
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/5df76111d96992700fcc7b89/default';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();
	</script>
	<!--End of Tawk.to Script-->
	<!-- /GetButton.io widget -->
	</body>
</html>