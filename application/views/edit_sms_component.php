<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
<?php $this->load->view('includes/head'); ?> 
<?php error_reporting(E_ALL & ~E_WARNING); ?>	
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
				<span><?php if( $lang["edit_sms_component"]['description'] !='' ){ echo $lang["edit_sms_component"]['description']; }else{ echo "Edit SMS Component"; } ?></span>
			</div>
			<div class="panel-body">
				<?php echo form_open_multipart($base_url.'Sms_controller/update_sms_component?id='.$record['id'], 'class="form-horizontal"', 'id="update_components"', 'method="post"'); ?>
					<div class="form-group">
						<label for="jc_number" class="col-md-4 control-label"><?php if( $lang["job_card_number"]['description'] !='' ){ echo $lang["job_card_number"]['description']; }else{ echo "Job Card Number"; } ?></label>
						<div class="col-md-6">
							<select class="form-control jc_number" id="jc_number" rel="smsComponent" name="jc_number" required>
								<option value=""> - Select Job Card - </option> 
								<?php
									if(!empty($jobcards)){
										foreach($jobcards as $value){ 
										$selected = ($value == $record['jc_number'])? 'selected' : '';
										?>
											<option <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
								<?php	}
									}
								?>	
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="sms_number" class="col-md-4 control-label"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo "SMS Number"; } ?></label>
						<div class="col-md-6">
								<select class="form-control sms_number" rel="sms_component_series"  id="sms_number" name="sms_number">
								<option value=""> - Select SMS Number - </option>
								<?php
									if(!empty($sms)){
										foreach($sms as $smsVal){
											$selected = ($smsVal == $record['sms_number'])? 'selected' : '';
											echo '<option '.$selected.' value="'.$smsVal.'">'.$smsVal.'</option>';
										}
									}
								?>
								</select>
						</div>
					</div>

					<div class="form-group">
						<label for="component_name" class="col-md-4 control-label"><?php if( $lang["asset_series"]['description'] !='' ){ echo $lang["asset_series"]['description']; }else{ echo "Asset Series"; } ?></label>
						<div class="col-md-6">
								<select class="form-control series_name" rel="series_item" id="series_name" name="series_name">
									<option value=""> - Select Asset Series - </option>
									<?php
										if(!empty($series)){
										foreach($series as $seriesVal){
											$selected = ($seriesVal == $record['series'])? 'selected' : '';
											echo '<option '.$selected.' value="'.$seriesVal.'">'.$seriesVal.'</option>';
										}
									}
									?>
								</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="component_name" class="col-md-4 control-label"><?php if( $lang["asset"]['description'] !='' ){ echo $lang["asset"]['description']; }else{ echo "Asset"; } ?></label>
						<div class="col-md-6">
								<select class="form-control item_code" value="" id="item_code" name="item_code">
									<option value=""> - Select Asset - </option>
									<?php
										if(!empty($asset)){
										foreach($asset as $assetVal){
											$selected = ($assetVal == $record['item_code'])? 'selected' : '';
											echo '<option '.$selected.' value="'.$assetVal.'">'.$assetVal.'</option>';
										}
									}
									?>
								</select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="component_no" class="col-md-4 control-label"> <?php if( $lang["asset_quantity"]['description'] !='' ){ echo $lang["asset_quantity"]['description']; }else{ echo "Asset Quantity"; } ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" value="<?php echo $record['item_quantity']; ?>" id="item_quantity" name="item_quantity" placeholder="Asset Quantity" required />
						</div>
					</div>
					
					<div class="form-group">
						<label for="component_lines" class="col-md-4 control-label"><?php if( $lang["no_of_lines"]['description'] !='' ){ echo $lang["no_of_lines"]['description']; }else{ echo "No. of Lines"; } ?></label>
						<div class="col-md-6">
							<input type="text" class="form-control" value="<?php echo $record['no_of_lines']; ?>" id="no_of_lines" name="no_of_lines" placeholder="No. of lines" required />
						</div>
					</div>
					
					<div class="form-group">
						<label for="mobile" class="col-md-4 control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></label>
						<div class="col-md-6">
							<select class="form-control" id="status" name="status" required>
								<option value=""> - Select Status - </option>
								<option value="Active" <?php if('Active'==$record['status']){ echo "selected";} ?>>Active</option>
								<option value="Inactive"  <?php if('Inactive'==$record['status']){ echo "selected";} ?>>Inactive</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-offset-4 col-md-8">
							<input type="submit" name="update_components" class="btn btn-primary" id="update_components" value="<?php if( $lang["update"]['description'] !='' ){ echo $lang["update"]['description']; }else{ echo "Update"; } ?>" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $base_url;?>sms_controller/sms_component_view"><button class="btn btn-info" type="button"> <?php if( $lang["back"]['description'] !='' ){ echo $lang["back"]['description']; }else{ echo "Back"; } ?>  </button></a>
						</div>
					</div>
					
				
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?>   
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
