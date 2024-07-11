<div class='table-responsive listPeristiwa'>
    <span class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right" id="addPeristiwa" data-mode="0">
        <b><i class="icon-file-plus "></i></b> <?=_l('fld_peristiwa_risiko_add');?>
    </span>
    <br/>&nbsp;<br/>&nbsp;
    <table class="table table-hover datatables" id="tbl_list_aktifitas_mitigasi">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%"><?=_l('fld_klasifikasi_risiko');?></th>
                <th width="15%"><?=_l('fld_tipe_risiko');?></th>
                <th><?=_l('fld_peristiwa_risiko');?></th>
                <th width="5%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $n = 1;
            foreach($libs as $q):
            ?>
                <tr>
                    <td width="5%" class="text-center"><?=$n++?></td>
                    <td width="15%"><?=$q['nama_kelompok']?></td>
                    <td width="15%"><?=$q['risk_type']?></td>
                    <td><?=$q['library']?></td>
                    <td width="5%" class="text-center">
                        <i class="icon-database-add text-primary-400 pilih-peristiwa"></i>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>


<script>


    $(document).ready(function() {
        $('#tbl_list_aktifitas_mitigasi').DataTable({
            pageLength: 10,
            fixedHeader: true,
            language: {
                "decimal":        '<?=lang('decimal');?>',
                "emptyTable":     '<?=lang('emptyTable');?>',
                "info":           '<?=lang('info');?>',
                "infoEmpty":      '<?=lang('infoEmpty');?>',
                "infoFiltered":   '<?=lang('infoFiltered');?>',
                "infoPostFix":    '<?=lang('infoPostFix');?>',
                "thousands":      '<?=lang('thousands');?>',
                "lengthMenu":     '<?=lang('lengthMenu');?>',
                "loadingRecords": '<?=lang('loadingRecords');?>',
                "processing":     '<img src="<?=img_url('ajax-loader.gif')?>">',
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
            dom: "<'row'<'col-sm-12'l>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        });
    });
</script>
