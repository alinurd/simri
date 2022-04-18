<!--=================================
contact from -->
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
                <a href="#" class="acd-heading<?=$text2;?>"><span class="ti-bar-chart-alt"></span><?=$row['kelompok'];?></a>
                <div class="acd-des">
                  <?=$row['career'];?>
              </div>
            </div>
            <?php endforeach;?>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="contact-form-title mb-4">
              <h4 class="mb-1">GET IN TOUCH</h4>
              <p>Please complete the form below.. Items marked with an asterisk () are required fields</p>
          </div>
          <div id="formmessage" class="text-danger" style="text-align:center;display:<?php echo (!empty($this->session->flashdata('career')))?'show':'none';?>"><?=$this->session->flashdata('career');?></div>
          <?php echo form_open_multipart("career/save-message", ['class' => 'gray-form row', 'id' => 'form_login']);?>
            <div class="col-md-6">
              <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Your Name">
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
            <div class="col-md-6">
              <div class="form-group">
                <input type="file" class="form-control" name="cv" placeholder="CV">
              </div>
            </div>
              <div class="col-md-12">
                  <div class="form-group">
                      <textarea class="form-control" rows="7" placeholder="Massage" name="pesan"></textarea>
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