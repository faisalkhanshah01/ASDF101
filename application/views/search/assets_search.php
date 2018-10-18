<?php $this->load->view('includes/header'); ?> 
<script>
    $(document).ready(function(){	
        $(".datepicker").each(function(){
            $(this).datepicker({
                        showOtherMonths:true,
                        dateFormat: 'yy-mm-dd' 
                });
        });  
        
        $("#ajaxdatalogShow").hide();
        $("#alldatalogShow").show();
        $( ".submit" ).click(function() {
				//var r = base_url + "auth_admin/admin_logs_view";
				 $("#ajaxdatalogShow").show();
				 $("#alldatalogShow").hide();
				
					var toDate = $("#criteria").find("input[name =toDate]").val();
					var fromDate = $("#criteria").find("input[name =fromDate]").val();
					  
			$.ajax({
				type: "POST",
				//dataType: "json",
				url: base_url + "Manage_kare/searchassetsAjax",
				data: {toDate:toDate,fromDate:fromDate},
				success: function(output){
								$('#subasset_view').html(output);
								$("#example_datatable1").DataTable({
						"order":[[ 0,"asc" ]]
					});
				},
				error: function(){
					alert('Error while request ajax...');
				}
			});	// end of ajax
			return false;
		});
	
		$(".exportList").click(function() {
            var toDate = $("#criteria").find("input[name =toDate]").val();
            var fromDate = $("#criteria").find("input[name =fromDate]").val();
            var url = <?php print "'". base_url()."manage_kare/assetexport?toDate='";?>+ toDate + '&fromDate=' + fromDate;
            window.open(url); 
	    return false; 
        });
    });    
