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
			<legend class="home-heading">Manage Privileges</legend>
			
			<div class="col-md-offset-10 col-md-2">
				<?php $disable = (! $this->flexi_auth->is_privileged('Insert Privileges')) ? 'disabled="disabled"' : NULL;?>
				<a href="<?php echo $base_url;?>auth_admin/insert_privilege" class="btn btn-info" <?php echo $disable; ?>>Insert New Privilege</a>
				<br>
				<br>
			</div>

				<?php echo form_open(current_url()); ?>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-bordered simple">
								<thead>
									<tr>
										<th title="The name of the privilege.">
											Privilege Name
										</th>
										<th title="A short description of the purpose of the privilege.">
											Description
										</th>
										<th title="If checked, the row will be deleted upon the form being updated.">
											Delete
										</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($privileges as $privilege) { ?>
									<tr>
										<td>
											<a href="<?php echo $base_url;?>auth_admin/update_privilege/<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>">
												<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'name')];?>
											</a>
										</td>
										<td><?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'description')];?></td>
										<td align="center">
                                                                                   
										<?php if ($this->flexi_auth->is_privileged('Delete Users') && $privilege['upriv_client_fk']!=0) { ?>
											<input type="checkbox" name="delete_privilege[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>]" value="1"/>
										<?php } else { ?>
											<input type="checkbox" disabled="disabled"/>
											<small> System default (NA) <!--Privileged--></small>
											<input type="hidden" name="delete_privilege[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')];?>]" value="0"/>
										<?php } ?>
										</td>
									</tr>
								<?php } ?>
								</tbody>
								<tfoot>
									<td colspan="3">
										<?php $disable = (! $this->flexi_auth->is_privileged('Update Privileges') && ! $this->flexi_auth->is_privileged('Delete Privileges')) ? 'disabled="disabled"' : NULL;?>
										<input type="submit" name="submit" value="Delete Checked Privileges" class=" btn btn-primary" <?php echo $disable; ?>/>
									</td>
								</tfoot>
							</table>
						</div>
					</div>
				<?php echo form_close();?>
		</div>
	</div> <!-- end of row -->
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>