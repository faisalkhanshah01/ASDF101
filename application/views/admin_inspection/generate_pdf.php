<div  id="generate_pdf">
	<div  class="row">
		<div class="col-md-12">			
		 	<table width="100%" border="1" cellspacing="0" cellpadding="5">	
				<tr>
					<?php //$color = ($approved_status =='Approved')? 'Green' : 'Red'; ?>
					<?php $img = ($approved_status =='Approved')? 'approved.png' : 'rejected.png'; ?>
					<td colspan="3" width="80%" style="border-right: none;" height="50px">
							<img src="http://karam.in/kare/includes/images/karam_logo.png" title="kare logo" />
					</td>
					<!--<td colspan="5"  align="center"  width="20%" style="border-left: none;" align="center" height="50px"><h2><font size="10" color="<?php echo $color; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $approved_status; ?></font></h2></td>-->
					<td colspan="5"  align="center"  width="20%" style="border-left: none;" align="center" height="50px">&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo base_url(); ?>includes/images/<?php echo $img; ?>" alt="" width="200" height="150" /></td>
				</tr>
				<tr>
					<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Client Name</th>
					<td colspan="5" align="center"><?php echo $master_values['client_name']; ?></td>		
					<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Report No.</th>		
					<td width="10%" align="center"><?php echo $inspection_values['report_no'] ; ?></td>	
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
						<?php 
							if($inspection_values['map_image'] !='FCPATH/uploads/images/users/default.jpg'){ ?>
								<img src="<?php echo str_replace('FCPATH/',base_url(),$inspection_values['map_image']); ?>" class="asset_image" title="Lattitude , Longitude : <?php echo $inspection_values['lattitude']." , ". $inspection_values['longitude']; ?>" alt="Lattitude , Longitude : <?php echo $inspection_values['lattitude']." , ". $inspection_values['longitude']; ?>" />
							<?php }else{ 
								if($inspection_values['lattitude']!='' && $inspection_values['longitude'] !=''){
									echo "Lattitude : ".$inspection_values['lattitude']." , <br />Longitude : ". $inspection_values['longitude'];
									}else{
										echo "No Lattitude and Longitude Provided";
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
					<td width="10%"  align="center"><?php echo $inspection_values['upro_first_name'].' '.$inspection_values['upro_last_name']; ?></td>
					<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Approved By:</th>
					<td width="10%" style="color: #498BF5;" align="center"><?php echo $_SESSION['firstName']." ". $_SESSION['lastName']; ?></td>
					
				</tr>
				<tr>
					<td colspan="8" height="20px"></td>			
				</tr>
			</table>
			<!-- UPPERMOST PART END --> 

			<!-- MIDDLE PART -->
			<table  border="1" width="100%" cellspacing="0" cellpadding="5">					
				<thead style="display: table-header-group">
					<tr>
						<th width="5%"  align="center" style="background-color: #E8E8E8; color: #333333;">S.No</th>	
						<th width="15%" align="center" style="background-color: #E8E8E8; color: #333333;">Assets</th>
						<th width="15%" align="center" style="background-color: #E8E8E8; color: #333333;">Sub Asset</th>
						<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Image</th>
						<th width="5%"  align="center" style="background-color: #E8E8E8; color: #333333;">Qty.</th>
						<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Type of Inspection</th>
						<th width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Observation/ Action/ Result</th>
						<th width="30%" align="center" style="background-color: #E8E8E8; color: #333333;">Image of Inspected Assets</th>
					</tr>
				</thead>
				<tbody>
					<?php if(!empty($inspection_data))
					{
						$insCount = 1;
						foreach($inspection_data as $middle_value){
								$result = strtoupper($middle_value['result']);
						
							$asset_image =  ($middle_value['asset_image']!='')? $middle_value['asset_image'] : 'FCPATH/uploads/images/users/default.jpg';
							$before_repair_image_array 	= ($middle_value['before_repair_image'] != 'null')? json_decode($middle_value['before_repair_image']) : 'FCPATH/uploads/images/users/default.jpg';
							$beforeImage = (is_array($before_repair_image_array))? $before_repair_image_array[0] : $before_repair_image_array;
							$after_repair_image 	= json_decode($middle_value['after_repair_image']);
					?>
						<tr>
							<td width="5%" 	align="center" style="background-color: #E8E8E8; color: #333333;"><?php echo $insCount; ?></td>
							<td width="15%" align="center"><?php echo $middle_value['asset_name'] ;  ?></td>	
							<td width="15%" align="center"><?php echo ($middle_value['subAsset_name'] !='0')? $middle_value['subAsset_name'] : '' ;  ?></td>	
							<td width="10%" align="center"><img src="<?php echo str_replace('FCPATH',base_url(),$asset_image); ?>" title="Asset Image" width="50" height="50" alt="Asset Image" /></td>
							<td width="5%" 	align="center"><?php echo $middle_value['asset_qty'] ;  ?></td>
							<td width="10%" align="center"><?php echo $middle_value['inspection_type_name'] ;  ?></td>
							<td>
								<table border="1" width="100%" cellspacing="0">
									<tr>
										<th style="background-color: #E8E8E8; border:1px solid black; margin:0;padding:0;">Observations</th>
										<th style="background-color: #E8E8E8; border:1px solid black; margin:0;padding:0;">Action Proposed</th>
										<th style="background-color: #E8E8E8; border:1px solid black; margin:0;padding:0;">Results</th>
									</tr>
									
									<?php 
										if(is_array($middle_value['observation_name'])){
											foreach($middle_value['observation_name'] as $obsKey=>$obsVal){
												echo "<tr style='border:1px solid black; margin:0;padding:0;'>
														<td style='border:1px solid black; margin:0;padding:0;'>".$obsVal."</td>
														<td style='border:1px solid black; margin:0;padding:0;'>".$middle_value['action_proposed_name'][$obsKey]."</td>
														<td style='border:1px solid black; margin:0;padding:0;'>".$middle_value['result_array'][$obsKey]."</td>
													</tr>";
											}
										}else{
											echo "<tr style='border:1px solid black; margin:0;padding:0'>
													<td style='border:1px solid black; margin:0;padding:0;'>".$middle_value['observation_name']."</td>
													<td style='border:1px solid black; margin:0;padding:0;'>".$middle_value['action_proposed_name']."</td>
													<td style='border:1px solid black; margin:0;padding:0;'>".strtoupper($middle_value['result'])."</td>
											</tr>";
										} ?>
								</table>
							</td>
							<td>
								<table border="1" width="100%" cellspacing="0">
									<tr>
										<th align="center" style="background-color: #E8E8E8; color: #333333;">Before Image</th>
										<th align="center" style="background-color: #E8E8E8; color: #333333;">After Image</th>
									</tr>
									<tbody>
										<tr>
											<td><img src="<?php echo str_replace('FCPATH',base_url(),$beforeImage); ?>" title="Before Repair Image" width="70" height="70" alt="Before Repair Image" /></td>	
											<td><img src="<?php echo str_replace('FCPATH',base_url(),$after_repair_image[0]); ?>" title="After Repair Image" width="70" height="70" alt="After Repair Image" /></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>						
					<?php 	$insCount++;
						}
					} else{ ?>
					<tr>
						<td width="5%" align="center" style="background-color: #E8E8E8; color: #333333;">1</td>
						<td width="20%">No Data</td>		
						<td width="10%" align="center">No Data</td>
						<td width="5%" align="center">No Data</td>
						<td width="10%" align="center">No Data</td>
						<td width="20%">No Data</td>
						<td width="10%" align="center">No Data</td>
						<td width="30%"align="center">No Data</td>				
					</tr>
				<?php } ?>
					<tr>
						<td colspan="8" width="20%" height="20px"></td>			
					</tr>
				</tbody>
			</table>
			<!-- MIDDLE PART END --> 
					  
			<!-- BOTTOM PART -->
			<table border="1" width="100%" cellspacing="0" cellpadding="5">
				<tr>
					<th width="10%" style="background-color: #E8E8E8;"></th>
					<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Karam</th>
					<th width="20%" align="center" style="background-color: #E8E8E8; color: #333333;">Client</th>
					<th colspan="2" align="center" style="background-color: #E8E8E8; color: #333333;">Remarks</th>
				</tr>
				<tr>
					<td width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Name:</td>
					<td width="20%" align="center"><?php echo $inspection_values['upro_first_name'].' '.$inspection_values['upro_last_name']; ?></td>
					<td width="20%" align="center"><?php echo $inspection_values['client_name']; ?></td>
					<td colspan="2" rowspan="3" align="center"><?php echo $inspection_values['client_remark']; ?></td>				
				</tr>
				<tr>
					<td width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Designation:</td>
					<td width="20%" align="center"><?php echo $inspection_values['inspector_group']; ?></td>
					<td width="20%" align="center"><?php echo $inspection_values['client_designation']; ?></td>				
				</tr>
				<tr>
					<td width="10%" align="center" style="background-color: #E8E8E8; color: #333333;">Signature:</td>
					<td width="20%" align="center"><img src="<?php echo str_replace('FCPATH',base_url(),$inspection_values['inspector_signature_image']); ?>" alt="inspector_image" width="80" height="40" /></td>
					<td width="20%" align="center"><img src="<?php echo str_replace('FCPATH',base_url(),$inspection_values['client_signature_image']); ?>" alt="client_image" width="80" height="40" /></td>				
				</tr>
			</table>
			<!-- BOTTOM PART END -->
		</div>
	</div>
</div>