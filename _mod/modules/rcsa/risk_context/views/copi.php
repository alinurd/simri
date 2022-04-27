<div class="row">
    <div class="col-md-7">
        SUMBER :
        <hr>
        <table width="100%" class="table table-borderless" id="tbl_event">
            <tbody>
                <tr>
                    <td width="25%">Assesment Type:</td>
                    <td><strong>
                            <?= $rcsa['type_ass']; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td>Nama Risk Owner:</td>
                    <td><strong>
                            <?= $rcsa['owner_name']; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td>Sasaran:</td>
                    <td><strong>
                            <?= $rcsa['sasaran_dept']; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td>Periode:</td>
                    <td><strong>
                            <?= $rcsa['period_name'] . ' term : ' . $rcsa['term']; ?></strong>
                    </td>
                </tr>
                <tr>
                    <td>Tanggal:</td>
                    <td><strong>
                            <?= $rcsa['tgl_mulai_term'] . ' s.d ' . $rcsa['tgl_selesai_term']; ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-5">
        TARGET :
        <hr>
        <table width="100%" class="table table-borderless">
       
            <!-- <tr>
                <td width="20%">Periode Data Sekarang : </td>
                <td>
                    <?php //$rcsa['period_name']; ?>
                </td>
            </tr> -->
            <tr>
                <td width="20%">Periode : </td>
                <td>
                    <?= $id. $periode; ?>
                </td>
            </tr>
            <tr>
                <td>Term : </td>
                <td>
                    <?= $term; ?>
                </td>
            </tr>

            <tr>
                <td>Bulan : </td>
                <td>
                    <?= $minggu; ?>
                </td>
            </tr>
        </table>
        <br />
        <?php
        $none='';
        if(count($cbo_term)==0)
        $none='d-none';
        ?>

        <span class="btn btn-primary <?=$none;?>" id="proses_copy" style="padding:15px;"> Copi Data </span>
    </div>
</div> 

<script>
    $(function(){
        var parent = $("#periode_copy").parent();
        var nilai = $("#periode_copy").val();
        var data = {
            'id': nilai
        };
        var target_combo = $("#term_copy");
        var url = "ajax/get-term";
        _ajax_("post", parent, data, target_combo, url);
        $("#periode_copy").change(function () {
            var parent = $(this).parent();
            var nilai = $(this).val();
            var data = {
                'id': nilai
            };
            var target_combo = $("#term_copy");
            var url = "ajax/get-term";
            _ajax_("post", parent, data, target_combo, url);
        });

        $("#term_copy").change(function () {
		var parent = $(this).parent();
		var nilai = $(this).val();
		var data = {
			'id': nilai
		};
		var target_combo = $("#minggu_copy");
		var url = "ajax/get-minggu";
		_ajax_("post", parent, data, target_combo, url);
    })
    })
</script>