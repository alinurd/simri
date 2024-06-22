<?php
  $hide='';
  $message = $this->session->flashdata('message');
  if (empty($message)){$hide='d-none';}
?>

<?php echo form_open("auth/login", ['class' => 'login-form', 'id' => 'form_login', 'autocomplete' => 'off']);?>
  <div class="card mb-0">
    <div class="card-body">
      <div class="text-center mb-3">
        <img src="<?=img_url('logo.png');?>" alt="" width="150">
        <h5><?php echo lang('login_heading');?></h5>
        <span class="d-block text-muted"><?php echo lang('login_subheading');?></span> 
        <div class="alert alert-danger border-0 alert-dismissible <?=$hide;?>">
          <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
          <span class="font-weight-semibold"><?php echo $message;?>
        </div>
      </div>
      
      <div class="form-group form-group-feedback form-group-feedback-left">
        <?php echo form_input($identity, '', ['autocomplete'=>'off']);?>
        <div class="form-control-feedback">
          <i class="icon-user text-muted"></i>
        </div>
      </div>

      <div class="form-group form-group-feedback form-group-feedback-left">
        <?php echo form_input($password, '', ['autocomplete'=>'off']);?>
        <div class="form-control-feedback">
          <i class="icon-lock2 text-muted"></i>
        </div>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block"><?=lang('login_submit_btn');?><i class="icon-circle-right2 ml-2"></i></button>
      </div>

      <div class="text-center">
        <a href="forgot-password"><?php echo lang('login_forgot_password');?></a>
      </div>
    </div>
  </div>
  <?php echo form_close();?>

  <script>
    $('document').ready(function(){
      setInterval(function(){ $('.alert').remove(); }, 5000);
    })
  </script>
