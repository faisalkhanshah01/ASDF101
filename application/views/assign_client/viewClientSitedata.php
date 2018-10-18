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
			$("#ajaxdatalogShow").show();
            $("#alldatalogShow").hide();
			
			var clientType  = $("#clientType").find("option:selected").val();
			var toDate = $("#criteria").find("input[name =toDate]").val();
			var fromDate = $("#criteria").find("input[name =fromDate]").val();
                  
			$.ajax({
				type: "POST",
							//dataType: "json",
				url: base_url + "assign_client_controller/view_client_dealer_ajax",
				data: {toDate:toDate,fromDate:fromDate,clientType:clientType},
				success: function(output){
								$('#logs_view').html(output);
								$("#kare_logs_view_datatable1").DataTable({
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
			var clientType  = $("#clientType").find("option:selected").val();
            var toDate = $("#criteria").find("input[name =toDate]").val();
            var fromDate = $("#criteria").find("input[name =fromDate]").val();
            var url = <?php print "'". base_url()."Manage_kare/exportAnalytics?exportAnalytics=4&toDate='";?>+ toDate + '&fromDate=' + fromDate + '&clientType=' + clientType;
			window.open(url); 
	    return false; 
        });
       
	});	
 </script>
 <?php $this->load->view('includes/head'); ?>  
	<section class="panel panel-default">
		<div class="panel-heading">
			<strong><span class="glyphicon glyphicon-th"></span><?php if( $lang["assign_site_id"]['description'] !='' ){ echo $lang["assign_site_id"]['description']; }else{ echo 'Assign Site ID'; } ?></strong>
		</div>
		<div class="panel-body">

			<form id="criteria" class="form-horizontal">
				<div class="form-group">
					<label class="col-sm-2"> <?php if( $lang["client_type"]['description'] !='' ){ echo $lang["client_type"]['description']; }else{ echo 'Client Type'; } ?> </label>
					<div class="col-sm-6">
						<select  id="clientType" name="clientType" class="form-control">
								<option selected value=""> - Select Option - </option>	
								<option  value="8">Client</option>
								<option  value="7">Dealer</option>
								<option  value="9">Inspector</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2"><?php if( $lang["date"]['description'] !='' ){ echo $lang["date"]['description']; }else{ echo 'Date'; } ?> </label>
					<div class="col-sm-3">
						<div class="input-group">
							<span class="input-group-addon"><?php if( $lang["from"]['description'] !='' ){ echo $lang["from"]['description']; }else{ echo 'From'; } ?> <i class="fa fa-calendar"></i></span>
							<input class="form-control datepicker" type="text" name="fromDate" value = "<?php if(isset($_REQUEST['fromDate'])) print $_REQUEST['fromDate']; ?>"/>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<span class="input-group-addon"><?php if( $lang["to"]['description'] !='' ){ echo $lang["to"]['description']; }else{ echo 'To'; } ?><i class="fa fa-calendar"></i></span>
							<input class="form-control datepicker" type="text" name="toDate" value = "<?php if(isset($_REQUEST['toDate'])) print $_REQUEST['toDate']; ?>"/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-2"></label>
					<div class="col-sm-6">
						<button class = "submit btn btn-primary" type="submit"  value="Submit"><span><span><?php if( $lang["update"]['description'] !='' ){ echo $lang["update"]['description']; }else{ echo 'Update'; } ?></span></span></button>
						<button class = "btn btn-success exportList" type="submit"  value="Export List"> <span class="glyphicon glyphicon-download-alt"></span> <?php if( $lang["export_list"]['description'] !='' ){ echo $lang["export_list"]['description']; }else{ echo 'Export List'; } ?></button>
					</div>
				</div>

			</form>

		</div>
	</section>

	<section class="panel panel-default">
		<div class="panel-heading">
			<strong><span class="glyphicon glyphicon-th"></span> <?php if( $lang["list"]['description'] !='' ){ echo $lang["list"]['description']; }else{ echo 'List'; } ?></strong>
		</div>
		<div class="panel-body">	
				<div class="row"  id="alldatalogShow">
					<div class="col-xs-12">
						<table id="view_client_dealer_datatable" class="table table-bordered table-hover tableview">
							<thead>
								<tr>
									<th><?php if( $lang["s_no"]['description'] !='' ){ echo $lang["s_no"]['description']; }else{ echo 'S.No.'; } ?></th>
									<th><?php if( $lang["client_name"]['description'] !='' ){ echo $lang["client_name"]['description']; }else{ echo 'Client Name'; } ?></th>
									<th><?php if( $lang["user_name"]['description'] !='' ){ echo $lang["user_name"]['description']; }else{ echo 'User Name'; } ?></th>
									<th><?php if( $lang["client_type"]['description'] !='' ){ echo $lang["client_type"]['description']; }else{ echo 'Client Type'; } ?></th>
									<th><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo 'status'; } ?></th>
									<th><?php if( $lang["site_detail"]['description'] !='' ){ echo $lang["site_detail"]['description']; }else{ echo 'Site Detail'; } ?></th>
									<th><?php if( $lang["date"]['description'] !='' ){ echo $lang["date"]['description']; }else{ echo 'Date'; } ?></th>
								</tr>
							</thead>
							<?php if (!empty($client_data) && is_array($client_data)) { ?>
							<tbody>
								<?php $i =1;
                                    foreach ($client_data as $key => $value) { ?>
								<tr>
									<td><?php echo $i;?></td>
									<td><?php $site_id = implode('<br/>', $value['site_data']);
														print $site_id; ?></td>
									<td>
										<?php $user_name = implode('<br/>', $value['user_name']);
                                                    print $user_name;?>
									</td>
									<td><?php echo $value['client_type'];?></td>
									<td><?php echo $value['status'];?></td>
									<td><a class="btn btn-success" href="<?php print base_url()."assign_client_controller/view_site_detail?assginClientId=".$value['id'];?>" role="button"> <?php if( $lang["site_detail"]['description'] !='' ){ echo $lang["site_detail"]['description']; }else{ echo 'Site Detail'; } ?> </a></td>
									<td><?php  print $value['date'];?></td>
								</tr>
								<?php $i++;} ?>
							</tbody>
                                                    <?php } else { ?>
                                                            <tbody>
                                                                    <tr>
                                                                            <td colspan="7" class="highlight_red">
                                                                                    <?php if( $lang["no_logs_data_are_available"]['description'] !='' ){ echo $lang["no_logs_data_are_available"]['description']; }else{ echo 'No Logs Data are available.'; } ?>
                                                                            </td>
                                                                    </tr>
                                                            </tbody>
                                                    <?php } ?>
						</table>
					</div>
					<script type="text/javascript">
						$(document).ready(function(){
							$("#view_client_dealer_datatable").DataTable({
								"order":[[ 0,"asc" ]]
						   });
					   });
					</script>
				</div>
                                
				<div class="row" id="ajaxdatalogShow">
					<div class="col-xs-12">
						 <spam id="logs_view"></spam>
					</div>  
				</div>    
			</div>
	</section>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?> 	