<div class="card" id="card_<?= $identity ?>">
    <div class="card-header border-bottom mb-2 pt-2 pb-2 bg-light">
        <div class="form-group row">
            <label for="<?= $identityFilter ?>" class="col-md-3 col-form-label">Filter
                Tahun</label>
            <div class="col-md-9">
                <select class="form-control select-filter text-center border" name="<?= $identityFilter ?>"
                    id="<?= $identityFilter ?>" identity="<?= $identity ?>" filter-year="<?= $labeltahun ?>">
                    <option value=""><i>-- Pilih Tahun --</i></option>
                    <?= ! empty( $tahun ) ? $tahun : "" ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?= $content ?>
    </div>
</div>