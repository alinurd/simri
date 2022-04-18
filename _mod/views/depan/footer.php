<?=$modal;?>
<div class="navbar navbar-expand-lg navbar-light">
    <div class="text-center d-lg-none w-100">
        <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
            <i class="icon-unfold mr-2"></i>
            Footer
        </button>
    </div>

    <div class="navbar-collapse collapse" id="navbar-footer">
        <span class="navbar-text">
            &copy; <a href="#"><?=$params['preference']['judul_bawah'];?></a> by <a href="http://www.limabit.com/" target="_blank">Limabit</a>
        </span>
        <ul class="navbar-nav ml-lg-auto">
            <li class="nav-item"><a data-popup="tooltip" class="pull-right" data-placement="top" href="<?=base_url('auth/logout');?>" data-original-title="Logout">
            <span class="text-pink-400"><i class="icon-enter3" aria-hidden="true"></i> Logout</span>
                </a></li>
        </ul>
        <span class="pull-right">
            
        </span>
    </div>
</div>