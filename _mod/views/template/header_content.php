<!-- Page header -->
<div class="page-header page-header-light">
    <?php
    if ($param['show_title_header']):?>
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Colors</span> - Grey Palette</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
    <?php endif;?>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline" style="<?=(!$param['show_title_header'])?'margin-top:8px;':'';?>">
        <div class="d-flex">
            <div class="breadcrumb">
                <i class="icon-home2 mr-2"></i>
                <?=$breadcrumbs;?>
            </div>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements d-none">
            <div class="breadcrumb justify-content-center">
                <div class="breadcrumb-elements-item dropdown p-0">
                    <a href="#" class="breadcrumb-elements-item dropdown-toggl" data-toggle="dropdown">
                        &nbsp;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page header -->