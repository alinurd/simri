<table class="table table-hover table-bordered table-striped table-sm" id="tbl_minggu">
    <thead>
        <tr>
            <th width="5%">Sort</th>
            <th>Bulan</th>
            <!-- <th>Minggu</th> -->
            <th width="15%">Start Date</th>
            <th width="15%">End Date</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $no=0;
        foreach($data as $row):
        $edit_id = form_hidden('edit_id_2[]', $row['id']);
        ?>
        <tr>
            <td><i class="pointer text-danger icon-square-up" title=" Pindah posisi Keatas "></i><i class="pointer text-primary icon-square-down" title=" Pindah posisi Kebawah "></i> </td>
            <td class="text-right"><?=form_dropdown('bulan[]', $bln, $row['param_int'],'class="form-control select"').$edit_id;?></td>
            <?= form_hidden('minggu[]', $row['data']);?>
            <!-- <td class="text-right"><?php //form_dropdown('minggu[]', [1=>'Minggu 1',2=>'Minggu 2'], $row['data'],'class="form-control select"');?></td> -->
            <td class="text-right"><?=form_input('param_date_2[]', date('j F, Y', strtotime($row['param_date'])),'class="form-control pickadate"');?></td>
            <td class="text-right"><?=form_input('param_date_after_2[]', date('j F, Y', strtotime($row['param_date_after'])),'class="form-control pickadate" style="width:100%;"');?></td>
            <td class="text-center">
                <i class="icon-file-plus pointer text-primary add-product-satu" title=" tambah data  "></i> | 
                <span class="text-danger" nilai="0" style="cursor:pointer;" onclick="remove_install(this,<?=$row['id'];?>,'combo')">
                    <i class="fa fa-cut" title="menghapus data"></i>
                </span>
            </td>
    <?php endforeach;?>
    </tbody>
</table>
<hr><span class="btn btn-info float-right pointer" id="add_minggu"> Add Weekday </span><br/>

<?php
    $edit = form_hidden('edit_id_2[]', 0);
    $bln = form_dropdown('bulan[]', $bln, '',' class="form-control select" style="width:100%;"');
    $minggu = form_hidden('minggu[]', 1);
    // $minggu = form_dropdown('minggu[]', [1=>'Minggu 1',2=>'Minggu 2'] , '',' class="form-control select" style="width:100%"');
    $date1 = form_input('param_date_2[]', '',' class="form-control pickadate" style="width:100%;"');
    $date2 = form_input('param_date_after_2[]', '',' class="form-control pickadate" style="width:100%;"');
?>
<script type="text/javascript">
    var edit2='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$edit));?>';
    var bln='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$bln));?>';
    var minggu='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$minggu));?>';
    var date3='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$date1));?>';
    var date4='<?php echo addslashes(preg_replace("/(\r\n)|(\n)/mi","",$date2));?>';

    $('.pickadate').pickadate({
		selectMonths: true,
		selectYears: true,
		formatSubmit: 'yyyy/mm/dd'
	});
</script>