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
			
			<?php if(!empty($imeiNo)){ foreach ($imeiNo as $value) { ?> 
				$('#popupsubmit_<?php echo $value['upro_id'];?>').click(function(){
					var data = {
						upro_uacc_fk:$('#upro_uacc_fk_<?php echo $value['upro_uacc_fk'];?>').val(),
						upro_id:$('#upro_id_<?php echo $value['upro_id'];?>').val(),
						upro_mob_imei:$('#upro_mob_imei_<?php echo $value['upro_id'];?>').val(),
						upro_mob_new_imei:$("#upro_mob_new_imei_<?php echo $value['upro_id'];?>").val(),
						submit:1
					};
					$.ajax({
						url: '<?php print base_url(); ?>auth_admin/update_imei',
						type: 'post',
						dataType: "json",
						data: data,
						success: function(output) {
							if(output.responseType == 'fail'){
								alert(output.message);
							}else{
								alert(output.message);
								location.reload();
							   //location.href=location.href;
							}     
						   
						}
					});  
					return false;
				});
		<?php }}?> 
			
		});
</script>	
<style>
	/* visited link */
	a{
		color: blue;
	}

	/* mouse over link */
	a:hover {
		color: green;
	}

</style>
<?php $this->load->view('includes/head'); ?>
		
	<section class="panel panel-default">
		<div class="panel-heading">
			<strong><span class="glyphicon glyphicon-th"></span><?php if( $lang["imei_no_list"]['description'] !='' ){ echo $lang["imei_no_list"]['description']; }else{ echo "IMEI No. List"; }  ?></strong>
		</div>
		<div class="panel-body">	
				<div class="row" >
					<div class="col-xs-12">
						<table id="imei_detail_datatable" class="table table-bordered table-hover tableview">
							<thead>
								<tr>
									<th><?php if( $lang["s_no"]['description'] !='' ){ echo $lang["s_no"]['description']; }else{ echo "S. No."; }  ?></th>
									<th><?php if( $lang["email"]['description'] !='' ){ echo $lang["email"]['description']; }else{ echo "Email"; }  ?></th>
									<th><?php if( $lang["imei"]['description'] !='' ){ echo $lang["imei"]['description']; }else{ echo "IMEI"; }  ?></th>
									<th><?php if( $lang["new_imei"]['description'] !='' ){ echo $lang["new_imei"]['description']; }else{ echo "New IMEI"; }  ?></th>
									<th><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo "Action"; }  ?></th>
								</tr>
							</thead>
							<?php if (!empty($imeiNo) && is_array($imeiNo)) { ?>
							<tbody>
								<?php $i =1;
                                    foreach ($imeiNo as $key => $value) { ?>
								<tr>
									<td><?php echo $i;?></td>
									<!--<td><?php print $value['emil_id'];?></td>-->
									<td><a href="#" data-toggle="modal" data-target="#modalRegister_<?php echo $value['upro_id'];?>"><?php  echo $value['emil_id']; ?></a></td>
									
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
						
						<?php foreach ($imeiNo as $value) { ?>
							<div id="modalRegister_<?php echo $value['upro_id'];?>" class="modal fade" role="dialog">
							
								<div class="modal-dialog">
									<!-- Modal content-->
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title" style="text-align-last: center"><?php  echo $value['upro_first_name'].' '.$value['upro_last_name']; ?></h4>
										</div>
										<div class="modal-body">
											<form class="form" id ="category_data">
												<input style="margin-top:10px;" class="form-control" id="upro_id_<?php echo $value['upro_id'];?>" type="hidden" name="upro_id" value="<?php echo $value['upro_id'];?>">
												<input style="margin-top:10px;" class="form-control" id="upro_uacc_fk_<?php echo $value['upro_uacc_fk']; ?>" type="hidden" name="upro_id" value="<?php echo $value['upro_uacc_fk']; ?>">
												<div class="form-group">
													<label for="imei-name" class="form-control-label">IMEI :</label>
													<input style="margin-top:10px;" class="form-control"  type="text" name="upro_mob_imei" id="upro_mob_imei_<?php echo $value['upro_id'];?>" value="<?php echo $value['upro_mob_imei'];?>">
												</div>
												<div class="form-group">
													<label for="imei-name" class="form-control-label">New IMEI :</label>
													<input style="margin-top:10px;" class="form-control"  type="text" name="upro_mob_new_imei" id="upro_mob_new_imei_<?php echo $value['upro_id'];?>" value="<?php echo $value['upro_mob_new_imei'];?>">
												</div>
											</form>	
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary" id="popupsubmit_<?php echo $value['upro_id'];?>">Update</button>
										</div>
									</div>
								</div>
							</div>
						<?php }?>
						
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