</script>
	<?php $this->load->view('includes/head'); ?> 
	 <div class="row">
            <div class="col-md-12" >
                <div class="panel panel-default">
                    <div class="panel-heading home-heading">
                          <span> Asset</span>
						  <span  class="pull-right"><a class="text-primary" href="<?php print base_url().'Manage_kare/assets_list/'?>"> <span class="glyphicon glyphicon-arrow-left"></span></a></span>
						  
                    </div>
                    <div class="panel-body">
                        <form id="criteria" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3">Date </label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">From <i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="text" name="fromDate" value = "<?php if(isset($_REQUEST['fromDate'])) print $_REQUEST['fromDate']; ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">To <i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="text" name="toDate" value = "<?php if(isset($_REQUEST['toDate'])) print $_REQUEST['toDate']; ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3"></label>
                                <div class="col-sm-9">
                                    <button class = "submit btn btn-primary" type="submit"  value="Submit"><span><span>Search</span></span></button>
                                    <button class = "btn btn-success exportList" type="submit"  value="Export List"> <span class="glyphicon glyphicon-download-alt"></span> Export List</button>
                                </div>
                            </div>

                        </form>
                    </div>   
                </div>
            </div>
        </div>    

	<div class="row">
            <div class="col-md-12" >
                <div class="panel panel-default">
			<div class="panel-body">	
				<div class="row"  id="alldatalogShow">
					<div class="col-xs-12">
						<table id="example_datatable" class="table table-bordered table-hover tableview">
							<thead>
								<tr>
									<th>Action</th>
									<th>Assets Code</th>
									<th>Description</th>
									<th>Image</th>
									<th>UOM</th>
									<th>Inspection Type</th>
									<th>Expected Result</th>
									<th>Observations</th>
									<th>Repair</th>
									<th>Infonet Status</th>
									<th>Status</th>
									<th> Add Featured Image</th>
									<th>Date</th>
								</tr>
							</thead>
							
							<?php if (!empty($assetData) && is_array($assetData)) { ?>
							<tbody>
								<?php $i =1;
                                                                $client_url=$_SESSION['client']['url_slug'];
                                    foreach ($assetData as $key => $value) { ?>
								<tr>
									<td style="width: 52px;">
										<a class="text-primary" href="<?php print base_url($client_url).'/manage_kare/edit_assets/'.$value['component_id']?>"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="<?php print base_url($client_url).'/manage_kare/delete_component/'.$value['component_id']?>" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>
									</td>
									<td style="width: 87px;"><?php echo $value['component_code'];?></td>
									<td style="width: 150px;"><?php echo $value['component_description'];?></td>
									<td style="width: 80px;">
										<?php 
											if($value['component_imagepath']!=''){
                                                                                            $cimg=$value['component_imagepath'];
                                                                                            $cimg=str_replace("./",base_url(),$cimg);
											    $imagePath = '<img src="'.$cimg.'" width="60" height="60" />';
                                                                                            
											}else{
												$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
											}
											#print $imagePath;
										?>
									</td>
									<td style="width: 39px;">
										<?php 
											if(is_array($uom) && !empty($value['component_uom'])){
												if(array_key_exists($value['component_uom'],$uom)){
													$uom_value = "<p>".$uom[$value['component_uom']]."</p>";
												}else{
													$uom_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_uom']."</p>";
												}
											}else{
												$uom_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_uom']."</p>";
											}
											print strtoupper($uom_value);
										?>
									</td>
									<td style="width: 127px;">
										<?php
											if(is_array($inspection) && !empty($value['component_inspectiontype'])){
												if(array_key_exists($value['component_inspectiontype'],$inspection)){
													$inspection_value = "<p>".$inspection[$value['component_inspectiontype']]."</p>";
												}else{
													$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_inspectiontype']."</p>";
												}
											}else{
												$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_inspectiontype']."</p>";
											}
											print $inspection_value;
										?>
									</td>
									<td style="width: 97px;">
										<?php 
											if(is_array($result) && !empty($value['component_expectedresult'])){
												$excpected_result = json_decode($value['component_expectedresult'],true);
												$result_value = '';
												foreach($excpected_result as $expResult){
													if(array_key_exists($expResult,$result)){
														$result_value .=  "<p>".$result[$expResult]."</p><hr>";
													}else{
														$result_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_expectedresult']."</p>";
													}
												}
											}else{
												$result_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_expectedresult']."</p>";
											}
											print $result_value;
										?>
									</td>
									<td style="width: 200px;">
										
											<?php 
												if(is_array($observations) && !empty($value['component_observation'])){
														$observations_array = json_decode($value['component_observation'],true);
														$observation_value = '';
														foreach($observations_array as $obsResult){
															if(array_key_exists($obsResult,$observations)){
																$observation_value .=  "<p>".$observations[$obsResult]."</p><hr>";
															}else{
																$observation_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_observation']."</p>";
															}
														}
												}else{
													$observation_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['component_observation']."</p>";
												}
						
												print $observation_value;
											?>
										
									</td>
									<td style="width: 50px;">
										<?php 
											print strtoupper($value['component_repair']);
										?>
									</td>
									<td style="width: 50px;">
										<?php 
											$infonet_status =  ($value['infonet_status'] ==1)? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					
											print $infonet_status;
										?>
									</td>
									<td style="width: 124px;">
										<?php 
											$status =  ($value['status'] =='Active')? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
											print $status;
										?>
									</td>
									<td style="width: 38px;">
										<?php 
											$featuredImage = '<a href="'.base_url().'manage_kare/upload_featured_images?type=asset&id='.$value['component_id'].'">Featured Image</a>';
											print $featuredImage;
										?>
									</td>
									<td><?php echo date("M jS, Y", strtotime($value['component_created_date']));?></td>
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
					<script type="text/javascript">
						$(document).ready(function(){
							$("#example_datatable").DataTable({
								"order":[[ 0,"asc" ]]
						   });
					   });
					</script>
				</div>
				 <div class="row" id="ajaxdatalogShow">
					<div class="col-xs-12">
						 <span id="subasset_view"></span>
					</div>  
				</div> 
			</div>
                </div>
            </div>
	</div> <!-- end of row -->
	<?php $this->load->view('includes/footer'); ?> 
<?php $this->load->view('includes/scripts'); ?>