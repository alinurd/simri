<table class="table table-hover table-striped table-sm" id="tbl_term">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th width="15%">Kode</th>
            <th>Kriteria Likelihood</th>
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
            <td><strong><?=++$no.$edit_id;?></strong></td>
            <td class="text-right"><?=form_dropdown('kode[]', [1=>1, 2=>2, 3=>3, 4=>4, 5=>5], $row['urut'],'class="form-control select"');?></td>
            
            <td class="text-right"><?=form_input('kriteria[]', $row['data'],'class="form-control"');?></td>
            <td class="text-center">
                <span class="text-danger" nilai="0" style="cursor:pointer;" onclick="remove_install(this,<?=$row['id'];?>,'combo')">
                    <i class="fa fa-cut" title="menghapus data"></i>
                </span>
            </td>
    <?php endforeach;?>
    </tbody>
</table>
<hr><span class="btn btn-info float-right pointer" id="add_kriteria"> Tambah Kreiteria </span><br/>

<?php
    $edit = form_hidden('edit_id[]', 0);
    $kriteria = form_input('kriteria[]', '',' class="form-control" style="width:100%;"');
    $kode = form_dropdown('kode[]', [1=>1,2=>2, 3=>3, 4=>4, 5=>5], 1,'class="form-control select"');
?>
<script type="text/javascript">
    var edit='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$edit));?>';
    var kriteria='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$kriteria));?>';
    var kode='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$kode));?>';

    $('.pickadate').pickadate({
		selectMonths: true,
		selectYears: true,
		formatSubmit: 'yyyy/mm/dd'
	});
</script>