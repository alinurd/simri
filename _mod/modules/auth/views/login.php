<?php
$hide    = '';
$message = $this->session->flashdata( 'message' );
if( empty( $message ) )
{
  $hide = 'd-none';
}
?>

<?php echo form_open( "auth/login", [ 'class' => 'login-form', 'id' => 'form_login', 'autocomplete' => 'off' ] ); ?>
<div class="card mb-0">
  <div class="card-body">
    <div class="text-center mb-3">
      <img src="<?= img_url( 'logo.png' ); ?>" alt="" width="150">
      <h5><?php echo lang( 'login_heading' ); ?></h5>
      <span class="d-block text-muted"><?php echo lang( 'login_subheading' ); ?></span>
      <div class="alert alert-danger border-0 alert-dismissible <?= $hide; ?>">
        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        <span class="font-weight-semibold  "><?php echo $message; ?>
      </div>
    </div>

    <div class="form-group form-group-feedback form-group-feedback-left">
      <?php echo form_input( $identity, '', [ 'autocomplete' => 'off' ] ); ?>
      <div class="form-control-feedback">
        <i class="icon-user text-muted"></i>
      </div>
    </div>

    <div class="form-group form-group-feedback form-group-feedback-left">
      <?php echo form_input( $password, '', [ 'autocomplete' => 'off' ] ); ?>
      <div class="form-control-feedback">
        <i class="icon-lock2 text-muted"></i>
      </div>
    </div> 
    <div class="form-group mt-3">
      
    <div class="g-recaptcha" data-sitekey="<?=$recaptcha['key']['site']?>" data-callback="recaptchaCallback" data-expired-callback="resetRecaptcha"></div>
    <?php echo $recaptcha['script']; ?>
    
    </div>
    <div class="form-group mt-3">
      <button type="submit" id="signInBtn" class="btn btn-info btn-block bg-bumn-gradient-1" disabled><?= lang( 'login_submit_btn' ); ?><i
          class="icon-circle-right2 ml-2"></i></button>
    </div>
  </div>
  <div class="card-footer">
    <div class="text-center">
      <a href="recovery-password"><?php echo lang( 'login_forgot_password' ); ?></a>
    </div>
  </div>
</div>
<?php echo form_close(); ?>


<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
  $('document').ready(function () {
    setInterval(function () { $('.alert').remove(); }, 5000);
    window.recaptchaCallback = function () {
      $('#signInBtn').prop('disabled', false);  
    }

    // Callback when reCAPTCHA expires
    window.resetRecaptcha = function () {
      $('#signInBtn').prop('disabled', true); 
    }
 
    $('#form_login').on('submit', function (e) { 
      var response = grecaptcha.getResponse();
      if (response.length === 0) {
        e.preventDefault();  
        alert('Please complete the reCAPTCHA.');
        resetRecaptcha();  
      }
    });
  })
</script>