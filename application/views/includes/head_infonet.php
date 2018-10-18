<?php
	$a = $_SESSION['flexi_auth']['group'];
	foreach($a as $k=>$v){
		$name = $v;
		$group_id = $k;
	}
	
	if (strpos($name, ' ') !== true) {
		$name = explode(' ',$name);
		$name = $name[0];
	}
?><div class="row">
		<nav role="navigation" class="navbar" >
			<div class="navbar-header navbar-default">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			
			<div class="collapse navbar-collapse paddingLeft" id="myNavbar" data-spy="affix" data-offset-top="147">
				<ul class="nav nav-justified">
					<?php	if ($this->flexi_auth->is_admin()) {
								if($group_id =='8'){ ?>
									<li id="dashboard">
										<a href="<?php echo $base_url;?>Clientuser_dashboard/">Dashboard</a>
									</li>
						<?php 	}elseif($group_id =='10'){ ?>
									<li id="dashboard">
										<a href="<?php echo $base_url;?>infonet_details/about_us">KARAM Infonet</a>
									</li>
						<?php	}elseif($group_id =='11'){ ?>
									<li id="dashboard">
										<a href="<?php echo $base_url;?>infonet_details/about_us">KARAM Infonet</a>
									</li>
						<?php	}else{ ?>
									<li id="dashboard">
										<a href="<?php echo $base_url;?>auth_admin/">Dashboard</a>
									</li>
						<?php	} 
						} else { ?>
							<li>
								<a href="<?php echo $base_url;?>auth_public/">Dashboard</a>
							</li>
					<?php } ?>
				
					<?php	if ($this->flexi_auth->is_admin()) { ?>
						<?php if ( $group_id == 2 || $group_id == 3){ ?>
						<li class="dropdown">
							<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Manage Kare <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo $base_url;?>subassets_kare/inspection_result_list">Inspection/Result Type</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>subassets_kare/inspection_observation_list">Action Proposed</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>subassets_kare/sub_assets_list">Manage Sub-Assets</a>
								</li> 
								<li>
									<a href="<?php echo $base_url;?>manage_kare/assets_list">Manage Assets</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>manage_kare/asset_series_list">Manage Assets Series</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>client_kare/client_view">Manage Client</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>manage_kare/mdata_inspection">Master Data Inspection</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>sms_controller/sms_component_view">SMS Component</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>siteId_kare/siteId_master">Manage Site ID</a>
								</li>
									<?php if ( $this->flexi_auth->is_privileged('View Inspector Form')){ ?>
								<li>
									<a href="<?php echo $base_url;?>manage_kare/assignInspector_list">Assign Inspector</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>assign_client_controller/assign_client">Assign Client</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>productedit_controller/product_edit">Product Edit</a>
								</li>
							<?php } ?>

							</ul>
						</li>
						<?php } ?>
					
					
					<?php if ( $group_id == 11 || $group_id == 10){ ?>
					<!--<li class="dropdown">
						<a href="<?php echo $base_url;?>Client_view/">Product Portfolio</a>
					</li>--->
						<li class="dropdown">
							<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Product Portfolio <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li class="dropdown">
									<a href="<?php echo $base_url;?>Client_view/">Client View</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>infonet_left_menu/menus_category">Infonet Product</a>
								</li>
							</ul>
						</li>
					<?php } ?>
					
					
					<?php if ( $group_id == 11 || $group_id == 10){ ?>
					<li class="dropdown">
						<a href="<?php echo $base_url;?>infonet_details/data_on_demand">Data on Demand</a>						
					</li>
						<?php if ($group_id == 10){ ?>
						<li class="dropdown">
							<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Knowledge Database<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<?php //if ( $this->flexi_auth->is_privileged('Manage Infonet')){ ?>
									<li>
										<a href="<?php echo $base_url;?>category_controller/manage_category">Manage Category</a>
									</li>
									<li>
										<a href="<?php echo $base_url;?>productCategory_controller/manage_product_categogy">Manage Product</a>
									</li>
									<li>
										<a href="<?php echo $base_url;?>specification">Specifications</a>
									</li>
									<li>
										<a href="<?php echo $base_url;?>specification/multi_uploads">Multi Uploads</a>
									</li>
								
								<?php //} ?>
								
							</ul>
						</li>
						
						<li class="dropdown">
							<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Admin Priviledges <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li>
									<a href="<?php echo $base_url;?>infonet_details/list_user_status/active">Active User</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>infonet_details/list_user_status/inactive">Inactive User</a>
								</li>
							</ul>
						</li>
						<?php }  ?>
					
					<?php } ?>

					

					<?php if ( $group_id == 3 || $group_id == 2){ ?>
					<li class="dropdown">
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Admin Management <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="dropdown-header">Select Feature to Manage</li>
							
						<?php 	if ( $this->flexi_auth->is_privileged('View Users') ||  $this->flexi_auth->is_privileged('Update Users') ||  $this->flexi_auth->is_privileged('Delete Users')) { ?>
							<li>
								<a href="<?php echo $base_url;?>auth_admin/manage_user_accounts">User Accounts</a>
							</li>
						<?php } ?>
						<?php 	 if ( $this->flexi_auth->is_privileged('Update User Groups') ||  $this->flexi_auth->is_privileged('View User Groups') ||  $this->flexi_auth->is_privileged('Insert User Groups') ||  $this->flexi_auth->is_privileged('Delete User Groups') ) { ?>
							<li>
								<a href="<?php echo $base_url;?>auth_admin/manage_user_groups">User Groups</a>			
							</li>
						<?php } ?>
						<?php 	 if ( $this->flexi_auth->is_privileged('View Privileges') ||  $this->flexi_auth->is_privileged('Insert Privileges') ||  $this->flexi_auth->is_privileged('Update Privileges') ||  $this->flexi_auth->is_privileged('Delete Privileges') ) { ?>
							<li>
								<a href="<?php echo $base_url;?>auth_admin/manage_privileges">User Privileges</a>			
							</li>
						<?php } ?>
							<li>
								<a href="<?php echo $base_url;?>auth_admin/list_user_status/active">Active Users</a>
							</li>	
							<li>
								<a href="<?php echo $base_url;?>auth_admin/list_user_status/inactive">Inactive Users</a>
							</li>	
							<li>
								<a href="<?php echo $base_url;?>auth_admin/delete_unactivated_users">Unactivated Users</a>
							</li>	
							<li>
								<a href="<?php echo $base_url;?>auth_admin/failed_login_users">Failed Logins</a>			
							</li>
							
						</ul>		
					</li>
					<?php }
					
					} // end: is_Admin ?>
					
					
					
					
					
					<li class="dropdown">
						<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">My Profile <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header">Select</li>
								<li>
									<a href="<?php echo $base_url;?>auth_public/update_account">Account Details</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>auth_public/update_email">Email Address</a>
								</li>
								<li>
									<a href="<?php echo $base_url;?>auth_public/change_password">Change Password</a>
								</li>
								<!--
								<li>
									<a href="<?php //echo $base_url;?>auth_public/manage_address_book">Address Book</a>
								</li>-->
								
								 <?php if (! $this->flexi_auth->is_logged_in()) { ?>
								<li>
									<a href="<?php echo $base_url;?>auth/register_account">Register</a>
								</li>
							<?php } else { ?>
								<li>
									<a href="<?php echo $base_url;?>Infonet/logout">Logout</a>
								</li>
							<?php } ?>	
								
							</ul>		
					</li>
					
				</ul>
			</div>
		</nav>
	</div>
	