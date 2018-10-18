<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span >Inspection Upcoming List</span>
				</div>
				<div class="panel-body">
					<?php if(!empty($upcoming_data) && is_array($upcoming_data)){?>
						<div class="table-responsive">
							<table class="table table-bordered" id="client_past_data">
								<thead>
									<th>Serial No.</th>
									<th>Client Name</th>
									<th>Job Card</th>
									<th>SMS</th>
									<th>Site ID</th>
								</thead>
								<tbody>
								<?php $c= 1;foreach($upcoming_data as $key => $value){?>
										<tr>
											<td><?php echo $c; ?></td>
											<td><?php echo $value['client_name']; ?></td>
											<td><?php echo $value['site_jobcard']; ?></td>
											<td><?php echo $value['site_sms']; ?></td>
											<td><?php echo $value['site_id']; ?></td>
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
			$("#client_past_data").DataTable({
		   		   "order":[[ 0,"asc" ]],
				    "pageLength": 5,
					"lengthChange": false
		   });
	});
</script>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
