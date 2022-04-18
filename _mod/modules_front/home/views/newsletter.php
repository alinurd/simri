<section class="progress-bar-main white-bg page-section-ptb">
        <div class="row row-eq-height">
            <div class="col-lg-7 col-md-12 mb-lg-0 mb-3 align-self-center" style="padding-right:0px;">
                <img class="img-fluid center-block" src="<?=file_url($this->preference['image_newsletter']);?>" alt="">
            </div>
            <div class="col-lg-5 col-md-12" style="background-color:#F1E713;padding:50px 30px;">
                <h1 class="mb-2 text-center">LIKE WHAT<br/>YOU SEE?</h1><br/>
                <p class="mb-2"><strong>Sign Up our FREE Email Newsletter So you don't miss out on any of Our great product!</srong></p><br/>
                <?php echo form_open_multipart("home/save-newsletter", ['class' => 'form-inline d-inline', 'id' => 'form_newsletter']);?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <input type="email" required="required" name="email" class="form-control" placeholder="Enter your email address..." style="width:100%;">
                            </div>
                        </div>
                        <br/>&nbsp;
                        <div class="col-md-12 col-sm-12">
                            <button class="button pointer" href="#">Subscribe now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</section>