 <!--=================================
Feature-->
<section class="feature-main page-section-ptb">
    <div class="container">
        <div class="row row-eq-height">
            <?php if (count($dService)>4):?>
            <div class="col-lg-12 col-md-12 service">
            <div class="owl-carousel" data-nav-dots="false" data-nav-arrow="true" data-items="4" data-xs-items="2" data-sm-items="2" data-md-items="4" data-lg-items="4" data-autoplay="false" data-loop="true">
            <?php
            foreach($dService as $row):
                ?>
                <div class="feature-box-03 clearfix" style="padding:15px 20px !important;overflow:hidden;">
                    <div class="info">
                    <a href="<?=base_url('category/'.$row['uri_title']);?>"><img class="img-fluid center-block" src="<?=file_url($row['param_other']);?>" alt="">
                        <h4 class=" mt-2 mb-2"><?=$row['data']?></h4></a>
                    </div>
                </div>
            <?php endforeach;?>
            <?php else:
                foreach($dService as $row):
                ?>
                <div class="col-md-3 col-sm-6 text-center service">
                    <div class="counter-block feature-box-03">
                        <a href="<?=base_url('category/'.$row['uri_title']);?>">
                            <img class="img-fluid center-block" src="<?=file_url($row['param_other']);?>" alt="">
                        </a>
                        <h6 class="text-black mt-3 mb-3" style="padding-top:20px !important;"><?=$row['data']?></h5>
                    </div>
                </div>
            <?php endforeach;?>

            <?php endif;?>
        </div>
    </div>
</section>
<!--=================================
Feature-->