<!-- Main navbar -->
<?php
    $flag='id.png';
    $flagE='';
    $flagI='active';
    if( _BAHASA_=="english"){
        $flag = 'gb.png';
        $flagE = 'active';
        $flagI = '';
    }

    $photo=img_url('profile.jpg');
	if(file_exists(file_path($params['user']['photo'])) && !empty($params['user']['photo']))
		$photo=file_url($params['user']['photo']);
?>
<div class="navbar navbar-expand-md navbar-light fixed-top">

    <!-- Header with logos -->
    <div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center">
        <div class="navbar-brand navbar-brand-md">
            <a href="<?=base_url();?>" class="d-inline-block">
                <span style="color:#ffffff;"><?=$params['preference']['judul_atas'];?></span>
            </a>
        </div>

        <div class="navbar-brand navbar-brand-xs">
            <a href="<?=base_url();?>" class="d-inline-block">
                <img src="assets/images/logo_icon_light.png" alt="">
            </a>
        </div>
    </div>
    <!-- /header with logos -->


    <!-- Mobile controls -->
    <div class="d-flex flex-1 d-md-none">
        <div class="navbar-brand mr-auto">
            <a href="<?=base_url();?>" class="d-inline-block">
                <img src="<?=img_url('logo_icon_light.png');?>" alt="">
            </a>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
        <?php if ($params['show_second_sidebar']):?>
            <button class="navbar-toggler sidebar-mobile-secondary-toggle" type="button">
                <i class="icon-more"></i>
            </button>
        <?php endif;?>
        <?php if ($params['show_right_sidebar']):?>
            <button class="navbar-toggler sidebar-mobile-right-toggle" type="button">
				<i class="icon-more"></i>
            </button>
        <?php endif;?>
    </div>
    <!-- /mobile controls -->


    <!-- Navbar content -->
    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
            <?php if ($params['show_second_sidebar']):?>
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-secondary-toggle d-none d-md-block" data-popup="tooltip-demo" title="Hide secondary" data-placement="bottom" data-container="body" data-trigger="hover">
                    <i class="icon-transmission"></i>
                </a>
            </li>
            <?php endif;?>
        </ul>

        <span class="ml-md-3 mr-md-auto">&nbsp;</span>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="<?=base_front_url();?>" target="_blank" class="navbar-nav-link">
                    Web
                </a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="<?=img_url('lang/'.$flag);?>" class="img-flag mr-2" alt="">
                    <?= ucwords(_BAHASA_);?>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="<?=base_url(_MODULE_NAME_.'/switchLang/english');?>" class="dropdown-item english <?=$flagE;?>"><img src="<?=img_url('lang/gb.png');?>" class="img-flag" alt="">
                        English</a>
                    <a href="<?=base_url(_MODULE_NAME_.'/switchLang/indonesia');?>" class="dropdown-item indonesia <?=$flagI;?>"><img src="<?=img_url('lang/id.png');?>" class="img-flag" alt="">
                        Indonesia</a>
                </div>
            </li>
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <img src="<?=$photo;?>" class="rounded-circle mr-2" height="34" alt="">
                    <span><?=$params['user']['real_name'];?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="<?=base_url('profile/edit/'.$params['user']['staft_id']);?>" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
                    <a href="<?=base_url('change-password');?>" class="dropdown-item"><i class="icon-coins"></i> Change Password</a>
                    <a href="#" class="dropdown-item"><i class="icon-comment-discussion"></i> Messages <span class="badge badge-pill bg-indigo-400 ml-auto">58</span></a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>
                    <a href="<?=base_url('auth/logout');?>" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                </div>
            </li>
            <?php
            if ($params['show_right_sidebar']):?>
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-right-toggle d-none d-md-block" data-popup="tooltip-demo" title="Toggle right" data-placement="bottom" data-container="body" data-trigger="hover">
                    <i class="icon-transmission"></i>
                </a>
            </li>
            <?php endif;?>
        </ul>
    </div>
    <!-- /navbar content -->
</div>
<!-- /main navbar -->