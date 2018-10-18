<!--<html>
<body>
	<h3>
	<div id="main">
		<div>
			Dear <?php //echo $identity;?>,
		</div>
		<div id="content">
		<p>The email address associated with your Infonet account is not confirmed. </p>
		<p>To confirm your email address, please click the following link: </p>
		</div>
		<div id="btn">
			
			<p><?php //echo anchor('Infonet/activate_account/'. $user_id .'/'. $activation_token, '<button type="button" title="Click This Button To Activate Your Account" style="color: #ffffff; background-color:#BD9D1B; border:2px solid #F2E6DF" >Activate Your Account</button>') ;?></p>
		</div>
		
		<div><p>If you didn't ask for this email, you can safely ignore it.</p></div>
		
		<div if="footer">
			<p>Best Regards,</p>
			</p>Infonet Team</p>
			<p><img src="http://karam.in/images/logo.png" alt="KARAM Industries" /></p>
		</div>
	</div>
	</h3>
</body>
</html>-->


<html>
<body>
	
	<div id="main">
		<div style="font-size:13px;  margin-bottom: 0;">
			Hello <strong><?php echo ucwords($user_name); ?></strong>,
		</div>
		  <p>Thank you for registering yourself into KARAM Infonet for accessing product technical information.</p>
			<p style="">Before proceeding, you need to complete a brief account verification process.</p>
			<p><?php echo anchor('Infonet/activate_account/'. $user_id .'/'. $activation_token, '<button type="button" title="Click This Button To Activate Your Account" style="color: #16a9cd;" >Click here to verify your Email ID.</button>') ;?></p>
		
		<p>For Security reasons, this link will be active for 24 hrs.</p>
		<p >On successfully completing this verification, your KARAM Infonet account will be activated for usages.</p>
		<p>In case you fail to complete your account verification process within 24 hrs. Follow the below steps to re-verify your account:</p>
		<ul style="list-style-type: none;">
			<li style="">1) Go to <a href="http://karam.in/kare/Infonet" style="color: #16a9cd; text-decoration: none;">Infonet</a></li>
			<li style="">2) Click on Resend Account Activation Token for completing verification process.</li>
		</ul>
		<p >Thank you once again for registering yourself into KARAM Infonet.</p>
		<p style="font-size:13px;  margin-bottom: 0;"><strong>Team Infonet</strong></p>
		<a href="#" style="font-size:13px; color: #16a9cd; text-decoration: none;">Karam.in/kare/Infonet</a>
		<hr style="border-bottom: 3px solid #000000; margin-top: 15px; margin-bottom: 2px;">
		<address style="font-size:13px;">This is an auto-generated email. Do not reply to this email.</address>
	</div>
	
</body>
</html>