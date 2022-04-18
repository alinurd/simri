
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
                <div class="row justify-content-md-center">
                    <div class="col col-lg-5">
                        <div class="col-md-auto">
                            <table class="table" style="margin:0 auto;">
                                <tr class="bg-primary">
                                    <td class="btb btn-primary text-center" colspan="4"><h2><?=lang('registration_success');?></h2></td></tr>
                                    <?php
                                    $no=0;
                                    foreach($post['detail'] as $row):?>
                                    <tr><td width="4%"><?=++$no;?></td>
                                    <td width="20%"><img src=<?=file_url($row['barcode']);?> width="50"></td>
                                    <td><?=$row['nama'];?></td>
                                    <td width="8%" class="text-center"><i class="fa fa-print"></i></td></tr>
                                    <?php endforeach;?>
                                    <tr>
                                        <td colspan="4" class="">
                                            <a href="<?=base_url();?>" class="btn bg-warning-400 legitRipple " style="color:#ffffff;" ><< <?=lang('button_back');?></a>
                                            <a href="#" class="btn bg-teal-400 legitRipple pull-right" style="color:#ffffff;" ><?=lang('button_print_all');?></a>
                                        </td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /search field -->