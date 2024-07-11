<table class="table table-hover table-striped table-sm" id="risk_type">
    <thead>
        <tr>
            <th width="5%">Sort</th>
            <th width="5%">No.</th>
            <th>Risk Type</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $no=0;
        foreach($data as $row):
        $edit_id = form_hidden('edit_id[]', $row['id']);
        ?>
        <tr>
            <td><i class="pointer text-danger icon-square-up" title=" Pindah posisi Keatas "></i><i class="pointer text-primary icon-square-down" title=" Pindah posisi Kebawah "></i> </td>
            <td><strong><?=++$no.$edit_id;?></strong></td>
            <td class="text-right"><?=form_input('risk_type[]',$row['data'],'class="form-control"');?></td>
            <td class="text-center">
                <i class="icon-file-plus pointer text-primary add-product-satu" title=" tambah data  "></i> | 
                <span class="text-danger" nilai="0" style="cursor:pointer;" onclick="remove_install(this,<?=$row['id'];?>,'combo')">
                    <i class="fa fa-cut" title="menghapus data"></i>
                </span>
            </td>
    <?php endforeach;?>
    </tbody>
</table>
<hr><span class="btn btn-info float-right pointer" id="add_risk"> Add Risk Type </span><br/>

<?php
    $edit = form_hidden('edit_id[]', 0);
    $risk_type = form_input('risk_type[]', '',' class="form-control" style="width:100%;"');
?>
<script type="text/javascript">
    var edit='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$edit));?>';
    var risk_type='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$risk_type));?>';
</script>