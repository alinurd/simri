<div class="rev_slider_wrapper fullwidthbanner-container my-4">
    <div id="carousel-thumb" class="carousel slide carousel-fade" data-ride="carousel">
        <!--Slides-->
        <div class="carousel-inner" role="listbox">
            <?php
            foreach($dSlide as $key=>$row):
                $active='';
                if ($key==0){
                    $active=' active ';
                }
                $url=base_url();
                $parent='pages/';
                if ($row['url_type']==1){
                    $parent='news/';
                }elseif($row['url_type']==2){
                    $parent='blog/';
                }
                $pages=$this->db->where('id', intval($row['url']))->get(_TBL_NEWS)->row();
                if ($pages)
                    $url.=$parent.$pages->uri_title;
            ?>
            <!-- SLIDE  -->
            <div class="carousel-item <?=$active;?>">
                <a href ="<?=$url;?>"><img class="d-block w-100" src="<?=file_url($row['cover_image']);?>" alt="First slide"></a>
            </div>
            <?php endforeach;?>
        </div>
        <!--/.Slides-->
        <!--Controls-->
        <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        <!--/.Controls-->
        <ol class="carousel-indicators">
            <li data-target="#carousel-thumb" data-slide-to="0" class="active"> 1</li>
            <li data-target="#carousel-thumb" data-slide-to="1">2</li>
            <li data-target="#carousel-thumb" data-slide-to="2">3</li>
        </ol>
    </div>
    <!--/.Carousel Wrapper-->

</div>