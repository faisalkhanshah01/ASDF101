<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span >Manage Sites LIST</span>
				</div>
				<div class="panel-body">
					<?php if(!empty($manage_inspector) && is_array($manage_inspector)){?>
						<div class="table-responsive">
							<table class="table table-bordered" id="manage_data">
								<thead>
									<th>Serial No.</th>
									<th>Client Name</th>
									<th>Job Card</th>
									<th>SMS</th>
									<th>Report No</th>
									<th>Site ID</th>
									<th>Status</th>
									<th>Report</th>
								</thead>
								<tbody>
								<?php $c= 1;foreach($manage_inspector as $key => $value){?>
										<tr>
											<td><?php echo $c; ?></td>
											<td><?php echo $value['client_name']; ?></td>
											<td><?php echo $value['job_card']; ?></td>
											<td><?php echo $value['sms']; ?></td>
											<td><?php echo !empty($value['report_no'])?$value['report_no']:'N/A'; ?></td>
											<td><?php echo $value['site_id']; ?></td>
											<td><?php echo $value['approved_status']; ?></td>
											<td><?php $res = !empty($value['report'])?$value['report']:'';echo $res; ?></td>
										</tr>	
								<?php $c++;}?>		
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
	
<script type="text/javascript">
    $(document).ready(function(){
		//search table
			$("#manage_data").DataTable({
		   		   "order":[[ 0,"asc" ]],
				    "pageLength": 5,
					"lengthChange": false
		   });
	});
</script>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
