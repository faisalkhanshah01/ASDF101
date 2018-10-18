<?php
  $_POST['admin_email']        = isset($_POST['admin_email'])?$_POST['admin_email']:'';
?>
<div class="col-md-4 col-md-offset-4 before-login-forms" id="forgot-password">
      <h4>Admin Forget Password</h4>
      <?php echo $this->session->flashdata('msg'); ?>
      <?php $attributes = array("name" => "adminForgetPasswordForm");
      echo form_open_multipart("admin/forgot_password.html", $attributes);?>
     
      <div class="form-group">
        <!-- <label for="admin_email">Email</label> -->
        <input class="form-control" name="admin_email" placeholder="Email Address" type="text" value="<?php echo set_value('admin_email'); ?>" />
        <span class="text-danger"><?php echo form_error('admin_email'); ?></span>
      </div>

      <div class="form-group">
        <button name="submit" type="submit" class="btn btn-default btn-block">Reset Password</button>
      </div>
      <?php echo form_close(); ?>
      <p class="txt-cntr">Not registered yet! <a href='<?php echo base_url("admin/register.html");?>'>Click here</a> to register now.</p>
      <p class="txt-cntr">Already registered? <a href='<?php echo base_url("admin/login.html");?>'>Click here</a> to login</p>
  </div>
</div>