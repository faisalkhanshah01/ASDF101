<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
	
	<div class="row" class="msg-display">
		<div class="col-md-12 text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
		</div>
	</div>
	
	<!-- Form Section Start-->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span ><?php echo $uom;  if( $lang["product_edit"]['description'] !='' ){ echo $lang["product_edit"]['description']; }else{ echo "Product Edit"; }  ?></span>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" name="product_edit" id="product_edit" action="<?php echo $base_url;?>Productedit_controller/update_project" method="post" >
						<div class="form-group">
							<label for="upload_type" class="col-md-2 control-label"><?php if( $lang["type"]['description'] !='' ){ echo $lang["type"]['description']; }else{ echo "Type"; }  ?></label>
							<div class="col-md-10">
								<select class="form-control select_product_type" id="upload_type" name="upload_type" required="required">
									<?php 
									$array = array('components'=>'Assets', 'sub_assets'=>'Sub-Assets', 'products'=>'Asset Series');
									$selected_type = (isset($_SESSION['product_edit']['selected_type']))? $_SESSION['product_edit']['selected_type'] : '';
									echo '<option value=""> - Select Type - </option>';
									foreach($array as $key=>$val){
										$selected = ($selected_type == $key)? 'selected' : '';
										echo '<option '.$selected.' value="'.$key.'">'.$val.'</option>';
									} ?>
								</select>
							</div>
						</div>
						<div id="display_value">
							<?php if(isset($_SESSION['product_edit']['fetch_data'])){
								echo $_SESSION['product_edit']['fetch_data'];
							}
							?>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Form Section END-->
		
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
<script type="text/javascript">	 
	$('#manage_product_categogy').validate({
		rules:{
			category_name:
			{
				required: true
			},
			product_category_status: 
			{
				required: true
				
			},
		},
		highlight: function(element){
				$(element).closest('.control-group').removeClass('success').addClass('error');
		},
	});
</script>