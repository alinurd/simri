<style>
    .has-fixed-height-dashboard {
        height: 300px !important;
    }
</style>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <?= $graph1; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <?= $graph2; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <?= $graph3; ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?= $bar ?>
            </div>
        </div>
    </div>
</div>