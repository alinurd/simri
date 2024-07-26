<!-- Main navbar -->
<?php
$flag  = 'id.png';
$flagE = '';
$flagI = 'active';
if( _BAHASA_ == "english" )
{
    $flag  = 'gb.png';
    $flagE = 'active';
    $flagI = '';
}

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
                if( file_exists( "assets/images/logo_icon_light.png" ) )
                {
                    ?>
                    <img src="assets/images/logo_icon_light.png" alt="">
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
                <img src="<?= img_url( 'logo_icon_light.png' ); ?>" alt="">
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
            <li class="nav-item">
                <span class="badge bg-primary badge-pill ml-md-3 mr-md-auto mt-1"> Periode <?= _TAHUN_; ?> -
                    <?= _TERM_; ?> - Bulan <?= date( 'F' ); ?> <!--- Minggu ke <?= _MINGGU_; ?> --> </span>
            </li>
            <li class="nav-item">
                <a target="_blank" href="<?= base_url( 'files/Pentunjuk_Penggunaan_Aplikasi_SIMRI.pdf' ) ?>"
                    class="dropdown-item bg-primary ml-2" id="modul-manual"><i class="icon-book"></i> Manual </a>
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