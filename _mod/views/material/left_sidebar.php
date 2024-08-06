<!-- Main sidebar -->
<?php
$photo = img_url( 'profile.jpg' );
if( file_exists( file_url( $params['user']['photo'] ) ) )
	$photo = file_url( $params['user']['photo'] );
?>
<div class="sidebar sidebar-light sidebar-main sidebar-expand-md">

	<!-- Sidebar mobile toggler -->
	<div class="sidebar-mobile-toggler text-center">
		<a href="#" class="sidebar-mobile-main-toggle">
			<i class="icon-arrow-left8"></i>
		</a>
		Navigation
		<a href="#" class="sidebar-mobile-expand">
			<i class="icon-screen-full"></i>
			<i class="icon-screen-normal"></i>
		</a>
	</div>
	<!-- /sidebar mobile toggler -->


	<!-- Sidebar content -->
	<div class="sidebar-content">
		<!-- User menu -->
		<div class="sidebar-user-material">
			<div class="sidebar-user-material-body"
				style="background:url('<?= img_url( 'slide2.jpg' ); ?>') repeat center center;background-size: cover;">
				<div class="card-body text-center">
					<a href="#">
						<img src="<?= $photo; ?>" width="38" height="38" class="rounded-circle" alt="">
					</a>
					<h6 class="mb-0 text-white text-shadow-dark"><?= $params['user']['real_name']; ?></h6>
					<span class="font-size-sm text-white text-shadow-dark"><?= $params['user']['email']; ?></span>
				</div>
				<div class="sidebar-user-material-footer d-none">
					<a href="#user-nav"
						class="d-flex justify-content-between align-items-center text-shadow-dark dropdown-toggle"
						data-toggle="collapse" style="background-color: rgb(51 149 220 / 70%);">&nbsp;</a>
				</div>
			</div>
			<div class="collapse" id="user-nav">
				<ul class="nav nav-sidebar">
					<li class="nav-item">
						<a href="<?= base_url( 'profile/edit/' . $params['user']['staft_id'] ); ?>" class="nav-link">
							<i class="icon-user-plus"></i>
							<span>My profile</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?= base_url( 'change-password/edit/' . $params['user']['staft_id'] ); ?>"
							class="nav-link">
							<i class="icon-coins"></i>
							<span>Change Pasword</span>
						</a>
					</li>
					<li class="nav-item d-none">
						<a href="<?= base_url( 'inbox' ); ?>" class="nav-link">
							<i class="icon-comment-discussion"></i>
							<span>Messages</span>
							<span class="badge bg-teal-400 badge-pill align-self-center ml-auto">58</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?= base_url( 'auth/logout' ); ?>" class="nav-link">
							<i class="icon-switch2"></i>
							<span>Logout</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- /user menu -->


		<!-- Main navigation -->
		<div class="card card-sidebar-mobile">
			<?= $menu; ?>
		</div>
		<!-- /main navigation -->
	</div>
	<!-- /sidebar content -->
</div>
<!-- /main sidebar -->

<script>
	$(function () {
		$('.nav-sidebar a.active').parents('li').addClass("active");
		$('.nav-sidebar a.active').parents('ul').css({
			'display': 'block'
		});
	})
</script>