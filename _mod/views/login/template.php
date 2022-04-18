<?php
	$repeat='no-repeat';
	$size='background-size: cover;';
	if ($this->template->_params['preference']['image_login_repeat']){
		$repeat='repeat';
		$size='';
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="<?=img_url('favicon.ico');?>" />
		<title><?=$this->template->_params['preference']['nama_kantor']. ' - '.$this->template->title;?></title>
		<?=$this->template->stylesheet;?>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
		<?=$this->template->javascript;?>

		<style>
			body:before{
			content: '';
			position: absolute;
			top: 0;
			right: 0;
			left: 0;
			bottom: 0;
			background: rgba(0,0,0,0.7);
		}
		</style>
	</head>
	<body class="login" style="background:url('<?=img_url($this->template->_params['preference']['image_login']);?>') <?=$repeat;?> center fixed;<?=$size;?>">
		<?php //echo $this->template->header_front;?>
		<div class="page-content">
			<div class="content-wrapper">
				<div class="content d-flex justify-content-center align-items-center">
					<?php echo $this->template->content;?>
				</div>
				<?php //echo $this->template->footer_front;?>
			</div>
		</div>
		<script>
			var gambar=['slide1.jpg','slide2.jpg','slide3.jpg','slide4.jpg','slide5.jpg','slide6.jpg'];
			var nil=0;
			$(function(){
			
			setInterval(function(){
				++nil;
				if (nil>5){
					nil=0;
				};
				$(".login").css('background','');
				$(".login").css('background','url("assets/images/'+gambar[nil]+'") no-repeat scroll center center / cover rgba(0, 0, 0, 0)'); }
			, 6000);
		});
		</script>
	</body>
</html>