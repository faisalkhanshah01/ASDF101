
		<nav>
			<ul class="nav nav-justified">
				<li>
					<a href="<?php echo $base_url;?>auth_admin">Dashboard</a>
				</li>
			<?php if (! $this->flexi_auth->is_logged_in_via_password()) { ?>
				<li>
					<a href="<?php echo $base_url;?>auth"><?php echo ($this->flexi_auth->is_logged_in()) ? 'Login via Password' : 'Login';?></a>
				</li>
				<!--<li>
					<a href="<?php echo $base_url;?>auth/login_via_ajax">Login via Ajax</a>
				</li>-->
			<?php } ?>

			<!--
				<li>
					<a href="<?php echo $base_url;?>auth_lite/privilege_examples">Privilege Examples</a>
				</li>
				
				<li class="dropdown">
					<a href="<?php echo $base_url;?>auth_public/" class="dropdown-toggle" data-toggle="dropdown">Public Dashboard</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo $base_url;?>auth_public/">Public Dashboard</a>
						</li>
						<li class="header">Select Feature to Manage</li>
						<li>
							<a href="<?php echo $base_url;?>auth_public/update_account">Update Account Details</a>
						</li>
						<li>
							<a href="<?php echo $base_url;?>auth_public/update_email">Update Email Address</a>
						</li>
						<li>
							<a href="<?php echo $base_url;?>auth_public/change_password">Update Password</a>
						</li>
						<li>
							<a href="<?php echo $base_url;?>auth_public/manage_address_book">Manage Address Book</a>
						</li>
					</ul>		
				</li>
				-->
				<li class="dropdown">
					<!--<a href="<?php echo $base_url;?>auth_admin/" class="dropdown-toggle" data-toggle="dropdown">Admin Dashboard <b class="caret"></b></a>-->
					<a href="<?php echo $base_url;?>auth_admin/" class="dropdown-toggle" data-toggle="dropdown">Admin Dashboard <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo $base_url;?>auth_admin/">Admin Dashboard</a>
						</li>
						<li class="dropdown-header">Select Feature to Manage</li>
						<li>
							<a href="<?php echo $base_url;?>auth_admin/manage_user_accounts">Manage User Accounts</a>
						</li>
						<li>
							<a href="<?php echo $base_url;?>auth_admin/manage_user_groups">Manage User Groups</a>			
						</li>
						<li>
							<a href="<?php echo $base_url;?>auth_admin/manage_privileges">Manage User Privileges</a>			
						</li>
						<li>
							<a href="<?php echo $base_url;?>auth_admin/list_user_status/active">List Active Users</a>
						</li>	
						<li>
							<a href="<?php echo $base_url;?>auth_admin/list_user_status/inactive">List Inactive Users</a>
						</li>	
						<li>
							<a href="<?php echo $base_url;?>auth_admin/delete_unactivated_users">List Unactivated Users</a>
						</li>	
						<li>
							<a href="<?php echo $base_url;?>auth_admin/failed_login_users">List Failed Logins</a>			
						</li>	
					</ul>		
				</li>
			<?php if (! $this->flexi_auth->is_logged_in()) { ?>
				<li>
					<a href="<?php echo $base_url;?>auth/register_account">Register</a>
				</li>
			<?php } else { ?>
				<li>
					<a href="<?php echo $base_url;?>auth/logout">Logout</a>
				</li>
			<?php } ?>				
			<!--	<li>
					<a href="<?php echo $base_url;?>auth_lite/lite_library">Lite Library</a>
				</li> -->
			</ul>
		</nav>
	</div>