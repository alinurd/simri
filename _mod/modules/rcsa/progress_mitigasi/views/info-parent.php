<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title"><?=_l('fld_title');?></h6>
                <div class="header-elements">
                    <span class="label"><?=(!empty($mode))?'<span class="badge bg-blue-400"> '.$mode_text.' </span>':'';?></span>
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td width="20%"><em><?=_l('fld_owner_id');?></em></td><td><strong><?=$parent['owner_name'];?></strong></td></tr>
                    <tr><td><em><?=_l('fld_sasaran_dept');?></em></td><td><strong><?=$parent['sasaran_dept'];?></strong></td></tr>
                    <tr><td><em><?=_l('fld_term_id');?></em></td><td><strong><?=$parent['period_name']. ' - '.$parent['term'];?></strong></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>