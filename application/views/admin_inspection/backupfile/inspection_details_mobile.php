<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php $this->load->view('includes/header_mobile'); ?>
	<div class="row" style="background-color: #404042; color: #ffffff; ">
		
		<legend class="home-heading">Inspection Details</legend>				
				
		<div class="table-responsive">
			<!-- UPPERMOST PART -->
				<table  width="100%" border="1" cellspacing="0" cellpadding="5">						
					<tr>
						<th width="20%" align="center" >Client Name</th>
						<td colspan="5" align="center"><?php echo $master_values['client_name']; ?></td>		
						<th width="10%" align="center">Report No.</th>		
						<td width="10%" align="center"><?php echo $inspection_values['report_no'] ; ?>
						</td>	
					</tr>
					<tr>
						<th width="20%" align="center">Address</th>
						<td colspan="5" align="center"><?php echo $client_address; ?></td>		
						<th width="10%" align="center">Date</th>		
						<td width="10%" align="center"><?php echo $inspection_values['created_date'] ; ?></td>	
					</tr>
					<tr>
						<th width="20%" align="center">Site ID:</th>		
						<td colspan="4" align="center"><?php echo $inspection_values['site_id'] ; ?></td>
						<th width="10%" rowspan="3">GPRS Location:</th>
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
						<th width="20%" align="center">Input Method:</th>
						<td width="10%" align="center"><?php echo $inspection_values['input_method'] ; ?></td>		
						<th width="20%" align="center">RFID/UNI/BARCODE:</th>
						<td colspan="2" align="center"><?php echo $inspection_values['input_value'] ; ?></td>	
					</tr>
					<tr>
						<th width="20%" align="center">Asset Series:</th>
						<td colspan="4" align="center"><?php echo $inspection_values['asset_series'] ; ?></td>		
					</tr>
					<tr>
						<th width="20%" align="center">Batch No.:</th>
						<td width="10%" align="center"><?php echo $master_values['mdata_batch']; ?></td>
						<th width="10%" align="center">Job Card No:</th>
						<td width="10%" colspan="2" align="center"><?php echo $inspection_values['job_card']; ?></td>
						<th width="10%" align="center">SMS No:</th>
						<td width="10%" align="center" colspan="2"><?php echo $inspection_values['sms'] ; ?></td>
					</tr>
					<tr>
						<th width="20%" align="center">Po No:</th>		
						<td width="10%" align="center"><?php echo $master_values['mdata_po']; ?></td>		
						<th width="20%" align="center">Serial No:</th>
						<td width="10%" align="center"><?php echo $master_values['mdata_serial']; ?></td>
						<th width="10%" colspan="2" align="center">Inspected By:</th>
						<td width="10%" colspan="2"  align="center"><?php echo $inspection_values['upro_first_name'].' '.$inspection_values['upro_last_name']; ?></td>
					</tr>
					<tr>
						<td colspan="8" height="20px"></td>			
					</tr>
				</table><!-- UPPERMOST PART END --> 
		</div>	  
		<!-- MIDDLE PART -->
		<div class="table-responsive">
				<table border="1" width="100%" cellspacing="0" cellpadding="5">
					<tr>
						<th width="5%"  align="center" >S.No</th>	
						<th width="15%" align="center" >Assets</th>
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
						<th width="5%" align="center" ><?php echo $count; ?></th>
						<td width="15%" align="center"><?php echo $middle_value['asset_name'] ;  ?></td>	
						<td width="15%" align="center"><?php echo ($middle_value['subAsset_name'] !='0')? $middle_value['subAsset_name'] : '' ;  ?></td>	
						<td width="10%" align="center"><img src="<?php echo str_replace('FCPATH',base_url(),$asset_image); ?>" title="Asset Image" width="50" height="50" alt="Asset Image" /></td>
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
						<th width="5%" align="center" >1</th>		
						<td width="15%" align="center">No Data</td>
						<td width="15%" align="center">No Data</td>
						<td width="10%" align="center">No Data</td>
						<td width="5%" 	align="center">No Data</td>
						<td width="10%" align="center">No Data</td>
						<td width="10%" align="center">No Data</td>
						<td width="30%" align="center">No Data</td>
					<?php } ?>
					<tr>
						<td colspan="8" width="20%" height="20px"></td>			
					</tr>
				</table>
		</div><!-- MIDDLE PART END --> 
					  
		<!-- BOTTOM PART -->
		<div class="table-responsive">
				<table border="1" width="100%" cellspacing="0" cellpadding="5">
					<tr>
						<th width="10%" ></th>
						<th width="20%" align="center" >Karam</th>
						<th width="20%" align="center" >Client</th>
						<th colspan="2" align="center" >Remarks</th>
					</tr>
					<tr>
						<th width="10%" align="center" >Name:</th>
							<td width="20%" align="center"><?php echo $inspection_values['upro_first_name'].' '.$inspection_values['upro_last_name']; ?></td>
							<td width="20%" align="center"><?php echo $inspection_values['client_name']; ?></td>
							<td colspan="2" rowspan="3" align="center"><?php echo $inspection_values['client_remark']; ?></td>				
						</tr>
					<tr>
						<th width="10%" align="center" >Designation:</th>
							<td width="20%" align="center"><?php echo $inspection_values['inspector_group']; ?></td>
							<td width="20%" align="center"><?php echo $inspection_values['client_designation']; ?></td>				
						</tr>
					<tr>
						<th width="10%" align="center" >Signature:</th>
							<td width="20%" align="center"><img src="<?php echo str_replace('FCPATH',base_url(),$inspection_values['inspector_signature_image']); ?>" class="asset_image" title="Inspector Signature" alt="Inspector Signature" width="100" height="80" /></td>
							<td width="20%" align="center"><img src="<?php echo str_replace('FCPATH',base_url(),$inspection_values['client_signature_image']); ?>" class="asset_image" title="Client Signature" alt="Client Signature" width="100" height="80" /></td>				
						</tr>
				</table>
		</div><!-- BOTTOM PART END -->
	</div>