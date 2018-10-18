<?php
  $_POST['admin_email']        = isset($_POST['admin_email'])?$_POST['admin_email']:'';
  $_POST['admin_pwd']          = isset($_POST['admin_pwd'])?$_POST['admin_pwd']:'';
?>
<div class="col-md-4 col-md-offset-4 before-login-forms" id="login">
      <h4>Admin Login</h4>
      <?php echo $this->session->flashdata('msg'); ?>
      <?php $attributes = array("name" => "adminLoginForm");
      echo form_open_multipart("auth/login", $attributes);?>
     
      <div class="form-group">
        <!-- <label for="admin_email">Email</label> -->
        <input class="form-control" name="login_identity" placeholder="Email Address" type="text" value="<?php echo set_value('login_identity'); ?>" />
        <span class="text-danger"><?php echo form_error('login_identity'); ?></span>
      </div>

      <div class="form-group">
        <!-- <label for="admin_pwd">Password</label> -->
        <input class="form-control" name="login_password" placeholder="Password" type="password" value="<?php echo htmlspecialchars($_POST['admin_pwd']); ?>"/>
        <span class="text-danger"><?php echo form_error('login_password'); ?></span>
      </div>

      <div class="form-group">
        <button name="login_user" type="submit" value="submit" class="btn btn-default btn-block">Login</button>
      </div>
      <?php echo form_close(); ?>
      <p class="txt-cntr">Not registered yet! <a href='<?php echo base_url("admin/register.html");?>'>Click here</a> to register now.</p>
      <p class="txt-cntr"><a href='<?php echo base_url("admin/forgot_password.html");?>'>Lost your password?</a></p>
  </div>
</div>

