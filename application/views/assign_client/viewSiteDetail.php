  <?php $this->load->view('includes/header'); ?> 
<?php $this->load->view('includes/head'); ?>
		<div style="float:right;margin-top:6px; margin-right:17px;">
			<a href="<?php print base_url()."assign_client_controller/assign_siteID";?>" class="btn btn-info btn-sm">
			  <span class="glyphicon glyphicon-menu-left"></span> 
			</a>
		</div>
	<section class="panel panel-default">
		<div class="panel-heading">
			<strong><span class="glyphicon glyphicon-th"></span> List</strong>
		</div>
		<div class="panel-body">	
				<div class="row" >
					<div class="col-xs-12">
						<table id="viewsite_detail_datatable" class="table table-bordered table-hover tableview">
							<thead>
								<tr>
									<th>S.No.</th>
									<th>Client Name</th>
									<th>Site Detail</th>
									<th>Site Address</th>
									<th>Report No</th>
									<th>Asset Series</th>
									<th>Inspector Name</th>
									<th>Approved Status</th>
								</tr>
							</thead>
							<?php if (!empty($siteDetail) && is_array($siteDetail)) { ?>
							<tbody>
								<?php $i =1;
                                    foreach ($siteDetail as $key => $value) { ?>
								<tr>
									<td><?php echo $i;?></td>
									<td><?php print $value['client_name'];?></td>
									<td>
										<span style=" width: 300px;height: 150px;">
											<?php print "<b>Site ID :</b> ".$value['site_id'];?><br/>
											<?php print "<b>Job Card :</b> ".$value['job_card'];?><br/>
											<?php print "<b>SMS :</b> ".$value['sms'];?>
										</span>
									</td>
									<td>
										<?php $address = explode(",", $value['site_address']); $addres = implode("<br/>",$address);?>
										<span  style=" width: 300px;height: 150px;">
											<?php print "<b>State : </b>".$value['site_location'];?>,<br/>
											<?php print "<b>City : </b>".$value['site_city'];?>,<br/>
											<?php print "<b>Address : </b>".$addres;?>
										</span>
									</td>
									<td><?php $res = !empty($value['report_no'])? $value['report_no'] : 'N/A'; print $res;?></td>
									<td><?php $res = !empty($value['asset_series'])? $value['asset_series'] : 'N/A'; print $res;?></td>
									<td><?php print $value['name'];?></td>
									<td><?php  print $value['approved_status'];?></td>
								</tr>
								<?php $i++;} ?>
							</tbody>
                                                    <?php } else { ?>
                                                            <tbody>
                                                                    <tr>
                                                                            <td colspan="7" class="highlight_red">
                                                                                    No Data are available.
                                                                            </td>
                                                                    </tr>
                                                            </tbody>
                                                    <?php } ?>
						</table>
					</div>
				</div>        
				  
			</div>
	</section>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#viewsite_detail_datatable").DataTable({
				"order":[[ 0,"asc" ]]
		   });
	   });
	</script>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>	