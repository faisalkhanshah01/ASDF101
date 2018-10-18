<html>
<body>
	<h3>
	<div id="main">
		<div>
			Dear <?php echo $identity;?>,
		</div>
		<div id="content">
		<p>The email address associated with your Kare account is not confirmed. </p>
		<p>To confirm your email address, please click the following link: </p>
		</div>
		<div id="btn">
			
			<p><?php echo anchor('auth/activate_account/'. $user_id .'/'. $activation_token, '<button type="button" style="color: #ffffff; background-color:#BD9D1B; border:2px solid #F2E6DF" >Activate Your Account</button>') ;?></p>
		</div>
		
		<div><p>If you didn't ask for this email, you can safely ignore it.</p></div>
		
		<div if="footer">
			<p>Best Regards,</p>
			</p>Karam Team</p>
			<p><img src="http://karam.in/images/logo.png" alt="KARAM Industries" /></p>
		</div>
	</div>
	</h3>
</body>
</html>