<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
			<?php if (! empty($message)) { ?>
				<div id="message" class="alert-success">
					<?php echo $message; ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="row" class="msg-display">
		<div class="col-md-12 text-center">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo '<span style="color:red"><strong>'.$this->session->flashdata('msg').'</strong></span>';
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span >CLIENT SITE ID's LIST</span>
				</div>
				<div class="panel-body">
					<?php if(!empty($client_site_data) && is_array($client_site_data)){?>
						<div class="table-responsive">
							<table class="table table-bordered" id="client_user_data">
								<thead>
									<th>Serial No.</th>
									<th>Client Name</th>
									<th>Job Card</th>
									<th>SMS</th>
									<th>Site ID</th>
								</thead>
								<tbody>
								<?php foreach($client_site_data as $key => $value){?>
										<tr>
											<td><?php echo $key+1; ?></td>
											<td><?php echo $value['clientName']; ?></td>
											<td><?php echo $value['site_jobcard']; ?></td>
											<td><?php echo $value['site_sms']; ?></td>
											<td><?php echo $value['site_id']; ?></td>
										</tr>	
								<?php }?>		
								</tbody>
							</table>
						</div>
					<?php }else{?>	
						<div  style="margin-top:18px;">
								<p style="background-color: #dff0d8;border-color: #d6e9c6;color: #3c763d;"><strong> No Data.</strong></p>
						</div>		
					<?php }?>	
				</div>
			</div>
		</div>
	</div>
	<!--<div class="row">
		<div class="col-md-5">
			<?php //if(!empty($inspected_site_data)){ ?>
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span >Reports of Site Id's which got Inspected</span>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="client_user_approved">
							<thead>
								<th>Serial No.</th>
								<th>View Report</th>
								<th>Report No.</th>
								<th>Site ID</th>
								<th>Inspected By</th>
								<th>Inspected Date</th>
							</thead>
							<tbody>
								<?php
									//$count = 1;
									//foreach($inspected_site_data as $data){
										//foreach($data as $sVal){ ?>
											<tr>
												<td><?php //echo $count; ?></td>
												<td class="text-center"><a target="_blank" title="View PDF" class="btn btn-danger btn-sm" href="<?php //echo $base_url;?>uploads/inspection_pdf/<?php // echo $sVal['report_no']; ?>.pdf"><span class="glyphicon glyphicon-file"></span></a></td>
												<td><?php //echo $sVal['report_no']; ?></td>
												<td><?php //echo $sVal['site_id']; ?></td>
												<td><?php //echo $sVal['inspector_name']; ?></td>
												<td><?php //echo $sVal['created_date']; ?></td>
											</tr>
								<?php 
									//$count++;
								//} } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php //} ?>
		</div>
	</div>-->
<script type="text/javascript">
    $(document).ready(function(){
		//search table
			$("#client_user_data").DataTable({
		   		   "order":[[ 0,"asc" ]],
				    "pageLength": 5,
					"lengthChange": false
		   });
	});
</script>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
