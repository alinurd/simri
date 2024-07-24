<div class="card card-body">
    <div class="row text-center text-primary">
        <div class="col-12 text-right" > 
            <i class="icon icon-envelope pointer  bg-info-400 rounded-circle"  style="font-size:25px;padding:10px;" data-toggle="modal" data-target="#modal_form_home" ></i> 
            <span class="badge bg-danger-400 badge-pill badge-float border-2 border-white" style="right:-.1rem;"></span>
        </div>
    </div>
</div>

<div class="card card-body">
    <div class="row text-center text-primary">
        <?php
        foreach($rows as $row):?>
            <div class="col-3 pointer library" data-id="<?=$row->id;?>">
                <p><i class="<?=$row->icon;?> bg-teal-400 rounded-circle" style="font-size:75px;padding:10px;"></i></p>
            <h5><?=strtoupper($row->library);?></h5>
        </div>
        <?php endforeach ;?>
    </div>
</div>

<div id="modal_form_home" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-300">
                <h5 class="modal-title"><i class="icon-envelop4"></i> Main & Notification</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?php
                foreach ($notif as $row):?>
                <div class="form-group row">
                    <label class="col-form-label col-sm-1"><i class="icon icon-book bg-teal-400 rounded-circle" style="font-size:25px;padding:10px;"></i></label>
                    <div class="col-sm-11">
                        Upcoming Expired Procedure:<br/><span style="font-size:20px;"?><strong></strong></span><br/>
                        <?=$row->judul;?><br/>
                        Rev No.<br/>
                        Rev Date:
                    </div>
                </div>
                    <?php endforeach;?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>