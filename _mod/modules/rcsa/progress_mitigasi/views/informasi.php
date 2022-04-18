<legend class="text-uppercase font-size-lg text-primary font-weight-bold"><i class="icon-grid"></i> Asesmen Risiko</legend>
<?php
foreach($info_1 as $key=>$row):
    ?>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-<?=$this->_preference_['align_label'];?>"><?=$row['title'];?></label>
        <div class="col-lg-9">
            <div class ="form-group form-group-feedback form-group-feedback-right">
                <?=$row['isi'];?>
            </div>
        </div>
    </div>
    <?php
endforeach;?>

<legend class="text-uppercase font-size-lg text-primary font-weight-bold"><i class="icon-grid"></i> MITIGASI RISIKO</legend>
<?php
foreach($info_2 as $key=>$row):
    ?>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-<?=$this->_preference_['align_label'];?>"><?=$row['title'];?></label>
        <div class="col-lg-9">
            <div class ="form-group form-group-feedback form-group-feedback-right">
                <?=$row['isi'];?>
            </div>
        </div>
    </div>
    <?php
endforeach;?>