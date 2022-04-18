<!-- Full width modal -->
<div id="modal_full" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header bg-teal-300">
                <h5 class="modal-title">&nbsp;</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_notif" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notif</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal_general" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header bg-teal-300">
                <h5 class="modal-title"><i class="icon-search4"></i> Title</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <?php echo form_open_multipart($this->uri->uri_string,array('id'=>'form_general', 'class'=>'form-horizontal','role'=>'form"'));?>
            <div class="modal-body">
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_general_title" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header bg-primary-300">
                <div class="row">
                    <div class="col-lg-3">
                        <img src="<?=img_url('logo_icon_light.png');?>" alt="" width="100"/>
                    </div>
                    <div class="col-lg-9">
                        <span class="card-title"><strong><?=$params['preference']['nama_kantor'];?></strong></span><br/>
                        <span class="card-title"><?=$params['preference']['alamat_kantor'];?></span>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- /full width modal -->