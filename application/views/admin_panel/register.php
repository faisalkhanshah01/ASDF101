<?php
$_POST['admin_pwd']          = isset($_POST['admin_pwd'])?$_POST['admin_pwd']:'';
$_POST['admin_confirm_pwd']  = isset($_POST['admin_confirm_pwd'])?$_POST['admin_confirm_pwd']:'';
$this->load->view('admin_panel/common/header', $header_data);
?>
<div class="col-md-4 col-md-offset-4 before-login-forms" id="register">
      <h4>Create Account</h4>
      <?php echo $this->session->flashdata('msg'); ?>
      <?php $attributes = array("name" => "adminRegistrationForm");
      echo form_open_multipart("admin/register.html", $attributes);?>

      <div class="form-group">
        <!-- <label for="admin_first_name">First Name</label> -->
        <input class="form-control" name="admin_first_name" placeholder="First Name" type="text" value="<?php echo set_value('admin_first_name'); ?>" />
        <span class="text-danger"><?php echo form_error('admin_first_name'); ?></span>
      </div>

      <div class="form-group">
        <!-- <label for="admin_last_name">Last Name</label> -->
        <input class="form-control" name="admin_last_name" placeholder="Last Name" type="text" value="<?php echo set_value('admin_last_name'); ?>" />
        <span class="text-danger"><?php echo form_error('admin_last_name'); ?></span>
      </div>
      
      <div class="form-group admin_email">
        <!-- <label for="admin_email">Email</label> -->
        <input class="form-control email_status_check" name="admin_email" placeholder="Email Address" type="text" value="<?php echo set_value('admin_email'); ?>" id="admin_email" autocomplete="off" />
        <span class="text-danger"><?php echo form_error('admin_email'); ?></span>
        <span class="text-success"></span>
      </div>

      <div class="form-group">
        <!-- <label for="admin_phone">Phone</label> -->
        <input class="form-control" name="admin_phone" placeholder="Phone" type="text" value="<?php echo set_value('admin_phone'); ?>" />
        <span class="text-danger"><?php echo form_error('admin_phone'); ?></span>
      </div>

      <div class="form-group">
        <!-- <label for="admin_pwd">Password</label> -->
        <input class="form-control" name="admin_pwd" placeholder="Password" type="password" value="<?php echo htmlspecialchars($_POST['admin_pwd']); ?>"/>
        <span class="text-danger"><?php echo form_error('admin_pwd'); ?></span>
      </div>

      <div class="form-group">
        <!-- <label for="admin_confirm_pwd">Confirm Password</label> -->
        <input class="form-control" name="admin_confirm_pwd" placeholder="Confirm Password" type="password" value="<?php echo htmlspecialchars($_POST['admin_confirm_pwd']); ?>"/>
        <span class="text-danger"><?php echo form_error('admin_confirm_pwd'); ?></span>
      </div>

      <div class="form-group">
        <button name="submit" type="submit" class="btn btn-default btn-block">Register</button>
      </div>
      <?php echo form_close(); ?>
      <p class="txt-cntr">Already registered? <a href='<?php echo base_url("admin/login.html");?>'>Click here</a> to login</p>
      <p class="txt-cntr"><a href='<?php echo base_url("admin/forgot_password.html");?>'>Lost your password?</a></p>
</div>

<?php
  $this->load->view('admin_panel/common/footer');
?>