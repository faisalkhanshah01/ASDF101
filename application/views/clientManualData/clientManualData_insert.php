<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
			<?php if (! empty($message)) { ?>
				<div id="message" class="alert-success">
					<?php echo $message; ?>
				</div>
			<?php } ?>
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
	<div class="col-md-12">
		<form class="form-horizontal" role="form" id="formone" action="<?php //echo base_url().'client_manual_data_controller/Client_manual_data'; ?>" method="post" >
			<legend class="home-heading">Insert Client Manual Data </legend>
			<div class="row" style="margin-bottom:15px;">
				<div class="col-md-12">
					<a href='<?php echo base_url(); ?>client_manual_data_controller/client_manual_data' class="btn btn-primary pull-right">Back</a>
				</div>
			</div>
			<div class="col-md-12">
			<div class="form-group">
				<table id="clientManualData" class="table table-hover table-bordered home_table" >
					<tbody>
						<tr>
							<td>Client Name:</td>
							<td colspan="2">
								<input type="text" class="form-control" name="client_name" id="client_name" placeholder="Client Name" />
							</td>								
							<td>Installation Date:</td>
							<td>
								<input type="text" class="form-control" name="installation_date" id="installation_date" />
							</td>
						</tr>
						<tr >
							<td rowspan="2">Dealer Name:</td>
							<td rowspan="2" colspan="2">
								<textarea class="form-control" name="dealer_name" id="dealer_name" row="2"></textarea>
							</td>
							<td rowspan="2">Material Invoice Date:</td>
							<td rowspan="2">
								<textarea  class="form-control" name="material_invoice_date" id="material_invoice_date" ></textarea>
							</td>
						</tr>
						<tr></tr>
						<tr>
							<td>Product:</td>
							<td colspan="2">
								<input type="text" class="form-control" name="product" id="product" placeholder="Product Name" />
							</td>								
							<td>Latitude/Longitude:</td>
							<td>
								<input type="text" class="form-control" name="latitude_longitude" id="latitude_longitude" />
							</td>
						</tr>
						<tr>
							<td rowspan="4">Contact Details:</td>
							<td>Circle/State:</td>
							<td>
								<input type="text" class="form-control" name="circle_state" id="circle_state" placeholder="Circle/State" />
							</td>								
							<td>RFID:</td>
							<td>
								<input type="text" class="form-control" name="RFID" id="RFID" placeholder="RFID" />
							</td>
						</tr>
						<tr>							
							<td>District:</td>
							<td>
								<input type="text" class="form-control" name="district" id="district" placeholder="District" />
							</td>								
							<td>BAR CODE NO:</td>
							<td>
								<input type="text" class="form-control" name="bar_code" id="bar_code" placeholder="BAR CODE" />
							</td>
						</tr>
						<tr>								
							<td>Batch NO.:</td>
							<td>
								<input type="text" class="form-control" name="batch_code" id="batch_code" placeholder="Batch NO" />
							</td>
							<td>UIN</td>
							<td>
								<input type="text" class="form-control" name="uin" id="uin" placeholder="UIN" />
							</td>
						</tr>						
					</tbody>
				</table>
			</div>
			</div>
			<div class="form-group">
				<div class="col-md-offset-5 col-md-6">
					<button class="btn btn-info"  name="clientManualData_submit" id="clientManualData_submit" value="Submit" type="submit">Insert</button>
				</div>
			</div>
		</form>
	</div>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>