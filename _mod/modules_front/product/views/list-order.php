<br/>
<div id="formmessage" class="text-danger" style="text-align:center;display:<?php echo (!empty($this->session->flashdata('order')))?'show':'none';?>"><?=$this->session->flashdata('order');?></div>
<br/>
<div class="alert alert-primary"><?=(!empty($info['instruction']))?$info['instruction']:$info['inst_default'];?></div><hr>
<?php
if (count($info['img_cover'])>0):?>
<div class="row">
    <div class="col-md-12">
        <div class="owl-carousel" data-nav-dots="true" data-nav-arrow="false" data-items="5" data-md-items="4" data-sm-items="3" data-xs-items="2" data-xx-items="1" data-autoplay="false" data-loop="true">
            <?php
            foreach($info['img_cover'] as $row):
                $img='';
                if (!empty($row['name'])){
                    $img = '<figure class="figure"><img class="img-fluid center-block pointer cover img-cover" data-name="'.$row['name'].'" alt="#" src="'.file_url($row['name']).'" width="100"><figcaption class="figure-caption">'.$row['title'].'</figcaption>
                    </figure>';
                }
            ?>
            <div class="item" >
                <?=$img;?>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<hr><br/>
<?php 
endif;
echo form_open_multipart("product/save-order", ['class' => 'gray-form form-horizontal', 'id' => 'form_login']);?>
<div id="order01">
<a class="fancybox d-none" href="1_b.jpg" title="gambar" id="show_image_combo">show</a>
    <?php
    $disabled = "";
    if (!$pilih){
        $disabled = ' disabled="disabled" ';
    }
    $jml=count($order);
    $no=0;
    foreach($order as $key=>$row):
    $pil='';
    $key=str_replace('-','_',$key);
    $cbo=$key.'_id';
    if ($key!=='spanram_price' && $key!=='laminated_price'):
        $data=' data-target ="'.$key.'"';
        $datano=' data-no ="'.$no.'"';
        ++$no;
        if ($pilih){
            $pil=$pilih[$key];
        }
        $romx = str_replace('-','_',$key); 
        $romx =lang('fld_dtl_'.$romx);
        $title = lang('fld_dtl_'.$key)

        ?>
        <div class="form-group row">
            <label class="col-form-label col-lg-2"><?=$title;?></label>
            <div class="col-lg-10">
                <?=form_dropdown($key, $row, $pil,' class="form-control input-sm cbo-order" id="'.$key.'" '.$data.$datano);?>
            </div>
        </div>
    <?php 
    endif; 
    endforeach;?>
    <?php
    if($info['attactment_sts']):
        if($info['wetransfer']):?>
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Attachment</label>
                <div class="col-lg-10">
                    <a href="https://wetransfer.com/" target="_blank" class="button btn-primary icon pointer">We Transfer</a>
                </div>
            </div>
        <?php else:?>
            <div class="form-group row">
                <label class="col-form-label col-lg-2">Attachment</label>
                <div class="col-lg-10">
                    <sub class="text-danger">File yang diijinkan hanya (jpg, jpeg, png, zip, rar) max size : 5 MB</sub>
                    <span class="btn btn-primary pull-right" id="addAtt"> <i class="fa fa-plus"></i> </span><br/>
                    <table class="table table-borderless" id="listAtt">
                        <tbody>
                            <tr>
                                <td width="15%">
                                    <div class="upload-btn-wrapper"> <button class="btn" style="padding:0.5rem 0.5rem;font-size:11px;">Upload a file</button><input type="file" name="upload[]" class="pointer" onchange="showMyImage(this,'img_photo_0')"></div></td>
                                </td>
                                <td>
                                    <img id="img_photo_0" style="margin-top:10px;"  width="100" src=""/></td><td width="5%">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif;
    endif;?>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Price</label>
        <div class="col-lg-10">
            <span id="price" style="font-size:18px;font-weight:bold;"><?=($pilih)?'Rp. '.number_format($pilih['mall_price']):0;?></span><?=form_hidden(['order_id'=>($pilih)?$pilih['id']:0,'cover_name'=>'','product_id'=>$info['id'],'uri_title'=>$info['uri_title'],'price'=>($pilih)?$pilih['mall_price']:0]);?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Qty</label>
        <div class="col-lg-10">
            <?=form_input(['name'=>'jml','type'=>'number','value'=>1,  'min'=>"1", 'max'=>"50",'class'=>"form-control", 'id'=>"jml", 'style'=>'width:15%;text-align:center;']);?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Total</label>
        <div class="col-lg-10">
            <span id="price_total" style="font-size:18px;font-weight:bold;"><?=($pilih)?'Rp. '.number_format($pilih['mall_price']):0;?></span>
            <span id="price_total_tmp" class="d-none"><?=($pilih)?$pilih['mall_price']:0;?></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">&nbsp;</label>
        <div class="col-lg-10 text-right">
        <a class="button btn-primary pointer" id="prosesnext">Lanjut</a>
        </div>
    </div>
</div>
<div id="order02" class="d-none">
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Nama</label>
        <div class="col-lg-10"> 
            <?=form_input('name','','class="form-control"  required="required"');?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Email</label>
        <div class="col-lg-10">
            <?=form_input([
            'type'  => 'email',
            'name'  => 'email',
            'id'    => 'email', 
            'class' => 'form-control',
            'required'=>'required',
        ]);?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Telp/Wa</label>
        <div class="col-lg-10">
            <?=form_input('telp','','class="form-control" required="required"');?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Catatan</label>
        <div class="col-lg-10">
            <textarea class="form-control" rows="3" name="pesan" placeholder="Massage"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Pick up</label>
        <div class="col-lg-10">
        <?=form_dropdown('pickup', $pickup, '','class="form-control input-sm" id="pickup"');?>
        </div>
    </div>
    <div class="form-group row d-none" id="div_alamat">
        <label class="col-form-label col-lg-2">Alamat</label>
        <div class="col-lg-10">
            <textarea class="form-control" rows="3" name="alamat" placeholder="Alamat Pengiriman"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">&nbsp;</label>
        <div class="col-lg-10">
            <a class="button btn-warning pointer" id="prosesback">Kembali</a>
            <button type="submit" class="button btn-primary pointer pull-right" id="proses" <?=$disabled;?>>Proses</button>
        </div>
    </div>
</div>
<?php form_close();
$att=form_upload('upload[]');
?>

<script>
    var tblAtt='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$att));?>';
    var product='<?php echo $info['id'];?>';
    var datas = {
    'product_id':product,
    'pilih':'',
    'no':0,
    'size':0,
    'album_size':0,
    'print_size':0,
    'paper_type':0,
    'qty':0,
    'laminated':0,
    'laminated_price':0,
    'calendar_type':0,
    'backing':0,
    'cover_type':0,
    'spanram':0,
    'spanram_price':0,
    'specification':0,
    'material':0,
    'pattern':0,
    'package':0,
    'printing_type':0,
    'sheet':0};
</script>