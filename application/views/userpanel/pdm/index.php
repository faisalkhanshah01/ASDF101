<?php $this->load->view('includes/header'); ?>
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?>

  <!-- Pdm content starts here -->
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
					<li role="presentation" class="active"><a data-target="#mdata_form" aria-controls="home" role="tab" data-toggle="tab"><?php if( $lang["add_site_id_data"]['description'] !='' ){ echo $lang["add_site_id_data"]['description']; }else{ echo 'PERIODIC MAINTENANCE'; } ?></a></li>
				</ul>
	<!-- Tab panes -->
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="mdata_form">
						<?php echo form_open_multipart(current_url() ,'class="master_data_form"'); ?>
							<div class="col-md-12">
								<legend  class="home-heading"><?php if( $lang["add_site_id_data"]['description'] !='' ){ echo $lang["add_site_id_data"]['description']; }else{ echo 'INSERT PERIODIC MAINTENANCE'; } ?></legend>
								<div class="form-group col-md-4">
									<label for="email" class="control-label"><?php if( $lang["job_card_no"]['description'] !='' ){ echo $lang["job_card_no"]['description']; }else{ echo 'SELECT ASSET'; } ?></label>
									<select id="jc_number" name="jc_number" rel="siteID" class="form-control jc_number" required>
										<option value="">Select Asset Id</option>
										<?php
										foreach($jobcards as $jobValue){
												echo "<option value='".$jobValue."'>".$jobValue."</option>";
										}
										?>
									</select>
									<?php echo form_error('jc_number'); ?>
								</div>


							</div>
							<div class="col-md-12">

								<div class="table-responsive" id="Table1">
									<table class="table siteID_table" id="site_id_table">
										<tr>
											<td colspan="6"><legend  class="home-heading"><?php if( $lang["add_site_id_data"]['description'] !='' ){ echo $lang["add_site_id_data"]['description']; }else{ echo 'Step No.1'; } ?></legend></td>
										</tr>
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
														<a class="btn btn-info" id="pdmbtnAddRow" href="javascript:void(0);" /><i class="glyphicon glyphicon-plus"></i></a>
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
									<input type="submit" name="submit_siteID" class="btn btn-primary" id="submit_mdata" value="SAVE PERIODIC MAINTENANCE DATA" />
								</div>
							</div>
							<!--</form>-->
						<?php echo form_close();?>
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

	</div>
<!-- Pdm content ends here -->

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
