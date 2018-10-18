<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?> 
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>
			<legend class="home-heading">Inspector Assign Site ID</legend>

			<?php echo form_open(current_url());	?>  	
				<div class="row">
					<div class="col-xs-12">
						<table class="table table-bordered table-hover assign_site_table">
							<thead>							
								<tr>
									<th>S.No</th>
									<th>Job Card Number</th>
									<th>SMS Number</th>
									<th>Assign Site ID</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($assign_list)){
									$count = 1;
									foreach($assign_list as $values) { ?>
								<tr>
									<td class="text-center"><?php echo $count; ?></td>
									<td class="text-center"><?php echo $values['site_jobcard']; ?></td>
									<td class="text-center"><?php echo $values['site_sms']; ?></td>
									<td class="text-center"><?php echo $values['site_id']; ?></td>
									<?php if($values['approved_status'] == '' || $values['approved_status'] != 'Pending'){ ?>
											<td class="text-center"><a href="<?php echo $base_url;?>form_controller/work_permit_form_inspection/<?php echo $values['siteID_id']; ?>">Get Inspection</a></td>
									<?php
										}else{
											echo "<td class='text-center'><font color='red'>Inspected</font></td>";
										} ?>
								</tr>
							<?php $count ++ ;  } } else{ ?>
									<div  style="margin-top:18px;">
											<p style="background-color: #dff0d8;border-color: #d6e9c6;color: #3c763d;"><strong> No Data.</strong></p>
									</div>							
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