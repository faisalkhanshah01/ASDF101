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
				url: base_url + "Manage_kare/searchassetsSeriesAjax",
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
            var url = <?php print "'". base_url()."manage_kare/assetseriesexport?toDate='";?>+ toDate + '&fromDate=' + fromDate;
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
                          <span>Asset Series</span>
		          <span  class="pull-right"><a class="text-primary" href="<?php print base_url('Manage_kare/asset_series_list/'); ?>"> <span class="glyphicon glyphicon-arrow-left"></span></a></span>
						  
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
									<th>Asset Series Code</th>
									<th>Description</th>
									<th>Image</th>
									<th>Asset List</th>
									<th>Inspection Type</th>
									<th>Infonet Status</th>
									<th>status</th>
									<th>Date</th>
								</tr>
							</thead>
							
							<?php if (!empty($assetData) && is_array($assetData)) { ?>
							<tbody>
								<?php $i =1;
                                    foreach ($assetData as $key => $value) {?>
								<tr>
									<td>
										<a class="text-primary" href="<?php print base_url().'manage_kare/asset_series_list/'.$value['product_id']?>"> <span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
										<a href="<?php print base_url().'manage_kare/delete_asset_series/'.$value['product_id']?>" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>
									</td>
									<td ><?php echo $value['product_code'];?></td>
									<td ><?php echo $value['product_description'];?></td>
									<td >
										<?php 
											if($value['product_imagepath']!=''){
												$imagePath = '<img src="'.$value['product_imagepath'].'" width="60" height="60" />';
											}else{
												$imagePath = '<img src="http://placehold.it/60x60" width="60" height="60" />';
											}
											print $imagePath;
										?>
									</td>
									<td >
										<?php 
											$components = ($value['product_components']!='')?json_decode($value['product_components'],true):$value['product_components'];
											if(is_array($components)){
												$asset ='';
												foreach($components as $code){
													$tCode = trim($code);
													$value = strtolower($tCode);
													if(is_array($asset_array)){
														if(in_array($value,$asset_array)){
															$asset .=  "<p>".$tCode."</p>";
														}else{
															$asset .= "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$tCode."</p>";
														}
													}else{
														$asset = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$tCode."</p>";
													}
												}	
											}
											print $asset;
										?>
									</td>
									<td >
										<?php 
											if(is_array($inspection) && !empty($value['product_inspectiontype'])){
												if(array_key_exists($value['product_inspectiontype'],$inspection)){
													$inspection_value = "<p>".$inspection[$value['product_inspectiontype']]."</p>";
												}else{
													$inspection_value = "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['product_inspectiontype']."</p>";
												}
											}else{
												$inspection_value =  "<p class='bg-danger'><span class='glyphicon glyphicon-exclamation-sign'></span>".$value['product_inspectiontype']."</p>";
											}
					
											print $inspection_value;
										?>
									</td>
									
									<td >
										<?php 
											$infonet_status =  ($value['infonet_status'] ==1)? '<font color="green">Active</font>':'<font color="red">Inactive</font>';
					
											print $infonet_status;
										?>
									</td>
									<td >
										<?php 
											print '<font color="green">Active</font>';
										?>
									</td>
									<td><?php echo date("M jS, Y", strtotime($value['product_created_date']));?></td>
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