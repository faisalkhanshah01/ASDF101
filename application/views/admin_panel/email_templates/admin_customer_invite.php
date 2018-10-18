<!DOCTYPE html>
<html>
	<body>
		<h3>
		<div id="main" style="padding:20px; background-color:#eee; width:750px; margin: 0 auto; color:#666 !important; ">
                    <div><img src="http://arresto.in/connect/assets/images/system/arresto-logo.jpg" alt="logo" width="150">
                    </div>
			<div id="content" style="color:#666 !important; font-size:14px;">
				<?php echo $message; ?>
			</div>

			<div id="content" style="color:#666 !important; font-size:14px;">
                            <p>Please click below link to register - </p>
                            <a href='http://www.arresto.in/connect/admin/auth/register_account?key=<?php echo $invite_code; ?>&email=<?php echo $invite_email; ?>'>Register</a>
			</div>
			
			<div if="footer">
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top:50px; color:#666; ">Thanking You,</p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; "><?php $this->session->userdata('admin_first_name')."".$this->session->userdata('admin_first_name'); ?></p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; ">Arresto Solutions</p>
				
			</div>
		</div>
		</h3>
	</body>
</html>