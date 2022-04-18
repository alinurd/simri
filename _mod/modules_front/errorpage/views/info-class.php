<!--=================================
page-section -->
<section class="page-section-ptb">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1 class="error-title">404</h1>
                    <h3 class="mb-2">Ooops, This Page Could Not Be Found!</h3>
                    <div class="error-info">
                        <p class="mb-5">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                        <a class="button" href="<?=base_url();?>">back to home</a>
                        <a class="button black" href="#" onclick="goBack()">back to Prev page</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=================================
page-section -->

<script>
function goBack() {
  window.history.back();
}
</script>