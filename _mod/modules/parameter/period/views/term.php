<table class="table table-hover table-striped table-sm" id="tbl_term">
    <thead>
        <tr>
            <th width="5%">Sort</th>
            <th width="5%">No.</th>
            <th>Term</th>
            <th width="15%">Start Date</th>
            <th width="15%">End Date</th>
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
            <td class="text-right"><?=form_input('term[]',$row['data'],'class="form-control"');?></td>
            <td class="text-right"><?=form_input('param_date[]', date('j F, Y', strtotime($row['param_date'])),'class="form-control pickadate"');?></td>
            <td class="text-right"><?=form_input('param_date_after[]', date('j F, Y', strtotime($row['param_date_after'])),'class="form-control pickadate"');?></td>
            <td class="text-center">
                <i class="icon-file-plus pointer text-primary add-product-satu" title=" tambah data  "></i> | 
                <span class="text-danger" nilai="0" style="cursor:pointer;" onclick="remove_install(this,<?=$row['id'];?>,'combo')">
                    <i class="fa fa-cut" title="menghapus data"></i>
                </span>
            </td>
    <?php endforeach;?>
    </tbody>
</table>
<hr><span class="btn btn-info float-right pointer" id="add_term"> Add Term </span><br/>

<?php
    $edit = form_hidden('edit_id[]', 0);
    $term = form_input('term[]', '',' class="form-control" style="width:100%;"');
    $date1 = form_input('param_date[]', '',' class="form-control pickadate" style="width:100%;"');
    $date2 = form_input('param_date_after[]', '',' class="form-control pickadate" style="width:100%;"');
?>
<script type="text/javascript">
    var edit='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$edit));?>';
    var term='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$term));?>';
    var date1='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$date1));?>';
    var date2='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$date2));?>';

    $('.pickadate').pickadate({
		selectMonths: true,
		selectYears: true,
		formatSubmit: 'yyyy/mm/dd'
	});
</script>