<section class="page-section-ptb testimonial-02">
    <div class="container">
        <div class="row justify-content-center testimonial-bg">
            <div class="col-md-8 text-center">
                <br/>&nbsp;
                <?php
                $message = str_replace('[[email]]', '<span class="text-primary">'.$email.'</span>', $message);
                echo $message;?>
                <br/>&nbsp;
                <div class="error-info">
                    <a class="button bg-primary" href="<?=base_url();?>">back to home</a><br/>&nbsp;
                </div>
            </div>
        </div>
    </div>
</section>