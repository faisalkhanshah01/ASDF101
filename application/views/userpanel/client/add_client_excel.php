<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?> 
	
	<div class="row" class="msg-display">
		<div class="col-md-12">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	print_r($this->session->flashdata('msg')); 
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
					<span>IMPORT CLIENT / DEALER BY EXCEL FILE</span>
					<a class="sample_btn pull-right" title="Click to Download" href="<?php echo $base_url;?>uploads/sampleFile/SampleClientFile.xlsx">Download Sample Excel File</a>
				</div>
				<div class="panel-body">
				
					<?php echo form_open_multipart($base_url.'Client_kare/add_client_excel', 'class="form-horizontal"', 'id="add_excelclients"', 'method="post"', 'enctype="multipart/form-data"'); ?>
						<div class="form-group">
						  <label for="mobile" class="col-md-2 control-label">Thumbnail </label>
						  <div class="col-md-6">
							<input type="file" class="form-control tooltip_trigger" value="excel_file" id="excel_file" name="excel_file" placeholder="Thumbnail" required >					   
							<p style="margin-left:0; font-size: 12px;"> *Only .csv, .xlsx or .xls file acceptable. </p>
						  </div>
						</div>
					
						<div class="form-group">
						<label class="col-md-2"> </label>
						  <div class="col-md-offset-2 col-md-10">
						  
							<button type="submit" class="btn btn-primary" id="add_clientexcel" name="add_clientexcel">Add Client</button>
							<a href="<?php echo $base_url;?>client_kare/client_view"><button class="btn btn-info" type="button"> Back  </button>
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
