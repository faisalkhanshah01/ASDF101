<html>
<body>
	<div id="main">
		<div>
			Hello <strong><?php echo $full_name;?></strong>,
		</div>
		<div id="content">
			<p>Welcome to Karam Industries.</p>
			<p>You have been assigned as <strong><?php echo $groupName;?></strong> in our website.</p>
			<p>Your Details are as follows.</p>
			<p>
				<table>
					<tr>
						<td>Email ID</td>
						<td> : </td>
						<td><strong><?php echo $email; ?></strong></td>
					<tr>
					<tr>
						<td>Username</td>
						<td> : </td>
						<td><strong><?php echo $username; ?></strong></td>
					<tr>
					<tr>
						<td>Group Name</td>
						<td> : </td>
						<td><strong><?php echo $groupName; ?></strong></td>
					<tr>
				</table>
				</strong>
			</p>
			<br />
			<p>You can now login to our website with your password using the following link: </p>
			<div id="btn">
				<?php if($groupID =='11'){ ?>
						<p><?php echo anchor('Infonet/','<button type="button" style="color: #ffffff; background-color:#BD9D1B; border:2px solid #F2E6DF" >Click To Login</button>') ;?></p>
				<?php }else{ ?>
					<p><?php echo anchor('auth/','<button type="button" style="color: #ffffff; background-color:#BD9D1B; border:2px solid #F2E6DF" >Click To Login</button>') ;?></p>
				<?php } ?>
			</div>
			
			<div><p>If you didn't ask for this email, you can safely ignore it.</p></div>
		</div>
		<div if="footer">
			<p>Best Regards,</p>
			</p>Karam Team</p>
			<p><img src="http://karam.in/images/logo.png" alt="KARAM Industries" /></p>
		</div>
	</div>
</body>
</html>