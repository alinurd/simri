<?php echo form_open_multipart(base_url(_MODULE_NAME_).'/import',array('menthod'=>'POST','id'=>'import_form','class'=>'form-horizontal'));?>

<div class="form-group row">
    <label class="col-form-label col-lg-2 text-right">Pilih File Excel:</label>
                    
    <div class="col-lg-10">
        <?php echo form_upload('import');;?>
    </div>

    <div class="col-lg-2"></div>
    <div class="col-lg-10">
        <button type="submit" class="btn bg-info-300   btn-labeled btn-labeled-left button-action"  style="margin-right:5px;">
        <b><i class="icon-floppy-disk"></i></b>Import</button>
    </div>
</div>

<?php echo form_close();?>