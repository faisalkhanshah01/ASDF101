<?php $this->load->view('includes/header_infonet_new'); ?>
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
<style>
.imagePreviewClass {
    width: 120px;
    height: 120px;
    background-position: center center;
    background-size: cover;
    -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
}
</style>
	<!-- Form Section Start-->
	<div id="global_searchAllView">
		<div class="row">
			<div class="col-md-12">
				<legend class="home-heading">UPDATE CATEGORY</legend>
			</div>
			<div class="col-md-12  msg-display text-center">
					<?php if(!empty($this->session->flashdata('msg'))){
								echo $this->session->flashdata('msg');
					} ?>
			</div>
			<div class="col-md-10" class="content">
				<div class="panel panel-default">
						<div class="panel-heading">
							<span>Update Category</span>
						</div>
					<div class="panel-body">
						<form class="form-horizontal" action="" method="post" name="update_category" id="update_category" enctype="multipart/form-data">
							
							<div class="form-group">
								<label class="col-md-4 control-label"  for="category_name">Category Name</label>
								<div class="col-md-6">
									<input type="text" name="category_name" class="form-control" placeholder="Category Name" value="<?php echo $category_data['cat_name'] ; ?>" required='required' />
								</div>
							</div>

							<div class="form-group">
									<label class="control-label col-md-4" for="parent_category">Parent Category</label>
									<div class="col-md-6">
										<?php echo draw_tree($category_data['cat_parentid']); ?>
									</div>
							</div>							
							
							<div class="form-group">
								<label for="page_image" class="control-label col-md-4">Feature Image</label>	
								<div class="col-md-6">
								<input type="file" rel='category' class="imagePreviewType form-control tooltip_trigger"  name="cat_image" id="cat_image" placeholder="Category Thumbnail" />
								<p class='text-danger'><em>(Best Resolution: 600 x 400 (width x height). The file size should not exceed 2MB)</em></p>
								</div>
							</div>
								
							<div class="form-group">
								<label class="col-md-4 control-label" for="category_status">Status</label>
								<div class="col-md-6">
									<select name="category_status" class="form-control" required>
										<option value="">Select</option>
										<option <?php echo ($category_data['cat_status'] == "active")? 'selected' : ''; ?> value="active">Active</option>
										<option <?php echo ($category_data['cat_status'] == "inactive")? 'selected' : ''; ?> value="inactive">Inactive</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
								<a href='<?php echo base_url(); ?>category_controller/manage_category' class="btn btn-default pull-left">Back</a>
								<button type="submit" name="cat_update" value='cat_update' class="btn btn-primary pull-right">Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span>Category Image Preview</span>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div id="thumbnail">
							<?php if($category_data['cat_image']!=NULL){
								$featuredImagePath = str_replace('FCPATH/',base_url(),$category_data['cat_image']);
							?>
								<div id="imageDefault-category"><img alt="<?php echo $category_data['cat_name'] ; ?>" src="<?php echo $featuredImagePath; ?>" width="120" height="120" class="asset_image" title='Click to View in Full'></div>
								<br />
								<div id="remove_category" rel="category" data-name="<?php echo $category_data['cat_image']; ?>" data-id="<?php echo $category_data['id'];?>" data-url="<?php echo base_url(); ?>" class="btn btn-danger remove_thumb_image" title="Click to Delete this Image">Remove Image</div>
							<?php }else{ ?>
							<div id="imageDefault-category"><img alt="Thumbnail Image" src="<?php echo base_url() ?>includes/images/no_image.jpg" width="120" height="120"></div>
							<?php } ?>
							</div>
						</div>
					</div>
				</div>		
			</div>
			<?php $this->load->view('includes/model'); ?>
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
	<?php 
	   // $kdvalue = array_values($this->session->flexi_auth['group']);
		$kdkey = array_keys($this->session->flexi_auth['group']);
		if(($kdkey[0] == 10) || ($kdkey[0] == 11)){ 
	?><br/>
		<?php $this->load->view('includes/new_footer'); ?>
		<?php }else{?>
			<?php $this->load->view('includes/footer'); ?>
		<?php }?>
	
<?php $this->load->view('includes/scripts'); ?>
<script type="text/javascript">
    $(document).ready(function(){
		//search table
			$("#formonedetails").DataTable({
		   		   "order":[[ 0,"asc" ]]
		   });
	 });
	 
	$('#manage_category').validate
	({
		rules:
		{
			
			category_name:
			{
				required: true
			},
			category_status: 
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