<!-- Main navbar -->
<?php
$photo = img_url( 'profile.jpg' );
if( file_exists( file_path( $params['user']['photo'] ) ) )
    $photo = file_url( $params['user']['photo'] );
?>
<div class="navbar navbar-expand-md navbar-light fixed-top">

    <!-- Header with logos -->
    <div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center">
        <div class="navbar-brand navbar-brand-md">
            <a href="/" class="d-inline-flex">
                <img src="<?= img_url( 'logo.png' ); ?>" alt="" style="width: 100px; height: 25px; margin-right: 5px;">
                <span style="color:#ffffff;font-size:20px"><?= $params['preference']['judul_atas']; ?></span>
            </a>
        </div>

        <div class="navbar-brand navbar-brand-xs">
            <a href="index.html" class="d-inline-block">
                <?php
                if( file_exists( img_path( "logo_icon_light.png" ) ) )
                {
                    ?>
                    <img src="<?= img_url( 'logo_icon_light.png' ); ?>" alt="">
                    <?php
                }
                ?>
            </a>
        </div>
    </div>
    <!-- /header with logos -->

    <!-- Mobile controls -->
    <div class="d-flex flex-1 d-md-none">
        <div class="navbar-brand mr-auto">
            <a href="index.html" class="d-inline-block">
                <?php if( file_exists( img_path( "logo_icon_light.png" ) ) )
                { ?>
                    <img src="<?= img_url( 'logo_icon_light.png' ); ?>" alt="">
                <?php } ?>
            </a>
        </div>

        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
        <?php if( $params['show_second_sidebar'] ) : ?>
            <button class="navbar-toggler sidebar-mobile-secondary-toggle" type="button">
                <i class="icon-more"></i>
            </button>
        <?php endif; ?>
        <?php if( $params['show_right_sidebar'] ) : ?>
            <button class="navbar-toggler sidebar-mobile-right-toggle" type="button">
                <i class="icon-more"></i>
            </button>
        <?php endif; ?>
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
            <?php if( $params['show_second_sidebar'] ) : ?>
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-secondary-toggle d-none d-md-block"
                        data-popup="tooltip-demo" title="Hide secondary" data-placement="bottom" data-container="body"
                        data-trigger="hover">
                        <i class="icon-transmission"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <span class="ml-md-3 mr-md-auto">&nbsp;</span>

        <ul class="navbar-nav">
            <li class="nav-item mr-2" style="align-content:center;">
                <span class="badge bg-primary badge-pill dropdown-item"> Periode
                    <?= _TAHUN_; ?> -
                    <?= _TERM_; ?> - Bulan <?= date( 'F' ); ?> <!--- Minggu ke <?= _MINGGU_; ?> --> </span>
            </li>
            <!-- <li class="nav-item">
                <a target="_blank" href="<?= base_url( 'files/Pentunjuk_Penggunaan_Aplikasi_SIMRI.pdf' ) ?>"
                    class="dropdown-item bg-primary ml-2" id="modul-manual"><i class="icon-book"></i> Manual </a>
            </li> -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle p-0" href="#" id="navbarDropdownMenuLink" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?= $photo; ?>" width="38" height="38"
                        class="img-fluid rounded-circle border border-secondary shadow-sm" alt="">
                </a>
                <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="<?= base_url( 'profile' ); ?>"><i class="icon-user"></i>Profile</a>
                    <div class="dropdown-divider m-0"></div>
                    <a class="dropdown-item"
                        href="<?= base_url( 'files/Pentunjuk_Penggunaan_Aplikasi_SIMRI.pdf' ) ?>"><i
                            class="icon-book"></i>Manual</a>
                    <div class="dropdown-divider m-0"></div>
                    <a class="dropdown-item" href="<?= base_url( 'auth/logout' ); ?>"><i class="icon-enter3"></i>Log
                        Out</a>
                </div>
            </li>
            <?php
            if( $params['show_right_sidebar'] ) : ?>
                <li class="nav-item">
                    <a href="#" class="navbar-nav-link sidebar-control sidebar-right-toggle d-none d-md-block"
                        data-popup="tooltip-demo" title="Toggle right" data-placement="bottom" data-container="body"
                        data-trigger="hover">
                        <i class="icon-transmission"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- /navbar content -->
</div>
<!-- /main navbar -->