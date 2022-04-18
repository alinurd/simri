<!-- Container -->
<div class="flex-fill">

<!-- Error title -->
<div class="text-center mb-3">
    <h1 class="error-title">405</h1>
    <h5>Oops, an error has occurred. Not allowed!</h5>
</div>
<!-- /error title -->


<!-- Error content -->
<div class="row">
    <div class="col-xl-4 offset-xl-4 col-md-8 offset-md-2">

        <!-- Search -->
        <form action="#">
            <div class="input-group mb-3">
                <input type="text" class="form-control form-control-lg" placeholder="Search">

                <div class="input-group-append">
                    <button type="submit" class="btn bg-slate-600 btn-icon btn-lg"><i class="icon-search4"></i></button>
                </div>
            </div>
        </form>
        <!-- /search -->


        <!-- Buttons -->
        <div class="row">
            <div class="col-sm-6">
                <a href="<?=base_url();?>" class="btn btn-primary btn-block"><i class="icon-home4 mr-2"></i> Dashboard</a>
            </div>

            <div class="col-sm-6">
                <a href="<?=base_url('support/add');?>" class="btn btn-light btn-block mt-3 mt-sm-0"><i class="icon-menu7 mr-2"></i> Support</a>
            </div>
        </div>
        <!-- /buttons -->

    </div>
</div>
<!-- /error wrapper -->

</div>
<!-- /container -->