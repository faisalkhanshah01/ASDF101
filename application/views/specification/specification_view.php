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
						<span >Specification</span>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" action="" name="upload_specification" id="upload_specification" method="post" enctype="multipart/form-data" >				
							
							<div class="form-group">
								<label for="upload_type" class="col-md-4 control-label">Type</label>
								<div class="col-md-6">
									<select class="form-control select_type" id="upload_type" name="upload_type" required="required">
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
							<div id="display_value"></div>
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
$(document).ready(function() {
	<?php 
		if(isset($_SESSION['specification']['selected_type'])){
	?>
		var type_name = "<?php echo $_SESSION['specification']['selected_type'] ?>";
		if(type_name !=""){
			$.ajax({
				type: "POST",
				url: base_url + "specification/get_selected_data",
				data: {type_name: type_name},
				success: function(res){
					$('#display_value').html(res);
					$("#type_value_details").DataTable({
						"order":[[ 0,"asc" ]]
					});
				},
				error: function(){
					alert('Error while request ajax...');
				}
			});	// end of ajax
		} // end of if statememnt
	<?php } ?>
});

</script>
<script type="text/javascript">
	
	$('#upload_specification').validate
	({
		rules:
		{			
			upload_type:
			{
				required: true
			},
			upload_file: 
			{
				required: true				
			},
		},
			highlight: function(element)
			{
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			
	});
	
	$(document).ready(function(){
			$("#type_value_details").DataTable({
		   		   "order":[[ 0,"asc" ]]
		   });
	});

	
</script>
