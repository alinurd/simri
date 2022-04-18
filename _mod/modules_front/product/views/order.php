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
                    $type=0;
                    if (array_key_exists('type', $row)){
                        $type=$row['type'];
                    }
                    if ($type==0){
                        $name_video = form_hidden(['text_video_tmp[]'=>$row['name']]);
                        if (!empty($row['name']) && intval($row['sticky'])==0){
                            $img ='<a class="fancybox" href="'.file_url($row['name']).'" data-fancybox-group="gallery" title="Lorem ipsum dolor sit amet">';
                            $img .= '<img class="img-fluid" alt="#" src="'.file_url($row['name']).'" width="100"></a>';
                        }
                    }else{
                        if (intval($row['sticky'])==0){
                            $img = '<iframe width="100%" height="369" src="'.$row['name'].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                        }
                    }
                ?>    
                    <div class="item" >
                        <?=$img;?>
                    </div>
                <?php endforeach;?>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="mb-2"><?=$info['product'];?></h3>
                <p class="mb-2"><?=$info['note'];?></p>
                <br/>
                <a class="button btn-primary icon pointer"  href="<?=base_url('product/'.$info['uri_title']);?>"  style="width:50%;margin-bottom:15px;" data-id="<?=$info['id'];?>" data-kel="1"><i class="fa fa-long-arrow-right" aria-hidden="true"></i>Price</a>
                <?php 
                if ($info['disc_sts']):
                    if (!empty($info['image_disc'])):?>
                        <img src="<?=file_url($info['image_disc']);?>" class="discount" width="100">
                    <?php else:?>
                        <img src="<?=img_admin_url($this->preference['image_disc']);?>" class="discount" width="100">
                <?php endif;
                endif;?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12" id="list_price">
                <?=$order;?>
            </div>
        </div>
    </div>
</section>

<!--=================================
accordion-main -->