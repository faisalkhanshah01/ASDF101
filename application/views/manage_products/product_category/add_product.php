<?php $this->load->view('includes/header'); ?>
	<?php //$this->load->view('includes/head'); ?>
	<?php
		$groupId = $_SESSION['flexi_auth']['group'];
		foreach($groupId as $k=>$v){
			$name = $v;
			$groupID = $k;
		}
		
	?>
	<?php 	if ( $groupID == 11 || $groupID == 10){
				$this->load->view('includes/head_infonet');
			}else{ 
				$this->load->view('includes/head'); 
			}
	?>
	<div class="row" class="msg-display">
		<div class="col-md-12 text-center">
			<?php 	if (!empty($this->session->flashdata('msg'))) { ?>
			<?php		echo $this->session->flashdata('msg');
						echo validation_errors(); ?>
			<?php 	} ?>
		</div>
	</div>
	<?php //echo draw_tree(); ?>
	<!-- Form Section Start-->
	<div id="global_searchAllView">
		<div class="row">
			<div class="col-md-12">
				<form class="form-horizontal" role="form" name="manage_product_categogy" id="manage_product_categogy" action="" method="post" >
					<legend class="home-heading" >Add Product			
					</legend>
					<div class="row" style="margin-bottom:15px;">
						<div class="col-md-12">
							<a href='<?php echo base_url(); ?>productCategory_controller/manage_product_categogy' class="btn btn-primary pull-right">Back</a>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4" for="category_name">Category Name</label>
						<div class="col-md-6">
							<?php echo draw_tree('','manage_product'); ?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="email" class="col-md-4 control-label">Add Sub Assets <br><small>Multiple Select</small></label>	
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12"> 
								<input type="text" id="search_tool_sub_assets" name="search_tool_sub_assets" class="form-control tooltip_trigger"  placeholder="Search sub assets"/> 
								</div>
							</div>
							<div class="row">
								<div class="col-md-5"> 
									<div class="form-control search-subAssets" id='sel_subAssets' style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;">
										<?php if($sub_assets_list !=''){
											foreach($sub_assets_list as $sub_assets){	
										?>
										<p><?php echo $sub_assets; ?>
										<input class="pull-right" type="checkbox" name="sub_assets[]" id="<?php echo "chk_".$sub_assets; ?>" value="<?php echo $sub_assets; ?>" /></p>
										<?php } } ?> 
									</div> 
								</div>
								<div class="col-md-2">
									<button id="com_sel_btn_subAssets" class="btn btn-info" type="button" style="margin-top:50px;"> >> </button>
								</div>
								<div class="col-md-5" >
									<div class="form-control" id="selected_subAssets" style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;"></div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="email" class="col-md-4 control-label">Add Assets <br><small>Multiple Select</small></label>	
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12"> 
									<input type="text" id="search_tool_for_multiple_assets" name="search_tool_for_multiple_assets" class="form-control tooltip_trigger"  placeholder="Search for assets"/> 
								</div>
							</div>
							<div class="row">
								<div class="col-md-5"> 
									<div class="form-control search_multiple_assets" id='sel_assets' style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;">
										<?php if($components_list !=''){
											foreach($components_list as $key=>$asset){ 
										?>
										<p><?php echo $asset; ?>
										<input class="pull-right" type="checkbox" name="asset[]" id="<?php echo "chk_".$asset."_asset"; ?>" value="<?php echo $asset; ?>" /></p>
										<?php } } ?> 
									</div> 
								</div>
								<div class="col-md-2">
									<button id="com_sel_btn_assets" class="btn btn-info" type="button" style="margin-top:50px;"> >> </button>
								</div>
								<div class="col-md-5">
									<div class="form-control" id="selected_assets" style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;"></div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label for="email" class="col-md-4 control-label">Add Assets Series <br><small>Multiple Select</small></label>	
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12"> 
									<input type="text" id="search_tool_for_multiple_assetsSeries" name="search_tool_for_multiple_assetsSeries" class="form-control tooltip_trigger"  placeholder="Search for assets series"/> 
								</div>
							</div>
							<div class="row">
								<div class="col-md-5"> 
									<div class="form-control search-AssetsSeries" id='sel_assets_series' style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;">
										<?php if($products_series_list !=''){
											foreach($products_series_list as $asset_series){ 
											
										?>
										<p><?php echo $asset_series; ?>
										<input class="pull-right" type="checkbox" name="asset_series[]" id="<?php echo "chk_".$asset_series; ?>" value="<?php echo $asset_series; ?>" /></p>
										<?php } } ?> 
									</div> 
								</div>
								<div class="col-md-2">
									<button id="com_sel_btn_assets_series" class="btn btn-info" type="button" style="margin-top:50px;"> >> </button>
								</div>
								<div class="col-md-5">
									<div class="form-control" id="selected_assets_series" style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4" for="product_category_status">Category Status</label>
						<div class="col-md-6">
							<select class="form-control" id="product_category_status" name="product_category_status" required="required">
								<option value=""> - Select Status - </option>
								<option value="active">Active</option>
								<option value="inactive">Inactive</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-md-offset-6">
							<button class="btn btn-primary"  name="add_product_category" id="add_product_category" value="Add Product Category" type="submit">Add Product Category</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	<!-- Form Section END-->
	</div>
	<div id="global_searchViewShow">
		<div class="row">
			<div class="col-md-12">
				 <div id="global_search_view"></div>
			</div>
		</div>	
	</div>		
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
<script type="text/javascript">	 
	$('#manage_product_categogy').validate
	({
		rules:
		{
			
			category_name:
			{
				required: true
			},
			product_category_status: 
			{
				required: true
				
			},
		},
			highlight: function(element)
			{
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			
	});
</script>