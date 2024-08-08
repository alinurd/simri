<div class="jumbotron border p-3">
    <?php

    if( ! empty( $this->session->flashdata( "alert_{$inputname}" ) ) )
    { ?>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata( "alert_{$inputname}" ) ?>
                </div>
            </div>
        </div>
    <?php } ?>


    <div class=" row justify-content-center">
        <div class="col-md-12">
            <?php

            if( ! empty( $attachment ) )
            {
                $n = 1;
                foreach( $attachment as $key => $value )
                {
                    ?>
                    <div class="input-group input-group-sm m-3 <?= $inputname ?>">
                        <div class="input-group-prepend">
                            <a target="_blank" <?= ! empty( $value ) ? "href='{$value}'" : "" ?>><span
                                    class="input-group-text"><i class="icon-link text-primary"></i></span></a>
                        </div>
                        <input type="text" class="form-control" name="<?= $inputname ?>[<?= $n ?>]"
                            value="<?= ! empty( $value ) ? $value : "" ?>">
                        <div class="input-group-append mr-3">
                            <?php
                            if( $n == 1 )
                            {
                                ?>
                                <button id="<?= $inputname ?>btnLink" type="button" class="btn btn-outline-success btn-sm"><i
                                        class="fa fa-plus"></i></button>
                                <?php
                            }
                            else
                            { ?>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-<?= $inputname ?>"><i
                                        class="fa fa-trash"></i></button>
                            <?php } ?>
                        </div>
                    </div>
                    <?php $n++ ?>
                <?php }
            }
            else
            {
                ?>
                <div class="input-group input-group-sm m-3 <?= $inputname ?>">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-link"></i></span>
                    </div>
                    <input type="text" class="form-control" name="<?= $inputname ?>[0]" value="">
                    <div class="input-group-append mr-3">
                        <button id="<?= $inputname ?>btnLink" type="button" class="btn btn-outline-success btn-sm"><i
                                class="fa fa-plus"></i></button>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center mb-3">
        <div class="col-md-12">
            <div class="dropzone pointer border-info bg-light" id="<?= $inputname ?>">
                <div class="fallback">
                    <input name="<?= $inputname ?>[doc][]" type="file" multiple />
                </div>
            </div>
        </div>
    </div>
</div>

<div id="preview-template" style="display:none">
    <div class="dz-preview dz-complete dz-image-preview p-0" style="box-shadow: none;">
        <table class="table table-sm table-striped table-bordered">
            <tbody>
                <tr>
                    <td class="text-center">
                        <div class="dz-details">
                            <!-- <div class="dz-size"><span data-dz-size=""></span></div> -->
                            <div class="dz-filename m-0"><span data-dz-name=""></span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dz-image text-center m-3">
                            <img data-dz-thumbnail draggable="false" class="" style="height:64px;width:64px;" />
                        </div>
                    </td>
                </tr>
                <tr class="d-none">
                    <td>
                        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                    </td>
                </tr>
                <tr class="d-none">
                    <td>
                        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                        <div class="dz-success-mark"></div>
                        <div class="dz-error-mark"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="dz-remove border m-1" href="javascript:undefined;" data-dz-remove=""><i
                                class="icon-trash" style="font-size:12px;">&nbsp;Delete</i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



<!-- <div class="dz-preview dz-file-preview">
    <div class="dz-details">
        <div class="dz-filename"><span data-dz-name></span></div>
        <div class="dz-size" data-dz-size></div>
        <img data-dz-thumbnail />
    </div>
    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
</div> -->