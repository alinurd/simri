<!--=================================
BUSINESS -->
<section class="page-section-ptb">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3 class="text-center">Produk Baru / Tren Produk</h3>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                    <?php
                    foreach($dProductCategory as $key=>$row):
                        $active='';
                        if($key==0){$active=" active ";}?>
                    <li role="presentation"><a  class="round <?=$active;?>" href="#category_<?=$row['category_id'];?>" aria-controls="all" role="tab" data-toggle="tab"><?=$row['kelompok'];?></a></li>
                    <?php endforeach;?>
                </ul>
                <div class="tab-content">
                    <?php
                    foreach($dProductCategory as $key=>$rox):
                    $active='';
                    if($key==0){$active=" active ";}?>
                    <div role="tabpanel" class="tab-pane <?=$active;?>" id="category_<?=$rox['category_id'];?>">
                        <div class="owl-carousel" data-nav-dots="true" data-nav-arrow="false" data-items="1" data-sm-items="1" data-lg-items="1" data-md-items="1" data-autoplay="true" data-loop="<?=(count($dProduct[$rox['category_id']])<=1)?'false':'true';?>">
                            <?php
                            foreach($dProduct[$rox['category_id']] as $row):
                                $pic='';
                                $pic_tmp='';
                                $type_tmp=0;
                                foreach($row['photo'] as $key=>$val){
                                    if ($val['sticky']==1){
                                        $pic=$val['name'];
                                        if(array_key_exists('type', $val))
                                            $type_tmp=$val['type'];
                                        break;
                                    }

                                    if ($key==0){
                                        $pic_tmp=$val['name'];
                                        if(array_key_exists('type', $val))
                                            $type_tmp=$val['type'];
                                    }
                                }

                                $result='';
                                if (empty($pic)){
                                    $pic=$pic_tmp;
                                }
                                if ($type_tmp==0){
                                    if (!empty($pic)){
                                        $pic=file_url($pic);
                                    }else{
                                        $pic=img_url('blank.jpg');
                                    }
                                    $pic = '<img class="img-fluid center-block"  style="max-width: 100%;max-height: 100%;display: block;" src="'.$pic.'" alt="gambar">';
                                }else{
                                    $pic = '<iframe width="100%" height="369" src="'.$pic.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                }
                            ?>
                            <div class="item">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 content-feature"  style="max-height:350px;">
                                        <h3 class="mb-2"><a href="<?=base_url('product/'.$row['uri_title']);?>"><?=$row['product'];?></a></h3>
                                        <p><?=$row['note'];?><br/><a class="button btn-warning icon order-product pointer" href="<?=base_url('product/'.$row['uri_title'].'/order');?>" style="width:200px;margin-bottom:15px;" data-id="<?=$row['id'];?>"><i class="fa fa-cart-plus" aria-hidden="true"></i>Order</a></p>
                                    </div>
                                    <div class="col-lg-6 col-md-12 text-center" style="max-height:350px;overflow:hidden;">
                                        <?=$pic;?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=================================
BUSINESS -->

<script>
    (function($){
        $(window).on("load",function(){
            
            $(".content-feature").mCustomScrollbar({
                autoHideScrollbar:true,
                theme:"minimal-dark"
            });
            
        });

        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            window.dispatchEvent(new Event('resize'));
        });
    })(jQuery);
</script>
