<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/head'); ?>
<?php 
$CI=& get_instance();
$CI->load->model('SiteId_model');
?>
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
<ul class="nav nav-tabs" role="tablist" style="margin-bottom:23px;">
  <li role="presentation" class="active"><a data-target="#mdata_form" aria-controls="home" role="tab" data-toggle="tab"><?php if( $lang["edit_site_id_data"]['description'] !='' ){ echo $lang["edit_site_id_data"]['description']; }else{ echo "EDIT SITE ID DATA"; } ?></a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="mdata_form">
		<div class="row">
			<div class="col-md-12"> <?php echo form_open_multipart(current_url() ,'class="master_data_form"'); ?>
				<legend  class="home-heading"><?php if( $lang["edit_site_id_data"]['description'] !='' ){ echo $lang["edit_site_id_data"]['description']; }else{ echo "EDIT SITE ID DATA"; } ?></legend>
				<div class="form-group col-md-4">
					<label for="email" class="control-label"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo 'Job Card No.'; } ?></label>
					<input type="text" class="form-control" id="jc_number" name="jc_number" rel="siteID" value="<?php echo set_value('site_jobcard',$item['site_jobcard']);?>" readonly />
					<?php echo form_error('jc_number'); ?>
				</div>
				<div class="form-group col-md-4">
					<label for="email" class="control-label"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo 'SMS Number'; } ?></label>
					<input type="text" class="form-control" id="site_sms" name="sms_number" value="<?php echo set_value('site_sms',$item['site_sms']);?>" readonly />
					<?php echo form_error('sms_number'); ?> 
				</div>
				<div class="form-group col-md-4">
					<label for="email" class=" control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo '	SMS Number'; } ?></label>
							<select  id="status" name="status"  class="form-control tooltip_trigger" required>
								<option value="" > - Status -</option>
								<option <?php set_option_state($item['status'],'Active'); ?> value="Active" >Active</option>
								<option <?php set_option_state($item['status'],'Inactive'); ?> value="Inactive">Inactive</option>
							</select>
						  <?php echo form_error('status'); ?>
				</div>
				<div class="col-md-12">
					<div class="table-responsive" id="Table1">
						<table class="table siteID_table" id="site_id_table">
							<tr>
								<td>
									<div class="form-group col-md-12">
										<label for="group" class="control-label"><?php if( $lang["site_id"]['description'] !='' ){ echo $lang["site_id"]['description']; }else{ echo 'Site ID'; } ?></label>
										<input type="text" class="form-control" name="siteID_info[0][site_id]" value="<?php echo ($item['site_id'] != '')? $item['site_id']: '';?>" required/>
									</div>
								</td>
								
								<td>
									<div class="form-group col-md-12">
										<label for="group" class="control-label"><?php if( $lang["site_location"]['description'] !='' ){ echo $lang["site_location"]['description']; }else{ echo 'Site Location'; } ?></label>
											<input type="text" class="form-control" name="siteID_info[0][site_location]" value="<?php echo ($item['site_location'] != '')? $item['site_location']: '';?>" required/>
									</div>
								</td>
								
								<td>
									<div class="form-group col-md-12">
											<label for="group" class="control-label"><?php if( $lang["city_district"]['description'] !='' ){ echo $lang["city_district"]['description']; }else{ echo 'City District'; } ?></label>
											<input type="text" class="form-control" name="siteID_info[0][site_city]" value="<?php echo ($item['site_city'] != '')? $item['site_city']: '';?>" required />
									</div>
								</td>
							
								<td>
									<div class="form-group col-md-12">
										<label for="group" class="control-label"><?php if( $lang["site_address"]['description'] !='' ){ echo $lang["site_address"]['description']; }else{ echo 'Site Address'; } ?></label>
											<input type="text" id="group" class="form-control" name="siteID_info[0][site_address]" value="<?php echo ($item['site_address'] != '')? $item['site_address']: '';?>" required/>
									</div>
								</td>
							
								<td>
									<div class="form-group col-md-12">
										<label for="group" class="control-label"><?php if( $lang["lattitude"]['description'] !='' ){ echo $lang["lattitude"]['description']; }else{ echo 'Lattitude'; } ?></label>
											<input type="text" id="group" class="form-control" name="siteID_info[0][site_lattitude]" value="<?php echo ($item['site_lattitude'] != '')? $item['site_lattitude']: '';?>" required/>
									</div>
								</td>
								
							</tr>
							<tr>
								<td>
									<div class="form-group col-md-12">
										<label for="group" class="control-label"><?php if( $lang["longitude"]['description'] !='' ){ echo $lang["longitude"]['description']; }else{ echo 'Longitude'; } ?></label>
										<input type="text" class="form-control" name="siteID_info[0][site_longitude]" value="<?php echo ($item['site_longitude'] != '')? $item['site_longitude']: '';?>" required />
									</div>
								</td>
								<td>
									<div class="form-group col-md-12">
										<label for="group" class="control-label"><?php if( $lang["client_contact_person_name"]['description'] !='' ){ echo $lang["client_contact_person_name"]['description']; }else{ echo 'Client Contact Person Name'; } ?></label>
										<input type="text" class="form-control" data-start="1" name="siteID_info[0][site_contact_name]" value="<?php echo ($item['site_contact_name'] != '')? $item['site_contact_name']: '';?>" required />
									</div>
								</td>
								<td>
									<div class="form-group col-md-12">
										<label for="group" class="control-label"><?php if( $lang["client_contact_person_number"]['description'] !='' ){ echo $lang["client_contact_person_number"]['description']; }else{ echo 'Client Contact Person Number'; } ?></label>
										<input type="text" class="form-control"  name="siteID_info[0][site_contact_number]" value="<?php echo ($item['site_contact_number'] != '')? $item['site_contact_number']: '';?>" required />
									</div>
								</td>
							
								<td>
									<div class="form-group col-md-12">
										<label for="group" class="control-label"><?php if( $lang["client_contact_person_email"]['description'] !='' ){ echo $lang["client_contact_person_email"]['description']; }else{ echo 'Client Contact Person Email'; } ?></label>
										<input type="text" class="form-control" name="siteID_info[0][site_contact_email]" value="<?php echo ($item['site_contact_email'] != '')? $item['site_contact_email']: '';?>" required />
									</div>
								</td>
								<td></td>
							</tr>
						</table>
					</div>
				</div>


				<div class="form-group" >
				<div class="col-md-offset-4 col-md-8" style="margin-top:30px;">
				<input type="submit" name="submit_siteID" class="btn btn-primary" id="submit_siteID" value="UPDATE SITE ID DATA" />
				<a href="<?php echo base_url('SiteId_kare/siteId_master'); ?>" class="btn btn-default"><?php if( $lang["back"]['description'] !='' ){ echo $lang["back"]['description']; }else{ echo "Back"; } ?></a>
				</div>
				</div>
				<!--</form>--> 
			<?php echo form_close();?> </div>
		</div>
	</div>
</div>
<!--/.tab-content -->
<!-- Footer -->
<?php $this->load->view('includes/footer'); ?>

<!-- Scripts -->
<?php $this->load->view('includes/scripts'); ?>