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
  		<div class="panel panel-default">
			<div class="panel-heading home-heading">
				<span><?php if( $lang["edit_assets_series"]['description'] !='' ){ echo $lang["edit_assets_series"]['description']; }else{ echo "EDIT ASSETS SERIES"; } ?></span>
			</div>
			<div class="panel-body">
				<?php echo form_open_multipart(current_url() , 'class="form-horizontal"'); ?>
					<div class="form-group">
						<div class="col-md-10">
							<input type="hidden" class="form-control" id="speci_file_id" name="speci_file_id" value="<?php print key($_SESSION['flexi_auth']['group']);?>" required/>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["asset_series_code"]['description'] !='' ){ echo $lang["asset_series_code"]['description']; }else{ echo "Asset Series Code"; } ?></label>
						<div class="col-md-8">
							<input type="text" class="form-control" id="product_code" name="product_code"
							 value="<?php echo $product['product_code'];?>"/>
							<?php echo form_error('product_code'); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["description"]['description'] !='' ){ echo $lang["description"]['description']; }else{ echo "Description"; } ?></label>
						<div class="col-md-8">
											<textarea id="product_description" name="product_description"  class="form-control tooltip_trigger" ><?php echo $product['product_description'];?></textarea>
						
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["upload_image"]['description'] !='' ){ echo $lang["upload_image"]['description']; }else{ echo "Upload Image"; } ?></label>
						<div class="col-md-8">
							<input type="file" class="form-control" id="product_image" name="product_image" />
						</div>
					</div>

					
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["add_assets"]['description'] !='' ){ echo $lang["add_assets"]['description']; }else{ echo "ADD Assets"; } ?></label>	
					   <div class="col-md-8">
						<div class="row">
						<div class="col-md-5"> 
						<input type="text" id="search_tool" name="search_tool" value="<?php echo set_value('product_uom');?>" class="form-control tooltip_trigger"  placeholder="Search components"/> 
						</div>
						</div>
						
						
						<div class="row">
						<div class="col-md-5"> 
						<div class="search-container" id='sel_component'>
						<?php if($asset_array!=''){
						foreach($asset_array as $component){ 
						?>
						<p><?php echo $component; ?>
						<input class="pull-right" type="checkbox" name="components[]" id="<?php echo "chk_".$component; ?>" value="<?php echo $component; ?>" /></p>
						<?php } } ?> 
					   </div>  
						</div>
						<div class="col-md-2">
						<button id="com_sel_btn" class="btn" type="button" style="margin-top:50px;"> >> </button>
						</div>
						<div class="col-md-5" >
						<div class="component-container" id="product_component">
							<?php if(!empty($product['product_components'])){
										foreach((json_decode($product['product_components'],true)) as $comp){ ?>
											<p id='<?php echo $comp; ?>' class='bg-success product_main'><?php echo $comp; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
											<input type="hidden" class="sel-component" name="product_components[]" value="<?php echo $comp; ?>"/>
											</p>
								<?php		}
									}
							 ?>
						</div>
						</div>
					   </div> 
					  </div>
					</div>
					
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["type_of_inspection"]['description'] !='' ){ echo $lang["type_of_inspection"]['description']; }else{ echo "Type of Inspection"; } ?></label>
						<div class="col-md-8">
						<select  id="product_inspectiontype" name="product_inspectiontype"  class="form-control tooltip_trigger">
							<option value="">Select Inspection Type</option>
							<?php
								if(!empty($inspection)){
									foreach($inspection as $insKey=>$insValue){
										$selected = ($insKey == $product['product_inspectiontype'])? 'Selected' : '' ;
										echo "<option ".$selected." value='".$insKey."'>".$insValue."</option>";
									}
								}
							?>
						</select>
						  <?php echo form_error('product_inspectiontype'); ?>  
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["geo_fancing"]['description'] !='' ){ echo $lang["geo_fancing"]['description']; }else{ echo "Geo Fancing"; } ?></label>
						<div class="col-md-8">
							<span class="col-md-3">
								<input  type="radio" id="geo_fancing" name="geo_fancing" value="yes" <?php echo set_radio_state($product['product_geo_fancing'],'yes');?> /> Yes
							</span>
							<input type="radio" id="geo_fancing" name="geo_fancing" value="no"  <?php echo set_radio_state($product['product_geo_fancing'],'no');?>/> No
							<?php //echo form_error('geo_fancing'); ?>  
						</div>
					</div>
												
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["work_permit"]['description'] !='' ){ echo $lang["work_permit"]['description']; }else{ echo "Work Permit"; } ?></label>
						<div class="col-md-8">
							<span class="col-md-3">
								<input  type="radio" id="work_permit" name="work_permit" value="yes" <?php echo set_radio_state($product['product_work_permit'],'yes');?> /> Yes
							</span>
							<input type="radio" id="work_permit" name="work_permit" value="no"  <?php echo set_radio_state($product['product_work_permit'],'no');?>/> No
							<?php //echo form_error('work_permit'); ?>  
						</div>
					</div>
										
					<div class="form-group">
							<label for="email" class="col-md-3 control-label"><?php if( $lang["frequency_of_asset_series"]['description'] !='' ){ echo $lang["frequency_of_asset_series"]['description']; }else{ echo "Frequency of Asset Series"; } ?></label>
							<div class="col-md-8">
									<input type="number" class="form-control" id="frequency_asset" name="frequency_asset" 
									value="<?php echo $product['product_frequency_asset'];?>" min="1" max="24" />
							</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3" for="product_category_status"><?php if( $lang["infonet_status"]['description'] !='' ){ echo $lang["infonet_status"]['description']; }else{ echo "Infonet Status"; } ?></label>
						<div class="col-md-8">
							<select class="form-control" id="infonet_status_status" name="infonet_status_status" required="required">
								<option value=""> - Select Status - </option>
								<option <?php set_option_state($product['infonet_status'],'1'); ?> value="Active" >Active</option>
								<option <?php set_option_state($product['infonet_status'],'0'); ?> value="Inactive">Inactive</option>
							</select>
						</div>
					</div>
					<!-- Start from manage_certificate --->
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["standards"]['description'] !='' ){ echo $lang["standards"]['description']; }else{ echo "Standards"; } ?></label>
						<div class="col-md-8">								    
							<select  id="standards_certificate" name="standards_certificate" class="form-control chosen" required>
								<option  value=""> - Status - </option>
									<?php if(is_array($standCerticate)){
										foreach($standCerticate as $obsKey=>$obsValue){
										if( $obsValue['id'] == $product['standard_certificate_id'] ){ ?>
										<option  value="<?php echo $obsValue['id']; ?>"  SELECTED ><?php echo $obsValue['name']; ?></option>
										<?php	}else{
									?>
									<option  value="<?php echo $obsValue['id']; ?>" ><?php echo $obsValue['name']; ?></option>
									<?php }} } ?>
							</select>
						 <?php echo form_error('standards'); ?> 
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["ec_type_certificate"]['description'] !='' ){ echo $lang["ec_type_certificate"]['description']; }else{ echo "EC Type Certificate"; } ?></label>
						<div class="col-md-8">
							<input type="text"   id="ec_type_certificate_txt" name="ec_type_certificate_txt" class="form-control " value="<?php echo $product['ec_type_certificate_text']; ?>"  required>
								
						 <?php echo form_error('ec_type_certificate'); ?> 
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["notified_body_certification"]['description'] !='' ){ echo $lang["notified_body_certification"]['description']; }else{ echo "Notified Body( certification )"; } ?></label>
						<div class="col-md-8">
							<select  id="notified_certified" name="notified_certified" class="form-control chosen" required>
								<option  value=""> - Status - </option>
									<?php if(is_array($NotifiedBodyCerticate)){
										foreach($NotifiedBodyCerticate as $obsKey=>$obsValue){
											if( $obsValue['id'] == $product['notified_body_certificate_id'] ){ ?>
										<option  value="<?php echo $obsValue['id']; ?>"  SELECTED ><?php echo $obsValue['name']; ?></option>
										<?php	}else{
									?>
									<option  value="<?php echo $obsValue['id']; ?>" ><?php echo $obsValue['name']; ?></option>
									<?php } } } ?>										
							</select>
						 <?php echo form_error('notified_certified'); ?> 
						</div>
					</div>

					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["notified_body_article_11b"]['description'] !='' ){ echo $lang["notified_body_article_11b"]['description']; }else{ echo "Notified Body( Article 11B)"; } ?></label>
						<div class="col-md-8">
							<select id="article_11b_certificate" name="article_11b_certificate" class="form-control chosen" />
							  <option  value=""> - Status - </option>
									<?php if(is_array($Article11BCerticate)){
										foreach($Article11BCerticate as $obsKey=>$obsValue){
											if( $obsValue['id'] == $product['article_11b_certificate_id'] ){ ?>
										<option  value="<?php echo $obsValue['id']; ?>"  SELECTED ><?php echo $obsValue['name']; ?></option>
										<?php	}else{
									?>
									<option  value="<?php echo $obsValue['id']; ?>" ><?php echo $obsValue['name']; ?></option>
									<?php } } } ?>
							</select>
							<?php echo form_error('article_11b'); ?>  
						 
						</div>
					</div>
					<!-- End from manage_certificate --->
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></label>
						<div class="col-md-8">
								<select  id="status" name="status"  class="form-control tooltip_trigger" required>
									<option value="" > - Status -</option>
									<option <?php set_option_state($product['status'],'Active'); ?> value="Active" >Active</option>
									<option <?php set_option_state($product['status'],'Inactive'); ?> value="Inactive">Inactive</option>
								</select>
							  <?php echo form_error('status'); ?>
						</div>
					</div>	
					
					
					<div class="form-group">
						<div class="col-md-offset-3 col-md-8">
							<input type="submit" name="edit_asset_series" class="btn btn-primary" id="submit" value="UPDATE" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('manage_kare/asset_series_list'); ?>" class="btn btn-default"><?php if( $lang["back"]['description'] !='' ){ echo $lang["back"]['description']; }else{ echo "Back"; } ?></a>
						</div>
					</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
    
  
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
