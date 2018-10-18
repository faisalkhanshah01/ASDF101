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
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
		</div>
	</div>
	<div id="global_searchAllView">
	<!-- Form Section Start-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading home-heading">
						<span >Multi Upload</span>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" name="product_edit" id="product_edit"  method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="upload_type" class="col-md-2 control-label">Type</label>
								<div class="col-md-10">
									<select class="form-control select_product_type" id="upload_speci" name="upload_type" required="required">
										<?php 
										$array = array('components'=>'Assets', 'sub_assets'=>'Sub-Assets', 'products'=>'Asset Series');
										$selected_type = (isset($_SESSION['specification']['selected_type']))? $_SESSION['specification']['selected_type'] : '';
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
							<div class="form-group">
								<div class="col-md-12">
									<table id="upload_specification_table" class="table table-hover table-bordered home_table" >
										<tbody>
											<?php
												$arr = array('Product Image', 'Technical Specification', 'Technical Data Sheet',
																'Certificate', 'Presentation');
												$count = count($arr);
												for($i = 0; $i < $count; $i++){
											?>
											<tr>
												<td class="col-md-4 alert-warning text-center">
													<label for="" class="control-label alert-warning"><?php echo $arr[$i]; ?></label>
													<input type='hidden' class='form-control' name='upload[<?php echo $i; ?>][title]' id='title<?php echo $i; ?>' value="<?php echo $arr[$i]; ?>" required />
												</td>
												<td class="col-md-8" colspan="2">
													<label for="" class="control-label alert-warning col-md-2 ">URL</label>
													<div class="col-md-10">
														<input class='form-control' type="file" name="upload[<?php echo $i; ?>][url]" />
													</div>
												</td>
											</tr>
												<?php } ?>
											<tr>
												<td class="col-md-4 alert-warning text-center">
													
												</td>
												<td class="col-md-8 text-center alert-warning">
													
												</td>
												<td><a class="btn btn-info" id="btnAddSpecification" href="javascript:void(0);" /><i class="glyphicon glyphicon-plus"></i></a></td>
											</tr>
										</tbody>
									</table>
								</div>	
								<div class="form-group">
									<div class="col-md-6 col-md-offset-5">
										<button class="btn btn-primary"  name="add_multiSpeci" id="add_multiSpeci" value="add_multiSpeci" type="submit">Insert Form</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
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
<script type="text/javascript">
	jQuery(document).ready(function(){
	var count = <?php echo $count; ?>;
	jQuery("#btnAddSpecification").on("click",function(){
		jQuery("#upload_specification_table").append('<tr id="upload_specification_table_'+count+'" data-id="'+count+'">'+
											'<td class="col-md-4 alert-warning text-center">'+
												'<div class="col-md-2">'+
												'<label for="" class="control-label"></label></div>'+
												'<div class="col-md-10">'+
												'<input type="text" class="form-control" name="upload['+count+'][title]" id="title'+count+'" placeholder="Enter Title Name" required />'+
												'</div>'+
											'</td>'+
											'<td class="col-md-8 text-center alert-warning">'+
												'<div class="col-md-2 ">'+
												'<label for="" class="control-label ">URL</label>'+
												'</div>'+
												'<div class="col-md-10">'+
													'<input type="file" class="form-control" name="upload['+count+'][url]" id="url['+count+']" placeholder="http://www.karam.in/kare" required />'+
												'</div>'+
											'</td>'+
											'<td>'+
												'<a class="btn btn-danger removeData" href="javascript:void(0)" data-id="'+count+'"><i class="glyphicon glyphicon-trash"></i></a>'+
											'</td>'+
										'</tr>');
									count++;
			});
	});
	</script>