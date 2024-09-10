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
                            <td><em>Tanggal Release (MR)</em></td>
                            <td><strong><?= ( ! empty( $headerRisk["release_date"] ) && $headerRisk["release_date"] != "0000-00-00" ? date( "d-m-Y", strtotime( $headerRisk["release_date"] ) ) : "" ) ?></strong>
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
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table table-borderless">
                    <tbody>

                        <?php if( ! empty( $headerRisk["date_submit"] ) && $headerRisk["request_date"] != "0000-00-00" )
                        { ?>
                            <tr>
                                <td><em>Tanggal Submit (TMRD)</em></td>
                                <td><strong><?= date( "d-m-Y", strtotime( $headerRisk["date_submit"] ) ) ?></strong>
                                </td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td><em>Tanggal Approval</em></td>
                            <td>
                                <strong><?= ( ! empty( $headerRisk["approved_by"] ) && date( "d-m-Y", strtotime( $headerRisk["approved_by"] ) ) != "00-00-0000" ? date( "d-m-Y", strtotime( $headerRisk["date_approval"] ) ) : "" ) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><em>Updated by</em></td>
                            <td><strong><?= ( ! empty( $headerRisk["approved_by"] ) ? $headerRisk["approved_by"] : "" ) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em>Status Approval</em>
                            </td>
                            <td>
                                <?php if( ! empty( $headerRisk["status_approval"] ) ) : ?>
                                    <?php switch( $headerRisk["status_approval"] )
                                    {
                                        case 'review': ?>
                                            <div class="btn btn-sm btn-block btn-warning disabled">
                                                <strong><?= ( ! empty( $headerRisk["status_approval"] ) ? strtoupper( $headerRisk["status_approval"] ) : "" ) ?></strong>
                                            </div>
                                            <?php break;
                                        case 'rejected': ?>
                                            <div class="btn btn-sm btn-block btn-danger disabled ">
                                                <strong><?= ( ! empty( $headerRisk["status_approval"] ) ? strtoupper( $headerRisk["status_approval"] ) : "" ) ?></strong>
                                            </div>
                                            <?php break;
                                        case 'approved': ?>
                                            <div class="btn btn-sm btn-block btn-success disabled ">
                                                <strong><?= ( ! empty( $headerRisk["status_approval"] ) ? strtoupper( $headerRisk["status_approval"] ) : "" ) ?></strong>
                                            </div>
                                            <?php break;

                                        default: ?>
                                            <div class="btn btn-sm btn-block btn-default disabled ">
                                                <strong>No Result</strong>
                                            </div>
                                            <?php break;
                                    } ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view( "register_" . $btn_view, $register ); ?>
<?php $this->load->view( "register_" . $view, $register ); ?>
<?php $this->load->view( "register_upload_dokumen_mr", $register ) ?>
