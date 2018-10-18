<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/head'); ?>
	<div class="row" >
		<div class="col-md-12">
			<div class="col-md-12 text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>
			<div class="loader loading-images" style="display:none" id="mydiv">
				<center><img class="" src="<?php echo $base_url?>/includes/images/loadingImg.gif" alt="loading.." style="margin-top:400px;"></center>
			</div>
			<legend class="home-heading">Inspection Details</legend>				

			<div class="row">
				<?php echo form_open(current_url());	?>  
				<div class="col-xs-12">
					<!-- UPPERMOST PART -->
					<table  width="100%" border="1" cellspacing="0" cellpadding="5">						
						<tr>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Client Name</th>
							<td colspan="5" align="center"><?php echo $master_values['client_name']; ?></td>		
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Report No.</th>		
							<td width="10%" align="center"><?php echo $inspection_values['report_no'] ; ?>
								<?php $_SESSION['admin_inspection']['report_no'] = $inspection_values['report_no']; ?>
								<?php $_SESSION['admin_inspection']['inspection_table_id'] = $inspection_values['id']; ?>
							</td>	
						</tr>
						<tr>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Address</th>
							<td colspan="5" align="center"><?php echo $client_address; ?></td>		
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Date</th>		
							<td width="10%" align="center"><?php echo $inspection_values['created_date'] ; ?></td>	
						</tr>
						<tr>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Site ID:</th>		
							<td colspan="4" align="center"><?php echo $inspection_values['site_id'] ; ?></td>
							<th width="10%" rowspan="3" style="background-color: #E8E8E8; color: #333333;">GPRS Location:</th>		
							<td width="10%" colspan="2" rowspan="3" align="center">
							<?php	if($inspection_values['map_image'] !='FCPATH/uploads/images/users/default.jpg'){ ?>
									<img src="<?php echo str_replace('FCPATH/',base_url(),$inspection_values['map_image']); ?>" class="asset_image" title="Lattitude , Longitude : <?php echo $inspection_values['lattitude']." , ". $inspection_values['longitude']; ?>" alt="Lattitude , Longitude : <?php echo $inspection_values['lattitude']." , ". $inspection_values['longitude']; ?>" />
								<?php }else{ 
									if($inspection_values['lattitude']!='' && $inspection_values['longitude'] !=''){
									echo "Lattitude : ".$inspection_values['lattitude']." , <br />Longitude : ". $inspection_values['longitude'];
									}else{
										echo "No Lattitude and Longitude";
									}
								} ?>
							</td>
						</tr>
						<tr>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Input Method:</th>
							<td width="10%" align="center"><?php echo $inspection_values['input_method'] ; ?></td>		
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">RFID/UNI/BARCODE:</th>
							<td colspan="2" align="center"><?php echo $inspection_values['input_value'] ; ?></td>	
						</tr>
						<tr>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Asset Series:</th>
							<td colspan="4" align="center"><?php echo $inspection_values['asset_series'] ; ?></td>		
						</tr>
						<tr>	
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Batch No.:</th>
							<td width="10%" align="center"><?php echo $master_values['mdata_batch']; ?></td>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Job Card No:</th>
							<td width="10%" colspan="2" align="center"><?php echo $inspection_values['job_card']; ?></td>
							<th width="10%" align="center"  style="background-color: #E8E8E8; color: #333333;">SMS No:</th>
							<td width="10%" align="center" colspan="2"><?php echo $inspection_values['sms'] ; ?></td>
						</tr>
						<tr>
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Po No:</th>		
							<td width="10%" align="center"><?php echo $master_values['mdata_po']; ?></td>		
							<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Serial No:</th>
							<td width="10%" align="center"><?php echo $master_values['mdata_serial']; ?></td>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Inspected By:</th>
							<td width="10%" align="center"><?php echo $inspection_values['upro_first_name'].' '.$inspection_values['upro_last_name']; ?></td>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Approved By:</th>
							<td width="10%" style="color: #498BF5;" align="center"><?php echo $_SESSION['admin_inspection']['admin_name']; ?></td>
						</tr>
						<tr>
							<td colspan="10" height="20px"></td>			
						</tr>
					</table>
					<!-- UPPERMOST PART END -->

					<!-- Work Permit START -->
					<table border="1" width="100%" cellspacing="0" cellpadding="5">
					</table>
					<!-- Work Permit END -->
					
					
					<!-- MIDDLE PART -->
					<table border="1" width="100%" cellspacing="0" cellpadding="5">
						<tr>
							<th width="5%"  align="center" >S.No</th>	
							<th width="15%" align="center">Assets</th>
							<th width="15%" align="center" >Sub Asset</th>
							<th width="10%" align="center" >Image</th>
							<th width="5%"  align="center" >Qty.</th>
							<th width="10%" align="center" >Type of Inspection</th>
							<th colspan='3' width="10%" align="center" >Observation/ Action/ Result</th>
							<th colspan='2' width="30%" align="center">Image of Inspected Assets</th>
						</tr>
						<?php if(!empty($inspection_data))
							{
								$count = 1;
								foreach($inspection_data as $middle_value){
									$asset_image =  ($middle_value['asset_image']!='')? $middle_value['asset_image'] : 'FCPATH/uploads/images/users/default.jpg';
									$before_repair_image_array 	= ($middle_value['before_repair_image'] != 'null')? json_decode($middle_value['before_repair_image']) : 'FCPATH/uploads/images/users/default.jpg';
									$beforeImage = (is_array($before_repair_image_array))? $before_repair_image_array[0] : $before_repair_image_array;
									$after_repair_image 	= json_decode($middle_value['after_repair_image']);
								?>
						<tr>
							<th width="5%" align="center" style="background-color: #E8E8E8; color: #333333;"><?php echo $count; ?></th>
							<td width="15%" align="center"><?php echo $middle_value['asset_name'] ;  ?></td>	
							<td width="15%" align="center"><?php echo ($middle_value['subAsset_name'] !='0')? $middle_value['subAsset_name'] : '' ;  ?></td>
							<td width="10%" align="center" ><img src="<?php echo str_replace('FCPATH',base_url(),$asset_image); ?>" id="myImg" class="asset_image" title="Asset Image" width="50" height="50" alt="Asset Image" /></td>
							<td width="5%" align="center"><?php echo $middle_value['asset_qty'] ;  ?></td>
							<td width="10%" align="center"><?php echo $middle_value['inspection_type_name'] ;  ?></td>
							<td width="10%" colspan="3">
								<table style="margin-bottom:0" class="table table-bordered">
									<tr>
										<th  align="center">Observations</th>
										<th  align="center">Action Proposed</th>
										<th  align="center">Results</th>
									</tr>
									<?php 
										if(is_array($middle_value['observation_name'])){
											foreach($middle_value['observation_name'] as $obsKey=>$obsVal){
												echo "<tr>
														<td style='border:1px solid black'>".$obsVal."</td>
														<td style='border:1px solid black'>".$middle_value['action_proposed_name'][$obsKey]."</td>
														<td style='border:1px solid black'>".$middle_value['result_array'][$obsKey]."</td>
													</tr>";
											}
										}else{
											echo "<tr>
													<td style='border:1px solid black'>".$middle_value['observation_name']."</td>
													<td style='border:1px solid black'>".$middle_value['action_proposed_name']."</td>
													<td style='border:1px solid black'>".strtoupper($middle_value['result'])."</td>
											</tr>";
										} ?>
								</table>
							</td>
							<td width="30%" align="center">
								<table style="margin-bottom:0" class="table table-bordered">
									<tr>
										<th align="center">Before Image</th>
										<th align="center">After Image</th>
									</tr>
									<body>
									<tr>
										<td><img src="<?php echo str_replace('FCPATH',base_url(),$beforeImage); ?>" class="asset_image thumbnail" title="Before Repair Image" width="100" height="100" alt="Before Repair Image" /></td>
										<td><img src="<?php echo str_replace('FCPATH',base_url(),$after_repair_image[0]); ?>" class="asset_image thumbnail" title="After Repair Image" width="100" height="100" alt="After Repair Image" /></td>
									</tr>
									</body>
								</table>
							</td>
						</tr>
						<?php $count ++ ; } }
						else{ ?>
							<th width="5%" 	align="center" style="background-color: #E8E8E8; color: #333333;">1</th>		
							<td width="15%" align="center">No Data</td>
							<td width="15%" align="center">No Data</td>
							<td width="10%" align="center">No Data</td>
							<td width="5%" 	align="center">No Data</td>
							<td width="10%" align="center">No Data</td>
							<td width="10%" align="center">No Data</td>
							<td width="30%" align="center">No Data</td>
						<?php } ?>
						<tr>
							<td colspan="10" width="20%" height="20px"></td>			
						</tr>
					</table>
					<!-- MIDDLE PART END --> 

					<!-- BOTTOM PART -->
					<table border="1" width="100%" cellspacing="0" cellpadding="5">
						<tr>
							<th width="10%"></th>
							<th width="20%" align="center">Karam</th>
							<th width="20%" align="center">Client</th>
							<th colspan="2" align="center">Remarks</th>
						</tr>
						<tr>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Name:</th>
							<td width="20%" align="center"><?php echo $inspection_values['upro_first_name'].' '.$inspection_values['upro_last_name']; ?></td>
							<td width="20%" align="center"><?php echo $inspection_values['client_name']; ?></td>
							<td colspan="2" rowspan="3" align="center"><?php echo $inspection_values['client_remark']; ?></td>				
						</tr>
						<tr>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Designation:</th>
							<td width="20%" align="center"><?php echo $inspection_values['inspector_group']; ?></td>
							<td width="20%" align="center"><?php echo $inspection_values['client_designation']; ?></td>				
						</tr>
						<tr>
							<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Signature:</th>
							<td width="20%" align="center"><img src="<?php echo str_replace('FCPATH',base_url(),$inspection_values['inspector_signature_image']); ?>" class="asset_image" title="Inspector Signature" alt="Inspector Signature" width="100" height="80" /></td>
							<td width="20%" align="center"><img src="<?php echo str_replace('FCPATH',base_url(),$inspection_values['client_signature_image']); ?>" class="asset_image" title="Client Signature" alt="Client Signature" width="100" height="80" /></td>				
						</tr>
					</table>
					<!-- BOTTOM PART END -->
				</div>
				<?php
				if($_SESSION['user_group'] !='9'){
				?>
				<div class="col-xs-12">
					<div class="col-md-offset-4 col-md-5">
						<br>
						<!--
						<button type="submit" class="btn btn-success" name="approved_inspection" id="approved_inspection" value="approved_inspection">Approved</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="submit" class="btn btn-danger" name="reject_inspection" id="reject_inspection" value="reject_inspection">Reject</button>
						-->
						<a  href="javascript:void(0)" title="approved_inspection" class="btn btn-success single">Approved</a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a  href="javascript:void(0)" title="reject_inspection" class="btn btn-danger single">Reject</a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a class="btn btn-info button" href="<?php echo $base_url;?>inspector_inspection">Back</a>
					</div>
				</div>
				<?php echo form_close();?>
				<?php }else{ ?>
					
					<div class="col-xs-12">
					
					<br /><a href="<?php echo base_url(); ?>dashboard_controller/inspection_reports_details?status=Pending" class="btn btn-primary pull-right">BACK</a>
					</div>
				<?php } ?>
			</div>
			<!-- The Modal -->
			<div id="myModal" class="modal">
				<span class="close">×</span>
				<img class="modal-content" id="img01" >
				<div id="caption"></div>
			</div>
		</div>
	</div>
<script>
$(document).ready(function(e) {
	
});

</script>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
