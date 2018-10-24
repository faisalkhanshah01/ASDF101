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
				url: base_url + "Subassets_kare/searchSubassetsAjax",
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
            var url = <?php print "'". base_url()."Subassets_kare/exportAnalytics?exportanalytics=8&toDate='";?>+ toDate + '&fromDate=' + fromDate;
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
                          <span><?php if( $lang["sub_asset"]['description'] !='' ){ echo $lang["sub_asset"]['description']; }else{ echo "Sub Asset"; } ?></span>
						  <span  class="pull-right"><a class="text-primary" href="<?php print base_url().'Subassets_kare/sub_assets_list/'?>"> <span class="glyphicon glyphicon-arrow-left"></span></a></span>
						  
                    </div>
                    <div class="panel-body">
                        <form id="criteria" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3"><?php if( $lang["date"]['description'] !='' ){ echo $lang["date"]['description']; }else{ echo "Date"; } ?> </label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php if( $lang["from"]['description'] !='' ){ echo $lang["from"]['description']; }else{ echo "From"; } ?> <i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="text" name="fromDate" value = "<?php if(isset($_REQUEST['fromDate'])) print $_REQUEST['fromDate']; ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php if( $lang["to"]['description'] !='' ){ echo $lang["to"]['description']; }else{ echo "To "; } ?><i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="text" name="toDate" value = "<?php if(isset($_REQUEST['toDate'])) print $_REQUEST['toDate']; ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3"></label>
                                <div class="col-sm-9">
                                    <button class = "submit btn btn-primary" type="submit"  value="Submit"><span><span><?php if( $lang["serach"]['description'] !='' ){ echo $lang["serach"]['description']; }else{ echo "Serach"; } ?></span></span></button>
                                    <button class = "btn btn-success exportList" type="submit"  value="Export List"> <span class="glyphicon glyphicon-download-alt"></span> <?php if( $lang["export_list"]['description'] !='' ){ echo $lang["export_list"]['description']; }else{ echo "Export List"; } ?></button>
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
									<th><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo "Action"; } ?></th>
									<th><?php if( $lang["sub_assets_code"]['description'] !='' ){ echo $lang["sub_assets_code"]['description']; }else{ echo "Sub Assets Code"; } ?></th>
									<th><?php if( $lang["action"]['description'] !='' ){ echo $lang["description"]['description']; }else{ echo "Description"; } ?></th>
									<th><?php if( $lang["image"]['description'] !='' ){ echo $lang["image"]['description']; }else{ echo "Image"; } ?></th>
									<th><?php if( $lang["uom"]['description'] !='' ){ echo $lang["uom"]['description']; }else{ echo "UOM"; } ?></th>
									<th><?php if( $lang["inspection_type"]['description'] !='' ){ echo $lang["inspection_type"]['description']; }else{ echo "Inspection Type"; } ?></th>
									<th><?php if( $lang["expected_results"]['description'] !='' ){ echo $lang["expected_results"]['description']; }else{ echo "Expected Result"; } ?></th>
									<th><?php if( $lang["observation"]['description'] !='' ){ echo $lang["observation"]['description']; }else{ echo "Observation"; } ?></th>
									<th><?php if( $lang["repair"]['description'] !='' ){ echo $lang["repair"]['description']; }else{ echo "Repair"; } ?></th>
									<th><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; } ?></th>
									<th><?php if( $lang["featured_image"]['description'] !='' ){ echo $lang["featured_image"]['description']; }else{ echo "Featured Image"; } ?></th> 
									<th><?php if( $lang["date"]['description'] !='' ){ echo $lang["date"]['description']; }else{ echo "Date"; } ?></th>
								</tr>
							</thead>
							
							<?php if (!empty($subAssetData) && is_array($subAssetData)) {
                                                            ?>
							 <tbody>
								<?php $i =1;
                                                                foreach ($subAssetData as $key => $value) { 
                                                                ?>
								<tr>
									<td style="width: 52px;">
										<a class="text-primary" href="<?php print base_url().'Subassets_kare/sub_assets_list/'.$value['sub_assets_id']?>"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="<?php print base_url().'Subassets_kare/delete_sub_assets/'.$value['sub_assets_id']?>" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>
									</td>
									<td style="width: 87px;"><?php echo $value['sub_assets_code'];?></td>
									<td style="width: 92px;"><?php echo $value['sub_assets_description'];?></td>
									<td style="width: 50px;">
										<?php 
											if($value['sub_assets_imagepath']!=''){
												$imagePath = '<img src="'.base_url().$value['sub_assets_imagepath'].'" width="60" height="60" />';
											}else{
												$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
											}
											print $imagePath;
										?>
									</td>
									<td style="width: 39px;">
										<?php 
											if(is_array($uom) && !empty($value['sub_assets_uom'])){
												if(array_key_exists($value['sub_assets_uom'],$uom)){
													$uom_value = "<p>".$uom[$value['sub_assets_uom']]."</p>";
												}else{
													$uom_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_uom']."</p>";
												}
											}else{
												$uom_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_uom']."</p>";
											}
											print strtoupper($uom_value);
										?>
									</td>
									<td style="width: 127px;">
										<?php
											if(is_array($inspection) && !empty($value['sub_assets_inspection'])){
												if(array_key_exists($value['sub_assets_inspection'],$inspection)){
													$inspection_value = "<p>".$inspection[$value['sub_assets_inspection']]."</p>";
												}else{
													$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_inspection']."</p>";
												}
											}else{
												$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_inspection']."</p>";
											}
											print $inspection_value;
										?>
									</td>
									<td style="width: 200px;">
										<?php 
											if(is_array($result) && !empty($value['sub_assets_result'])){
												$excpected_result = json_decode($value['sub_assets_result'],true);
												$result_value = '';
												foreach($excpected_result as $expResult){
													if(array_key_exists($expResult,$result)){
														$result_value .=  "<p>".$result[$expResult]."</p><hr>";
													}else{
														$result_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_result']."</p>";
													}
												}
											}else{
												$result_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_result']."</p>";
											}
											print $result_value;
										?>
									</td>
									<td style="width: 52px;">
										
											<?php 
												if(is_array($observations) && !empty($value['sub_assets_observation'])){
														$observations_array = json_decode($value['sub_assets_observation'],true);
														$observation_value = '';
														foreach($observations_array as $obsResult){
															if(array_key_exists($obsResult,$observations)){
																$observation_value .=  '<p>'.$observations[$obsResult].'</p><hr>';
															}else{
																$observation_value .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['sub_assets_observation']."</p>";
															}
														}
												}else{
													$observation_value = '<p>'.$value['sub_assets_observation'].'</p>';
												}
						
												print $observation_value."<hr>";
											?>
										
									</td>
									<td style="width: 50px;">
										<?php 
											print strtoupper($value['sub_assets_repair']);
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
											$featuredImage = '<a href="'.base_url().'manage_kare/upload_featured_images?type=subAsset&id='.$value['sub_assets_id'].'">Featured Image</a>';
											print $featuredImage;
										?>
									</td>
									<td><?php echo date("M jS, Y", strtotime($value['time']));?></td>
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