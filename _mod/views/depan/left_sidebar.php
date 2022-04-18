<!-- Main sidebar -->
<?php
	$photo=img_url('profile.jpg');
	if(file_exists(file_path($params['user']['photo'])))
		$photo=file_url($params['user']['photo']);
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
			<div class="sidebar-user-material-body">
				<div class="card-body text-center">
					<a href="#">
						<img src="<?=$photo;?>" width="38" height="38" class="rounded-circle" alt="">
					</a>
					<h6 class="mb-0 text-white text-shadow-dark"><?=$params['user']['real_name'];?></h6>
					<span class="font-size-sm text-white text-shadow-dark"><?=$params['user']['email'];?></span>
				</div>
				<div class="sidebar-user-material-footer">
					<a href="#user-nav" class="d-flex justify-content-between align-items-center text-shadow-dark dropdown-toggle" data-toggle="collapse">&nbsp;</a>
				</div>
			</div>
			<div class="collapse" id="user-nav">
				<ul class="nav nav-sidebar">
					<li class="nav-item">
						<a href="<?=base_url('profile/edit/'.$params['user']['staft_id']);?>" class="nav-link">
							<i class="icon-user-plus"></i>
							<span>My profile</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?=base_url('change-password');?>" class="nav-link">
							<i class="icon-coins"></i>
							<span>Change Pasword</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?=base_url('inbox');?>" class="nav-link">
							<i class="icon-comment-discussion"></i>
							<span>Messages</span>
							<span class="badge bg-teal-400 badge-pill align-self-center ml-auto">58</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?=base_url('auth/logout');?>" class="nav-link">
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
			<?=$menu;?>
		</div>

		<div class="sidebar-footer hidden-small">
			<a href="<?=base_url('support');?>" data-popup="tooltip" data-placement="top" data-original-title="Support">
			<i class="icon-cog2" aria-hidden="true"></i>
			</a>
			<a  href="#"  data-popup="tooltip" data-placement="top" data-original-title="FullScreen">
			<i class="icon-screen-full full_screen" aria-hidden="true"></i>
			</a>
			<a data-popup="tooltip" data-placement="top" data-original-title=" Loct Screen " href="<?=base_url('lock-screen');?>">
			<i class="icon-lock" aria-hidden="true"></i>
			</a>
			<a data-popup="tooltip" data-placement="top" href="<?=base_url('auth/logout');?>" data-original-title="Logout">
			<i class="icon-enter3" aria-hidden="true"></i>
			</a>
		</div>
		<!-- /main navigation -->
	</div>
	<!-- /sidebar content -->
</div>
<!-- /main sidebar -->

<script>
	$(function(){
		$('.nav-sidebar a.active').parents('li').addClass("active");
		$('.nav-sidebar a.active').parents('ul').css({'display':'block'});
	})
</script>