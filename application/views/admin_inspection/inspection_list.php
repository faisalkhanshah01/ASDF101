<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?> 
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-12 text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>

			<legend class="home-heading"><?php if( $lang["inspection_list"]['description'] !='' ){ echo $lang["inspection_list"]['description']; }else{ echo 'Inspection List'; } ?></legend>

			<?php echo form_open(current_url());?>  	
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered table-hover assign_site_table">
							<thead>
								<th><?php if( $lang["s_no"]['description'] !='' ){ echo $lang["s_no"]['description']; }else{ echo 'S.No'; } ?></th>
								<th><?php if( $lang["report_number"]['description'] !='' ){ echo $lang["report_number"]['description']; }else{ echo 'Report Number'; } ?></th>
								<th><?php if( $lang["site_id"]['description'] !='' ){ echo $lang["site_id"]['description']; }else{ echo 'Site ID'; } ?></th>
								<th><?php if( $lang["jobcard_number"]['description'] !='' ){ echo $lang["jobcard_number"]['description']; }else{ echo '	JobCard Number'; } ?></th>
								<th><?php if( $lang["sms_number"]['description'] !='' ){ echo $lang["sms_number"]['description']; }else{ echo 'SMS Number'; } ?></th>
								<th><?php if( $lang["inspector_name"]['description'] !='' ){ echo $lang["inspector_name"]['description']; }else{ echo 'Inspector Name'; } ?></th>
								<th width='20%' ><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo 'Action'; } ?></th>
							</thead>
							<tbody>
								<?php if(!empty($inspection_list)){
									foreach($inspection_list as $key=>$values) {
										if($values['approved_status'] =='Pending'){
											$title  = "Click to Inspect Report Number : ";
										}else if($values['approved_status'] =='Approved'){
											$title  = "Approved Report Number : ";
										}else if($values['approved_status'] =='Rejected'){
											$title  = "Rejected Report Number : ";
										}
										$count = $key+1;
								?>
									<tr>
										<td class="text-center"><?php echo $count; ?></td>
										<td class="text-center"><?php echo $values['report_no']; ?></td>
										<td class="text-center"><?php echo $values['site_id']; ?></td>
										<td class="text-center"><?php echo $values['job_card']; ?></td>
										<td class="text-center"><?php echo $values['sms']; ?></td>
										<td class="text-center"><?php echo $values['upro_first_name'].' '.$values['upro_last_name']; ?></td>
										
										<!--<td class="text-center"><?php //echo $status; ?></td>-->
										<td width="15%">
										<?php if($values['approved_status'] =='Pending'){ ?>
											<a title="<?php echo $title.$values['report_no']; ?> " class="btn btn-warning btn-sm" href="<?php echo $base_url;?>inspector_inspection/view_inspection?id=<?php echo $values['id']; ?>">Pending Report</a>
										<?php  }else if($values['approved_status'] =='Approved'){ ?>
											<p title="<?php echo $title.$values['report_no']; ?> " class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span></p>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" title="View PDF" href="<?php echo $base_url;?>uploads/inspection_pdf/<?php echo $values['report_no']; ?>.pdf"><span class="glyphicon glyphicon-file"></span></a>
										<?php  }else{ ?>
											<span title="<?php echo $title.$values['report_no']; ?> " class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></span>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" title="View PDF"  href="<?php echo $base_url;?>uploads/inspection_pdf/<?php echo $values['report_no']; ?>.pdf"><span class="glyphicon glyphicon-file"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
											<a title="Click To Delete Report" class="text-danger delete" href="<?php echo $base_url;?>inspector_inspection/delete_report/<?php echo $values['id']; ?>"><span class="glyphicon glyphicon-trash"></span></a>
										<?php  } ?>
										</td>
									</tr>
								<?php //$countValue ++ ; 
									} 
								}else{ ?>
										<tr>
											<td class="text-center" colspan="100%" ><?php if( $lang["no_data_available"]['description'] !='' ){ echo $lang["no_data_available"]['description']; }else{ echo 'No data Available'; } ?></td>											
										</tr>						
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php echo form_close();?>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>