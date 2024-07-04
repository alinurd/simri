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

		document.addEventListener('DOMContentLoaded', function () {
			document.getElementById('new').addEventListener('input', validatePassword);
			document.getElementById('new_confirm').addEventListener('input', validatePassword);
		});
	</script>

</head>

<body>
	<?php
	function maskName($fullName) {
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


				<?php echo form_open("auth/reset_password_prosess" . $code, ['class' => 'login-form', 'id' => 'forma_login']); ?>
				<div class="card mb-0">
					<div class="card-body">
						<div class="text-center mb-3">
							<span class="d-block text-muted">
								<h1><?php echo lang('reset_password_heading'); ?></h1> 
								<h6 class="font-weight-semibold mb-0">Hallo <?php echo $maskedName; ?></h6>
							</span> 
							<h6 class="font-weight-semibold mb-0">Please enter your new password</h6>
						</div>
						<div class="form-group form-group-feedback form-group-feedback-right">
							<input type="<?=$new_password['type']?>" name="<?=$new_password['name']?>" pattern="<?=$new_password['pattern']?>" id="<?=$new_password['id']?>" class="form-control" placeholder="Your password">
							<div class="form-control-feedback">
								<i class="icon-user-lock text-muted"></i>
							</div>
						</div>
						<div class="form-group form-group-feedback form-group-feedback-right">
							<input type="<?=$new_password_confirm['type']?>" name="<?=$new_password_confirm['name']?>" pattern="<?=$new_password_confirm['pattern']?>" id="<?=$new_password_confirm['id']?>" class="form-control" placeholder="Confirm password">
							<div class="form-control-feedback">
								<i class="icon-user-lock text-muted"></i>
							</div>
						</div>
						<div id="error_message" class="text-danger" style="display:none;">
							Passwords do not match or do not meet the minimum length requirement.
						</div>
						<input type="hidden" value="<?=$user_id['value']?>">
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
