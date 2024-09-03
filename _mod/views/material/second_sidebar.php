<!-- Secondary sidebar -->
<div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-secondary-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        <span class="font-weight-semibold">Secondary sidebar</span>
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Sidebar search -->
        <div class="card">
            <div class="card-body text-center">
                <div class="card-img-actions d-inline-block mb-3">
                    <img class="img-fluid rounded-circle" src="<?= file_url( $params['user']['photo'] ); ?>" alt=""
                        width="170" height="170">
                    <div class="card-img-actions-overlay card-img rounded-circle">
                        <a href="#"
                            class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round">
                            <i class="icon-plus3"></i>
                        </a>
                        <a href="user_pages_profile.html"
                            class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round ml-2">
                            <i class="icon-link"></i>
                        </a>
                    </div>
                </div>
                <h6 class="font-weight-semibold mb-0"><?= $params['user']['real_name']; ?></h6>
                <span class="d-block text-muted"><?= $params['user']['position']; ?></span>
                <hr>
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    <li class="nav-item" style="margin:10px 15px;">
                        <span class="btn bg-teal-300 btn-labeled left rounded-round"
                            style="width:100%;padding:15px;">HOME</span>
                    </li>
                    <li class="nav-item" style="margin:10px 15px;">
                        <span class="btn bg-danger-300 btn-labeled left rounded-round"
                            style="width:100%;padding:15px;">Assignment
                            <span class="badge bg-danger-400 badge-pill badge-float border-2 border-white"
                                style="top:-1rem; right:-4rem;"><?= count( $data ); ?></span></span>
                    </li>
                    <li class="nav-item" style="margin:10px 15px;">
                        <span class="btn bg-orange-300 btn-labeled left rounded-round"
                            style="width:100%;padding:15px;">Progress</span>
                    </li>
                    <li class="nav-item" style="margin:10px 15px;">
                        <span class="btn bg-green btn-labeled left rounded-round"
                            style="width:100%;padding:15px;">Chat</span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar search -->

    </div>
    <!-- /sidebar content -->

</div>
<!-- /secondary sidebar -->