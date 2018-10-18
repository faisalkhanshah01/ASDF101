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
		<div class="col-md-12" >
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>APPROVED INSPECTION LIST</span>
				</div>
				<div class="panel-body">
					<table id="approved_table" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th><center>S.No</th>
							<th><center>Reoprt No.</center></th>
							<th><center>Site ID</center></th>
							<th><center>Job Card No.</center></th>
							<th><center>SMS No.</center></th>
							<th><center>Inspector Names</center></th>
							<th><center>Approved By</center></th>
							<th><center>Status</center></th>
							<th><center>View Report</center></th>
						</tr>
					</thead>
					<tbody>
						<?php  
							foreach($approved as $akey=>$aVal){
								$title  = "Approved Report Number : ";
								$acount = $akey+1;
						?>
						<tr>							
							<td class="text-center"><?php echo $acount; ?></td>
							<td class="text-center"><?php echo $aVal['report_no']; ?></td>
							<td class="text-center"><?php echo $aVal['site_id']; ?></td>
							<td class="text-center"><?php echo $aVal['job_card']; ?></td>
							<td class="text-center"><?php echo $aVal['sms']; ?></td>
							
							<td class="text-center"><?php echo $aVal['upro_first_name'].' '.$aVal['upro_last_name']; ?></td>
							<td class="text-center"><?php echo $aVal['admin_fname'].' '.$aVal['admin_lname']; ?></td>
							<td class="text-center"><p title="<?php echo $title.$aVal['report_no']; ?> " class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span></p></td>
									<td class="text-center" class="btn btn-primary btn-block" ><a target="_blank" title="Click To View PDF Report" class="btn btn-info btn-sm" href="<?php echo $base_url;?>uploads/inspection_pdf/<?php echo $aVal['report_no']; ?>.pdf"><span class="glyphicon glyphicon-file"></span></a>
							</td>								
						</tr>
						<?php } ?>
					</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>REJECTED INSPECTION LIST</span>
				</div>
				<div class="panel-body">
					<table id="rejected_table" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th><center>S.No</th>
							<th><center>Reoprt No.</center></th>
							<th><center>Site ID</center></th>
							<th><center>Job Card No.</center></th>
							<th><center>SMS No.</center></th>
							<th><center>Inspector Names</center></th>
							<th><center>Rejected By</center></th>
							<th><center>Status</center></th>
							<th><center>View Report</center></th>
						</tr>
					</thead>
						<tbody>
						<?php  
							foreach($rejected as $rkey=>$rval){ 
								$title  = "Rejected Report Number : ";
								$rcount = $rkey+1;
						?>
						<tr>						
							<td class="text-center"><?php echo $rcount; ?></td>
							<td class="text-center"><?php echo $rval['report_no']; ?></td>
							<td class="text-center"><?php echo $rval['site_id']; ?></td>
							<td class="text-center"><?php echo $rval['job_card']; ?></td>
							<td class="text-center"><?php echo $rval['sms']; ?></td>
							<td class="text-center"><?php echo $rval['upro_first_name'].' '.$rval['upro_last_name']; ?></td>
							<td class="text-center"><?php echo $aVal['admin_fname'].' '.$aVal['admin_lname']; ?></td>
							<td class="text-center"><p title="<?php echo $title.$rval['report_no']; ?> " class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></p></td>
									<td class="text-center" class="btn btn-primary btn-block" ><a target="_blank" title="Click To View PDF Report" class="btn btn-info btn-sm" href="<?php echo $base_url;?>uploads/inspection_pdf/<?php echo $rval['report_no']; ?>.pdf"><span class="glyphicon glyphicon-file"></span></a>
							</td>	
						</tr>
						<?php	} ?>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
