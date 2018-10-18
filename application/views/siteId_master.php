<?php $this->load->view('includes/header'); ?>
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
	<?php 
	if($group_id != 9){ ?>
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs" role="tablist" style="margin-bottom:23px;">
					<li role="presentation" class="active"><a data-target="#mdata_form" aria-controls="home" role="tab" data-toggle="tab"><?php if( $lang["add_site_id_data"]['description'] !='' ){ echo $lang["add_site_id_data"]['description']; }else{ echo 'ADD SITE ID DATA'; } ?></a></li>
					<li role="presentation"><a data-target="#mdata_import" aria-controls="profile" role="tab" data-toggle="tab"><?php if( $lang["import_site_id_data"]['description'] !='' ){ echo $lang["import_site_id_data"]['description']; }else{ echo 'IMPORT SITE ID DATA'; } ?></a></li>
					<li role="presentation"><a href="#mdata_export" aria-controls="messages" role="tab" data-toggle="tab"><?php if( $lang["export_site_id_data"]['description'] !='' ){ echo $lang["export_site_id_data"]['description']; }else{ echo 'EXPORT SITE ID DATA'; } ?></a></li>
					<li role="presentation"><a aria-controls="messages" href="<?php echo $base_url;?>uploads/sampleFile/SampleSiteIDFile.xlsx"><?php if( $lang["download_sample_excel_file"]['description'] !='' ){ echo $lang["download_sample_excel_file"]['description']; }else{ echo 'Download Sample Excel File'; } ?></a></li>
				</ul>
	<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="mdata_form">
						<?php echo form_open_multipart(current_url() ,'class="master_data_form"'); ?>
							<div class="col-md-12">
								<legend  class="home-heading"><?php if( $lang["add_site_id_data"]['description'] !='' ){ echo $lang["add_site_id_data"]['description']; }else{ echo 'ADD SITE ID DATA'; } ?></legend>
								<div class="form-group col-md-4">
									<label for="email" class="control-label"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo 'Job Card No.'; } ?></label>
									<select id="jc_number" name="jc_number" rel="siteID" class="form-control jc_number" required>
										<option value="">Select Job Card</option>
										<?php
										foreach($jobcards as $jobValue){
												echo "<option value='".$jobValue."'>".$jobValue."</option>";
										}
										?>
									</select>
									<?php echo form_error('jc_number'); ?>
								</div>

								<div class="form-group col-md-4">
									<label for="email" class="control-label"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo 'SMS Number'; } ?></label>
									<select name="sms_number" id="sms_number" rel="siteID" class="form-control sms_number" required>
										<option value=""> - Select SMS Number - </option>
									</select>
									<?php echo form_error('sms_number'); ?>
								</div>
								<div class="form-group col-md-4">
									<label for="email" class="control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo '	SMS Number'; } ?></label>
										<select  id="status" name="status" class="form-control tooltip_trigger">
											<option selected value=""> - Status - </option>
											<option value="Active">Active</option>
											<option value="Inactive">Inactive</option>
										</select>
									 <?php echo form_error('status'); ?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="table-responsive" id="Table1">
									<table class="table siteID_table" id="site_id_table">
										<tr>
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label"><?php if( $lang["site_id"]['description'] !='' ){ echo $lang["site_id"]['description']; }else{ echo 'Site ID'; } ?></label>
													<input type="text" class="form-control" name="siteID_info[0][site_id]" required/>
												</div>
											</td>
											
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label"><?php if( $lang["site_location"]['description'] !='' ){ echo $lang["site_location"]['description']; }else{ echo 'Site Location'; } ?></label>
														<input type="text" class="form-control" name="siteID_info[0][site_location]" required/>
												</div>
											</td>
											
											<td>
												<div class="form-group col-md-12">
														<label for="group" class="control-label"><?php if( $lang["city_district"]['description'] !='' ){ echo $lang["city_district"]['description']; }else{ echo 'City /District'; } ?></label>
														<input type="text" class="form-control" name="siteID_info[0][site_city]" required />
												</div>
											</td>
										
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label"><?php if( $lang["site_address"]['description'] !='' ){ echo $lang["site_address"]['description']; }else{ echo 'Site Address'; } ?></label>
														<input type="text" id="group" class="form-control" name="siteID_info[0][site_address]"required/>
												</div>
											</td>
										
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label"><?php if( $lang["lattitude"]['description'] !='' ){ echo $lang["lattitude"]['description']; }else{ echo 'Lattitude'; } ?></label>
														<input type="text" id="group" class="form-control" name="siteID_info[0][site_lattitude]" required/>
												</div>
											</td>
											
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label"></label>
														<a class="btn btn-info" id="btnAddRow" href="javascript:void(0);" /><i class="glyphicon glyphicon-plus"></i></a>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label"><?php if( $lang["longitude"]['description'] !='' ){ echo $lang["longitude"]['description']; }else{ echo 'Longitude'; } ?></label>
													<input type="text" class="form-control" name="siteID_info[0][site_longitude]" required />
												</div>
											</td>
											<!--
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label">Length of the System</label>
													<input type="text" class="form-control" data-start="1" name="siteID_info[0][site_length]" required />
												</div>
											</td>
											-->
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label"><?php if( $lang["client_contact_person_name"]['description'] !='' ){ echo $lang["client_contact_person_name"]['description']; }else{ echo 'Client Contact Person Name'; } ?></label>
													<input type="text" class="form-control" data-start="1" name="siteID_info[0][site_contact_name]" required />
												</div>
											</td>
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label"><?php if( $lang["client_contact_person_number"]['description'] !='' ){ echo $lang["client_contact_person_number"]['description']; }else{ echo 'Client Contact Person Number'; } ?></label>
													<input type="text" class="form-control"  name="siteID_info[0][site_contact_number]" required />
												</div>
											</td>
										
											<td>
												<div class="form-group col-md-12">
													<label for="group" class="control-label"><?php if( $lang["client_contact_person_email"]['description'] !='' ){ echo $lang["client_contact_person_email"]['description']; }else{ echo 'Client Contact Person Email'; } ?></label>
													<input type="text" class="form-control" name="siteID_info[0][site_contact_email]" required />
												</div>
											</td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</div>
							</div>
							
							<div class="form-group" >
								<div class="col-md-offset-4 col-md-8" style="margin-top:30px;">
									<input type="hidden" name="master_id"  id="master_id" value="" />
									<input type="submit" name="submit_siteID" class="btn btn-primary" id="submit_mdata" value="SAVE SITE ID DATA" />
								</div>
							</div>
							<!--</form>--> 
						<?php echo form_close();?> 
					</div>
					<div role="tabpanel" class="tab-pane" id="mdata_import">
						<div class="row">
							<div class="col-md-12"> <?php echo form_open_multipart($base_url.'SiteId_kare/import_siteID_data', 'class="form-horizontal"'); ?>
								<legend  class="home-heading"><?php if( $lang["import_data_from_xls_csv_file"]['description'] !='' ){ echo $lang["import_data_from_xls_csv_file"]['description']; }else{ echo 'Import Data from XLS/CSV file'; } ?> </legend>

								<div class="form-group">
									<label for="email" class="control-label col-md-4"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo 'Job Card No.'; } ?></label>
									<div class="col-md-4">
										<select id="jc_number" name="jc_number" class="form-control jc_number" required>
											<option value="">Select Job Card</option>
											<?php
											foreach($jobcards as $jobValue){
											echo "<option value='".$jobValue."'>".$jobValue."</option>";
											}
											?>
										</select>
										<?php echo form_error('mdata_jobcard'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="control-label col-md-4"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo 'SMS Number'; } ?></label>
									<div class="col-md-4">
										<select name="sms_number" id="sms_numberss" class="form-control sms_number" required>
											<option value=""> - Select SMS Number - </option>
										</select>
										<?php echo form_error('mdata_sms'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-md-4 control-label"><?php if( $lang["upload_xls_file"]['description'] !='' ){ echo $lang["upload_xls_file"]['description']; }else{ echo 'Upload XLS file'; } ?></label>
									<div class="col-md-4">
										<input type="file" id="file_upload" name="file_upload" class="form-control tooltip_trigger" />
										<?php echo form_error('file_upload'); ?> 
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-offset-4 col-md-8">
										<input type="submit" name="import_siteID_xls" class="btn btn-primary" id="import_siteID_xls" value="Uplaod XLS" />
									</div>
								</div>
								<?php echo form_close();?> 
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane" id="mdata_export">
						<div class="row">
							<div class="col-md-12"> <?php echo form_open_multipart($base_url.'SiteId_kare/export_siteID_data' , 'class="form-horizontal"'); ?>
								<legend  class="home-heading"><?php if( $lang["export_data_into_xls_csv_file"]['description'] !='' ){ echo $lang["export_data_into_xls_csv_file"]['description']; }else{ echo 'Export Data into XLS/CSV file'; } ?> </legend>
								<div class="form-group">
									<label for="email" class="control-label col-md-4"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo 'Job Card No.'; } ?></label>
									<div class="col-md-4">
										<select id="jc_number" name="jc_number" class="form-control jc_number">
											<option value="">Select Job Card</option>
											<?php
											foreach($jobcards as $jobValue){
											echo "<option value='".$jobValue."'>".$jobValue."</option>";
											}
											?>
										</select>
										<?php echo form_error('mdata_jobcard'); ?>
									</div>
								</div>

								<div class="form-group">
									<label for="email" class="control-label col-md-4"><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo 'SMS Number'; } ?></label>
									<div class="col-md-4">
										<select name="sms_number" id="sms_numbers" class="form-control sms_number">
											<option value=""> - Select SMS Number - </option>
										</select>
										<?php echo form_error('sms_numbers'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-md-4 control-label"><?php if( $lang["select_file_type"]['description'] !='' ){ echo $lang["select_file_type"]['description']; }else{ echo 'Select File Type'; } ?></label>
									<div class="col-md-4">
										<select name="export_filetype" class="form-control">
											<option>CSV Format</option>
											<option>XLS Format</option>
										</select>
										<?php echo form_error('export_filetype'); ?> 
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-offset-4 col-md-8">
										<input type="submit" name="export_siteID_data" class="btn btn-primary" id="submit" value="EXPORT" />
									</div>
								</div>
								<?php echo form_close();?> 
							</div>
						</div>
					</div>
				</div>
			</div>
		<!--/.tab-content -->
		</div>
	<!--/.row -->
<?php } ?>

	<div class="row">
		<div class="col-md-12" >
			<?php if(is_array($displayValues)){ ?>
				<div class="row">
					<div class="col-md-12">
						<legend  class="home-heading"><?php if( $lang["table_showing_missing_site_id"]['description'] !='' ){ echo $lang["table_showing_missing_site_id"]['description']; }else{ echo "Table Showing Missing Site ID's"; } ?></legend>
						<div class="table-responsive">
							<table id="siteidMissingTable" class="table table-bordered table-hover">
								<thead>
								<tr>
									<th><center><?php if( $lang["job_card"]['description'] !='' ){ echo $lang["job_card"]['description']; }else{ echo 'Job Card'; } ?> #</center></th><th><center><?php if( $lang["sms"]['description'] !='' ){ echo $lang["sms"]['description']; }else{ echo 'SMS'; } ?> #</center></th><th><center><?php if( $lang["no_of_lines"]['description'] !='' ){ echo $lang["no_of_lines"]['description']; }else{ echo 'No of Lines'; } ?></center></th>
									<th><center><?php if( $lang["site_id_provided"]['description'] !='' ){ echo $lang["site_id_provided"]['description']; }else{ echo 'Site ID Provided'; } ?></center></th><th class='backColorTH'><center># <?php if( $lang["of_site_id_left"]['description'] !='' ){ echo $lang["of_site_id_left"]['description']; }else{ echo 'of Site ID Left'; } ?></center></th>
								</tr>
								</thead>
								<tbody>
								<?php  
										foreach($displayValues as $dValues){
											echo "<tr>";
											echo "<td><center>".$dValues['job']."</center></td>";
											echo "<td><center>".$dValues['sms']."</center></td>";
											echo "<td><center>".$dValues['lines']."</center></td>";
											echo "<td><center>".$dValues['totalSiteID']."</center></td>";
											echo "<td class='backColor'><center>".$dValues['difference']."</center></td>";
											echo "</tr>";
										}
								?>
								<tbody>
							</table>
						</div>
					</div>
				</div>
					<br />
			<?php } ?>
	
	
			<div class="row">
				<div class="col-md-12">
				<legend  class="home-heading"><?php if( $lang["table_showing_site_id_present_in_database"]['description'] !='' ){ echo $lang["table_showing_site_id_present_in_database"]['description']; }else{ echo "Table Showing Site ID's Present In Database"; } ?></legend>
				<?php if( $this->flexi_auth->is_privileged('Reset Database Table')){ ?>
				<div class="col-md-offset-10">
					<a href="<?php echo $base_url;?>manage_kare/reset_table_data/siteID" class="btn btn-danger delete"><?php if( $lang["reset_site_id_table"]['description'] !='' ){ echo $lang["reset_site_id_table"]['description']; }else{ echo "Reset Site ID Table"; } ?></a>
					</br></br>
				</div>
				<?php } ?>
				<div class="table-responsive">
					<table id="siteID_master_table" class="table table-bordered table-hover">
						<thead>
								<th><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo "Action"; } ?><br /></th>
								<th><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo "Job Card No."; } ?></th>
								<th><?php if( $lang["sma_number"]['description'] !='' ){ echo $lang["sma_number"]['description']; }else{ echo "SMS Number"; } ?></th>
								<th><?php if( $lang["site_id"]['description'] !='' ){ echo $lang["site_id"]['description']; }else{ echo "Site ID"; } ?></th>
								<th><?php if( $lang["site_location"]['description'] !='' ){ echo $lang["site_location"]['description']; }else{ echo "Site Location"; } ?></th>
								<th><?php if( $lang["city_district"]['description'] !='' ){ echo $lang["city_district"]['description']; }else{ echo "City/District"; } ?></th>
								<th><?php if( $lang["site_address"]['description'] !='' ){ echo $lang["site_address"]['description']; }else{ echo "Site Address"; } ?></th>
								<th><?php if( $lang["lattitude"]['description'] !='' ){ echo $lang["lattitude"]['description']; }else{ echo "Lattitude"; } ?></th>
								<th><?php if( $lang["longitude"]['description'] !='' ){ echo $lang["longitude"]['description']; }else{ echo "Longitude"; } ?></th>
								<th><?php if( $lang["client_name"]['description'] !='' ){ echo $lang["client_name"]['description']; }else{ echo "Client Name"; } ?></th>
								<th><?php if( $lang["client_number"]['description'] !='' ){ echo $lang["client_number"]['description']; }else{ echo "Client Number"; } ?></th>
								<th><?php if( $lang["client_email"]['description'] !='' ){ echo $lang["client_email"]['description']; }else{ echo "Client Email"; } ?></th>
								<th><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></th>
						</thead>
					</table>
				</div>
				</div>
			</div>
		</div>
	</div>
<!-- Footer -->
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>