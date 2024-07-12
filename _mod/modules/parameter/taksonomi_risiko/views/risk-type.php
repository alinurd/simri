<table class="table table-hover table-striped table-sm" id="risk_type">
    <thead>
        <tr>
            <th width="5%">Sort</th>
            <th width="5%">No.</th>
            <th>Tipe Risiko</th>
            <th width="10%">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        foreach( $data as $row ) :
            $edit_id = form_hidden( 'edit_id[]', $row['id'] );
            ?>
            <tr>
                <td><i class="pointer text-danger icon-square-up" title=" Pindah posisi Keatas "></i><i
                        class="pointer text-primary icon-square-down" title=" Pindah posisi Kebawah "></i> </td>
                <td><strong><?= ++$no . $edit_id; ?></strong></td>
                <td class="text-right"><?= form_input( 'risk_type[]', $row['data'], 'class="form-control"' ); ?></td>
                <td class="text-center">
                    <i class="icon-file-plus pointer text-primary add-product-satu" title=" tambah data  "></i> |
                    <span class="text-danger" nilai="0" style="cursor:pointer;"
                        onclick="remove_install(this,<?= $row['id']; ?>,'combo')">
                        <i class="fa fa-cut" title="menghapus data"></i>
                    </span>
                </td>
            <?php endforeach; ?>
    </tbody>
</table>
<hr><span class="btn btn-info float-right pointer" id="add_risk"> Add Tipe Risiko </span><br />

<?php
$edit      = form_hidden( 'edit_id[]', 0 );
$risk_type = form_input( 'risk_type[]', '', ' class="form-control" style="width:100%;"' );
?>
<script type="text/javascript">
    var edit = '<?php echo addslashes( preg_replace( "/(\r\n)|(\n)/mi", "", $edit ) ); ?>';
    var risk_type = '<?php echo addslashes( preg_replace( "/(\r\n)|(\n)/mi", "", $risk_type ) ); ?>';

    $(function () {
        $(document).on('click', '.icon-square-up, .icon-square-down', function () {
            var row = $(this).parents("tr:first");
            $(".icon-square-up,.icon-square-down").show();
            if ($(this).is(".icon-square-up")) {
                row.insertBefore(row.prev());
            } else {
                row.insertAfter(row.next());
            }
            // $("tbody tr:last .down").hide();
            $(this).closest('table').find('tbody tr:last').find('.icon-square-down').hide();
            $(this).closest('table').find('tbody tr:first').find('.icon-square-up').hide();
        });

        $(document).on('click', '#add_risk', function () {
            var row = $("#risk_type > tbody");
            row.append('<tr><td><i class="pointer text-danger icon-square-up" title=" Pindah posisi Keatas "></i><i class="pointer text-primary icon-square-down" title=" Pindah posisi Kebawah "></i> </td><td>' + edit + '</td><td>' + risk_type + '</td><td class="text-center"><i class="icon-file-plus pointer text-primary add-product-satu" title=" tambah data harga "></i> | <span class="text-primary" nilai="0" style="cursor:pointer;" onclick="remove_install(this,0)"><i class="fa fa-cut" title="menghapus data"></i></span></td></tr>');
        });
    });
</script>