<div class="col-md-4 col-md-offset-4 before-login-forms" id="login">
      <h4>Set New Password</h4>
      <?php echo $this->session->flashdata('msg'); ?>
      <?php $attributes = array("name" => "adminSetNewPasswordForm");
      echo form_open_multipart("admin/setnew_password.html", $attributes);?>
     
      <div class="form-group">
        <!-- <label for="admin_email">Email</label> -->
        <input class="form-control" name="admin_email" placeholder="Email Address" type="text" value="<?php echo $this->session->userdata('admin_email'); ?>" readonly />
        <span class="text-danger"><?php echo form_error('admin_email'); ?></span>
      </div>

      <div class="form-group">
        <!-- <label for="admin_pwd">Password</label> -->
        <input class="form-control" name="admin_pwd" placeholder="New Password" type="password" value=""/>
        <span class="text-danger"><?php echo form_error('admin_pwd'); ?></span>
      </div>

      <div class="form-group">
        <!-- <label for="admin_pwd">Password</label> -->
        <input class="form-control" name="admin_confirm_pwd" placeholder="Confirm Password" type="password" value=""/>
        <span class="text-danger"><?php echo form_error('admin_confirm_pwd'); ?></span>
      </div>

      <div class="form-group">
        <button name="submit" type="submit" class="btn btn-default btn-block">Set New password</button>
      </div>
      <?php echo form_close(); ?>
  </div>
</div>