<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?> 

	<div class="row" class="msg-display">
		<div class="col-md-12">
			<div class="text-center bg-primary">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo $this->session->flashdata('msg'); 
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
			</div>
		</div>
	</div>
    
    
	<div class="row">
	<div class="col-md-12">
  		<ul class="nav nav-tabs" role="tablist" style="margin-bottom:23px;">
			<li role="presentation" class="active"><a data-target="#mdata_form" aria-controls="home" role="tab" data-toggle="tab"><?php if( $lang["add_asset_series"]['description'] !='' ){ echo $lang["add_asset_series"]['description']; }else{ echo "ADD ASSET SERIES"; } ?></a></li>
			<li role="presentation"><a href="<?php echo $base_url;?>manage_kare/download_asset_series_sample"><?php if( $lang["download_sample_asset_series"]['description'] !='' ){ echo $lang["download_sample_asset_series"]['description']; }else{ echo "DOWNLOAD SAMPLE ASSET SERIES"; } ?></a></li>
			<li role="presentation"><a href="<?php echo $base_url;?>manage_kare/assets_series_search" class="hover_class"><?php if( $lang["view_asset_series_list"]['description'] !='' ){ echo $lang["view_asset_series_list"]['description']; }else{ echo "VIEW  ASSET SERIES LIST"; } ?></a></li>
		</ul>

	<!-- Tab panes -->
	<div class="tab-content">
	  <div role="tabpanel" class="tab-pane active" id="mdata_form">
		<div class="row">
			<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="col-md-8">
						<?php echo form_open_multipart(current_url() , 'class="form-horizontal" id="asset_seriesForm"'); ?>
							<legend class="home-heading"><?php if( $lang["add_asset_series"]['description'] !='' ){ echo $lang["add_asset_series"]['description']; }else{ echo "ADD ASSET SERIES"; } ?></legend>
							<div class="form-group">
								<div class="col-md-10">
									<input type="hidden" class="form-control" id="speci_file_id" name="speci_file_id" value="<?php print key($_SESSION['flexi_auth']['group']);?>" required/>
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["asset_series_code"]['description'] !='' ){ echo $lang["asset_series_code"]['description']; }else{ echo "Asset Series Code"; } ?></label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="product_code" name="product_code" value="<?php echo set_value('product_code');?>" required/>
									<?php echo form_error('product_code'); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["description"]['description'] !='' ){ echo $lang["description"]['description']; }else{ echo "Description"; } ?></label>
								<div class="col-md-10">
									<textarea id="product_description" name="product_description"  class="form-control tooltip_trigger" required><?php echo set_value('product_description');?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["upload_image"]['description'] !='' ){ echo $lang["upload_image"]['description']; }else{ echo "Upload Image"; } ?></label>
								<div class="col-md-10">
									<input type="file" class="form-control" id="product_image" name="product_image" />
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["add_assets"]['description'] !='' ){ echo $lang["add_assets"]['description']; }else{ echo "ADD Assets"; } ?></label>	
							   <div class="col-md-10">
								<div class="row">
									<div class="col-md-6"> 
										<input type="text" id="search_tool" name="search_tool" value="<?php echo set_value('product_uom');?>" class="form-control tooltip_trigger"  placeholder="Search sub-assets / assets"/> 
									</div>
								</div>
								<div class="row">
								<div class="col-md-6"> 
								<div class="search-container" id='sel_component'>
								<?php 
								if($asset_array!=''){
								foreach($asset_array as $component){ 
								?>
								<p><?php echo $component; ?>
								<input class="pull-right" type="checkbox" name="components[]" id="<?php echo "chk_".$component; ?>" value="<?php echo $component; ?>" /></p>
								<?php } } ?> 
							   </div> 
								</div>
								<div class="col-md-1">
								<button id="com_sel_btn" class="btn" type="button" style="margin-top:50px;"> >> </button>
								</div>
								<div class="col-md-5" >
								<div class="component-container" id="product_component" style="margin-left:20px;">
								</div>
								</div>
							   </div> 
							  </div>
							</div>
							
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["type_of_inspection"]['description'] !='' ){ echo $lang["type_of_inspection"]['description']; }else{ echo "Type of Inspection"; } ?></label>
								<div class="col-md-10">
								<select  required id="product_inspectiontype" name="product_inspectiontype"  class="form-control tooltip_trigger">
								<option value="">Select Inspection Type</option>
								<?php
									if(!empty($inspection)){
										foreach($inspection as $insKey=>$insValue){
											echo "<option value='".$insKey."'>".$insValue."</option>";
										}
									}
								?>
								</select>
								  <?php echo form_error('product_inspectiontype'); ?>  
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["geo_fancing"]['description'] !='' ){ echo $lang["geo_fancing"]['description']; }else{ echo "Geo Fancing"; } ?></label>
								<div class="col-md-10">
									<span class="col-md-3">
										<input  type="radio" id="geo_fancing" name="geo_fancing" value="yes" <?php echo set_checkbox('geo_fancing','yes',true);?> /> Yes
									</span>
									<input type="radio" id="geo_fancing" name="geo_fancing" value="no"  <?php echo set_checkbox('geo_fancing','no');?>/> No
									<?php //echo form_error('geo_fancing'); ?>  
								</div>
							</div>
                                                        
                                                        <div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["work_permit"]['description'] !='' ){ echo $lang["work_permit"]['description']; }else{ echo "Work Permit"; } ?></label>
								<div class="col-md-10">
									<span class="col-md-3">
										<input  type="radio" id="work_permit" name="work_permit" value="yes" <?php echo set_checkbox('work_permit','yes',true);?> /> Yes
									</span>
									<input type="radio" id="work_permit" name="work_permit" value="no"  <?php echo set_checkbox('work_permit','no');?>/> No
									<?php //echo form_error('work_permit'); ?>  
								</div>
							</div>
                                                
							<div class="form-group">
									<label for="email" class="col-md-2 control-label"><?php if( $lang["frequency_of_asset_series"]['description'] !='' ){ echo $lang["frequency_of_asset_series"]['description']; }else{ echo "Frequency of Asset Series"; } ?></label>
									<div class="col-md-10">
											<input type="number" class="form-control" id="frequency_asset" name="frequency_asset" 
											value="<?php echo set_value('sub_frequency_asset');?>" min="1" max="24" />
									</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2" for="product_category_status"><?php if( $lang["knowledgetree_status"]['description'] !='' ){ echo $lang["knowledgetree_status"]['description']; }else{ echo "Knowledgetree Status"; } ?></label>
								<div class="col-md-10">
									<select class="form-control" id="infonet_status_status" name="infonet_status_status" required="required">
										<option value=""> - Select Status - </option>
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
								</div>
							</div>

							<!-- Start from manage_certificate --->
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["standards"]['description'] !='' ){ echo $lang["standards"]['description']; }else{ echo "Standards"; } ?></label>
								<div class="col-md-10">								    
									<select  id="standards_certificate" name="standards_certificate" class="form-control chosen" required>
										<option  value=""> - Status - </option>
											<?php if(is_array($standCerticate)){
												foreach($standCerticate as $obsKey=>$obsValue){
											?>
											<option  value="<?php echo $obsValue['id']; ?>" ><?php echo $obsValue['name']; ?></option>
											<?php } } ?>
									</select>
								 <?php echo form_error('standards'); ?> 
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["ec_type_certificate"]['description'] !='' ){ echo $lang["ec_type_certificate"]['description']; }else{ echo "EC Type Certificate"; } ?></label>
								<div class="col-md-10">
									<input type="text"   id="ec_type_certificate_txt" name="ec_type_certificate_txt" class="form-control " required>
										
								 <?php echo form_error('ec_type_certificate'); ?> 
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["notified_body_certification"]['description'] !='' ){ echo $lang["notified_body_certification"]['description']; }else{ echo "Notified Body( certification )"; } ?></label>
								<div class="col-md-10">
									<select  id="notified_certified" name="notified_certified" class="form-control chosen" required>
										<option  value=""> - Status - </option>
											<?php if(is_array($NotifiedBodyCerticate)){
												foreach($NotifiedBodyCerticate as $obsKey=>$obsValue){
											?>
											<option  value="<?php echo $obsValue['id']; ?>" ><?php echo $obsValue['name']; ?></option>
											<?php } } ?>										
									</select>
								 <?php echo form_error('notified_certified'); ?> 
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["notified_body_article_11b"]['description'] !='' ){ echo $lang["notified_body_article_11b"]['description']; }else{ echo "Notified Body( Article 11B)"; } ?></label>
								<div class="col-md-10">
									<select id="article_11b_certificate" name="article_11b_certificate" class="form-control chosen" />
									  <option  value=""> - Status - </option>
											<?php if(is_array($Article11BCerticate)){
												foreach($Article11BCerticate as $obsKey=>$obsValue){
											?>
											<option  value="<?php echo $obsValue['id']; ?>" ><?php echo $obsValue['name']; ?></option>
											<?php } } ?>
									</select>
									<?php echo form_error('article_11b'); ?>  
								 
								</div>
							</div>
							<!-- End from manage_certificate --->
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></label>
								<div class="col-md-10">
									<select  id="status" name="status" class="form-control tooltip_trigger" required>
									<option selected value=""> - Status - </option>
										<option value="Active">Active</option>
										<option value="Inactive">Inactive</option>
									</select>
								 <?php echo form_error('status'); ?> 
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-6 col-md-8">
									<input type="submit" name="save_asset_series" class="btn btn-primary" id="submit" value="SAVE" />
								</div>
							</div>

						<!--</form>-->
							<?php echo form_close();?>
					</div>
					
					<div class="col-md-4">
						<?php echo form_open_multipart($base_url.'manage_kare/import_asset_series_list', 'class="form-horizontal"'); ?>
						<legend class="home-heading"><?php if( $lang["import_data_from_xls_csv_file"]['description'] !='' ){ echo $lang["import_data_from_xls_csv_file"]['description']; }else{ echo "IMPORT DATA FROM XLS/CSV"; } ?></legend>
							<div class="form-group">
							<label for="email" class="col-md-4 control-label"><?php if( $lang["upload_xls_file"]['description'] !='' ){ echo $lang["upload_xls_file"]['description']; }else{ echo "Upload XLS FIle"; } ?></label>
							<div class="col-md-8">
								<input type="file" id="file_upload" name="file_upload" class="form-control tooltip_trigger" />
								<?php echo form_error('file_upload'); ?>    
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<input type="submit" name="import_asset_series_list" class="btn btn-primary" id="import_asset_series_list" value="Uplaod XLS" />
							</div>
						</div>
						<?php echo form_close();?>
					</div>
				</div>
			</div>
			</div>
		</div></div></div></div>
	</div>

    <!---<div class="row">
	<div class="col-md-12">
    <div class="panel panel-default">
		<div class="panel-heading home-heading">
			<span>ASSET SERIES LIST</span>
		</div>
		<div class="panel-body">
			<?php if( $this->flexi_auth->is_privileged('Reset Database Table')){ ?>
			<div class="col-md-offset-10">
				<a href="<?php echo $base_url;?>manage_kare/reset_table_data/assetSeries" class="btn btn-danger delete">Reset Asset Series Table</a>
				</br></br>
			</div>
			<?php } ?>
			<table class="table table-bordered table-hover" id="assetSeries_table">
			<thead>
			<th>Action</th>
			<th>Asset Series Code</th><th>Description</th><th>Image</th><th>Asset List</th>
			<th>Inspection Type</th><th>Infonet Status</th><th>status</th>
			</thead>
			<tbody>
			</tbody>
			</table>
		</div>
	</div>
    </div>
    </div>-->
	<!-- Footer --> 
<!-- Start to choosen of select box -->
<link rel="stylesheet" href="<?php echo $includes_dir;?>css/choosen_selectbox_style.css">
<script src="<?php echo $includes_dir;?>js/choosen_selectbox.js"></script>
<script type="text/javascript">
$(".chosen").chosen();
</script>
<!-- End to choosen of select box -->	
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
