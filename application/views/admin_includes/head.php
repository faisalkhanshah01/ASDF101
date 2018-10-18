<?php
?>
	<div class="row">
		<nav role="navigation" class="navbar">
		
			<div class="navbar-header navbar-default">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			
			<div class="collapse navbar-collapse paddingLeft" id="myNavbar">
				<ul class="nav nav-justified">
								
                                        <li>
                                           <a href="<?php echo $base_url;?>auth/"  ><?php  echo "Dashboard";  ?></a>
                                        </li>
                                        
					<?php if($this->flexi_auth->is_admin()) { ?>
					<li class="dropdown">
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"  > <?php  echo "Registration";  ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li>
                                                              <a href="<?php echo $base_url;?>auth_admin/new_customer_invitation"  ><?php echo "New Customer Invite";  ?></a>
							</li>
							<li>
                                                             <a href="<?php echo $base_url;?>auth_admin/manage_client_accounts/"  ><?php  echo "Registered Clients";   ?></a>
							</li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"  ><?php if( $this->data['lang']['admin']['description'] !='' ){ echo $this->data['lang']['admin']['description']; }else{ echo "Admin"; }  ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="dropdown-header"><?php if( $this->data['lang']['select_feature_to_manage']['description'] !='' ){ echo $this->data['lang']['select_feature_to_manage']['description']; }else{ echo "Select Feature to Manage"; }  ?></li>
							
						<?php /*	if ( $this->flexi_auth->is_privileged('View Users') ||  $this->flexi_auth->is_privileged('Update Users') ||  $this->flexi_auth->is_privileged('Delete Users')) { ?>
							<li>
								<a href="<?php echo $base_url;?>auth_admin/manage_user_accounts"  ><?php if( $this->data['lang']['user_accounts']['description'] !='' ){ echo $this->data['lang']['user_accounts']['description']; }else{ echo "User Accounts"; }  ?></a>
							</li>
						<?php }*/ ?>
						<?php 	 if ( $this->flexi_auth->is_privileged('Update User Groups') ||  $this->flexi_auth->is_privileged('View User Groups') ||  $this->flexi_auth->is_privileged('Insert User Groups') ||  $this->flexi_auth->is_privileged('Delete User Groups') ) { ?>
							<li>
<!--								<a href="<?php //echo $base_url;?>auth_admin/manage_user_groups"  ><?php //echo "User Groups";  ?></a>			-->
							</li>
						<?php } ?>
                                                        
						<?php /*	 if ( $this->flexi_auth->is_privileged('View Privileges') ||  $this->flexi_auth->is_privileged('Insert Privileges') ||  $this->flexi_auth->is_privileged('Update Privileges') ||  $this->flexi_auth->is_privileged('Delete Privileges') ) { ?>
							<li>
								<a href="<?php echo $base_url;?>auth_admin/manage_privileges"  ><?php echo "User Privileges"; ?></a>			
							</li>
						<?php }*/ ?>

							<li>
								<a href="<?php echo $base_url;?>auth_admin/manage_language"><?php  echo "Manage Language";  ?></a>
							</li>
							
						</ul>		
					</li>
					<?php 
					
					} // end: is_Admin ?>

					
					<li class="dropdown">
						<!--<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">My Profile <b class="caret"></b></a>-->
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"  > ( <?php  echo $this->flexi_auth->get_user_identity();  ?> ) <b class="caret"></b></a>	
							<ul class="dropdown-menu">
								<li class="dropdown-header"><?php  "Select";  ?></li>
								<li>
									<a href="<?php echo $base_url;?>auth_public/update_account"  ><?php  echo "Account Details";  ?></a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>auth_public/change_password"  ><?php  echo "Change Password";  ?></a>
								</li>
								
								 <?php if (! $this->flexi_auth->is_logged_in()) { ?>
								<li>
									<a href="<?php echo $base_url;?>auth/register_account"  ><?php if( $this->data['lang']['register']['description'] !='' ){ echo $this->data['lang']['register']['description']; }else{ echo "Register"; }  ?></a>
								</li>
							<?php } else { ?>

                                                        <li>
                                                           <a href="<?php echo $base_url;?>auth/logout"  ><?php if( $this->data['lang']['logout']['description'] !='' ){ echo $this->data['lang']['logout']['description']; }else{ echo "Logout"; }  ?></a>
                                                        </li>		
							<?php } ?>	
								
							</ul>		
					</li>
				</ul>
			</div>
		
		</nav>
	</div>