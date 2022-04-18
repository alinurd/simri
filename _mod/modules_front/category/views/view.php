<!--=================================
contact from -->
<section class="page-section-ptb pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs justify-content-center" role="tablist">
                            <li role="presentation" style="width:100%;"><a  class="<?=(empty($uri_title))?'active':'';?>" href="#all" aria-controls="all" role="tab" data-toggle="tab">All Category</a></li>
                            <?php
                            foreach($cProduct as $key=>$row):
                                $active='';
                                if (strtolower($uri_title)==strtolower($row['uri_title'])){
                                    $active = ' active ';
                                }
                                $jml = count($info[$key]);
                            ?>
                            <li role="presentation" style="width:100%;"><a  class="porto <?=$active;?>" href="#cat<?=$key;?>" aria-controls="cat<?=$key;?>" role="tab" data-toggle="tab" style="padding:12px 12px !important;"><?=$row['data'];?>
                            <span class="pull-right badge badge-success"><?=$jml;?></span></a>
                            </li>
                            <?php
                            endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content" id="portfolio">
                    <div role="tabpanel" class="tab-pane <?=(empty($uri_title))?'active':'';?>" id="all">
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
                                    $image='<img src="'.img_url('blank.jpg').'" style="width:100%;">';
                                    if (!empty($pic)){
                                        $image = img($pic, 'file', ['class'=>'detail-img pointer', 'data-file'=>$pic, 'data-path'=>'file', 'style'=>'width:100%;', 'alt'=>'gambar product'], 'large');
                                    }
                                    ?>
                                    <figure class="figure"><a href="<?=base_url('product/'.$row['uri_title']);?>"><?=$image;?></a>
                                    <figcaption class="figure-caption" style="font-size:14px;color:#000000 !important;"><a href="<?=base_url('product/'.$row['uri_title']);?>"><?=$row['product'];?></a></figcaption></figure>
                                </div>
                                <?php
                                endforeach;
                            endforeach;?>
                        </div>
                    </div>
                    <?php
                    foreach($cProduct as $key=>$row):
                        $active='';
                        if (strtolower($uri_title)==strtolower($row['uri_title'])){
                            $active = ' active ';
                        }
                    ?>
                    <div role="tabpanel" class="tab-pane <?=$active;?>" id="cat<?=$key;?>">
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
                                    $image='<img src="'.img_url('blank-cat.jpg').'" style="width:100%;">';
                                    if (!empty($pic)){
                                        $image = img($pic, 'file', ['class'=>'detail-img pointer', 'data-file'=>$pic, 'data-path'=>'file', 'style'=>'width:100%;', 'alt'=>'gambar product'], '');
                                    }
                                    ?>
                                    <figure class="figure"><a href="<?=base_url('product/'.$row['uri_title']);?>"><?=$image;?></a><figcaption class="figure-caption" style="font-size:14px;color:#000000 !important;"><a href="<?=base_url('product/'.$row['uri_title']);?>"><?=$row['product'];?></a></figcaption></figure>
                                </div>
                                <?php
                            endforeach;?>
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