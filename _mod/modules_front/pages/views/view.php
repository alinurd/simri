<section class="page-section-ptb">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                if (!empty($info['cover_image'])):?>
                <img class="img-fluid" src="<?=file_url($info['cover_image']);?>" alt="">
                <?php endif;?>
                <h3 class="mb-2"><?=$info['title'];?> </h3>
                <p><?=$info['news'];?></p>
            </div>
        </div>
    </div>
</section>