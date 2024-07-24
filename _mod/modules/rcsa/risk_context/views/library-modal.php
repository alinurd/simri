<div class='table-responsive'>
    <span class="btn bg-primary-400 btn-labeled btn-labeled-right legitRipple pull-right" id="addLibrary" lib-type="<?=$thead3 ?>"
        data-mode="0">
        <b><i class="icon-file-plus "></i></b> Add <?=$thead3 ?>
    </span>
    <br />&nbsp;<br />&nbsp;
    <table class="table table-hover datatables" id="list_library">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th><?= $thead3 ?></th>
                <th width="5%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n = 1;
            foreach( $data as $q ) :
                ?>
                <tr>
                    <td width="5%" class="text-center"><?= $n++ ?></td>
                    <td><?= $q['library'] ?></td>
                    <td width="10%" class="text-center">
                        <input type="hidden" id="libraryName<?= $q['id'] ?>" value="<?= $q['library'] ?>">
                        <input type="hidden" id="identity<?= $q['id'] ?>" value="<?= $identity ?>">
                        <span class="btn bg-primary-400 btn-labeled btn-labeled-left legitRipple" data-id="<?= $q['id'] ?>" data-lib="<?=$libtype?>"
                            id="pilihLibrary">
                            <b><i class="text-light icon-database-add"></i></b>Pilih <?= $thead3 ?>
                        </span>
                    </td>
                </tr>

            <?php endforeach ?>
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function () {
        var table = $('#list_library').DataTable({
            pageLength: 10,
            fixedHeader: true,
            language: {
                "decimal": '<?= lang( 'decimal' ); ?>',
                "emptyTable": '<?= lang( 'emptyTable' ); ?>',
                "info": '<?= lang( 'info' ); ?>',
                "infoEmpty": '<?= lang( 'infoEmpty' ); ?>',
                "infoFiltered": '<?= lang( 'infoFiltered' ); ?>',
                "infoPostFix": '<?= lang( 'infoPostFix' ); ?>',
                "thousands": '<?= lang( 'thousands' ); ?>',
                "lengthMenu": '<?= lang( 'lengthMenu' ); ?>',
                "loadingRecords": '<?= lang( 'loadingRecords' ); ?>',
                "processing": '<img src="<?= img_url( 'ajax-loader.gif' ) ?>">',
                "search": '<?= lang( 'search' ) . ' &nbsp; '; ?>',
                "zeroRecords": '<?= lang( 'zeroRecords' ); ?>',
                "paginate": {
                    "first": '<?= lang( 'first' ); ?>',
                    "last": '<?= lang( 'last' ); ?>',
                    "next": '<?= lang( 'next' ); ?>',
                    "previous": '<?= lang( 'previous' ); ?>',
                },
                "aria": {
                    "sortAscending": '<?= lang( 'sortAscending' ); ?>',
                    "sortDescending": '<?= lang( 'sortDescending' ); ?>',
                }
            },
            dom: "<'row'<'col-sm-5'i><'col-sm-7'p><'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",

            // dom: "<'row'<'col-sm-12'l>>" +
            //     "<'row'<'col-sm-12'tr>>" +
            //     "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        })
    });
</script>