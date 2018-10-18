<?php $this->load->view('includes/header_infonet_new'); ?>
	<?php $this->load->view('includes/head'); ?>
<style>
.imagePreviewClass {
    width: 120px;
    height: 120px;
    background-position: center center;
    background-size: cover;
    -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
}
</style>
	<div id="global_searchAllView">
	<!-- Form Section Start-->
		<div class="row">
			<div class="col-md-12">
				<legend class="home-heading">ADD CATEGORY</legend>
			</div>
			<div class="col-md-12 text-center">
					<?php if(!empty($this->session->flashdata('msg'))){
								echo $this->session->flashdata('msg');
					} ?>
			</div>
				<div class="col-md-10" class="content">
					<div class="panel panel-default">
						<div class="panel-heading">
							<span>Add Category</span>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" action="<?php echo base_url(); ?>/category_controller/add_category" name="manage_category" id="manage_category" method="post" enctype="multipart/form-data" >
								<div class="form-group">
									<label class="col-md-4 control-label">Name</label>
									<div class="col-md-6">
										<input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter Category Name" required/>
									</div>
								</div>
								<div class="form-group">
									<label  class="col-md-4 control-label">Parent Category</label>
									<div class="col-md-6">
										<?php echo draw_tree(); ?>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-4 control-label">Feature Image</label>	
									<div class="col-md-6">
										<input type="file" rel='category' class="imagePreviewType form-control tooltip_trigger"  name="cat_image" id="cat_image" placeholder="Thumbnail" />
										<p class='text-danger'><em>(Best Resolution: 600 x 400 (width x height). The file size should not exceed 2MB)</em></p>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-4 control-label">Status</label>
									<div class="col-md-6">
										<select class="form-control" id="category_status" name="category_status" required="required">
											<option value=""> - Select Status - </option>
											<option value="active">Active</option>
											<option value="inactive">Inactive</option>
										</select>
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-12">
										<a href='<?php echo base_url(); ?>category_controller/manage_category' class="btn btn-default">Back</a>
										<button class="btn btn-primary pull-right"  name="add_category" id="add_category" type="submit">Add Category</button>
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
								<div id="imageDefault-category"><img alt="Preview Image" src="<?php echo base_url(); ?>includes/images/no_image.jpg"width="120" height="120"></div>
							</div>
						</div>
					</div>		
				</div>
				<?php $this->load->view('includes/model'); ?>
		</div>
	</div>
	<div id="global_searchViewShow">
		<div class="row">
			<div class="col-md-12">
				 <div id="global_search_view"></div>
			</div>
		</div>	
	</div>	
	<!-- Form Section END-->
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