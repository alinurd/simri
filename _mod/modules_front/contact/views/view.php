<!--=================================
contact from -->
<iframe width="100%" height="500" frameborder="0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.5076635966807!2d106.82615951431322!3d-6.196550595514088!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f421229aaa1b%3A0xd2566d5ae531f7b1!2sAdorama!5e0!3m2!1sid!2sid!4v1508839257334" marginwidth="0" marginheight="0" scrolling="no"></iframe>
<section class="page-section-ptb pb-5">
  <div class="container">
    <div class="row">
        <div class="col-lg-4">
          <div class="accordion mb-3">
            <?php
            $first=true;
            foreach($info as $row):
              $text1='';
              $text2=' text-black';
              if ($first){
                $text1=' text-black';
                $text2='';
                $first=false;
              }
            ?>
            <div class="acd-group acd-active<?=$text1;?>">
                <a href="#" class="acd-heading<?=$text2;?>"><span class="ti-bar-chart-alt"></span><?=$row['store'];?></a>
                <div class="acd-des">
                  <div class="contact-address mb-3 white-bg">
                    <div class="address-title mb-3">
                      <?=img($row['photo'], 'file', ['class'=>'pointer', 'data-file'=>$row['photo'], 'data-path'=>'file'], 'small');?>
                      <br/>&nbsp;
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
                        <span class="mb-0">24 X 7 online support</span>
                        </div>
                    </div>
                    </div>
                  </div>
                </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="contact-form-title mb-4">
              <h4 class="mb-1">GET IN TOUCH</h4>
              <p>Ask us a question and we'll write back to you promptly!, Simply fill out the form below and click Send Email.<br/>
              Thanks. Items marked with an asterisk () are required fields</p>
          </div>
          <div id="formmessage" class="text-danger" style="text-align:center;display:<?php echo (!empty($this->session->flashdata('contact')))?'show':'none';?>"><?=$this->session->flashdata('contact');?></div>
          <?php echo form_open_multipart("contact/save-message", ['class' => 'gray-form row', 'id' => 'form_login']);?>
              <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control" name="name" placeholder="Your Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <input type="text" class="form-control" name="website" placeholder="Website URL">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <input type="text" class="form-control" name="email" placeholder="Email Adress">
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <input type="text" class="form-control" name="phone" placeholder="Phone Number">
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group">
                      <textarea class="form-control" rows="7" name="pesan" placeholder="Massage"></textarea>
                  </div>
              </div>
              <div class="col-md-12">
                  <input type="hidden" name="action" value="sendEmail"/>
                  <button id="submit" name="submit" type="submit" value="Send" class="button pointer"><span>Submit Now</span></button>
              </div>
              <?php form_close();?>
          <div id="ajaxloader" style="display:none"><img class="center-block" src="images/form-loader.gif" alt=""></div> 
        </div>
      </div>
    </div>
  </div>
</section>
    <!--=================================
contact from -->