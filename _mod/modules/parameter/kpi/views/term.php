<table class="table table-hover table-striped table-sm" id="tbl_term">
    <thead>
        <tr>
            <th width="5%">Sort</th>
            <th width="5%">No.</th>
            <th>Owner</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $no=0;
        foreach($data as $key => $row):
        // $edit_id = form_hidden('edit_id[]', $row['id']);
        ?>
        <tr>
            <td><i class="pointer text-danger icon-square-up" title=" Pindah posisi Keatas "></i><i class="pointer text-primary icon-square-down" title=" Pindah posisi Kebawah "></i> </td>
            <td><strong><?=++$no?></strong></td>
            <td class="text-right"><?=form_dropdown('term[]', $owner,$row,'class="form-control select2"');?></td>
            
            <td class="text-center">
                <i class="icon-file-plus pointer text-primary add-product-satu" title=" tambah data  "></i> | 
                <span class="text-danger" nilai="0" style="cursor:pointer;" onclick="remove_install(this,<?=$key;?>,'combo')">
                    <i class="fa fa-cut" title="menghapus data"></i>
                </span>
            </td>
    <?php endforeach;?>
    </tbody>
</table>
<hr><span class="btn btn-info float-right pointer" id="add_term"> Add Term </span><br/>

<?php
    $edit = form_hidden('edit_id[]', 0);
    $term = form_dropdown('term[]', $owner,'',' class="form-control select2" style="width:100%;"');
?>
<script type="text/javascript">
    var edit='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$edit));?>';
    var term='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$term));?>';

    $('.select2').select2({
            allowClear: false,
        });
</script>