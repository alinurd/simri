<?php if (!empty($menu['param_other'])):?>
<section class="intro-title bg bg-overlay-black-70" style="background:url(<?=file_url($menu['param_other']);?>) fixed;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-left">
                <div class="intro-content">
                    <div class="intro-name">
                        <h3 class="text-white"><?=$breadcrumb['title'];?></h3>
                        <ul class="breadcrumb mt-1">
                            <?php
                            foreach($breadcrumb['detail'] as $key=>$row):
                            $link = $row;
                            $active='';
                            if ($key==0)
                                $link='<a href="'.base_url().'">'.$row.'</a>';

                            if ($key==(count($breadcrumb['detail'])-1))
                                $active=' active ';
                            ?>
                            <li class="breadcrumb-item text-white <?=$active;?>"><?=$link;?></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif;?>
<!--=================================
accordion-main -->

<section class="accordion-main page-section-ptb sec-relative">
        <div class="side-content-image">
            <img class="img-fluid" src="images/bg-element/04.png" alt="">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 text-center">
                    <div class="owl-carousel mt-3" data-nav-dots="true" data-nav-arrow="false" data-items="1" data-sm-items="1" data-lg-items="1" data-md-items="1" data-autoplay="false" data-loop="true">
                    <?php
                    foreach($info['photo'] as $row):
                        if ($row['type']==0){
                            // $name_video = form_hidden(['text_video_tmp[]'=>$row['name']]);
                            if (!empty($row['name'])){
                                $img = '<img class="img-fluid" alt="#" src="'.file_url($row['name']).'" width="100">';
                            }
                        }else{
                            $img = '<iframe width="100%" height="369" src="'.$row['name'].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                        }
                    ?>    
                        <div class="item" >
                            <?=$img;?>
                        </div>
                    <?php endforeach;?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3 class="mb-2"><?=$info['title'];?></h3>
                    <p class="mb-2"><?=$info['news'];?></p>
                </div>
            </div>
        </div>
    </section>

<!--=================================
accordion-main -->