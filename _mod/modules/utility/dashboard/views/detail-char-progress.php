<?php
if (!$mode):?>
<a class="btn btn-primary" href="<?=base_url(_MODULE_NAME_.'/cetak-task');?>" target="_blank"><i class="icon-file-excel"> Ms-Excel </i></a>
<br><br>
<?php endif;?>
<table class="table table-bordered table-striped table-hover datatable" id="datatable" border="1">
    <thead>
        <tr class="text-center">
            <th width="5%">No.</th>
            <th>Tahun</th>
            <th>Dir/Dept/Proyek</th>
            <th>Risiko Dept</th> 
            <th>Taksonomi </th> 
            <th>Tipe Risiko</th> 
        </tr>
    </thead>
     
</table>