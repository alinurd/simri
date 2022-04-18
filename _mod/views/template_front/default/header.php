    <!--=================================
        header -->
    <header id="header" class="fancy">
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="topbar-left text-left">
                            <ul class="list-inline">
                                <li title="google location"> <a target="_blank" href="<?=$params['preference']['map_kantor'];?>"><i class="ti-location-pin"> </i> <?=$params['preference']['nama_kantor'];?></a></li>
                                <li> <i class="ti-headphone-alt"></i><?=$params['preference']['telp_kantor'];?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="topbar-right text-right">
                            <ul class="list-inline">
                                <?php
                                foreach($menus0 as $row):?>
                                <!--<li><a href="<?=$row['url'];?>"> <?=$row['title'];?></a></li>-->
                                <?php endforeach;?>
                                <li>
                                    <div class="dropdown">
                                        <a class="dropdown-toggle pointer" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Language : <?=_BAHASA_;?>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <?php
                                            foreach($bahasa as $row):?>
                                            <a class="dropdown-item" href="<?=base_url(_MODULE_NAME_.'/switchLang/'.$row['key']);?>"><?=$row['title'];?></a>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--=================================
            mega menu -->
        <div class="menu">
            <!-- menu start -->
            <nav id="menu" class="mega-menu">
                <!-- menu list items container -->
                <section class="menu-list-items">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- menu logo -->
                                <ul class="menu-logo">
                                    <li>
                                    <a href="<?=base_url();?>"><img id="logo_img" src="<?=img_url('logo-dark.png');?>" alt="logo"> </a>
                                    </li>
                                </ul>
                                <!-- menu links -->
                                <ul class="menu-links">
                                    <?php
                                    foreach($menus1 as $row):
                                        $x=[];
                                        if ($row['detail']){
                                            $x=$row['detail'];
                                        }elseif(array_key_exists('children', $row)){
                                            $x=$row['children'];
                                        }

                                        if (!$row['detail'] && !array_key_exists('children', $row)):?>
                                            <li><a href="<?=$row['url'];?>"> <?=$row['title'];?></a></li>
                                        <?php elseif (array_key_exists('children', $row)):?>
                                        <li><a href="javascript:void(0)"><?=$row['title'];?> <i class="fa fa-angle-down fa-indicator"></i></a>
                                            <ul class="drop-down-multilevel">
                                                <?php
                                                foreach($row['children'] as $key=>$dtls):?>
                                                <li><a href="<?=$dtls['url']?>"><?=$dtls['title']?></a>
                                                </li>
                                                <?php endforeach;?>
                                            </ul>
                                        </li>
                                        <?php elseif ($row['detail']):?>
                                        <li><a href="<?=base_url('category');?>"><?=$row['title'];?> <i class="fa fa-angle-down fa-indicator"></i></a>
                                            <ul class="drop-down-multilevel">
                                                <?php
                                                foreach($row['detail'] as $key=>$dtls):
                                                    $arrkey=explode('#', $key);
                                                ?>
                                                <li><a href="<?=base_url('category/'.$arrkey[1]);?>"><?=$arrkey[0];?> <i class="fa fa-angle-right fa-indicator"></i></a>
                                                    <ul class="drop-down-multilevel level2">
                                                        <?php
                                                        foreach($dtls as $dtl):?>
                                                            <li><a href="<?=$dtl['url']?>"><?=$dtl['title']?></a></li>
                                                        <?php endforeach;?>
                                                    </ul>
                                                </li>
                                                <?php endforeach;?>
                                            </ul>
                                        </li>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    <li class="side-menu-main">
                                        <div class="side-menu">
                                            <div class="mobile-nav-button">
                                                <div class="mobile-nav-button-line"></div>
                                                <div class="mobile-nav-button-line"></div>
                                                <div class="mobile-nav-button-line"></div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            </nav>
            <!-- menu end -->
        </div>
        <!-- menu end -->
    </header>
    <!--=================================
search and side menu content -->
    <div class="search-overlay"></div>
    <div class="menu-overlay"></div>
    <div id="search" class="search header fancy">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <input type="search" placeholder="Type and hit enter...">
                </div>
            </div>
        </div>
    </div>
    <div class="side-content" id="scrollbar">
        <div class="side-content-info"   style="margin-bottom:100px;">
            <div class="menu-toggle-hamburger menu-close"><span class="ti-close"> </span></div>
            <div class="side-logo">
                <img class="img-fluid mb-3" src="<?=img_url('logo-dark.png');?>" alt="">
                <hr class="mt-2 mb-3" />
            </div>
            <div class="contact-address">
                <div class="address-title mb-3">
                    <h4 class="mb-1">Office</h4>
                    <?=img($store['photo'], 'file', ['class'=>'pointer', 'data-file'=>$store['photo'], 'data-path'=>'file'], 'small');?>
                    <br/>&nbsp;
                    <p>mollitia omnis fuga, nihil suscipit lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti sit quos.</p>
                </div>
                <div class="contact-box mb-2">
                    <div class="contact-icon">
                        <i class="ti-direction-alt text-blue"></i>
                    </div>
                    <div class="contact-info">
                        <div class="text-left">
                            <h6><a href="service-detail.html"><?=$store['address'];?></a></h6>
                          <span class="mb-0"><?=$store['city'];?></span>
                        </div>
                    </div>
                </div>
                <div class="contact-box mb-2">
                    <div class="contact-icon">
                        <i class="ti-headphone-alt text-blue"></i>
                    </div>
                    <div class="contact-info">
                        <div class="text-left">
                            <h6><a href="service-detail.html">  <?=$store['phone'];?></a></h6>
                            <span class="mb-0"><?=$store['work_hour'];?></span>
                        </div>
                    </div>
                </div>
                <div class="contact-box mb-2">
                    <div class="contact-icon">
                        <i class="ti-email text-blue"></i>
                    </div>
                    <div class="contact-info">
                        <div class="text-left">
                            <h6><a href="service-detail.html"><?=$store['email'];?></a></h6>
                            <span class="mb-0">24 X 7 online support</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="side-content-image">
            <img class="img-fluid center-block" src="<?=img_url('bg-element/04.png');?>" alt="">
        </div>
    </div>
    <!--=================================
search and side menu content -->
    <!--=================================
header -->