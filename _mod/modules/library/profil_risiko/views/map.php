
<ul class="nav nav-tabs nav-tabs-top">
    <li class="nav-item">
        <a href="#content-tab-00" class="nav-link active show" data-toggle="tab">Inheren <?=$jml_inherent;?> </a>
    </li>
    <li class="nav-item">
        <a href="#content-tab-01" class="nav-link " data-toggle="tab">Residual <?=$jml_residual;?> </a>
    </li>
    <li class="nav-item">
        <a href="#content-tab-02" class="nav-link " data-toggle="tab">Target <?=$jml_target;?> </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade active show" id="content-tab-00"><?=$map_inherent;?><?=$jml_inherent_status;?></div>
    <div class="tab-pane fade" id="content-tab-01"><?=$map_residual;?><?=$jml_residual_status;?></div>
    <div class="tab-pane fade" id="content-tab-02"><?=$map_target;?><?=$jml_target_status;?></div>
</div>