<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?> 
	
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
					<legend class="home-heading">IMPORT DATA FROM XLS/CSV</legend>
				</div>
				<div class="panel-body">					
					<?php echo form_open_multipart($base_url.'language_controller/import_lang_level_list', 'class="form-horizontal"'); ?>
						<div class="form-group">
							 <label for="email" class="col-md-4 control-label">Upload XLS FIle</label>
							  <div class="col-md-6">
								<input type="file" id="file_upload" name="file_upload" class="form-control tooltip_trigger" />
								<?php echo form_error('file_upload'); ?>
							  </div>
							</div>
							
							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">
								    <input type="hidden" name="imp_excel"  value="yes" />
									<input type="submit" name="import_asset_series_list" class="btn btn-primary" id="import_asset_series_list" value="Uplaod XLS" />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $base_url;?>language_controller/index"><button class="btn btn-info" type="button">Back</button>
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
