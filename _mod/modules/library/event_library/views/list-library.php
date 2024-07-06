<div id="konten_event">
    <span class='btn btn-success pull-right' id="add_library">Tambah <?= $kel; ?> Baru</span>
    <br /><br />
    <div id="listEvent">
        <table class="table data bordered" id="datatables_library">
            <thead>
                <tr>
                    <th width="5%" style="text-align:center;">No.</th>
                    <th width="10%"><?php echo lang('msg_field_pop_risk_event_type'); ?></th>
                    <th width="80%"><?php echo lang('msg_field_pop_event_description_library'); ?></th>
                    <th width="5%"><?php echo lang('msg_tombol_select'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
				$i = 1;
				foreach ($field as $key => $row) {
					$value_chek = $row['id'] . "#" . $row['code'] . ' - ' . $row['library'];
					?>
                <tr style="cursor:pointer;" data-value="<?php echo $value_chek; ?>">
                    <td><?= $i; ?></td>
                    <td><?php echo $row['nama_kelompok']; ?></td>
                    <td>
                        <?php echo $row['library']; ?>
                    </td>
                    <td class="text-center"><span class="btn btn-info pilih-<?php echo $kel; ?>" data-value="<?php echo $value_chek; ?>" data-dismiss="modal" style="padding:2px 8px;"> Pilih </span></td>
                </tr>
                <?php 
				++$i;
			}
			?>
            </tbody>
        </table>
    </div>
</div>
<div id="konten_add_library" class="d-none">
    <div class="row">
        <div clas="col-md-12 col-sm-12 col-xs-12">
            <table class="table" id="tblEvent">
                <tbody>
                    <tr>
                        <td style="padding-left:0px;">
                            <?= $kel; ?><br /><?= form_textarea('add_event_name', '', " id='add_event_name' maxlength='500' size=500 class='form-control' rows='2' cols='5' style='overflow: hidden; width: 500 !important; height: 104px;'") . form_hidden(['add_kel' => $nilKel]); ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <span class="btn btn-primary" id="simpan_library"> Simpan </span>
                            <span class="btn btn-warning" id="cancel_library"> Cancel </span>
                        </td>
                    </tr>
                </tbody>
            </table>';
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#datatables_library').DataTable();
    })
</script>