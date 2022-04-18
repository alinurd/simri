<?php
if (!empty($menu['param_other'])):?>
    <section class="intro-title black-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-left">
                    <div class="intro-content">
                        <div class="intro-img">
                            <img class="img-fluid" src="<?=file_url('menus/'.$menu['param_other']);?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif;?>

<section class="faq page-section-ptb">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <span>Have a question? </span>
                    <h3 class="text-center">Frequently asked questions</h3>
                </div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs justify-content-center" role="tablist">
                    <?php
                    $first=true;
                    foreach($faq as $key=>$row):
                    $active='';
                    if ($first){
                        $active='active';
                        $first=false;
                    }
                    ?>
                    <li role="presentation"><a class="<?=$active;?>" href="#faq<?=$key;?>" aria-controls="faq<?=$key;?>" role="tab" data-toggle="tab"><?=$row['name'];?></a></li>
                    <?php endforeach;?>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                <?php
                     $first=true;
                    foreach($faq as $key=>$row):
                        $active='';
                        if ($first){
                            $active='active';
                            $first=false;
                        }
                    ?>
                    <div role="tabpanel" class="tab-pane <?=$active;?>" id="faq<?=$key;?>">
                        <div class="accordion">
                    <?php 
                        foreach($row['detail'] as $keys=>$fa):?>
                            <div class="acd-group acd-<?=$active;?>">
                                <a href="#" class="acd-heading">
                                    <i class="fa fa-question" aria-hidden="true"></i><?=$fa['faq'];?>
                                </a>
                                <div class="acd-des text-gray"><?=$fa['answer'];?></div>
                            </div>
                    <?php
                        endforeach;
                    endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    
    <!--=================================
faq -->