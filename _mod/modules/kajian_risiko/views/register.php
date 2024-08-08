<hr>
<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-3 mb-3 border d-flex justify-content-start">
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
                            <td><em>Risk Owner</em></td>
                            <td>
                                <strong><?= ( ! empty( $headerRisk["owner_name"] ) ? $headerRisk["owner_name"] : "" ) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><em>Tanggal Permintaan</em></td>
                            <td><strong><?= ( ! empty( $headerRisk["request_date"] ) ? $headerRisk["request_date"] : "" ) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><em>Status</em></td>
                            <td><span
                                    class="btn btn-sm disabled btn-block  <?= ( ! empty( $headerRisk["status"] ) && $headerRisk["status"] == 1 ? "btn-success" : "btn-danger" ) ?>"><strong><?= ( ! empty( $headerRisk["status"] ) ? "SUBMITTED" : "DRAFT" ) ?></strong></span>
                            </td>
                        </tr>
                        <?php if( ! empty( $headerRisk["date_submit"] ) )
                        { ?>
                            <tr>
                                <td><em>Tanggal Submit</em></td>
                                <td><strong><?= $headerRisk["date_submit"] ?></strong>
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
