 <?php $this->load->view('includes/header'); ?> 
 <?php $a = $_SESSION['flexi_auth']['group'];
	foreach($a as $k=>$v){
		$name = $v;
		$group_id = $k;
	}
	
	if (strpos($name, ' ') !== true) {
		$name = explode(' ',$name);
		$name = $name[0];
	}
?>	
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
			
			var clientType  = <?php print $group_id ;?>;
			var toDate = $("#criteria").find("input[name =toDate]").val();
			var fromDate = $("#criteria").find("input[name =fromDate]").val();
                  
			$.ajax({
				type: "POST",
							//dataType: "json",
				url: base_url + "clientuser_dashboard/view_client_dealer_ajax",
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
			var clientType  = <?php print $group_id ;?>;
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
			<strong><span class="glyphicon glyphicon-th"></span> Assign Site ID</strong>
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
						<button class = "submit btn btn-primary" type="submit"  value="Submit"><span><span>Update</span></span></button>
						<button class = "btn btn-success exportList" type="submit"  value="Export List"> <span class="glyphicon glyphicon-download-alt"></span> Export List</button>
					</div>
				</div>

			</form>

		</div>
	</section>

	<section class="panel panel-default">
		<div class="panel-heading">
			<strong><span class="glyphicon glyphicon-th"></span> List</strong>
		</div>
		<div class="panel-body">	
				<div class="row"  id="alldatalogShow">
					<div class="col-xs-12">
						<table id="view_client_dealer_datatable" class="table table-bordered table-hover tableview">
							<thead>
								<tr>
									<th>S.No.</th>
									<th>Client Name</th>
									<th>User Name</th>
									<th>Client Type</th>
									<th>status</th>
									<th>Site Detail</th>
									<th>Date</th>
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
									<td><a class="btn btn-success" href="<?php print base_url()."clientuser_dashboard/view_site_detail?assginClientId=".$value['id'];?>" role="button"> Site Detail </a></td>
									<td><?php  print $value['date'];?></td>
								</tr>
								<?php $i++;} ?>
							</tbody>
                                                    <?php } else { ?>
                                                            <tbody>
                                                                    <tr>
                                                                            <td colspan="7" class="highlight_red">
                                                                                    No Logs Data are available.
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