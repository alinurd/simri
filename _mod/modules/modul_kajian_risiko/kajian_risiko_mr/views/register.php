<hr>
<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 mb-3 border d-flex justify-content-start">
            <div class="col-md-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                <em>Kajian Risiko</em>
                            </td>
                            <td><strong><?= ( ! empty( $headerRisk["name"] ) ? $headerRisk["name"] : "" ) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em>Tipe Kajian</em>
                            </td>
                            <td><strong><?= ( ! empty( $headerRisk["tipe_kajian"] ) ? $headerRisk["tipe_kajian"] : "" ) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em>Latar Belakang</em>
                            </td>
                            <td><strong><?= ( ! empty( $headerRisk["latar_belakang"] ) ? $headerRisk["latar_belakang"] : "" ) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><em>Risk Owner</em></td>
                            <td>
                                <strong><?= ( ! empty( $headerRisk["owner_name"] ) ? $headerRisk["owner_name"] : "" ) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><em>Tanggal Permintaan</em></td>
                            <td><strong><?= ( ! empty( $headerRisk["request_date"] ) && $headerRisk["request_date"] != "0000-00-00" ? date( "d-m-Y", strtotime( $headerRisk["request_date"] ) ) : "" ) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><em>Status</em></td>
                            <td><?php
                            if( ! empty( $headerRisk["status"] ) )
                            {
                                switch( $headerRisk["status"] )
                                {
                                    case 1: ?>
                                            <span
                                                class="btn btn-sm disabled btn-block btn-success"><strong>SUBMITTED</strong></span>
                                            <?php break; ?>

                                        <?php case 2: ?>
                                            <span class="btn btn-sm disabled btn-block btn-warning"><strong>REVISI</strong></span>
                                            <?php break; ?>

                                        <?php default:

                                        break;
                                }
                            }
                            ?>
                            </td>
                        </tr>
                        <?php if( ! empty( $headerRisk["date_submit"] ) && $headerRisk["request_date"] != "0000-00-00" )
                        { ?>
                            <tr>
                                <td><em>Tanggal Submit</em></td>
                                <td><strong><?= date( "d-m-Y", strtotime( $headerRisk["date_submit"] ) ) ?></strong>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view( "register_" . $btn_view, $register ); ?>
<?php $this->load->view( "register_" . $view, $register ); ?>
<?php $this->load->view( "register_upload_dokumen_mr", $register ) ?>
