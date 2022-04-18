<!--=================================
page-section -->
<section class="blog-page page-section-ptb">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="sidebar-widgets-wrap m-0">
                        <div class="sidebar-widget">
                            <h5><strong>search</strong></h5>
                            <div class="widget-search">
                                <i class="fa fa-search"></i>
                                <input type="search" class="form-control placeholder" placeholder="Search....">
                            </div>
                        </div>
                        <div class="sidebar-widget">
                            <h5><strong>Recent Posts</strong></h5>
                            <?php
                            foreach ($recent as $row):?>
                            <div class="recent-post">
                                <div class="recent-post-info">
                                    <a href="<?=base_url('blog/'.$row['uri_title']);?>"><?=$row['title']?></a>
                                    <span><i class="ti-calendar"></i> <?=date('D, d-M-Y', strtotime($row['created_at']));?></span>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </div>
                        <div class="sidebar-widget">
                            <h5><strong>categories</strong></h5>
                            <div class="widget-link">
                                <ul>
                                  <?php
                                  foreach($category as $row):?>
                                    <li>
                                        <a href="<?=base_url('blog/category/'.$row['kelompok']);?>"> <i class="fa fa-angle-double-right"></i> <?=$row['kelompok'];?> </a>
                                    </li>
                                  <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                        <div class="sidebar-widget">
                            <h5><strong>archives</strong></h5>
                            <div class="widget-link">
                                <ul>
                                <?php
                                  foreach($archives as $row):?>
                                    <li>
                                        <a href="<?=base_url('blog/archives/'.$row['bulan']);?>"> <i class="fa fa-angle-double-right"></i> <?=$row['bulan'];?> <span class="float-right"><?=$row['jml'];?></span></a>
                                    </li>
                                  <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php 
                    if (empty($content)):
                        foreach($news as $row):?>
                        <div class="blog-entry mb-5">
                            <?php
                            if (!empty($row['cover_image'])):?>
                            <div class="entry-image clearfix" style="text-align:center;">
                                <img class="img-fluid" src="<?=file_url($row['cover_image']);?>" alt="#">
                            </div>
                            <?php endif;?>
                            <div class="blog-detail">
                                <div class="entry-title mb-1">
                                    <a href="<?=base_url('blog/'.$row['uri_title']);?>"><?=$row['title'];?></a>
                                </div>
                                <div class="entry-meta mb-1">
                                    <ul>
                                        <li><a href="#"><i class="ti-folder"></i> <?=$row['created_by'];?></a></li>
                                        <li><a href="#"><i class="ti-calendar"></i> <?=date('d M Y',strtotime($row['created_at']));?>7</a></li>
                                    </ul>
                                </div>
                                <div class="entry-content">
                                    <p><?=split_words($row['news'],400);?></p>
                                </div>
                                <div class="entry-share clearfix">
                                    <div class="entry-button">
                                        <a class="button arrow" href="<?=base_url('blog/'.$row['uri_title']);?>">Read More<i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    else:?>
                        <div class="blog-entry mb-3">
                            <?php
                            if (!empty($content['cover_image'])):?>
                            <div class="entry-image clearfix" style="text-align:center;">
                                <img class="img-fluid" src="<?=file_url($content['cover_image']);?>" alt="#">
                            </div>
                            <?php endif;?>
                            <div class="blog-detail mt-3">
                                <div class="entry-title mb-1">
                                    <a href="#"><?=$content['title'];?></a>
                                </div>
                                <div class="entry-meta mb-1">
                                    <ul>
                                        <li><a href="#"><i class="ti-folder"></i> <?=$content['created_by'];?></a></li>
                                        <li><a href="#"><i class="ti-comments"></i> 5</a></li>
                                        <li><a href="#"><i class="ti-calendar"></i> <?=date('D, M Y', strtotime($content['created_at']));?></a></li>
                                    </ul>
                                </div>
                                <div class="entry-content">
                                    <p><?=$content['news'];?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </section>
    <!--=================================
page-section -->