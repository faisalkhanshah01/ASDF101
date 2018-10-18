<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?> 
	<style>
		.sample_btn{color:white;}
	</style>
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
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>IMPORT SMS COMPONENT BY EXCEL FILE</span>
					<a class="sample_btn pull-right" title="Click to Download" href="<?php echo $base_url;?>uploads/sampleFile/Sample_SMSComponent_File.xlsx">Download Sample Excel File</a>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart($base_url.'sms_controller/add_SmsComponent_excel', 'class="form-horizontal"', 'id="add_excelcomponents"', 'method="post"', 'enctype="multipart/form-data"'); ?>
						<div class="form-group">
						  <label for="mobile" class="col-md-4 control-label">Thumbnail </label>
						  <div class="col-md-6">
							<input type="file" class="form-control tooltip_trigger" value="excel_file" id="excel_file" name="excel_file" placeholder="Thumbnail" required >					   
							<h4> only .csv, .xlsx or .xls file acceptable* </h4>
						  </div>
						</div>
					
						<div class="form-group">
						  <div class="col-md-offset-4 col-md-8">
							<button type="submit" class="btn btn-primary" id="add_componentexcel" name="add_componentexcel">Add Component</button>
							<a href="<?php echo $base_url;?>sms_controller/sms_component_view"><button class="btn btn-info" type="button"> Back  </button>
						  </div>
						</div>
					 <?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?>   
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
