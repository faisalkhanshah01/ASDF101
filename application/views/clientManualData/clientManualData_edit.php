<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
	
	<?php 
		// echo "<pre>";
		// print_r($clientManualData);
		// die();
	?>
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
			<?php if (! empty($message)) { ?>
				<div id="message" class="alert-success">
					<?php echo $message; ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="row" class="msg-display">
		<div class="col-md-12 text-center">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo '<span style="color:red"><strong>'.$this->session->flashdata('msg').'</strong></span>';
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
	
	<!-- Form Section Start-->
	<div class="row">
		<div class="col-md-12">
			<form class="form-horizontal" role="form" id="formone" action="<?php // echo base_url().'client_manual_data_controller/edit_client_manual_data'; ?>" method="post" >
			<legend class="home-heading">Edit Client Manual Data</legend>
			<div class="row" style="margin-bottom:15px;">
				<div class="col-md-12">
					<a href='<?php echo base_url(); ?>client_manual_data_controller/Client_manual_data' class="btn btn-primary pull-right">Back</a>
				</div>
			</div>
			<div class="col-md-12">
			<div class="form-group">
				<table id="clientManualData" class="table table-hover table-bordered home_table" >
				
					<tbody>
						<tr>
							<td>Client Name:</td>
							<td colspan="2">
								<input type="text" class="form-control" name="client_name" id="client_name" value= "<?php echo $clientManualData['client_name']; ?>" placeholder="Client Name" />
							</td>								
							<td>Installation Date:</td>
							<td>
								<input type="text" class="form-control" name="installation_date" id="installation_date" value= "<?php echo $clientManualData['installation_date']; ?>" />
							</td>
						</tr>
						<tr >
							<td rowspan="2">Dealer Name:</td>
							<td rowspan="2" colspan="2">
								<textarea class="form-control" name="dealer_name" id="dealer_name" row="2"><?php echo $clientManualData['dealer_name']; ?></textarea>
							</td>
							<td rowspan="2">Material Invoice Date:</td>
							<td rowspan="2">
								<textarea  class="form-control" name="material_invoice_date" id="material_invoice_date" ><?php echo $clientManualData['material_invoice_date']; ?></textarea>
							</td>
						</tr>
						<tr></tr>
						<tr>
							<td>Product:</td>
							<td colspan="2">
								<input type="text" class="form-control" name="product" id="product" placeholder="Product Name" value= "<?php echo $clientManualData['product']; ?>"/>
							</td>								
							<td>Latitude/Longitude:</td>
							<td>
								<input type="text" class="form-control" name="latitude_longitude" id="latitude_longitude" value= "<?php echo $clientManualData['latitude_longitude']; ?>" />
							</td>
						</tr>
						<tr>
							<td rowspan="4">Contact Details:</td>
							<td>Circle/State:</td>
							<td>
								<input type="text" class="form-control" name="circle_state" id="circle_state" placeholder="Circle/State" value= "<?php echo $clientManualData['circle_state']; ?>" />
							</td>								
							<td>RFID:</td>
							<td>
								<input type="text" class="form-control" name="RFID" id="RFID" value= "<?php echo $clientManualData['RFID']; ?>" />
							</td>
						</tr>
						<tr>							
							<td>District:</td>
							<td>
								<input type="text" class="form-control" name="district" id="district" placeholder="District" value= "<?php echo $clientManualData['district']; ?>" />
							</td>								
							<td>BAR CODE NO:</td>
							<td>
								<input type="text" class="form-control" name="bar_code" id="bar_code" value= "<?php echo $clientManualData['bar_code']; ?>" />
							</td>
						</tr>
						<tr>								
							<td>Batch NO.:</td>
							<td>
								<input type="text" class="form-control" name="batch_code" id="batch_code" value= "<?php echo $clientManualData['batch_code']; ?>" />
							</td>
							<td>UIN</td>
							<td>
								<input type="text" class="form-control" name="uin" id="uin" value= "<?php echo $clientManualData['uin']; ?>" />
							</td>
						</tr>
						
					</tbody>
				</table>
			</div>
			</div>
				<input type="hidden" class="form-control" name="id" id="id" value="<?php echo $clientManualData['id']; ?>" />
				<div class="form-group">
					<div class="col-md-6 col-md-offset-5">
						<button class="btn btn-primary"  name="edit_Client_manual_data" id="edit_Client_manual_data" value="Edit Client Manual Data" type="submit">Update Client Manual Data</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- Form Section END-->
		
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>