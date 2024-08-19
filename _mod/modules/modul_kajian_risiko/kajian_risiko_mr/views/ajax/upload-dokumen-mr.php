<input name="filename" type="hidden" value="<?= $filename ?>" id="file-exist">
<?php
if( ! empty( $filename ) )
{ ?>
    <div class="row">
        <div class="col-md-12">
            <label><strong>Dokumen MR</strong></label><br>
            <a href="<?= base_url( "files/kajian_risiko_mr/" . $filename ) ?>" target="_blank">
                <?= $filename ?>
            </a>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <button class="btn btn-sm btn-outline-danger" id="btn-clear-dokumen" data-filename="<?= $filename ?>"
                data-url="<?= $urlclearbtn ?>"><i class="icon-trash font-sm"></i></button>
        </div>
    </div>
<?php } ?>
