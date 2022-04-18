<!--=================================
testimonial -->
<section class="testimonial testimonial-02 page-section-ptb">
    <div class="container">
        <div class="row row-eq-height testimonial-bg">
            <div class="col-lg-4 col-md-4 blue-bg">
                <div class="testimonial-title">
                    <div class="mt-4">
                        <span class="text-white text-uppercase">they say we did great job!</span>
                        <h3 class="text-white mt-1">Our Customers feedback</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 white-bg">
                <div class="owl-carousel" data-nav-dots="false" data-nav-arrow="true" data-items="1" data-sm-items="1"
                    data-lg-items="1" data-md-items="1" data-autoplay="true" data-loop="true">
                    <?php
                    foreach($dTesti as $row):?>
                    <div class="item">
                        <div class="testimonial-block text-center">
                            <div class="testimonial-avtar mb-3">
                                <img class="img-fluid center-block" src="<?=file_url($row['cover_image']);?>" alt="">
                            </div>
                            <div class="testimonial-info">
                                <div class="testimonial-name mb-1">
                                    <h5 class="text-blue"><?=$row['title'];?></h5>
                                </div>
                                <p><?=$row['news_short'];?></p>
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
testimonial -->