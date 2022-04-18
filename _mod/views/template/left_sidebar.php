<!-- Main sidebar -->
<?php
	$photo=img_url('profile.jpg');
	if(file_exists(file_path($params['user']['photo'])) && !empty($params['user']['photo']))
		$photo=file_url($params['user']['photo']);
?>
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

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
		<div class="sidebar-user">
			<div class="card-body">
				<div class="media">
					<div class="mr-3">
					<a href="#">
						<img src="<?=$photo;?>" width="38" height="38" class="rounded-circle" alt="">
					</a>
					</div>

					<div class="media-body">
						<div class="media-title font-weight-semibold"><?=$params['user']['real_name'];?></div>
						<div class="font-size-xs opacity-50">
							<i class="icon-pin font-size-sm"></i> &nbsp;<?=$params['user']['email'];?>
						</div>
					</div>

					<div class="ml-3 align-self-center">
						<a href="#" class="text-white"><i class="icon-cog3"></i></a>
					</div>
				</div>
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