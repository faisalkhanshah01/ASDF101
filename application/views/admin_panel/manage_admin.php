<?php
	$CI =& get_instance();
	$CI->load->model('Base_Model');
?>

<div class="col-md-10 col-md-offset-1 after-login-forms" id="admin-dashboard">

	<div class="col-md-12 form">
		<div class="row">
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
		<div class="row client-list">
			<h4>Admin List</h4>			
			<table class="table table-striped table-bordered" id="client-search-table">
				<thead>
					<tr>
						<th>Sr. No.</th>
						<th>Name</th>
						<th>Email</th>
						<th>Current Role</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php if(isset($admin_data)) { ?>
						<?php foreach($admin_data as $value) { ?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $value['admin_first_name']." ".$value['admin_last_name']; ?></td>
								<td><?php echo $value['admin_email']; ?></td>
								<td><?php echo $value['account_role']; ?></td>
								<td>
									<?php if($value['admin_account_type'] == 2) { ?>
										-
									<?php } else {
											$admin_id = $CI->Base_Model->generate_num_token().$value['admin_id'].$CI->Base_Model->generate_num_token();
											$admin_id = $CI->Base_Model->encrypt_decrypt_string($admin_id, 'e');
											$path = 'admin/update_admin_role.html?data='.$admin_id;
										?>
										<a href="<?php echo base_url($path);?>" class="btn btn-success">Make Master Admin</a>
									<?php } ?>
								</td>
							</tr>
							<?php $i++; ?>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td colspan="6">No Data Available!!!</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

</div>