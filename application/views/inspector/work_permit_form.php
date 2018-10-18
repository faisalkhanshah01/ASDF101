<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/head'); 

	$map_loc = "https://maps.googleapis.com/maps/api/staticmap?
	center=".$list['site_lattitude'].",".$list['site_longitude']." 
	&zoom=16
	&size=300x200
	&maptype=roadmap
	&markers=color:red%7Clabel:A%7C".$list['site_lattitude'].",".$list['site_longitude']."
	&visible=100Vivekanand+PURI&key=AIzaSyBpSn0LXbq1wyv2bnZ9EQsqBMzpcxl6DEE";
	
	if(isset($_SESSION['inspector']['workPermit'])){
		$work = $_SESSION['inspector']['workPermit'];
	}

?>

	<div class="row" >
		<div class="col-md-12">
			<div class="text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>
			<legend class="home-heading">Work Permit</legend>
			
			<div class="row">
				<?php echo form_open(current_url());	?>  
				<div class="col-xs-12">
					
					<!-- UPPERMOST PART -->
					<table  width="100%" border="1" cellspacing="0" cellpadding="7">						
						<tr>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Client Name</th>
							<td colspan="2" align="center"><?php echo $list['client_name']; ?><?php $_SESSION['inspector']['work_permit_client_name'] = $list['client_name']; ?></td>		
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Date</th>
							<td width="10%" align="center"><input style="border:none;" title="Click to Select Form Submitted Date" value="<?php echo (isset($work))? $work['today_date'] : set_value('today_date'); ?>"  type="text" name="today_date" class="work_date" required readonly /><?php echo form_error('today_date'); ?></td>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Work Permit No.</th>		
							<td width="20%" align="center"><?php echo $list['work_number']; ?></td><?php $_SESSION['inspector']['work_permit_workPermit_number'] = $list['work_number']; ?>
						</tr>
						<tr>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Site ID</th>
							<td colspan="2" align="center"><?php echo $list['site_id']; ?><?php $_SESSION['inspector']['work_permit_siteID_name'] = $list['siteID_id']; ?></td>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">PERMIT DATE FROM</th>
							<td  align="center"><input style="border:none;" title="Start of Permit Date From" type="text" class="work_date" value="<?php echo (isset($work))? $work['permitDate_from'] : set_value('permitDate_from'); ?>" name="permitDate_from" readonly required /><?php echo form_error('permitDate_from'); ?></td>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">PERMIT VALID UPTO</th>
							<td align="center"><input style="border:none;" title="Click to Select Permit Valid Upto Date" type="text" class="work_date" value="<?php echo (isset($work))? $work['permitValid_upto'] : set_value('permitValid_upto'); ?>" name="permitValid_upto" required  readonly /><?php echo form_error('permitValid_upto'); ?></td>
						</tr>
						<tr>
							<th rowspan=2 width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Site ID ADDRESS:</th>
							<td rowspan=2 colspan="3" align="center"><?php echo $list['site_address']; ?><?php $_SESSION['inspector']['work_permit_siteID_address'] = $list['site_address']; ?></td>
							<th rowspan=2 width="10%" style="background-color: #E8E8E8; color: #333333;">GPRS Location:</th>
							<td rowspan=2 width="10%" colspan="2" rowspan="3" align="center">
								<img src="<?php echo $map_loc; ?>" class="asset_image" title="<?php echo $list['site_address']; ?>" alt="Lattitude , Longitude : <?php echo $list['site_lattitude']." , ". $list['site_longitude']; ?>" />
							<?php 	$_SESSION['inspector']['workPermit_lattitude']	= $list['site_lattitude'];
									$_SESSION['inspector']['workPermit_longitude']	= $list['site_longitude']; ?>
							</td>
						</tr>
						<tr></tr>
						<tr>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">ASSET SERIES:</th>
							<td width="20%" align="center"><?php echo $list['asset_series_code'] ; ?></td>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">BATCH NO:</th>
							<td width="10%" align="center"><?php echo $list['batch_no'] ; ?></td>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">SERIAL NO:</th>
							<td	colspan=2 width="10%" align="center"><?php echo $list['serial_no'] ; ?></td>
							<?php 	$_SESSION['inspector']['workPermit_asset_series']	= $list['asset_series_code'];
									$_SESSION['inspector']['workPermit_batch_no']		= $list['batch_no'];
									$_SESSION['inspector']['workPermit_serial_no']		= $list['serial_no']; ?>
						</tr>
						<tr>
							<td colspan="7" width="20%" height="20px"></td>			
						</tr>
					</table>
					<!-- UPPERMOST PART END --> 
				  
					<!-- MIDDLE PART -->
					<table border="1" width="100%" cellspacing="0" cellpadding="5">
						<tr><th colspan=7>DETAILS</th></tr>
						<tr><td colspan="7" width="20%" height="20px"></td></tr>
						<tr><th colspan=7>PPE</th></tr>
						<tr>
							<th  align="center" style="background-color: #E8E8E8; color: #333333;"> SR NO</th>
							<th colspan=6 width="20%" align="center" style="background-color: #E8E8E8; color: #333333;"> PARTICULARS</th>
						</tr>
						<tr>
							<td rowspan=3 width="5%" align="center">1</td>
							<td colspan=6 align="center">ARE THE WORKERS WEARING THE RIGHT PPE ?</td>
						</tr>
						<tr>
							<td width="15%" align="center"><img src="<?php echo base_url(); ?>includes/images/harness.jpg" alt="HARNESS WITH LANYARD" width=100 height=100 /></td>
							<td width="15%" align="center"><img src="<?php echo base_url(); ?>includes/images/helmet.jpg" alt="HELMET" width='' height='' /></td>
							<td width="15%" align="center"><img src="<?php echo base_url(); ?>includes/images/shoes.jpg" alt="SAFETY SHOES" width='' height='' /></td>
							<td width="15%" align="center"><img src="<?php echo base_url(); ?>includes/images/gloves.jpg" alt="GLOVES" width='' height='' /></td>
							<td width="15%" align="center"><img src="<?php echo base_url(); ?>includes/images/goggles.jpg" alt="SAFETY GOGGLE" width='' height='' /></td>
							<td width="15%" align="center"><img src="<?php echo base_url(); ?>includes/images/working_position.jpg" alt="WORK POSITIONING" width='' height='' /></td>
						</tr>
						<tr>
							<td width="15%" align="center">
								<input type="radio" name='harness' value="yes" <?php echo (isset($work) && $work['harness'] =='yes')? 'checked' : '' ?>  required>Yes
								<input type="radio" name='harness' value="no" <?php echo (isset($work) && $work['harness'] =='no')? 'checked' : '' ?> >No
							</td>
							<td width="15%" align="center">
								<input type="radio" name='helmet' value="yes" <?php echo (isset($work) && $work['helmet'] =='yes')? 'checked' : '' ?> required>Yes
								<input type="radio" name='helmet' value="no" <?php echo (isset($work) && $work['helmet'] =='no')? 'checked' : '' ?>>No
							</td>
							<td width="15%" align="center">
								<input type="radio" name='shoes' value="yes" <?php echo (isset($work) && $work['shoes'] =='yes')? 'checked' : '' ?> required>Yes
								<input type="radio" name='shoes' value="no" <?php echo (isset($work) && $work['shoes'] =='no')? 'checked' : '' ?> >No
							</td>
							<td width="15%" align="center">
								<input type="radio" name='gloves' value="yes" <?php echo (isset($work) && $work['gloves'] =='yes')? 'checked' : '' ?> required>Yes
								<input type="radio" name='gloves' value="no" <?php echo (isset($work) && $work['gloves'] =='no')? 'checked' : '' ?> >No
							</td>
							<td width="15%" align="center">
								<input type="radio" name='goggle' value="yes" <?php echo (isset($work) && $work['goggle'] =='yes')? 'checked' : '' ?> required>Yes
								<input type="radio" name='goggle' value="no" <?php echo (isset($work) && $work['goggle'] =='no')? 'checked' : '' ?>>No
							</td>
							<td width="15%" align="center">
								<input type="radio" name='work_position' value="yes" <?php echo (isset($work) && $work['work_position'] =='yes')? 'checked' : '' ?> required>Yes
								<input type="radio" name='work_position' value="no" <?php echo (isset($work) && $work['work_position'] =='no')? 'checked' : '' ?>>No
							</td>
						</tr>
						<tr>
							<td  width="5%" align="center">2</td>
							<td  colspan=4 width="15%" align="center">IS THE EQUIPMENT INSPECTED AND GOOD TO USE ?</td>
							<td  width="15%" align="center"><input type="radio" name='equipment_use' value="yes" <?php echo (isset($work) && $work['equipment_use'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='equipment_use' value="no" <?php echo (isset($work) && $work['equipment_use'] =='no')? 'checked' : '' ?>>No</td>
							<?php echo form_error('equipment_use'); ?>
						</tr>
						<tr>
							<th colspan=7>TRAINING</th>
						</tr>
						<tr>
							<td  width="5%" align="center">1</td>
							<td  colspan=4 width="15%" align="center">ARE THE WORKER TRAINED FOR THE JOB ?</td>
							<td  width="15%" align="center"><input type="radio" name='worker_trained' value="yes" <?php echo (isset($work) && $work['worker_trained'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='worker_trained' value="no" <?php echo (isset($work) && $work['worker_trained'] =='no')? 'checked' : '' ?>>No</td>
							<?php echo form_error('worker_trained'); ?>
						</tr>
						<tr>
							<td  width="5%" align="center">2</td>
							<td  colspan=4 width="15%" align="center">ARE THE WORKER TRAINED FOR SAFETY ?</td>
							<td  width="15%" align="center"><input type="radio" name='worker_safety' value="yes" <?php echo (isset($work) && $work['worker_safety'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='worker_safety' value="no" <?php echo (isset($work) && $work['worker_safety'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">3</td>
							<td  colspan=4 width="15%" align="center">ARE THE WORKER TRAINED FOR WORK AT HEIGHT ?</td>
							<td  width="15%" align="center"><input type="radio" name='worker_height' value="yes" <?php echo (isset($work) && $work['worker_height'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='worker_height' value="no" <?php echo (isset($work) && $work['worker_height'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">4</td>
							<td  colspan=4 width="15%" align="center">ARE THE WORKER TRAINED FOR DEFENCIVE DRIVING ?</td>
							<td  width="15%" align="center"><input type="radio" name='worker_defensive' value="yes" <?php echo (isset($work) && $work['worker_defensive'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='worker_defensive' value="no" <?php echo (isset($work) && $work['worker_defensive'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<th colspan=7>SITUATIONS</th>
						</tr>
						<tr>
							<td  width="5%" align="center">1</td>
							<td  colspan=4 width="15%" align="center">IS WEATHER GOOD TO WORK ?</td>
							<td  width="15%" align="center"><input type="radio" name='weather_good' value="yes" <?php echo (isset($work) && $work['weather_good'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='weather_good' value="no" <?php echo (isset($work) && $work['weather_good'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">2</td>
							<td  colspan=4 width="15%" align="center">IS THERE ENOUGH LIGHT TO PERFORM THE JOB ?</td>
							<td  width="15%" align="center"><input type="radio" name='enough_light' value="yes" <?php echo (isset($work) && $work['enough_light'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='enough_light' value="no" <?php echo (isset($work) && $work['enough_light'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">3</td>
							<td  colspan=4 width="15%" align="center">IS THE JOB SITE ACCESSABLE ?</td>
							<td  width="15%" align="center"><input type="radio" name='site_accessable' value="yes" <?php echo (isset($work) && $work['site_accessable'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='site_accessable' value="no" <?php echo (isset($work) && $work['site_accessable'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">4</td>
							<td  colspan=4 width="15%" align="center">IS ACCESS EQUIPMENT AVAILABLE ?</td>
							<td  width="15%" align="center"><input type="radio" name='equipment_available' value="yes" <?php echo (isset($work) && $work['equipment_available'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='equipment_available' value="no" <?php echo (isset($work) && $work['equipment_available'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="15%" align="center">5</td>
							<td  colspan=4 width="15%" align="center">ARE THERE ANY UNGUARDED EDGES ?</td>
							<td  width="15%" align="center"><input type="radio" name='unguarded_edges' value="yes" <?php echo (isset($work) && $work['unguarded_edges'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='unguarded_edges' value="no" <?php echo (isset($work) && $work['unguarded_edges'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<th colspan=7>EQUIPMENT</th>
						</tr>
						<tr>
							<td  width="5%" align="center">1</td>
							<td  colspan=4 width="15%" align="center">IS THE EQUIPMENT IN GOOD WORKING CONDITION ?</td>
							<td  width="15%" align="center"><input type="radio" name='equipment_condition' value="yes" <?php echo (isset($work) && $work['equipment_condition'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='equipment_condition' value="no" <?php echo (isset($work) && $work['equipment_condition'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">2</td>
							<td  colspan=4 width="15%" align="center">IS THE ELECTRICAL WIRING IN GOOD CONDITION ?</td>
							<td  width="15%" align="center"><input type="radio" name='wiring_condition' value="yes" <?php echo (isset($work) && $work['wiring_condition'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='wiring_condition' value="no" <?php echo (isset($work) && $work['wiring_condition'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">3</td>
							<td  colspan=4 width="15%" align="center">IS THE EQUIPMENT INSPECTED, CALIBRATED AND GOOD TO USE ?</td>
							<td  width="15%" align="center"><input type="radio" name='equipment_calibrated' value="yes" <?php echo (isset($work) && $work['equipment_calibrated'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='equipment_calibrated' value="no" <?php echo (isset($work) && $work['equipment_calibrated'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<th colspan=7>MEDICAL CONDITION</th>
						</tr>
						<tr>
							<td  width="5%" align="center">1</td>
							<td  colspan=4 width="15%" align="center">ARE THE WORKER PHYSICALLY / MEDICALLY / MENTALLY FIT ?</td>
							<td  width="15%" align="center"><input type="radio" name='physically_fitness' value="yes" <?php echo (isset($work) && $work['physically_fitness'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='physically_fitness' value="no" <?php echo (isset($work) && $work['physically_fitness'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">2</td>
							<td  colspan=4 width="15%" align="center">ARE THE WORKER FIT TO WORK AT HEIGHT ?</td>
							<td  width="15%" align="center"><input type="radio" name='work_at_height' value="yes" <?php echo (isset($work) && $work['work_at_height'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='work_at_height' value="no" <?php echo (isset($work) && $work['work_at_height'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">3</td>
							<td  colspan=4 width="15%" align="center">ARE THE WORKER UNDER THE INFLUENCE OF ALCOHOL ?</td>
							<td  width="15%" align="center"><input type="radio" name='alcohol_influence' value="yes" <?php echo (isset($work) && $work['alcohol_influence'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='alcohol_influence' value="no" <?php echo (isset($work) && $work['alcohol_influence'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">4</td>
							<td  colspan=4 width="15%" align="center">ARE THE WORKER INSURED UNDER MEDICLAIM ?</td>
							<td  width="15%" align="center"><input type="radio" name='mediclaim_insured' value="yes" <?php echo (isset($work) && $work['mediclaim_insured'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='mediclaim_insured' value="no" <?php echo (isset($work) && $work['mediclaim_insured'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">5</td>
							<td  colspan=4 width="15%" align="center">ARE THE WORKER CARRYING FIRST AID BOX ?</td>
							<td  width="15%" align="center"><input type="radio" name='carry_firstAid' value="yes" <?php echo (isset($work) && $work['carry_firstAid'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='carry_firstAid' value="no" <?php echo (isset($work) && $work['carry_firstAid'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<th colspan=7>MISCELLANCES</th>
						</tr>
						<tr>
							<td  width="5%" align="center">1</td>
							<td  colspan=4 width="15%" align="center">DOES THE WORKER HAVE CLIENT APPROVAL FOR THE JOB ?</td>
							<td  width="15%" align="center"><input type="radio" name='client_approval' value="yes" <?php echo (isset($work) && $work['client_approval'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='client_approval' value="no" <?php echo (isset($work) && $work['client_approval'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
						<tr>
							<td  width="5%" align="center">2</td>
							<td  colspan=4 width="15%" align="center">ARE ALL PRE REQUISITE DOCUMENTATION WITH CLIENT DONE ?</td>
							<td  width="15%" align="center"><input type="radio" name='documentation_with_client' value="yes" <?php echo (isset($work) && $work['documentation_with_client'] =='yes')? 'checked' : '' ?> required>Yes</td>
							<td  width="15%" align="center"><input type="radio" name='documentation_with_client' value="no" <?php echo (isset($work) && $work['documentation_with_client'] =='no')? 'checked' : '' ?>>No</td>
						</tr>
					</table>
					<!-- MIDDLE PART END --> 
				</div>
				
				<div class="col-xs-12">
				<br />
					<div class="col-md-offset-4 col-md-5">
						<button type="submit" class="btn btn-success" name="work_permit" id="work_permit" value="work_permit">Submit</button>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a class="btn btn-info button" href="<?php echo $base_url;?>form_controller/assign_list">Back</a>
					</div>
				</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>