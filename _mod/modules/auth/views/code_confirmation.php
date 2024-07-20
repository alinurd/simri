<?php
$hide    = '';
$message = $this->session->flashdata( 'message' );

if( empty( $message ) )
{
    $hide = 'd-none';
}
?>

<?php echo form_open( "auth/reset-password", [ 'class' => 'login-form', 'id' => 'form_login', 'autocomplete' => 'off' ] ); ?>
<div class="card mb-0">
    <div class="card-body">
        <div class="text-center mb-3">
            <div class="alert alert-danger border-0 alert-dismissible <?= $hide; ?>">
                <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                <span class="font-weight-semibold"><?= $message ?></span>
            </div>
            <img src="<?= img_url( 'logo.png' ); ?>" alt="" width="150">
            <h5>Confirmation Code</h5>
            <span class="d-block text-muted">Please Enter The code that we have sent to your email below</span>
        </div>

        <div class="form-group form-group-feedback form-group-feedback-left">
            <?php echo form_input( "code_confirmation", '', [ 'autocomplete' => 'off', "class" => "form-control", "placeholder" => "Enter Code Confirmation" ] ); ?>
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
            </div>
        </div>
        <br>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Send<i class="icon-circle-right2 ml-2"></i></button>
        </div>

    </div>
    <div class="card-footer">
        <div class="text-center">
            <a href="login">Kembali ke Login</a>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script>
    $('document').ready(function () {
        setInterval(function () { $('.alert').remove(); }, 5000);
    })
</script>