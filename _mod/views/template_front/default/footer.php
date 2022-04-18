<!--=================================
footer -->
<footer class="footer footer-topbar page-section-pt">
    <div class="container">
        <div class="row top">
            <div class="col-lg-3 col-md-2">
                <img class="img-fluid" src="<?=img_url('logo-dark.png');?>" alt="">
            </div>
            <div class="col-lg-5 col-md-6">
                <div class="footer-nav text-right">
                    <ul>
                        <li><a href="<?=base_url();?>">Home</a></li>
                        <?php
                        foreach($menus2 as $row):?>
                        <li><a href="<?=$row['url'];?>"> <?=$row['title'];?></a></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="social text-right">
                    <ul>
                        <li>
                            <a href="https://facebook.com/<?=$params['preference']['sos_fb'];?>" target="_blank"> <i class="fa fa-facebook"></i> </a>
                        </li>
                        <li class="d-none">
                            <a href="https://twitter.com/<?=$params['preference']['sos_twiter'];?>" target="_blank"> <i class="fa fa-twitter"></i> </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/user/<?=$params['preference']['sos_youtube'];?>" target="_blank"> <i class="fa fa-youtube"></i> </a>
                        </li>
                        <li>
                            <a href="https://instagram.com/<?=$params['preference']['sos_ig'];?>" target="_blank"> <i class="fa fa-instagram"></i> </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/company/<?=$params['preference']['sos_linkedin'];?>" target="_blank"> <i class="fa fa-linkedin"></i> </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr />
        
        <div class="row mt-8">
            <div class="col-md-4 bottom-m3">
                <div class="contact-box">
                    <div class="contact-icon">
                        <i class="ti-direction-alt"></i>
                    </div>
                    <div class="contact-info">
                        <h5><?=$params['preference']['alamat_kantor'];?></h5>
                        <span><?=$params['preference']['kota_kantor'];?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 bottom-m3">
                <div class="contact-box">
                    <div class="contact-icon">
                        <i class="ti-headphone-alt"></i>
                    </div>
                    <div class="contact-info">
                        <h5><?=$params['preference']['telp_kantor'];?></h5>
                        <span><?=$params['preference']['jam_kantor'];?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-box">
                    <div class="contact-icon">
                        <i class="ti-email"></i>
                    </div>
                    <div class="contact-info">
                        <h5><?=$params['preference']['email_kantor'];?></h5>
                        <span>24 X 7 online support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright mt-6">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-6">
                    <div class="text-right">
                        <p><?=$params['preference']['judul_bawah'];?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--=================================
footer -->
   
     


<!--=================================
back to top -->
 <div class="back-to-top">
     <span><img src="<?=img_url('rocket.png');?>" data-src="<?=img_url('rocket.png');?>" data-hover="<?=img_url('rocket.gif');?>" alt=""></span>
 </div>
<!--=================================
back to top -->