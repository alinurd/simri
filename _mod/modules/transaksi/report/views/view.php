
<!-- Search field -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Filter</h6>
            </div>
            <div class="card-body">
                <?php echo form_open($this->uri->uri_string,array('id'=>'form_report', 'class'=>'form-horizontal','role'=>'form"'));?>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Periode</label>
                            <div class="col-lg-10">
                                <?=$cboWaktu;?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Kategori</label>
                            <div class="col-lg-10">
                                <?=$cboCatProduct;?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Status</label>
                            <div class="col-lg-10">
                                <?=$cboStatus;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="form-group row" id="divTanggal">
                            <label class="col-form-label col-lg-2">Tanggal</label>
                            <div class="col-lg-4">
                                <?=$cboTanggal1;?>
                            </div>
                            <label class="col-form-label col-lg-2">Sampai</label>
                            <div class="col-lg-4">
                                <?=$cboTanggal2;?>
                            </div>
                        </div>
                        <div class="form-group row d-none" id="divBulan">
                            <label class="col-form-label col-lg-2">Tahun</label>
                            <div class="col-lg-4">
                                <?=$cboTahun;?>
                            </div>
                            <label class="col-form-label col-lg-2">Bulan</label>
                            <div class="col-lg-4">
                                <?=$cboBulan;?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Product</label>
                            <div class="col-lg-10">
                                <?=$cboProduct;?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-12 btn btn-primary" id="proses">Proses</label>
                        </div>
                    </div>
                </div>
                <?=form_close();?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Result</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="result_lap">

            </div>
        </div>
    </div>
</div>