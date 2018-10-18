<!DOCTYPE html>
<html>
	<body>
		<h3>
		<div id="main" style="padding:20px; background-color:#eee; width:750px; margin: 0 auto; color:#666 !important; ">
			<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top:50px; color:#666; ">
				Dear <span style="color:#666; text-decoration: none;"><?php echo $admin_first_name." ".$admin_last_name; ?></span>,
			</p>
			<br>
			<div id="content">
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; ">Please click the button below to reset your password.</p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; ">Please Note - Link is valid till <strong><?php echo date('d-M-Y G:i:s',$forgot_pwd_exp_time);?></strong></p>
			</div>
			<div id="btn">			
				<a href='<?php echo base_url("admin/reset_password.html?token=".$forgot_pwd_token."||".$admin_id);?>'>Reset Password</a>
			</div>
			
			<div if="footer">
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top:50px; color:#666; ">Best Regards,</p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; ">Team Arresto</p>
				<img src="http://www.arresto.com/assets/images/system/arresto-logo.jpg" alt="logo" width="150">
			</div>
		</div>
		</h3>
	</body>
</html>