<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?= css_url('icons/icomoon/styles.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?= css_url('bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?= css_url('bootstrap_limitless.min.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?= css_url('layout.min.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?= css_url('components.min.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?= css_url('colors.min.css'); ?>" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="<?= js_url('jquery.min.js'); ?>"></script>
	<script src="<?= js_url('bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?= js_url('loaders/blockui.min.js'); ?>"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="<?= js_url('forms/styling/uniform.min.js'); ?>"></script>
	<script src="<?= js_url('app.js'); ?>"></script>
	<script src="<?= js_url('login.js'); ?>"></script>
	<!-- /theme JS files -->

	<script>
		function validatePassword() {
			var newPassword = document.getElementById('new').value;
			var confirmPassword = document.getElementById('new_confirm').value;
			var submitButton = document.getElementById('submit_buttonx');
			var errorMessage = document.getElementById('error_message');

			if (newPassword === confirmPassword && newPassword.length >= <?= $min_password_length ?>) {
				submitButton.disabled = false;
				errorMessage.style.display = 'none';
			} else {
				submitButton.disabled = true;
				errorMessage.style.display = 'block';
			}
		}

		document.addEventListener('DOMContentLoaded', function() {
			document.getElementById('new').addEventListener('input', validatePassword);
			document.getElementById('new_confirm').addEventListener('input', validatePassword);
		});
	</script>

</head>

<body>
	<?php
	$hide = '';
	$message = $message;
	if (empty($message)) {
		$hide = 'd-none';
	}


	function maskName($fullName)
	{
		$words = explode(' ', $fullName);
		$maskedName = '';

		foreach ($words as $word) {
			$maskedName .= substr($word, 0, 2) . str_repeat('*', strlen($word) - 2) . ' ';
		}
		return trim($maskedName);
	}
	$realName = $user_id['real_name'];
	$maskedName = maskName($realName);
	?>
	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">


				<?php echo form_open("auth/proses_reset_password", ['class' => 'login-form', 'id' => 'forma_login']); ?>
				<div class="card mb-0">
					<div class="card-body">
						<div class="text-center mb-3">
							<img src="<?= img_url('logo.png'); ?>" alt="" width="150">
							<h5><?php echo lang('reset_password_heading'); ?></h5>
							<span class="d-block text-muted">Hallo <?php echo $maskedName; ?> <br> Please enter your new password</span>

							<div class="alert alert-danger border-0 alert-dismissible <?= $hide; ?>">
								<button type="button" class="close" data-dismiss="alert"><span>×</span></button>
								<span class="font-weight-semibold"><?php 
 								foreach ($message as $msg) {
									echo "-".$msg . " <br>";
								}
								?>
							</div>
							<div class="form-group form-group-feedback form-group-feedback-right">
								<input type="<?= $new_password['type'] ?>" name="<?= $new_password['name'] ?>" pattern="<?= $new_password['pattern'] ?>" id="<?= $new_password['id'] ?>" class="form-control" placeholder="Your password">
								<div class="form-control-feedback">
									<i class="icon-user-lock text-muted"></i>
								</div>
							</div>
							<div class="form-group form-group-feedback form-group-feedback-right">
								<input type="<?= $new_password_confirm['type'] ?>" name="<?= $new_password_confirm['name'] ?>" pattern="<?= $new_password_confirm['pattern'] ?>" id="<?= $new_password_confirm['id'] ?>" class="form-control" placeholder="Confirm password">
								<div class="form-control-feedback">
									<i class="icon-user-lock text-muted"></i>
								</div>
							</div>
							<div id="error_message" class="text-danger" style="display:none;">
								Passwords do not match or do not meet the minimum length requirement.
							</div>
							<input type="hidden" name="user_id" value="<?= $user_id['value'] ?>">
							<input type="hidden" name="code" value="<?= $code ?>">
							<br />
							<button type="submit" id="submit_buttonx" class="btn btn-primary " disabled><?php echo lang('reset_password_submit_btn'); ?></button><br>
						</div>
					</div>
					<?php echo form_close(); ?>
					<!-- /unlock form -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

</body>

</html>