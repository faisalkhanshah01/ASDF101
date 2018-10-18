     <?php $this->load->view('includes/header'); ?> 
	 <script>
		$(document).ready(function(){
		$('.imeiNoVeryfication').click(function() {
			var data = {
				user_id:$(this).attr("id")
			};
			
			$.ajax({
				url: "<?php print base_url(); ?>auth_admin/update_imei_no",
				type: 'post',
				dataType: "json",
				data: data,
				success: function(output) { 
					alert(output.message);
					location.reload();
				}
			});  

			return false; 
		});
	});
</script>	
<?php $this->load->view('includes/head'); ?>
		
	<section class="panel panel-default">
		<div class="panel-heading">
			<strong><span class="glyphicon glyphicon-th"></span> IMEI No. List</strong>
		</div>
		<div class="panel-body">	
				<div class="row" >
					<div class="col-xs-12">
						<table id="imei_detail_datatable" class="table table-bordered table-hover tableview">
							<thead>
								<tr>
									<th>S.No.</th>
									<th>Email ID</th>
									<th>IMEI</th>
									<th>New IMEI</th>
									<th>Action</th>
								</tr>
							</thead>
							<?php if (!empty($imeiNo) && is_array($imeiNo)) { ?>
							<tbody>
								<?php $i =1;
                                    foreach ($imeiNo as $key => $value) { ?>
								<tr>
									<td><?php echo $i;?></td>
									<td><?php print $value['emil_id'];?></td>
									
									<td><?php $res = !empty($value['upro_mob_imei'])? $value['upro_mob_imei'] : 'N/A'; print $res;?></td>
									<td><?php $res = !empty($value['upro_mob_new_imei'])? $value['upro_mob_new_imei'] : 'N/A'; print $res;?></td>
									<td>
										<?php if(!empty($value['upro_mob_new_imei']) && ($value['upro_mob_imei'] == $value['upro_mob_new_imei'])){?>
											<div class="btn-group" role="group">
												<!--<button type="button" id=""  class="btn btn-success">
													<span class="glyphicon glyphicon-ban-circle"></span>
												</button>-->
											 
											</div>
											<?php }else{?>
												<div class="btn-group" role="group">
													<button type="button" id="<?php echo $value['upro_uacc_fk']; ?>"  class="imeiNoVeryfication btn btn-danger">
														<span class="glyphicon glyphicon-ok"></span>
													</button>
												</div>
											<?php }?>
									</td>
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
			$("#imei_detail_datatable").DataTable({
				"order":[[ 0,"asc" ]]
		   });
	   });
	</script>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>	