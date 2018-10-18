<?php 
#echo "this file"; die;
?>
<?php $this->load->view('includes/header'); ?>
	 <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.css" />-->
	 <link rel="stylesheet" href="http://karam.in/kare/includes/css/bootstrap-select.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.js"></script>
        
	<?php $this->load->view('includes/head'); ?>
        
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if (!empty($this->session->flashdata('msg'))||isset($msg)) {
						echo (isset($msg))? $msg : $this->session->flashdata('msg');
					}
					if(validation_errors() !=''){
						echo '<div class="alert alert-danger capital">'.validation_errors().'</div>';
					}
				?>
			</div>
			<style>
				.loading-image {
				   position: absolute;
					top: 100px;
					left: 240px;
					z-index: 100;
				}
				.loader
				{
					    width: 100%;
					height: 100%;
					top: 0px;
					left: 0px;
					position: fixed;
					display: block;
					opacity: 0.7;
					background-color: #fff;
					z-index: 99;
					text-align: center;
				}
			</style>
			<script>
				$(document).ready(function() {
					 $("#ajaxdataSearchShow").hide();
					$("#alldataShow").show();
					$('#search').click(function(){
						var client_name = $('#client').find(":selected").val();
						var district = $('#district').find(":selected").val();
						var circle = $('#circle').find(":selected").val();
						var invoice = $('#invoice').find(":selected").val();
						var asset_series = $('#asset_series').find(":selected").val();
						var asset = $('#asset').find(":selected").val();
						if (client_name != ''){
							$("#ajaxdataSearchShow").show();
							$("#alldataShow").hide();
						}else if (district != ''){
							$("#ajaxdataSearchShow").show();
							$("#alldataShow").hide();
						}else if (circle != ''){
							$("#ajaxdataSearchShow").show();
							$("#alldataShow").hide();
						}else if (invoice != ''){
							$("#ajaxdataSearchShow").show();
							$("#alldataShow").hide();
						}else if (asset_series != ''){
							$("#ajaxdataSearchShow").show();
							$("#alldataShow").hide();
						}else if (asset != ''){
							$("#ajaxdataSearchShow").show();
							$("#alldataShow").hide();
						}else{
							$("#ajaxdataSearchShow").hide();
							$("#alldataShow").show();
							error = 1;
							alert('You should select a parameter.');
						}
						
						
						
						var data = {
							client:client_name,
							district:district,
							circle:circle,
							invoice:invoice,
							asset_series:asset_series,
							asset:asset,
							toDate : $("#criteria").find("input[name =toDate]").val(),
							fromDate : $("#criteria").find("input[name =fromDate]").val(),
							search:1
						};
						$.ajax({
							url: '<?php print base_url()."Dashboard_controller/searchHistory";?>',
							type: 'post',
							dataType: "json",
							data: data,
							success: function(output) {
								//alert(output);
								if(output['success'] == 'success'){
									 $('.loader').hide();
									$('#searchView').html(output['data']);
									$("#kare_search_view").DataTable({
										"order":[[ 0,"asc" ]]
									});
								}else{
									$('.loader').show();
								}	
							},beforeSend: function(output){
								   $('.loader').show();
						    },complete: function(output){
								  $('.loader').hide();
						    }
						});  
						return false;
					});
					
					
					$(".exportList").click(function() {
						var toDate = $("#criteria").find("input[name =toDate]").val();
						var fromDate = $("#criteria").find("input[name =fromDate]").val();
						var client = $('#client').find(":selected").val();
						var district = $('#district').find(":selected").val();
						var	circle = $('#circle').find(":selected").val();
						var	invoice = $('#invoice').find(":selected").val();
						var	asset_series = $('#asset_series').find(":selected").val();
						var	asset = $('#asset').find(":selected").val();
						
						
						
						var where = '';
						if(client != ''){
							where += "&client="+client;
						}
						if(toDate != ''){
							where += "&toDate="+toDate;
						}
						if(fromDate != ''){
							where += "&fromDate="+fromDate;
						}
						if(district != ''){
							where += "&district="+district;
						}
						if(circle != ''){
							where += "&circle="+circle;
						}
						if(invoice != ''){
							where += "&invoice="+invoice;
						}
						if(asset_series != ''){
							where += "&asset_series="+asset_series;
						}
						if(asset != ''){
							where += "&asset="+asset;
						}
						
						
						var url = <?php print "'". base_url()."Manage_kare/exportAnalytics?exportAnalytics=5'";?>+ where;
						window.open(url); 
						return false; 
					});
					
				});
		  </script>  
	
			<!--<form class="form-horizontal" role="form" id="formone" action="<?php //echo base_url().'dashboard_controller/search_history_new'; ?>" method="post" >-->
			<form id="criteria" class="form-horizontal" >
				<legend class="home-heading"><?php if( $lang['dashboard']['description'] ){ echo $lang['dashboard']['description']; }else{ echo 'Dashboard'; }  ?> </legend> <?php echo $account_creation_successful; ?>
				<div class="form-group">
					<div id="formone">
						<div class="col-md-4">
							<input type="text" class="form-control date1" name="fromDate" id="datepicker" placeholder="<?php if( $lang["from_date"]['description'] !='' ){ echo $lang["from_date"]['description']; }else{ echo "From Date"; }  ?>"/>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control date1" name="toDate" id="datepicker1" placeholder="<?php if( $lang["to_date"]['description'] !='' ){ echo $lang["to_date"]['description']; }else{ echo "To Date"; }  ?>"/>
						</div>
						<div class="col-md-4">
							<select class="form-control selectpicker" name="client" data-live-search="true" data-live-search-style="startsWith" id="client">
								<option value=""><?php if( $lang["select_client"]['description'] !='' ){ echo $lang["select_client"]['description']; }else{ echo "Select Client"; }  ?></option>
								<?php foreach($client as $ckey=>$cval){ ?>
								<option value="<?php echo $cval; ?>"><?php echo $cval; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<select class="form-control selectpicker" name="district" data-live-search="true" data-live-search-style="startsWith" id="district">
								<option value=""><?php if( $lang["select_district"]['description'] !='' ){ echo $lang["select_district"]['description']; }else{ echo "Select District"; }  ?></option>
								<?php foreach($district as $key=>$districtval){ ?>
								<option value="<?php echo $districtval; ?>"><?php echo $districtval; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<select  class="form-control selectpicker" name="circle" data-live-search="true" data-live-search-style="startsWith" id="circle">
								<option  value=""><?php if( $lang["select_circle"]['description'] !='' ){ echo $lang["select_circle"]['description']; }else{ echo "Select Circle"; }  ?></option>
								<?php foreach($circle as $key=>$circleval){ ?>
								<option value="<?php echo $circleval; ?>"><?php echo $circleval; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<select class="form-control selectpicker" name="invoice" data-live-search="true" data-live-search-style="startsWith" id="invoice">
								  <option value=""><?php if( $lang["select_invoice_no"]['description'] !='' ){ echo $lang["select_invoice_no"]['description']; }else{ echo "Select Invoice No"; }  ?></option>
								  <option value="NA">NA</option>
								  <?php if(!empty($invoice_data)){
											foreach($invoice_data as $key => $value){
									  ?>
										<option value="<?php print $value;?>"><?php print $value;?></option>
								  <?php } }?>
							</select>
						</div>
						<div class="col-md-8">
							<select class="form-control selectpicker" name="asset_series" data-live-search="true" data-live-search-style="startsWith" id="asset_series">
								  <option value=""><?php if( $lang["select_asset_series"]['description'] !='' ){ echo $lang["select_asset_series"]['description']; }else{ echo "Select Asset Series"; }  ?></option>
								  <?php if(!empty($asset_series_data)){
											foreach($asset_series_data as $key => $value){
									  ?>
										<option value="<?php print $value['product_code'];?>"><?php print $value['product_code'];?></option>
								  <?php } }?>
							</select>
						</div>
						<div class="col-md-4">
							<select class="form-control selectpicker" name="asset" data-live-search="true" data-live-search-style="startsWith" id="asset">
								  <option value=""><?php if( $lang["select_asset"]['description'] !='' ){ echo $lang["select_asset"]['description']; }else{ echo "Select Asset"; }  ?></option>
								  <?php if(!empty($asset_data)){
											foreach($asset_data as $key => $value){
									  ?>
										<option value="<?php print $value['component_code'];?>"><?php print $value['component_code'];?></option>
								  <?php } }?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group" id="formone-but">
					<div class="col-md-12 text-center"> 
						<button name="search" id="search" value="Submit" type="submit" style="background-color: <?php echo $_SESSION['color_code'];?> !important; border-color: <?php echo $_SESSION['color_code'];?> !important;"><?php if( $lang['serach']['description'] !='' ){ echo $lang['serach']['description']; }else{ echo 'Search'; }  ?></button>
						<button class="exportList" type="submit"  value="Export List" id="export"> <span class="glyphicon glyphicon-download-alt"></span> &nbsp;<?php if( $lang['export_list']['description'] !='' ){ echo $lang['export_list']['description']; }else{ echo 'Export List'; }  ?></button>
                    </div>
				</div>
			</form>
		</div>
	</div> 
	<div class="row" id="ajaxdataSearchShow">
		<div class="col-xs-12">
			<div class="loader">
			   <center>
				    <img id="loading-image" src="http://cdn.nirmaltv.com/images/generatorphp-thumb.gif" alt="Loading..." />
			   </center>
			</div>
			 <span id="searchView"></span>
		</div>  
	</div>
	<div class="row" id="alldataShow">
			<div class="col-md-12">
				<legend class="home-heading"><?php if( $lang['inspection_reports']['description'] !='' ){ echo $lang['inspection_reports']['description']; }else{ echo 'Inspection Reports'; }  ?></legend>
				<table class="table table-bordered home_table" >
					<thead>
						<tr>
							<th class="text-center"><?php if( $lang['details']['description'] ){ echo $lang['details']['description']; }else{ echo 'details'; }  ?></th>
							<th class="text-center"><?php if( $lang['total_numbers']['description'] ){ echo $lang['total_numbers']['description']; }else{ echo 'Total Numbers'; }  ?> </th>
							<th class="text-center"><?php if( $lang['action']['description'] ){ echo $lang['action']['description']; }else{ echo 'Action'; }  ?></th>
						</tr>
					</thead>
					<tbody>
						
						<tr>
							<td class="col-md-6 text-center"><?php if( $lang["total_number_of_site_id's"]['description'] !='' ){ echo $lang["total_number_of_site_id's"]['description']; }else{ echo "total_number_of_site_id's"; }  ?><strong class="pending"><?php if( $lang["pending"]['description'] !='' ){ echo $lang["pending"]['description']; }else{ echo "Pending"; }  ?></strong> ( <small><?php if( $lang["not_yet_inspected"]['description'] !='' ){ echo $lang["not_yet_inspected"]['description']; }else{ echo "Not yet Inspected"; }  ?></small> )</td>
							<td class="col-md-3 text-center "><?php echo $not_inspected_siteIDs; ?></td>
							<td class="text-center"><a href="<?php echo base_url('dashboard_controller/inspection_reports_details?status=NotInspected'); ?>" class="btn btn-default "><?php if( $lang['view']['description'] ){ echo $lang['view']['description']; }else{ echo 'View'; }  ?> <i class="glyphicon glyphicon-play"></i></a></td>
						</tr>
						<tr>
							<td class="col-md-6 text-center"><?php if( $lang["total_number_of_site_id's"]['description'] !='' ){ echo $lang["total_number_of_site_id's"]['description']; }else{ echo "total_number_of_site_id's"; }  ?> <strong class="pending"><?php if( $lang["pending"]['description'] !='' ){ echo $lang["pending"]['description']; }else{ echo "Pending"; }  ?></strong> ( <small><?php if( $lang["not_yet_approved_by_admin"]['description'] !='' ){ echo $lang["not_yet_approved_by_admin"]['description']; }else{ echo "Not yet Approved by Admin"; }  ?></small> )</td>
							<td class="col-md-3 text-center "><?php echo $pending_inspection; ?></td>
							<td class="text-center"><a href="<?php echo base_url('dashboard_controller/inspection_reports_details?status=Pending'); ?>" class="btn btn-default "><?php if( $lang['view']['description'] ){ echo $lang['view']['description']; }else{ echo 'View'; }  ?> <i class="glyphicon glyphicon-play"></i></a></td>
						</tr>
						<tr>
							<td class="col-md-6 text-center"><?php if( $lang["total_number_of_site_id's"]['description'] !='' ){ echo $lang["total_number_of_site_id's"]['description']; }else{ echo "total_number_of_site_id's"; }  ?> <strong class="approved"><?php if( $lang["approved"]['description'] !='' ){ echo $lang["approved"]['description']; }else{ echo "Approved"; }  ?></strong></td>
							<td class="col-md-3 text-center "><?php echo $approved_inspection; ?></td>
							<td class="text-center"><a href="<?php echo base_url('dashboard_controller/inspection_reports_details?status=Approved'); ?>" class="btn btn-default"><?php if( $lang['view']['description'] ){ echo $lang['view']['description']; }else{ echo 'View'; }  ?> <i class="glyphicon glyphicon-play"></i></a></td>
						</tr>
						<tr>
							<td class="col-md-6 text-center"><?php if( $lang["total_number_of_site_id's"]['description'] !='' ){ echo $lang["total_number_of_site_id's"]['description']; }else{ echo "total_number_of_site_id's"; }  ?> <strong class="rejected"><?php if( $lang["rejected"]['description'] !='' ){ echo $lang["rejected"]['description']; }else{ echo "Rejected"; }  ?></strong></td>
							<td class="col-md-3 text-center "><?php echo $rejected_inspection; ?></td>
							<td class="text-center"><a href="<?php echo base_url('dashboard_controller/inspection_reports_details?status=Rejected'); ?>" class="btn btn-default"><?php if( $lang['view']['description'] ){ echo $lang['view']['description']; }else{ echo 'View'; }  ?> <i class="glyphicon glyphicon-play"></i></a></td>
						</tr>
						
					</tbody>
				</table>
			</div>
	</div>
	
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
<script type="text/javascript">
    $(document).ready(function(){
		//search table
			$("#formonedetails").DataTable({
		   		   "order":[[ 0,"asc" ]]
		   });
	 });
</script>