<!--=================================
contact from -->
<section class="page-section-ptb pb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs justify-content-center" role="tablist">
                            <?php
                            $first=true;
                            foreach($group as $key=>$row):
                                $active='';
                                if ($first){
                                    $active = ' active ';
                                    $first=false;
                                }
                            ?>
                            <li role="presentation" style="width:100%;"><a  class="porto <?=$active;?>" href="#cat<?=$key;?>" aria-controls="cat<?=$key;?>" role="tab" data-toggle="tab" style="padding:12px 12px !important;"><?=ucfirst($row);?></a>
                            </li>
                            <?php
                            endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content" id="portfolio">
                    <?php
                    $first=true;
                    foreach($group as $key=>$row):
                      $active='';
                      if ($first){
                          $active = ' active ';
                          $first=false;
                      }
                    ?>
                    <div role="tabpanel" class="tab-pane <?=$active;?>" id="cat<?=$key;?>">
                    <?php
                        foreach($info[$key] as $keys=>$row):?>
                          <div class="row">
                            <div class="col-md-5">
                              <a class="fancybox" href="<?=file_url($row['photo']);?>" data-fancybox-group="<?=$key;?>" title="<?=$row['store'];?>">';
                              <?=img($row['photo'], 'file', ['class'=>'pointer', 'data-file'=>$row['photo'], 'data-path'=>'file'], 'small');?>
                              </a>
                            </div>
                            <div class="col-md-7">
                              <h4 class="text-warning" style="padding-bottom:10px;"><strong><?=$row['store'];?></strong></h4>
                                <div class="d-flex">
                                  <div class="contact-box">
                                    <div class="contact-icon">
                                      <i class="ti-direction-alt text-blue"></i>
                                    </div>
                                  </div>
                                  <div class="">
                                    <h6><a href="service-detail.html"><?=$row['address'];?></a></h6>
                                    <span class="mb-0"><?=$row['city'];?></span>
                                  </div>
                                </div>
                                <div class="d-flex">
                                  <div class="contact-box">
                                      <div class="contact-icon">
                                          <i class="ti-headphone-alt text-blue"></i>
                                      </div>
                                  </div>
                                  <div class="">
                                      <h6><a href="service-detail.html">  <?=$row['phone'];?></a></h6>
                                      <span class="mb-0"><?=$row['work_hour'];?></span>
                                  </div>
                                </div>
                                <div class="d-flex">
                                  <div class="contact-box">
                                      <div class="contact-icon">
                                          <i class="ti-email text-blue"></i>
                                      </div>
                                  </div>
                                  <div class="">
                                  <h6><a href="service-detail.html"><?=$row['email'];?></a></h6>
                                  <span class="mb-0">24 X 7 online support <?=$row['lat'];?></span>
                                  </div>
                              </div>
                              <?php
                              if (!empty($row['lat'])):?>
                              <a href="https://www.google.com/maps/?q=<?=$row['lat'];?>,<?=$row['lng'];?>" target="_blank">
                              <button type="button" class="add-to-cart gbtn" title="Plaza Indonesia">
													        <span><i class="fa fa-map-marker" aria-hidden="true"></i> Map</span>
                              </button>
                              </a>
                              <?php endif;?>
                            </div>
                          </div>
                        <br/>&nbsp;
                            <?php
                        endforeach;?>
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