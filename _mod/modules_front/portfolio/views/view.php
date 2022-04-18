<!--=================================
contact from -->
<section class="page-section-ptb pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs justify-content-center" role="tablist">
                            <li role="presentation" style="width:100%;"><a  class="active" href="#all" aria-controls="all" role="tab" data-toggle="tab">All Portofolio</a></li>
                            <?php
                            foreach($cProduct as $key=>$row):?>
                            <li role="presentation" style="width:100%;"><a  class="porto" href="#cat<?=$key;?>" aria-controls="cat<?=$key;?>" role="tab" data-toggle="tab" style="padding:12px 12px !important;"><?=$row['kelompok'];?><span class="pull-right badge badge-info"><?=$row['jml'];?></span></a></li>
                            <?php 
                            endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <?php
                if ($satu):?>
                <div id="detail_portfolio" class="<?=($satu)?'':'d-none';?>">
                    <div id="carousel-thumb" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
                        <!--Slides-->
                        <div class="carousel-inner" role="listbox">
                            <?php
                            foreach($detail['image'] as $key=>$row):
                                $active='';
                                if ($key==0){
                                    $active=' active ';
                                }
                                $caption=false;
                                if(!empty($row['title'])){
                                    $caption=true;
                                }
                            ?>
                            <!-- SLIDE  -->
                            <div class="carousel-item <?=$active;?>">
                                <a class="fancybox" href="<?=file_url($row['name']);?>" data-fancybox-group="gallery" title="<?=$row['title'];?>">';
                                <img class="d-block w-100" src="<?=file_url($row['name']);?>" alt="First slide"></a>
                                <?php
                                if ($caption):?>
                                <div class="carousel-caption d-md-block" style="background-color:#CCE5FF;color:#0153a5;">
                                    <h3><?=$row['title'];?></h3>
                                    <p style="margin-bottom:0px !important;"><?=$row['note'];?></p>
                                </div>
                                <?php endif;?>
                            </div>
                            <?php endforeach;?>
                        </div>
                        <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>

                        <ol class="carousel-indicators">
                            <?php
                            foreach($detail['image'] as $key=>$row):
                                $active='';
                                if ($key==0){
                                    $active=' active ';
                                }
                            ?>
                            <li data-target="#carousel-thumb" data-slide-to="<?=$key;?>" class=" <?=$active;?>"> <img class="d-block w-100" src="<?=file_url($row['name']);?>" class="img-fluid"></li>
                            <?php endforeach;?>
                        </ol>
                    </div>
                    <hr>
                    <?php
                    if(!empty($detail['news_short'])):?>
                    <div class="alert alert-primary"><?=(!empty($detail['news_short']))?$detail['news_short']:'';?></div>
                    <?php endif;?>
                </div>
                <?php endif;?>
                <div class="tab-content <?=($satu)?'d-none':'';?>" id="portfolio">
                    <div role="tabpanel" class="tab-pane active" id="all">
                        <div class="row">
                            <?php
                            foreach($info as $key=>$ros):
                                foreach($ros as $key=>$row):?>
                                <div class="col-md-4 hover02" style="overflow:hidden;">
                                    <?php
                                    $pic='';
                                    if ($row['image']){
                                        foreach($row['image'] as $val){
                                            if ($val['default']==1){
                                                $pic=$val['name'];
                                                break;
                                            }
                                        }
                                        if (empty($pic)){
                                            $pic=$row['image'][0]['name'];
                                        }
                                    }
                                    $image='';
                                    if (!empty($pic)){
                                        $image = img($pic, 'file', ['class'=>'detail-img pointer', 'data-file'=>$pic, 'data-path'=>'file', 'style'=>'width:100%;'], 'large');
                                    }
                                    ?>
                                    <figure class="figure"><a href="<?=base_url('portfolio/'.$row['uri_title']);?>"><?=$image;?></a>
                                    <figcaption class="figure-caption" style="font-size:14px;color:#000000 !important;"><a href="<?=base_url('portfolio/'.$row['uri_title']);?>"><?=$row['title'];?></a></figcaption></figure>
                                </div>
                                <?php 
                                endforeach;
                            endforeach;?>
                        </div>
                        <div class="pagination-nav text-center">
                            <ul class="pagination">
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                            </ul>
                        </div>
                    </div>
                    <?php
                    foreach($cProduct as $key=>$row):?>
                    <div role="tabpanel" class="tab-pane" id="cat<?=$key;?>">
                        <div class="row">
                        <?php
                            foreach($info[$key] as $key=>$row):?>
                                <div class="col-md-4" style="overflow:hidden !important;">
                                    <?php
                                    $pic='';
                                    if ($row['image']){
                                        foreach($row['image'] as $val){
                                            if ($val['default']==1){
                                                $pic=$val['name'];
                                                break;
                                            }
                                        }
                                        if (empty($pic)){
                                            $pic=$row['image'][0]['name'];
                                        }
                                    }
                                    $image='';
                                    if (!empty($pic)){
                                        $image = img($pic, 'file', ['class'=>'detail-img pointer', 'data-file'=>$pic, 'data-path'=>'file', 'style'=>'width:100%;'], 'large');
                                    }
                                    ?>
                                    <figure class="figure"><a href="<?=base_url('portfolio/'.$row['uri_title']);?>"><?=$image;?></a><figcaption class="figure-caption" style="font-size:14px;color:#000000 !important;"><a href="<?=base_url('portfolio/'.$row['uri_title']);?>"><?=$row['title'];?></a></figcaption></figure>
                                </div>
                                <?php 
                            endforeach;?>
                        </div>
                        <div class="pagination-nav text-center">
                            <ul class="pagination">
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                            </ul>
                        </div>
                    </div>
                    <?php 
                    endforeach;?>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=================================
contact from -->