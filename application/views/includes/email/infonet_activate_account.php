
<html>
<body>
	
	<div id="main">
		<div style="font-size:13px;  margin-bottom: 0;">
			Hello <strong><?php //echo ucwords($contain['user_name']); ?></strong>,
		</div>
		  <p>Thank you for registering yourself into KARAM Infonet for accessing product technical information.</p>
			<p style="">Before proceeding, you need to complete a brief account verification process.</p>

			<p><?php //echo anchor('Infonet/activate_account/'. $contain['user_id'] .'/'. $contain['activation_token'], '<button type="button" title="Click This Button To Activate Your Account" style="color: #16a9cd;" >Click here to verify your Email ID.</button>') ;?></p>
		
		<p>For Security reasons, this link will be active for 24 hrs.</p>
		<p >On successfully completing this verification, your KARAM Infonet account will be activated and intimated through an email.</p>
		<p>In case you fail to complete your account verification process within 24 hrs. Follow the below steps to reset your password:</p>
		<ul style="list-style-type: none;">
			<li style="">1) Go to <a href="http://karam.in/kare/Infonet" style="color: #16a9cd; text-decoration: none;">Infonet</a></li>
			<li style="">2) Click on received link for completing verification process.</li>
		</ul>
		<p >Thank you once again for registering yourself into KARAM Infonet.</p>
		<p style="font-size:13px;  margin-bottom: 0;"><strong>Team Infonet</strong></p>
		<a href="#" style="font-size:13px; color: #16a9cd; text-decoration: none;">Karam.in/kare/Infonet</a>
        <p style="margin:5px 0 0;"><img src="<?php echo $includes_dir ?>images/email_logo.png"></p>
		<hr style="border-bottom: 3px solid #000000; margin-top: 15px; margin-bottom: 2px;">
		<address style="font-size:13px;">This is an auto-generated email. Do not reply to this email.</address>
	</div>
	
</body>
</html>