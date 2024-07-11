
<hr/>
<span class="btn bg-warning-400 btn-labeled btn-labeled-left legitRipple" id="backListPeritwa"><b><i class="icon-arrow-left5"></i></b> <?=_l('fld_back_like_indi');?></span>
<!-- save  ke js-->
<span class="btn bg-success-400 btn-labeled btn-labeled-right legitRipple pull-right" id="savePeristiwa"><b><i class="icon-floppy-disk "></i></b> <?=_l('fld_save_like_indi');?></span>
<br/>
<hr/>
<?php echo form_close(); ?>
<script>
    $(function () {
        $('.select').select2({
            allowClear: false,
            dropdownParent: $("#modal_general")
        });
        $('[data-popup="tooltip"]').tooltip();
        $('.pickadate').pickadate({
            selectMonths: true,
            selectYears: true,
            formatSubmit: 'yyyy/mm/dd'
        });
        $('#form_like_indi input').keydown(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
    })
    
</script>
</div>