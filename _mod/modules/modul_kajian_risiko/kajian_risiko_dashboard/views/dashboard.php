<style>
    .has-fixed-height-dashboard {
        height: 300px !important;
    }
</style>
<div class="row">
    <div class="col-md-6" id="tiket_terbit">
        <div class="card" id="card_tiket_terbit">
            <div class="card-header border-bottom mb-2 pt-2 pb-2 bg-light">
                <div class="form-group row">
                    <label for="filter-tiket-terbit" class="col-md-3 col-form-label">Filter
                        Tahun</label>
                    <div class="col-md-9">
                        <select class="form-control select-filter text-center border" name="filter_tiket_terbit"
                            id="filter-tiket-terbit" identity="tiket_terbit">
                            <option value=""><i>-- Pilih Tahun --</i></option>
                            <?= ! empty( $tahun ) ? $tahun : "" ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $bar ?>
            </div>
        </div>
    </div>
    <div class="col-md-6" id="tanggal_release">
        <div class="card" id="card_tanggal_release">
            <div class="card-header border-bottom mb-2 pt-2 pb-2 bg-light">
                <div class="form-group row">
                    <label for="filter-tanggal-release" class="col-md-3 col-form-label">Filter
                        Tahun</label>
                    <div class="col-md-9">
                        <select class="form-control select-filter text-center border" name="filter-tanggal-release"
                            id="filter-tanggal-release" identity="tanggal_release">
                            <option value=""><i>-- Pilih Tahun --</i></option>
                            <?= ! empty( $tahun ) ? $tahun : "" ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $bar2 ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4" id="status_kajian">
        <div class="card" id="card_status_kajian">
            <div class="card-header border-bottom mb-2 pt-2 pb-2 bg-light">
                <div class="form-group row">
                    <label for="filter-status-kajian" class="col-md-4 col-form-label">Filter
                        Tahun</label>
                    <div class="col-md-8">
                        <select class="form-control select-filter text-center border" name="filter-status-kajian"
                            id="filter-status-kajian" identity="status_kajian">
                            <option value=""><i>-- Pilih Tahun --</i></option>
                            <?= ! empty( $tahun ) ? $tahun : "" ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $graph1; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4" id="status_approval">
        <div class="card" id="card_status_approval">
            <div class="card-header border-bottom mb-2 pt-2 pb-2 bg-light">
                <div class="form-group row">
                    <label for="filter-status-approval" class="col-md-4 col-form-label">Filter
                        Tahun</label>
                    <div class="col-md-8">
                        <select class="form-control select-filter text-center border" name="filter-status-approval"
                            id="filter-status-approval" identity="status_approval">
                            <option value=""><i>-- Pilih Tahun --</i></option>
                            <?= ! empty( $tahun ) ? $tahun : "" ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $graph2; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4" id="status_progress">
        <div class="card" id="card_status_progress">
            <div class="card-header border-bottom mb-2 pt-2 pb-2 bg-light">
                <div class="form-group row">
                    <label for="filter-progress-mitigasi" class="col-md-4 col-form-label">Filter
                        Tahun</label>
                    <div class="col-md-8">
                        <select class="form-control select-filter text-center border" name="filter-progress-mitigasi"
                            id="filter-progress-mitigasi" identity="status_progress">
                            <option value=""><i>-- Pilih Tahun --</i></option>
                            <?= ! empty( $tahun ) ? $tahun : "" ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $graph3; ?>
            </div>
        </div>
    </div>
</div>