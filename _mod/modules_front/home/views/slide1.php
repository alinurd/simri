<div id="rev_slider_15_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-alias="seo-2" data-source="gallery" style="margin:0px auto;background-color:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
    <!-- START REVOLUTION SLIDER 5.3.0.2.1 fullwidth mode -->
    <div id="rev_slider_15_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.3.0.2.1">
        <ul>
            <?php
            foreach($dSlide as $row):
           
            ?>
            <!-- SLIDE  -->
            <li data-index="rs-49" data-transition="random-static,random-premium,random" data-slotamount="default,default,default,default" data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on" data-easein="default,default,default,default" data-easeout="default,default,default,default" data-masterspeed="default,default,default,default" data-thumb="<?=img_url('revolution/100x50_1cf5f-21.jpg');?>" data-rotate="0,0,0,0" data-saveperformance="off" data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                <!-- MAIN IMAGE -->
                <img src="<?=file_url($row['cover_image']);?>" alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
                <!-- LAYERS -->
            </li>
            <?php endforeach;?>
        </ul>
        <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
    </div>
</div>

<!-- END REVOLUTION SLIDER -->
<!--=================================
banner -->