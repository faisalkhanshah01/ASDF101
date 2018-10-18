<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if (!empty($this->session->flashdata('msg'))||isset($msg)) {
						echo (isset($msg))? $msg : $this->session->flashdata('msg');
					}
					if(validation_errors() !=''){
						echo '<div class="alert alert-danger capital">'.validation_errors().'</div>';
					}
				?>
			</div>

			<form class="form-horizontal" role="form" id="formone" action="<?php echo base_url().'dashboard_controller/search_history'; ?>" method="post" >
					<legend class="home-heading">History</legend>
				<div class="form-group col-md-12">
					<table id="formone" class="table table-hover table-bordered home_table" >
						<tbody>
							<tr>
								<td>From Date</td>
								<td>
									<input type="text" class="form-control date1" name="from_date" id="datepicker" />
								</td>
								<td>To Date</td>
								<td>
									<input type="text" class="form-control date1" name="to_date" id="datepicker1" />
								</td>
								<td>Client</td>
								<td>
									<select class="form-control" id="client" name="client" >
									<option value=""> - Select Client - </option>
									<?php foreach($client as $ckey=>$cval){ ?>
									<option value="<?php echo $cval; ?>"><?php echo $cval; ?></option>
									<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>District</td>
								<td>
									<select class="form-control" id="district" name="district" >
									<option value=""> - Select District - </option>
									<?php foreach($district as $key=>$districtval){ ?>
									<option value="<?php echo $districtval; ?>"><?php echo $districtval; ?></option>
									<?php } ?>
									</select>
								</td>
								<td>Circle</td>
								<td>
									<select class="form-control" id="circle" name="circle" >
									<option value=""> - Select Circle - </option>
									<?php foreach($circle as $key=>$circleval){ ?>
									<option value="<?php echo $circleval; ?>"><?php echo $circleval; ?></option>
									<?php } ?>
									</select>
								</td>
								<td>Invoice No.</td>
								<td>
									<input type="text" class="form-control" list="List_invoice" name="invoice" id="invoice"  />
									<datalist  id="List_invoice">
										<?php foreach($invoice_data as $invoice_dataval){
										$invoice_value = $invoice_dataval['mdata_material_invoice']; ?>
											<option value="<?php echo $invoice_value; ?>"></option>
										<?php } ?>
									</datalist>
								</td>
							</tr>
							<tr>
								<td>Asset Series</td>
								<td colspan="2">
									<input type="text" class="form-control" list="List_asset_series" name="asset_series" id="asset_series" />
									<datalist id="List_asset_series">
										<?php foreach($asset_series_data as $asset_seriesval){
										$asset_seriesvalue = $asset_seriesval['product_code']; ?>
											<option value="<?php echo $asset_seriesvalue; ?>"></option>
										<?php } ?>
									</datalist>
								</td>
								<td>Asset</td>
								<td colspan="2">
									<input type="text" class="form-control" list="List_asset" name="asset" id="asset" />
									<datalist id="List_asset" >
										<?php foreach($asset_data as $asset_dataval){
										$asset_value = $asset_dataval['component_code']; ?>
											<option value="<?php echo $asset_value; ?>"></option>
										<?php } ?>
									</datalist>
								</td>
							</tr>
							<tr>
								<td>Due for Inspection</td>
								<td colspan="2" class=" text-center">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="due_inspection">
										</label>
									</div>
								</td>
								<td>Scheduled for Inspection</td>
								<td colspan="2" class=" text-center">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="scheduled_inspection">
										</label>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="form-group">
					<div class="col-md-offset-5 col-md-6">
						<button class="btn btn-info"  name="search" id="search" value="Submit" type="submit" style="background-color: <?php echo $_SESSION['color_code'];?> !important;">Search</button>
					</div>
				</div>
			</form>
		</div>
<?php if(isset($result)){ ?>
		<div class="col-md-12">
			<div class="table-responsive">
				<table id="formonedetails" class="table table-hover table-bordered home_table" >
					<thead>
						<tr>
							<th class="col-md-1 text-center">Asset Series</th>
							<th class="col-md-2 text-center">Asset</th>
							<th class="col-md-2 text-center">Client</th>
							<th class="col-md-1 text-center">Sale Order</th>
							<th class="col-md-1 text-center">Invoice No.</th>
							<th class="col-md-1 text-center">Qty.</th>
							<th class="col-md-2 text-center">Tentative Date of Inspections</th>
						</tr>
					</thead>
					<tbody>
					<?php // echo "<pre>";
					//print_r($result);
					//die();
					?>
						<tr>
							<td class="text-center"><?php echo $result['asset_series']; ?></td>
							<td class="text-center"><?php echo $result['asset']; ?></td>					
							<td class="text-center"><?php echo $result['client_name']; ?></td>
							<td class="text-center"><?php   ?></td>
							<td class="text-center"><?php echo $result['invoice'];  ?></td>
							<td class="text-center"><?php echo $result['qty'];  ?></td>
							<td class="text-center"><?php   ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
<?php } ?>
	</div> 
	<div class="row">
		<div class="col-md-12">
			<legend class="home-heading">Inspection Reports</legend>
			<table id="" class="table table-hover table-bordered home_table" >
				<thead>
					<tr>
						<th class="text-left">Details</th>
						<th class="text-center">Total Numbers</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					<!--
					<tr>
						<td class=" col-md-6">Total Number of Site Id's <strong>Allotted</strong></td>
						<td class=" col-md-3 text-center"><?php //echo $alloted_inspection; ?></td>
						<?php //$url = ($group_id == 9)? 'form_controller/assign_list' : 'inspector_inspection' ; ?>
						<td class="text-center"><a href="<?php //echo base_url($url); ?>" class="btn btn-default">View</a></td>
					</tr> -->
					<tr>
						<td class=" col-md-8">Total Number of Site Id's <strong class="Pending">Pending</strong> ( <small>Not yet Inspected</small> )</td>
						<td class=" col-md-3 text-center"><?php echo $not_inspected_siteIDs; ?></td>
						<td class="text-center"><a href="<?php echo base_url('dashboard_controller/inspection_reports_details?status=NotInspected'); ?>" class="btn btn-default">View</a></td>
					</tr>
					<tr>
						<td class=" col-md-6">Total Number of Site Id's <strong class="Pending">Pending</strong> ( <small>Not yet Approved by Admin</small> )</td>
						<td class=" col-md-3 text-center"><?php echo $pending_inspection; ?></td>
						<td class="text-center"><a href="<?php echo base_url('dashboard_controller/inspection_reports_details?status=Pending'); ?>" class="btn btn-default">View</a></td>
					</tr>
					<tr>
						<td class="col-md-6">Total Number of Site Id's <strong class="approved">Approved</strong></td>
						<td class=" col-md-3 text-center"><?php echo $approved_inspection; ?></td>
						<td class="text-center"><a href="<?php echo base_url('dashboard_controller/inspection_reports_details?status=Approved'); ?>" class="btn btn-default">View</a></td>
					</tr>
					<tr>
						<td class=" col-md-8">Total Number of Site Id's <strong class="rejected">Rejected</strong></td>
						<td class=" col-md-3 text-center"><?php echo $rejected_inspection; ?></td>
						<td class="text-center"><a href="<?php echo base_url('dashboard_controller/inspection_reports_details?status=Rejected'); ?>" class="btn btn-default">View</a></td>
					</tr>
					
				</tbody>
			<table>
		</div>
	</div>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
<script type="text/javascript">
    $(document).ready(function(){
		//search table
			$("#formonedetails").DataTable({
		   		   "order":[[ 0,"asc" ]]
		   });
	 });
</script>