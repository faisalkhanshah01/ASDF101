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
	<div class="row">
		<div class="col-md-offset-3 col-md-6">
			<?php if (! empty($message)) { ?>
				<div id="message" class="alert-success">
					<?php echo $message; ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="row" class="msg-display">
		<div class="col-md-12 text-center">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo '<span style="color:red"><strong>'.$this->session->flashdata('msg').'</strong></span>';
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
	
	<!-- Form Section Start-->
	<div id="global_searchAllView">
		<div class="row">
			<div class="col-md-12">
				<form class="form-horizontal" role="form" name="manage_product_categogy" id="manage_product_categogy" action="" method="post" >
					<legend class="home-heading" >Edit Product			
					</legend>
					<div class="row" style="margin-bottom:15px;">
						<div class="col-md-12">
							<a href='<?php echo base_url(); ?>productCategory_controller/manage_product_categogy' class="btn btn-primary pull-right">Back</a>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4" for="category_name">Category Name</label>
						<div class="col-md-6">
							<?php echo draw_tree($product_result['category_id'],'manage_product'); ?>
						</div>
					</div>
		
					<div class="form-group">
						<label for="" class="col-md-4 control-label">Add Sub Assets <br><small>Multiple Select</small></label>	
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12"> 
								<input type="text" id="search_tool_sub_assets" name="search_tool_sub_assets" class="form-control tooltip_trigger"  placeholder="Search sub assets"/> 
								</div>
							</div>
							<div class="row">
								<div class="col-md-5"> 
									<!--<div class="component-container form-control search-subAssets" id='sel_subAssets'>-->
									<div class="form-control search-subAssets" style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;" id='sel_subAssets'>
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
									<!--<div class="component-container form-control" id="selected_subAssets">-->
									<div class="form-control" id="selected_subAssets" style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;">
										<?php if(!empty($product_result['sub_assets'])){
												$sub_assets_result = json_decode($product_result['sub_assets'],true);
												foreach($sub_assets_result as $comp){
													 ?>
													<p id='<?php echo $comp; ?>' class='bg-success'><?php echo $comp; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
														<input type="hidden" class="sel-component subAssets" name="sub_assets[]" value="<?php echo $comp; ?>"/>
													</p>
											<?php	
												}
											}
									?>
									</div>
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
									<!--<div class="component-container form-control search_multiple_assets" id='sel_assets'>-->
										<?php if($components_list !=''){
											foreach($components_list as $asset){ 
										?>
										<p><?php echo $asset; ?>
										<input class="pull-right" type="checkbox" name="asset[]" value="<?php echo $asset; ?>" /></p>
										<?php } } ?> 
									</div> 
								</div>
								<div class="col-md-2">
									<button id="com_sel_btn_assets" class="btn btn-info" type="button" style="margin-top:50px;"> >> </button>
								</div>
								<div class="col-md-5">
									<!--<div class="component-container form-control" id="selected_assets">-->
									<div class="form-control" id="selected_assets" style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;">
										<?php if(!empty($product_result['assets'])){
												$assets_result = json_decode($product_result['assets'],true);
												foreach($assets_result as $comp){ ?>
													<p id='<?php echo $comp; ?>' class='bg-success'><?php echo $comp; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
													<input type="hidden" class="sel-component asset" name="assets[]" value="<?php echo $comp; ?>"/>
													</p>
											<?php
												}
											}
									?>
									</div>
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
									<!--<div class="component-container form-control search-AssetsSeries" id='sel_assets_series'>-->
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
									<!--<div class="component-container form-control" id="selected_assets_series">-->
									<div class="form-control" id="selected_assets_series" style="background-color: white;border: 1px solid #ccc;height: 150px;margin-top: 5px;overflow-y: auto;padding: 0 5px;position: relative;">
										<?php if(!empty($product_result['assets_series'])){
												$assets_series_result = json_decode($product_result['assets_series'],true);
												foreach($assets_series_result as $comp){ ?>
													<p id='<?php echo $comp; ?>' class='bg-success'><?php echo $comp; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
													<input type="hidden" class="sel-component assetSeries" name="assets_series[]" value="<?php echo $comp; ?>"/>
													</p>
											<?php
												}
											}
									?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4" for="product_category_status">Category Status</label>
						<div class="col-md-6">
							<select class="form-control" id="product_category_status" name="product_category_status" required="required">
								<option value=""> - Select Status - </option>
								<option value="active" <?php if($product_result['status'] == "active") { ?> selected <?php } ?>>Active</option>
								<option value="inactive">Inactive</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-md-offset-6">
							<button class="btn btn-primary"  name="edit_product_category" id="edit_product_category" value="Edit Product Category" type="submit">Edit Product Category</button>
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