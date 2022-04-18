<section class="newsletter blue-bg page-section-pb" style="padding-bottom:30px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 text-center">
                <div class="newsletter-info">
                    <h4 class="text-white mb-3">Subscribe to our Newsletter</h4>
                    <p class="mb-4 text-white">Sign up for new Seosignt content, updates, surveys & offers. Fusce commodo tincidunt convallis.Nunc at purus vitae nisl sagittis gravida ut sit amet diam. </p>
                </div>
                <?php echo form_open_multipart("home/save-newsletter", ['class' => 'form-inline d-inline', 'id' => 'form_newsletter']);?>
                    <div class="row">
                        <div class="col-md-8 col-sm-8">
                            <div class="form-group">
                                <input type="email" required="required" name="email" class="form-control" id="inputPassword2" placeholder="Enter your email address...">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 text-center text-md-left">
                            <button class="button border-white" href="#">Subscribe now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>