<?php
$this->load->view('admin_panel/common/header', $header_data);
?>
<div class="col-md-10 col-md-offset-1 after-login-forms" id="admin-dashboard">
	<div class="col-md-12 form">
		<div class="row">
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
		<div class="row client-list">
			<h4>Arresto Clients/Customer Accounts</h4>
			<table class="table table-striped table-bordered" id="client-search-table">
				<thead>
					<tr>
						<th>Sr. No.</th>
						<th>Customer Name/Email</th>
						<th>Company Name </th>
						<th>Address</th>
						<th>City</th>
						<th>Country</th>
						<th>No. of License</th>
						<th>License No.</th>
						<th>License Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
                                <?php
                                  $count=0;
                                  foreach($clients as $client){
                                  $count++;
                                 ?>
					<tr>
						<td><?php echo $count; ?></td>
						<td><?php echo $client['uacc_username']."<br/>". $client['uacc_email']; ?></td>
						<td><?php echo $client['customer_company_name']; ?></td>
						<td><?php echo $client['cutomer_address']; ?></td>
						<td><?php echo $client['customer_city']; ?></td>
						<td><?php echo $client['customer_country']; ?></td>
						<td><?php echo $client['customer_licence_no ']; ?></td>
						<td><?php echo $client['customer_licence_count']; ?></td>
						<td><?php echo $client['cutomer_licence_status']; ?></td>
						<td><button class="btn btn-success">Activate</button></td>
					</tr>
			<?php }?>
					<tr>
						<td>2</td>
						<td>Subham Agarwal</td>
						<td>My Company</td>
						<td>Vivekanandpuri</td>
						<td>New Delhi</td>
						<td>India</td>
						<td>3</td>
						<td>1AF234</td>
						<td>Inactive</td>
						<td><button class="btn btn-danger">De-activate</button></td>
					</tr>
		      </tbody>
			</table>
		</div>
	</div>
</div>
 <?php 
 $this->load->view('admin_panel/common/footer_after_login');
?>