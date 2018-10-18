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
			
				</legend><h2>Manage Address Book</h2></legend>
				<div class="col-md-offset-10 ">
					<a href="<?php echo $base_url;?>auth_public/insert_address" class="btn btn-info">Insert New Address</a>
					</br></br>
				</div>
				
				<?php echo form_open(current_url());	?>  	
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th title="An alias to reference the address by.">
											Alias
										</th>
										<th>Recipient</th>
										<th>Company</th>
										<th>Post Code</th>
										<th title="If checked, the row will be deleted upon the form being updated.">
											Delete
										</th>	
									</tr>
								</thead>
								<?php 
									if (!empty($addresses)) {
										foreach ($addresses as $address) {
								?>
								<tbody>
									<tr>
										<td>
											<a href="<?php echo $base_url;?>auth_public/update_address/<?php echo $address['uadd_id'];?>/"><?php echo $address['uadd_alias'];?></a>
										</td>
										<td><?php echo $address['uadd_recipient'];?></td>
										<td><?php echo $address['uadd_company'];?></td>
										<td><?php echo $address['uadd_post_code'];?></td>
										<td class="align_ctr">
											<input type="checkbox" name="delete_address[<?php echo $address['uadd_id'];?>]" value="1"/>
										</td>
									</tr>
								</tbody>
								<?php } ?>
								<tfoot>
									<tr>
										<td colspan="5">
											<input type="submit" name="update_addresses" value="Delete Checked Addresses" class="btn btn-primary"/>
										</td>
									</tr>
								</tfoot>
								<?php } else { ?>
								<tbody>
									<tr>
										<td colspan="5">
											<p>There are no addresses in your address book</p>
										</td>
									</tr>
								</tbody>
								<?php } ?>
							</table>
						</div>
					</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>