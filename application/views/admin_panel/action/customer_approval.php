<?php
	$CI =& get_instance();
	$CI->load->model('Base_Model');
?>

<div class="col-md-10 col-md-offset-1 after-login-forms" id="admin-dashboard">

	<div class="col-md-12 form">
		<div class="row">
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
		<div class="row client-status-search">
			<h4>Search Customer</h4>
			<form action='<?php echo base_url("admin/new_customer_approval.html"); ?>' method="get" name="clientSearchForm" class="form-horizontal customer-status-search">
				<div class="form-group">
					<input class="form-control" name="customer_company_name" placeholder="Customer Company Name" type="text" value="<?php if(isset($_GET['customer_company_name'])) {echo $_GET['customer_company_name'];}?>" />
					<span class="text-danger"><?php echo form_error('customer_company_name'); ?></span>
				</div>
				<div class="form-group check-box-cont">
					<label class="checkbox-cont">Unregistered Customers Only
						<input type="checkbox" name="unregistered_customer" value="1" <?php if(isset($_GET['unregistered_customer'])) {echo "checked";}?>>
						<span class="checkmark"></span>
					</label>
				</div>
				<div class="form-group">
					<button name="search_customer" type="submit" class="btn btn-default btn-block">Search</button>
				</div>

			</form>
		</div>
		<?php //if(isset($_GET['search_customer']) && ((isset($_GET['customer_company_name'])) || (isset($_GET['unregistered_customer'])))){  ?>
		<?php if(isset($_GET['search_customer'])) { ?>
			<div class="row client-list">
				<h4>User List</h4>			
				<table class="table table-striped table-bordered" id="client-search-table">
					<thead>
						<tr>
							<th>Sr. No.</th>
							<th>Company Name</th>
							<th>Address</th>
							<th>City</th>
							<th>Country</th>
							<th>Registration Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $customer_data = array_reverse($customer_data); ?>
						<?php $i = 1; ?>
						<?php foreach($customer_data as $value_customer) { ?>
						<?php //print_r($value_customer); die(); ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $value_customer['customer_company_name']; ?></td>
							<td><?php echo $value_customer['customer_address']; ?></td>
							<td><?php echo $value_customer['customer_city']; ?></td>
							<td><?php echo $value_customer['customer_country']; ?></td>
							<td>
								<?php if($value_customer['activation_flag']) { ?>
									Active
								<?php } else { ?>
									Inactive
								<?php } ?>
							</td>
							<td>
								<?php
									$customer_id 	= $CI->Base_Model->generate_num_token().$value_customer['customer_id'].$CI->Base_Model->generate_num_token();
									$customer_id 	= $CI->Base_Model->encrypt_decrypt_string($customer_id, 'e');
									$path 			= 'admin/update_customer_status.html?data='.$customer_id; 
								?>
								<?php if($value_customer['activation_flag']) { ?>
									User Active
								<?php } else { ?>
									<a href="<?php echo base_url($path);?>" class="btn btn-success">Activate</a>
								<?php } ?>
							</td>
						</tr>
						<?php $i++; ?>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } ?>
	</div>

</div>