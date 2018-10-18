<?php
echo "this file";
?>

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
			<legend class="home-heading"><?php if( $lang["manage_user_groups"]['description'] !='' ){ echo $lang["manage_user_groups"]['description']; }else{ echo 'Manage User Groups'; }  ?></legend>
                        <div class="row" style="margin-bottom: 20px;">
			<div class="col-md-offset-10">
				<?php $disable = (! $this->flexi_auth->is_privileged('Insert User Groups')) ? 'disabled="disabled"' : NULL;?>
				<a href="<?php echo $base_url;?>auth_admin/insert_user_group" class="btn btn-info" <?php echo $disable; ?> > <?php if( $lang["insert_new_user_group"]['description'] !='' ){ echo $lang["insert_new_user_group"]['description']; }else{ echo 'Insert New User Group'; }  ?> </a>
				</br></br>
			</div>
                        </div>    
                        
				
				<?php echo form_open(current_url());	?>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-bordered simple">
								<thead>
									<tr>
										<th class="spacer_150 tooltip_trigger" 
											title="The user group name.">
											<?php if( $lang["group_name"]['description'] !='' ){ echo $lang["group_name"]['description']; }else{ echo 'Group Name'; }  ?>
										</th>
										<th class="tooltip_trigger" 
											title="A short description of the purpose of the user group.">
											<?php if( $lang["description"]['description'] !='' ){ echo $lang["description"]['description']; }else{ echo 'Description'; }  ?>
										</th>
<!--										<th class="spacer_100 align_ctr tooltip_trigger" 
											title="Indicates whether the group is considered an 'Admin' group.">
											<?php //if( $lang["is_admin_group"]['description'] !='' ){ echo $lang["is_admin_group"]['description']; }else{ echo 'Is Admin Group'; }  ?>
										</th>-->
<!--										<th class="spacer_100 align_ctr tooltip_trigger"
											title="Manage the access privileges of user groups.">
											<?php //if( $lang["user_group_privileges"]['description'] !='' ){ echo $lang["user_group_privileges"]['description']; }else{ echo 'User Group Privileges'; } ?>
										</th>-->
										<th class="spacer_100 align_ctr tooltip_trigger" title="If checked, the row will be deleted upon the form being updated.">
                                                                                    Action <br/>
                                                                                    (Active|Inactive|change)
										</th>
									</tr>
								</thead>
								<tbody>
                                                                    
                                                               <?php
                                                                 $client_group=$_SESSION['client']['group_id'];
                                                                   echo $this->flexi_auth->groupTableTree($client_group); 
                                                                   #die;
                                                               ?>     
 
								<?php 
                                                                $user_groups=array();
                                                                foreach ($user_groups as $key=>$group) {
                                                                    
                                                                    ?>
									<tr>
										<td>
											<a href="<?php echo $base_url;?>auth_admin/update_user_group/<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>">
												<?php echo $group[$this->flexi_auth->db_column('user_group', 'name')];?>
											</a>
										</td>
										<td><?php echo  $group[$this->flexi_auth->db_column('user_group', 'description')];?></td>
										<td class="align_ctr"><?php echo ($group[$this->flexi_auth->db_column('user_group', 'admin')] == 1) ? "Yes" : "No";?></td>
										<td class="align_ctr">
											<a href="<?php echo $base_url.'auth_admin/update_group_privileges/'.$group[$this->flexi_auth->db_column('user_group', 'id')];?>"><?php if( $lang["manage"]['description'] !='' ){ echo $lang["manage"]['description']; }else{ echo 'Manage'; } ?></a>
										</td>
										<td class="align_ctr">
										<?php if ($this->flexi_auth->is_privileged('Delete User Groups')) { ?>
											<input type="checkbox" name="delete_group[<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>]" value="1"/>
										<?php } else { ?>
											<input type="checkbox" disabled="disabled"/>
											<small><?php if( $lang["not_privileged"]['description'] !='' ){ echo $lang["not_privileged"]['description']; }else{ echo 'Not Privileged'; } ?></small>
											<input type="hidden" name="delete_group[<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')];?>]" value="0"/>
										<?php } ?>
										</td>
									</tr>
                                        
                                                                        
                                                                        
								<?php } ?>
								</tbody>
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
								<tfoot>
									<td colspan="5">
										<?php $disable = (! $this->flexi_auth->is_privileged('Update User Groups') && ! $this->flexi_auth->is_privileged('Delete User Groups')) ? 'disabled="disabled"' : NULL; ?>
										<input type="submit" name="submit" value="<?php if( $lang["delete_checked_user_groups"]['description'] !='' ){ echo $lang["delete_checked_user_groups"]['description']; }else{ echo 'Delete Checked User Groups'; } ?>" class="btn btn-primary" <?php echo $disable; ?>/>
									</td>
								</tfoot>
							</table>
						</div>
					</div>
				<?php echo form_close();?>
		</div>
	</div>
	<?php  $this->load->view('includes/footer'); ?>
<?php //$this->load->view('includes/scripts'); ?>