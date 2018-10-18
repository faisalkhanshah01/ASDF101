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
					<span>Inspection Lists</span>
				</div>
				<div class="panel-body">
					<?php if(isset($inspection_status)){ ?>
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
								<th><center>Action</center></th>				
							</tr>
						</thead>
						<tbody>
								<?php  
									foreach($inspection_status as $akey=>$aVal){
										$title  = $aVal['approved_status']." Report Number : ";
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
										<td class="text-center" width="15%">
										<?php if($aVal['approved_status'] =='Pending'){ ?>
											<a title="<?php echo $title.$aVal['report_no']; ?> " class="btn btn-warning btn-sm" href="<?php echo $base_url;?>inspector_inspection/view_inspection?id=<?php echo $aVal['id']; ?>">Pending Report</a>
										<?php  }else if($aVal['approved_status'] =='Approved'){ ?>
											<p title="<?php echo $title.$aVal['report_no']; ?> " class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span></p>&nbsp;&nbsp;<a target="_blank" title="View PDF" class="btn btn-info btn-sm" href="<?php echo $base_url;?>uploads/inspection_pdf/<?php echo $aVal['report_no']; ?>.pdf"><span class="glyphicon glyphicon-file"></span></a>
										<?php  }else{ ?>
											<p title="<?php echo $title.$aVal['report_no']; ?> " class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span></p>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" title="View PDF" class="btn btn-info btn-sm" href="<?php echo $base_url;?>uploads/inspection_pdf/<?php echo $aVal['report_no']; ?>.pdf"><span class="glyphicon glyphicon-file"></span></a>
										<?php  } ?>
										</td>
							</tr>
							<?php } ?>
						</tbody>
						</table>
					<?php } ?>
					<?php if(isset($notInspected_status) && $user_type =='admin'){ ?>
						<table id="approved_table" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th><center>S.No</th>
								<th><center>Site ID</center></th>
								<th><center>Job Card No.</center></th>
								<th><center>SMS No.</center></th>
								<th><center>Inspector Names</center></th>
								<th><center>Assigned Date</center></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(!empty($notInspected_status)){
								$count = 0;
								foreach($notInspected_status as $nkey=>$nVal){
									$count = $count+1;
									$lastweek = strtotime('-1 weeks');;
									$dbdate = strtotime($nVal['inspector_created_date']);
									$cdate = date_format(date_create($nVal['inspector_created_date']),"d-M-Y");
									$createdDate = ($dbdate > $lastweek)? '<b><font color="red">'.$cdate.'</font></b>' : $cdate;
							?>
							<tr>
										<td class="text-center"><?php echo $count; ?></td>
										<td class="text-center"><?php echo $nVal['siteID_name']; ?></td>
										<td class="text-center"><?php echo $nVal['inspector_jobCard']; ?></td>
										<td class="text-center"><?php echo $nVal['inspector_sms']; ?></td>
										<td class="text-center"><?php echo $nVal['inspector_name']; ?></td>
										<td class="text-center"><?php echo $createdDate; ?></td>
							</tr>
					<?php } } ?>
						</tbody>
						</table>
					<?php } ?>
					<?php if(isset($notInspected_status) && $user_type =='inspector') { ?>
						<table id="approved_table" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th><center>S.No</th>
								<th><center>Site ID</center></th>
								<th><center>Job Card No.</center></th>
								<th><center>SMS No.</center></th>
								<th><center>Inspector Names</center></th>
								<th><center>Assigned Date</center></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(!empty($notInspected_status)){
								$count = 0;
								foreach($notInspected_status as $nkey=>$nVal){
									$count = $count+1;
									$createdDate = date("d-M-Y",$nVal['created_date']);
							?>
							<tr>
										<td class="text-center"><?php echo $count; ?></td>
										<td class="text-center"><?php echo $nVal['site_id']; ?></td>
										<td class="text-center"><?php echo $nVal['site_jobcard']; ?></td>
										<td class="text-center"><?php echo $nVal['site_sms']; ?></td>
										<td class="text-center"><?php echo $_SESSION['firstName'].' '.$_SESSION['lastName']; ?></td>
										<td class="text-center"><?php echo $createdDate; ?></td>
							</tr>
					<?php } } ?>
						</tbody>
						</table>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
