<?php $this->load->view('includes/header'); ?>
	<?php //$this->load->view('includes/head'); ?> 
	<?php
		$groupId = $_SESSION['flexi_auth']['group'];
		foreach($groupId as $k=>$v){
			$name = $v;
			$groupID = $k;
		}
		
	?>
	<?php 	if ( $groupID == 11 || $groupID == 10){
				$this->load->view('includes/head_infonet');
			}else{ 
				$this->load->view('includes/head'); 
			}
	?>
	<div id="global_searchAllView">
		<div class="row">
			<div class="col-md-12">
				<div class="row" class="msg-display">
					<div class="col-md-12">
						<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
							<p>
						<?php	echo $this->session->flashdata('msg'); 
							if(isset($msg)) echo $msg;
							echo validation_errors(); ?>
							</p>
						<?php } ?>
					</div>
				</div>
				<div class="content_wrap intro_bg">
					<div class="content clearfix">
						<div class="col100">
							<legend class="home-heading"><?php if( $lang["failed_login_users"]['description'] !='' ){ echo $lang["failed_login_users"]['description']; }else{ echo 'Failed Login Users'; }  ?></legend>
						<?php if (isset($status) && $status == 'failed_login_users') { ?>
							<p><?php if( $lang["this_page_display_all_user_accounts_that_have_a_high_number_of_failed_login_attempts_since_the_users_last_successful_log"]['description'] !='' ){ echo $lang["this_page_display_all_user_accounts_that_have_a_high_number_of_failed_login_attempts_since_the_users_last_successful_log"]['description']; }else{ echo 'This page display all user accounts that have a high number of failed login attempts since the users last successful login. Such data could be used to highlight potential brute force hacking attempts on user accounts.'; }  ?></p>
						<?php } else { ?>
							<?php if (isset($status) && $status == 'active_users') { ?>
								<p><?php if( $lang["this_page_display_all_users_that_have_activated_their_account_since_registration"]['description'] !='' ){ echo $lang["this_page_display_all_users_that_have_activated_their_account_since_registration"]['description']; }else{ echo 'This page display all users that have activated their account since registration.'; }  ?></p>
							<?php } else { ?>
								<p><?php if( $lang["this_page_display_all_users_that_have_not_activated_their_account_since_registration"]['description'] !='' ){ echo $lang["this_page_display_all_users_that_have_not_activated_their_account_since_registration"]['description']; }else{ echo 'This page display all users that have not activated their account since registration'; }  ?></p>
							<?php } ?>
						<?php } ?>
						</div>		
					</div>
				</div>

					<?php echo form_open(current_url()); ?>
						<div class="row">
							<div class="col-xs-12">
								<table class="table table-bordered shorting">
									<thead>
										<tr>
											<th><?php if( $lang["email"]['description'] !='' ){ echo $lang["email"]['description']; }else{ echo 'Email'; }  ?></th>
											<th><?php if( $lang["first_name"]['description'] !='' ){ echo $lang["first_name"]['description']; }else{ echo 'First Name'; }  ?></th>
											<th><?php if( $lang["last_name"]['description'] !='' ){ echo $lang["last_name"]['description']; }else{ echo 'Last Name'; }  ?></th>
											<th title="Indicates the user group the user belongs to.">
												<?php if( $lang["user_group"]['description'] !='' ){ echo $lang["user_group"]['description']; }else{ echo 'User Group'; }  ?>
											</th>
										<?php if (isset($status) && $status == 'failed_login_users') { ?>
											<th title="The number of consecutive failed login attempts made since the user last successfully logged in.">
												<?php if( $lang["failed_attempts"]['description'] !='' ){ echo $lang["failed_attempts"]['description']; }else{ echo 'Failed Attempts'; }  ?></th>
										<?php } ?>
											<th title="Indicates whether the users account is currently set as 'active'.">
												<?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo 'Status'; }  ?>
											</th>
											<?php if (isset($status) && $status == 'active_users') { ?>
											<th><?php if( $lang["last_login_date"]['description'] !='' ){ echo $lang["last_login_date"]['description']; }else{ echo 'Last login date'; }  ?></th>
											<th><?php if( $lang["last_login_time"]['description'] !='' ){ echo $lang["last_login_time"]['description']; }else{ echo 'Last Login Time'; }  ?></th>
											<?php } ?>
										</tr>
									</thead>
									<?php if (! empty($users)) { ?>
										<tbody>
										<?php foreach ($users as $user) {
											$dateString = $user['uacc_date_last_login'];
											$dateObject = new DateTime($dateString);
											$Time = $dateObject->format('h:i:s A').'</br>';
											$Date = $dateObject->format('d-M-Y').'</br>';
											
										?>
											<tr>
												<td>
													<a href="<?php echo $base_url;?>auth_admin/update_user_account/<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')];?>">
														<?php echo $user[$this->flexi_auth->db_column('user_acc', 'email')];?>
													</a>
												</td>
												<td>
													<?php echo $user['upro_first_name'];?>
												</td>
												<td>
													<?php echo $user['upro_last_name'];?>
												</td>
												<td class="align_ctr">
													<?php echo $user[$this->flexi_auth->db_column('user_group', 'name')];?>
												</td>
											<?php if (isset($status) && $status == 'failed_login_users') { ?>
												<td class="align_ctr">
													<?php echo $user[$this->flexi_auth->db_column('user_acc', 'failed_logins')];?>
												</td>
											<?php } ?>
												<td class="align_ctr">
													<?php echo ($user[$this->flexi_auth->db_column('user_acc', 'active')] == 1) ? 'Active' : 'Inactive';?>
												</td>
											<?php if (isset($status) && $status == 'active_users') { ?>
												<td class="align_ctr">
													<?php echo $Date; ?>
												</td>
												<td class="align_ctr">
													<?php echo $Time; ?>
												</td>
											<?php } ?>
											</tr>
										<?php } ?>
										</tbody>
									<?php } else { ?>
										<tbody>
											<tr>
												<td colspan="<?php echo (isset($status) && $status == 'failed_login_users') ? '6' : '5'; ?>" class="highlight_red">
													<?php if( $lang["no_users_are_available"]['description'] !='' ){ echo $lang["no_users_are_available"]['description']; }else{ echo '	No users are available.'; }  ?>
												</td>
											</tr>
										</tbody>
									<?php } ?>
								</table>
							</div>
						</div>
					<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	<div id="global_searchViewShow">
		<div class="row">
			<div class="col-md-12">
				 <div id="global_search_view"></div>
			</div>
		</div>	
	</div>		
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>