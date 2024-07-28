<style>
    table {
        table-layout: fixed;
        width: 100%;
    }

    table,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }


    tr,
    td {
        width: 30px !important;
        height: 50px !important;
    }


    .top-border {
        border-style: solid;
        border-color: white;
        border-width: 10px;
    }

    .remove-border {
        border-style: hidden !important;
    }

    .rotate {
        -moz-transform: scale(-1, -1);
        -webkit-transform: scale(-1, -1);
        -o-transform: scale(-1, -1);
        -ms-transform: scale(-1, -1);
        transform: scale(-1, -1);
    }
</style>
<div class="row">
    <div class="col-md-4 text-center">
        <div class="row">
            <div class="col-md-12">
                <h6>Inherent <?= ! empty( $jml_inherent ) ? $jml_inherent : 0; ?>
                </h6>
                <?= $map_inherent ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-secondary">
                    <table>
                        <tr>
                            <td>5</td>
                            <td>Hampir Pasti Terjadi</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Sangat Mungkin Terjadi</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Mungkin Terjadi</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jarang Terjadi</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Hampir Tidak Terjadi</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-4 text-center">
        <div class="row">
            <div class="col-md-12">
                <h6>Current <?= ! empty( $jml_residual ) ? $jml_residual : 0; ?>
                </h6>
                <?= $map_residual ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-secondary">
                    <table>
                        <tr>
                            <td>5</td>
                            <td>Hampir Pasti Terjadi</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Sangat Mungkin Terjadi</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Mungkin Terjadi</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jarang Terjadi</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Hampir Tidak Terjadi</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>

    </div>
    <div class="col-md-4 text-center">
        <div class="row">
            <div class="col-md-12">
                <h6>Residual <?= ! empty( $jml_target ) ? $jml_target : 0; ?>
                </h6>
                <?= $map_target ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-secondary">
                    <table>
                        <tr>
                            <td>5</td>
                            <td>Hampir Pasti Terjadi</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Sangat Mungkin Terjadi</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Mungkin Terjadi</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jarang Terjadi</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Hampir Tidak Terjadi</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>