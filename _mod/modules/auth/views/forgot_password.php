<?php echo form_open("auth/forgot_password", ['class' => 'login-form', 'id' => 'form_login']);?>
      <div class="card mb-0">
            <div class="card-body">
                  <div class="text-center mb-3">
                        <i class="icon-spinner11 icon-2x text-warning border-warning border-3 rounded-round p-3 mb-3 mt-1"></i>
                        <h5 class="mb-0">Password recovery</h5>
                        <span class="d-block text-muted"><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></span>
                        <div id="infoMessage"><?php echo $message;?></div>
                  </div>

                  <div class="form-group form-group-feedback form-group-feedback-right">
                        <input type="email" name='identity' class="form-control" placeholder="Your email">
                        <div class="form-control-feedback">
                              <i class="icon-mail5 text-muted"></i>
                        </div>
                  </div>

                  <button type="submit" class="btn bg-blue btn-block"><i class="icon-spinner11 mr-2"></i> <?=lang('forgot_password_submit_btn');?></button>
            </div>
      </div>
      <?php echo form_close();?>
