<!DOCTYPE html>
<html>
	<body>
		<h3>
		<div id="main" style="padding:20px; background-color:#eee; width:750px; margin: 0 auto; color:#666 !important; ">
			<div id="content" style="color:#666 !important; font-size:14px;">
				Dear <strong><?php echo $customer_contact_person; ?></strong>,<br><br>

				Greetings from Arresto Solutions.<br><br><br>

				Your account has been activated.
			</div>

			
			<div if="footer">
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin-top:50px; color:#666; ">Thanking You,</p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; "><?php $this->session->userdata('admin_first_name')."".$this->session->userdata('admin_first_name'); ?></p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; ">Arresto Solutions</p>
				<p style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; color:#666; ">Arresto Solutions</p>
				<img src="http://www.arresto.com/assets/images/system/arresto-logo.jpg" alt="logo" width="150">
			</div>
		</div>
		</h3>
	</body>
</html>