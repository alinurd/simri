<section class="page-section-ptb  testimonial-02">
    <div class="container">
        <div class="row justify-content-center  testimonial">
            <div class="text-center">
                <br/>&nbsp;
                <?php
                $message = str_replace('[[email]]', '<span class="text-primary">'.$email.'</span>', $message);
                echo $message;?>
                <br/>&nbsp;
                <div class="error-info">
                    <a class="button btn-warning" href="<?=base_url('category');?>">back to all product</a><br/>&nbsp;
                </div>
            </div>
        </div>
    </div>
</section>