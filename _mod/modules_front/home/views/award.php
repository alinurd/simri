<!--=================================
awesome Service -->
<section class="our-blog page-section-ptb">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <h3 class="text-center">Penghargaan</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="owl-carousel" data-nav-dots="false" data-nav-arrow="true" data-items="3" data-xs-items="2" data-sm-items="2" data-md-items="3" data-lg-items="3" data-autoplay="false" data-loop="true">
                        <?php
                        foreach($dAward as $row):?>
                        <div class="item">
                            <div class="blog-entry">
                                <div class="entry-image clearfix">
                                    <img class="img-fluid" src="<?=file_url($row['cover_image']);?>" alt="" style="margin:0 auto">
                                </div>
                                <div class="blog-detail">
                                    <div class="entry-title mb-1">
                                        <a href="#"><?row['title'];?></a>
                                    </div>
                                    <div class="entry-content text-center">
                                        <p><?=$row['news_short'];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!--=================================
case-studies -->