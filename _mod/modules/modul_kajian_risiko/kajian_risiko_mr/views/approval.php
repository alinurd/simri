<hr>
<?php $this->load->view( "approval_" . $btn_view, $getfiledata ); ?>
<div class="row">
    <div class="col-md-12">
        <div class="jumbotron p-2 mb-3 border">
            <div class="card m-0 shadow-sm border">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
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
                                        <td><strong><?= ( ! empty( $headerRisk["request_date"] ) ? date( "d-m-Y", strtotime( $headerRisk["request_date"] ) ) : "" ) ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><em>Tanggal Release</em></td>
                                        <td><strong><?= ( ! empty( $headerRisk["release_date"] ) ? date( "d-m-Y", strtotime( $headerRisk["release_date"] ) ) : "" ) ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><em>Status</em></td>
                                        <td>
                                            <?php
                                            if( ! empty( $headerRisk["status"] ) )
                                            {
                                                switch( $headerRisk["status"] )
                                                {
                                                    case 1: ?>
                                                        <span
                                                            class="btn btn-sm disabled btn-block btn-success"><strong>SUBMITTED</strong></span>
                                                        <?php break; ?>
                                                    <?php case 2: ?>
                                                        <span
                                                            class="btn btn-sm disabled btn-block btn-warning"><strong>REVISI</strong></span>
                                                        <?php break; ?>
                                                    <?php default:

                                                        break;
                                                }
                                            }
                                            ?>

                                        </td>
                                    </tr>
                                    <?php if( ! empty( $headerRisk["date_submit"] ) )
                                    { ?>
                                        <tr>
                                            <td><em>Tanggal Submit</em></td>
                                            <td><strong><?= date( "d-m-Y", strtotime( $headerRisk["date_submit"] ) ) ?></strong>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td><em>Tanggal Approval</em></td>
                                        <td>
                                            <strong><?= ( ! empty( $headerRisk["approved_by"] ) ? date( "d-m-Y", strtotime( $headerRisk["date_approval"] ) ) : "" ) ?></strong>
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
                        <div class="col-md-6">
                            <form class="form-horizontal" action="<?= $formUrl ?>" method="POST">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                    value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="jumbotron p-2 mb-2 border">
                                            <div class="card m-0 shadow-none border">
                                                <div class="card-header text-center p-1 border-bottom">
                                                    <h6 class="mb-0">Catatan</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <textarea class="form-control" id="exampleFormControlTextarea1"
                                                            name="note" rows="10"
                                                            placeholder="Catatan Approval Kajian Risiko"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card m-0 shadow-none border">
                                            <div class="card-body bg-light">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group row d-none">
                                                            <label class="col-form-label col-md-4">Send Email
                                                                Notification
                                                            </label>
                                                            <div class="col-md-8">
                                                                <div
                                                                    class="form-check form-check-switchery form-check-inline form-check-switchery-double">
                                                                    <label class="form-check-label">No<input
                                                                            type="checkbox" name="send_notif_approval"
                                                                            value="1" checked="checked"
                                                                            id="send_notif_approval"
                                                                            class="pointer form-switchery-primary" />
                                                                        Yes</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-form-label col-md-4">Max Release Date
                                                            </label>
                                                            <div class="col-md-6">
                                                                <input type="text" name="release_date"
                                                                    class="form-control pickadate border bg-white"
                                                                    placeholder="Max Release Date" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mt-2">
                                                            <label class="col-form-label col-md-4">Status Approval
                                                            </label>
                                                            <div class="col-md-6">
                                                                <select class="form-control text-center"
                                                                    name="status_approval" required>
                                                                    <option value="rejected">REJECT</option>
                                                                    <option value="approved">APPROVE</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 align-content-end justify-content-end">
                                                        <button
                                                            class="btn bg-success btn-labeled btn-labeled-left button-action pull-right"
                                                            type="submit"> <b><i class="icon-checkmark-circle"></i></b>
                                                            Submit
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-2">
                                    <div class="col-md-12">
                                        <div class="alert alert-primary shadow-sm mb-0 p-2 text-center">
                                            Anda Memiliki
                                            <strong><?= ! empty( $countNotifDocumen ) ? $countNotifDocumen : 0 ?></strong>
                                            daftar
                                            Kajian Risiko yang belum
                                            terupload dokumen.&nbsp;
                                            <button type="button" id="btn-list-upload-dokumen-mr"
                                                data-url="<?= ( ! empty( $notifdokumenUrl ) ? $notifdokumenUrl : "" ) ?>"
                                                class="btn btn-sm btn-primary">Lihat
                                                Daftar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view( "approval_" . $view, $getfiledata ); ?>
