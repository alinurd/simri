<style>
    .high-similarity {
    background-color: #FF0000;
    color: #fff;
}
.medium-similarity {
    background-color: #fff3cd; /* Kuning muda */
    color: #000000;
}

.low-similarity {
    background-color: #f8d7da; /* Merah muda */
    color: #000000;
}


</style>
<legend class="text-uppercase font-size-lg text-dark font-weight-bold"><i class="fa fa-database" aria-hidden="true"></i> 
<?=$entry;?> <br> <br>
<span class="text-info">Library: <?=$lib;?></span> <br>
<span class="text-info">Threshold: <?=$percent;?>%</span>
</legend>
<table class="table table-hover table-bordered" id="datatable">
    <thead>
        <tr class="bg-primary-300">
            <th width="5%">No.</th>
            <th>Library</th>
            <th>Tasktonomi</th>
            <th>Tipe Risiko</th>
            <th>Persentase</th>
        </tr>
    </thead>
    <tboy>
    <?php
        $no=0;
        foreach($rows as $row):
            $bg="";
            $c="";
            if ($row['similarity']>=90) {
                $c = 'high-similarity';
                $bg = 'danger';
            } elseif ($row['similarity']<= 75) {
                $c = 'medium-similarity';
            } elseif ($row['similarity']<= 50) {
                $c = 'low-similarity'; 
            } else {
                $c = 'low-similarity';
            }
            ?>
        
        <tr class="pointer similarity <?= $c; ?>" data-id="<?=$row['id'];?>">
            <td width="8%"><?=++$no;?></td>
            <td><?=$row['nama'];?></td>
            <td><?=$row['nama_kelompok'];?></td>
            <td><?=$row['risk_type'];?></td>
            <td width="20%"><?=$row['similarity'];?> %</td>
        </tr>
        <?php endforeach; ?>
    </tboy>
</table>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            pageLength:20,
            language:{
                "decimal":        '<?=lang('decimal');?>',
                "emptyTable":     '<?=lang('emptyTable');?>',
                "info":           '<?=lang('info');?>',
                "infoEmpty":      '<?=lang('infoEmpty');?>',
                "infoFiltered":   '<?=lang('infoFiltered');?>',
                "infoPostFix":    '<?=lang('infoPostFix');?>',
                "thousands":      '<?=lang('thousands');?>',
                "lengthMenu":     '<?=lang('lengthMenu');?>',
                "loadingRecords": '<?=lang('loadingRecords');?>',
                "processing":      '<img src="<?=img_url('ajax-loader.gif')?>">',
                "search":         '<?=lang('search').' &nbsp; ';?>',
                "zeroRecords":    '<?=lang('zeroRecords');?>',
                "paginate": {
                    "first":      '<?=lang('first');?>',
                    "last":       '<?=lang('last');?>',
                    "next":       '<?=lang('next');?>',
                    "previous":   '<?=lang('previous');?>',
                },
                "aria": {
                    "sortAscending":  '<?=lang('sortAscending');?>',
                    "sortDescending": '<?=lang('sortDescending');?>',
                }
            },
            dom: "<'row'<'col-sm-5'i><'col-sm-7'p><'col-sm-6'l><'col-sm-6'f>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        });
    })
</script>