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
			
			
			<div class="row" id="bookmark">
				<div class="col-md-12">
					
					<legend  class="home-heading"><?php if( $lang["sms_component_list"]['description'] !='' ){ echo $lang["sms_component_list"]['description']; }else{ echo "SMS COMPONENT LIST"; } ?></legend>
						<a href="<?php echo $base_url;?>sms_controller/add_SmsComponent_excel"><button class="btn btn-info" type="button" ="right"><?php if( $lang["import_sms_component_data"]['description'] !='' ){ echo $lang["import_sms_component_data"]['description']; }else{ echo "IMPORT SMS COMPONENT DATA"; } ?></button></a>&#160;&#160;
						<a href="javascript:void(0);" id="download_sms_excel"><button class="btn btn-info" type="button" ="right"> <?php if( $lang["export_sms_component_data"]['description'] !='' ){ echo $lang["export_sms_component_data"]['description']; }else{ echo "EXPORT SMS COMPONENT DATA"; } ?> </button></a>&#160;&#160;
						<a href="<?php echo $base_url;?>uploads/sampleFile/Sample_SMSComponent_File.xlsx"><button class="btn btn-info" type="button"> <?php if( $lang["download_sample_excel_file"]['description'] !='' ){ echo $lang["download_sample_excel_file"]['description']; }else{ echo "DOWNLOAD SAMPLE EXCEL"; } ?></button></a>&#160;&#160;
					<?php if( $this->flexi_auth->is_privileged('Reset Database Table')){ ?>
					
						<a href="<?php echo $base_url;?>manage_kare/reset_table_data/smsComponent" class="btn btn-danger delete pull-right"><?php if( $lang["reset_sms_component_table"]['description'] !='' ){ echo $lang["reset_sms_component_table"]['description']; }else{ echo "Reset SMS Component Table"; } ?></a>
					<?php } ?>
					</br></br>
				
					<table class="table table-bordered" id="sms_component_table">
						<thead>
						<th><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo "Action"; } ?></th><th><?php if( $lang["job_card_number"]['description'] !='' ){ echo $lang["job_card_number"]['description']; }else{ echo "Job Card Number"; } ?></th><th><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo "SMS Number"; } ?></th>
						<th><?php if( $lang["asset_series"]['description'] !='' ){ echo $lang["asset_series"]['description']; }else{ echo "Asset Series"; } ?></th><th><?php if( $lang["asset"]['description'] !='' ){ echo $lang["asset"]['description']; }else{ echo "Asset"; } ?></th><th><?php if( $lang["asset_quantity"]['description'] !='' ){ echo $lang["asset_quantity"]['description']; }else{ echo "Asset Quantity"; } ?></th><th><?php if( $lang["number_of_lines"]['description'] !='' ){ echo $lang["number_of_lines"]['description']; }else{ echo "Number of Lines"; } ?></th><th><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></th>
						</thead>
					</table>
					
				</div>
			</div>
		</div>
	</div>

<!-- Scripts -->
<?php $this->load->view('includes/scripts'); ?>
<!-- Footer -->
<?php $this->load->view('includes/footer'); ?>