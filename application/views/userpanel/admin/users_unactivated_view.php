<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?> 
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
						<legend class="home-heading"><?php if( $lang["user_accounts_not_activated_in_31_days"]['description'] !='' ){ echo $lang["user_accounts_not_activated_in_31_days"]['description']; }else{ echo 'User Accounts Not Activated in 31 Days.'; }  ?></legend>
						<p><?php if( $lang["the_page_display_all_accounts_that_have_not_been_activated_within_31_days_since_registation"]['description'] !='' ){ echo $lang["the_page_display_all_accounts_that_have_not_been_activated_within_31_days_since_registation"]['description']; }else{ echo 'The page display all accounts that have not been activated within 31 days since registation.'; }  ?></p>
					</div>		
				</div>
			</div>
				<?php echo form_open(current_url()); ?>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-bordered shorting" >
								<thead>
									<tr>
										<th><?php if( $lang["email"]['description'] !='' ){ echo $lang["email"]['description']; }else{ echo 'Email'; }  ?></th>
										<th><?php if( $lang["first_name"]['description'] !='' ){ echo $lang["first_name"]['description']; }else{ echo 'First Name'; }  ?></th>
										<th><?php if( $lang["last_name"]['description'] !='' ){ echo $lang["last_name"]['description']; }else{ echo 'Last Name'; }  ?></th>
										<th title="Indicates the user group the user belongs to.">
											<?php if( $lang["user_group"]['description'] !='' ){ echo $lang["user_group"]['description']; }else{ echo 'User Group'; }  ?>
										</th>
										<th title="Indicates whether the users account is currently set as 'active'.">
											<?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo 'Status'; }  ?>
										</th>
									</tr>
								</thead>
								<?php if (!empty($users)) { ?>
									<tbody>
									<?php foreach ($users as $user) { ?>
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
											<td class="align_ctr">
												<?php echo ($user[$this->flexi_auth->db_column('user_acc', 'active')] == 1) ? 'Active' : 'Inactive';?>
											</td>
										</tr>
									<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5">
												<input type="submit" name="delete_unactivated" value="Delete Listed Users" class="btn btn-primary"/>
											</td>
										</tr>
									</tfoot>
								<?php } else { ?>
									<tbody>
										<tr>
											<td colspan="5" class="highlight_red">
												<?php if( $lang["no_users_are_available"]['description'] !='' ){ echo $lang["no_users_are_available"]['description']; }else{ echo 'No users are available'; }  ?>
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
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>