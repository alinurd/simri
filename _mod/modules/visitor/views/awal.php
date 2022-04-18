
<!-- Search field -->
<div class="row h-100">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header bg-primary text-center">
                <div class="row ">
                    <div class="col-lg-2">
                        <img src="<?=img_url('logo_icon_light.png');?>" alt="" width="150"/>
                    </div>
                    <div class="col-lg-10">
                        <h2 class="card-title"><?=$this->preference['nama_kantor'];?></h2>
                        <h5 class="card-title"><?=$this->preference['alamat_kantor'];?></h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row h-100">
                    <div class="col">
                        <div class="bg-slate rounded py-2 px-3 mb-3" style="padding:0px !important;">
                            <a href="<?=base_url('visitor');?>" class="btn btn-success btn-float legitRipple" style="width:100%"><b><i class="icon-user icon-3x"></i> <h2><?=lang('button_visitor');?></h2> </b></a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-slate rounded py-2 px-3 mb-3" style="padding:0px !important;">
                            <button type="button" class="btn btn-info btn-float legitRipple" style="width:100%"><b><i class="icon-list3 icon-3x"></i> <h3><?=lang('button_barang');?></h3> </b></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /search field -->