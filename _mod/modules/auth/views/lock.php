<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?=css_url('icons/icomoon/styles.css');?>" rel="stylesheet" type="text/css">
	<link href="<?=css_url('bootstrap.min.css');?>" rel="stylesheet" type="text/css">
	<link href="<?=css_url('bootstrap_limitless.min.css');?>" rel="stylesheet" type="text/css">
	<link href="<?=css_url('layout.min.css');?>" rel="stylesheet" type="text/css">
	<link href="<?=css_url('components.min.css');?>" rel="stylesheet" type="text/css">
	<link href="<?=css_url('colors.min.css');?>" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="<?=js_url('jquery.min.js');?>"></script>
	<script src="<?=js_url('bootstrap.bundle.min.js');?>"></script>
	<script src="<?=js_url('loaders/blockui.min.js');?>"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="<?=js_url('forms/styling/uniform.min.js');?>"></script>

	<script src="<?=js_url('app.js');?>"></script>
	<script src="<?=js_url('login.js');?>"></script>
	<!-- /theme JS files -->

</head>

<body>
<?php
  $_data_user_ =$this->session->userdata('data_user');
  $hide='';
  $message = $this->session->flashdata('message');
  if (empty($message)){$hide='d-none';}
?>
	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Unlock form -->
				<?php echo form_open("auth/unlock", ['class' => 'login-form', 'id' => 'form_login']);?>
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center">
								<div class="card-img-actions d-inline-block mb-3">
                  <img src="<?=file_url($_data_user_['photo']);?>" width="160" height="160" class="rounded-circle" alt="">
									<div class="card-img-actions-overlay card-img rounded-circle">
										<a href="#" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
											<i class="icon-question7"></i>
										</a>
									</div>
								</div>
							</div>

							<div class="text-center mb-3">
								<h6 class="font-weight-semibold mb-0"><?=$_data_user_['real_name'];?></h6>
                <span class="d-block text-muted">Unlock your account</span>
                <div class="alert alert-danger border-0 alert-dismissible <?=$hide;?>">
                  <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                  <span class="font-weight-semibold"><?php echo $message;?>
                </div>
				</div>

				<div class="form-group form-group-feedback form-group-feedback-right">
					<input type="password" name="sandi" class="form-control" placeholder="Your password">
					<div class="form-control-feedback">
						<i class="icon-user-lock text-muted"></i>
					</div>
				</div>

				<br/>

				<button type="submit" class="btn btn-primary btn-block"><i class="icon-unlocked mr-2"></i> Unlock</button><br>
				<em class="text-danger"><sup><?='lock by user since '.$waktu;?></sup></em>
			</div>
					</div>
        <?php echo form_close();?>
				<!-- /unlock form -->

			</div>
			<!-- /content area -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

</body>
</html>
