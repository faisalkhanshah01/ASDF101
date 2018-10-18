<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?>
	
	<style>
		#image { border: 10px solid #fff;
		 box-sizing: border-box;
		-webkit-box-shadow: 0 0 10px #999;
		box-shadow: 0 0 10px #999;}
	</style>
	<div class="row" class="msg-display">
		<div class="col-md-12">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo $this->session->flashdata('msg'); 
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
    
    
	<div class="row">
        <div class="col-md-12">
			<div class="panel panel-default">
				<?php
				if($view == 'insert'){
					$title ="ADD FEATURED IMAGES";
					$buttonText = "SAVE";
				}elseif($view == 'update'){
					$title ="UPDATE FEATURED IMAGES";
					$buttonText = "UPDATE";
				}
				if($view == 'insert'  || $view =='update'){ ?>
					<div class="panel-heading home-heading">
						<span><?php echo $title; ?></span>
					</div>
					<div class="panel-body">
						<?php echo form_open_multipart(current_url() , 'class="form-horizontal"'); ?>
							<div class="form-group">
								<label for="email" class="col-md-4 control-label">Pass</label>
								<div class="col-md-8">
									<input type="file" class="form-control" id="pass_image" name="pass_image" />
									<?php echo form_error('pass_image'); ?>  
								</div>
						   </div>

						   <div class="form-group">
								<label for="email" class="col-md-4 control-label">Fail</label>
								<div class="col-md-8">
									<input type="file" class="form-control" id="fail_image" name="fail_image" />
									<?php echo form_error('fail_image'); ?>  
								</div>
						   </div>

							<div class="form-group">
								<label for="email" class="col-md-4 control-label">Repair</label>
								<div class="col-md-8">
									<input type="file" class="form-control" id="repair_image" name="repair_image" />
								  <?php echo form_error('repair_image'); ?>  
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">
									<input type="submit" name="fimage_submit" class="btn btn-primary" id="fimage_submit" value="<?php echo $buttonText; ?>" />
									<?php if($_SESSION['request_type'] =='asset'){ 
										$backUrl = base_url('manage_kare/assets_list');
									}else{
										$backUrl = base_url('subassets_kare/sub_assets_list');
									}
									?>
									<a href="<?php echo $backUrl; ?>" class="btn btn-default">BACK</a>
								</div>
							</div>

						<!--</form>-->
							<?php echo form_close();?>
					</div>
				<?php 
					}
				?>
			</div>
		</div>
	</div>
    
	<?php
	$sno=0;
	if(!empty($image)){
		//http://karam.in/kare/uploads/images/featured/pass/pass1510220002.png
		$pass_image = ($image['pass_image']!='')? base_url()."uploads/images/featured/pass/".$image['pass_image'] : '';
		$fail_image = ($image['fail_image']!='')? base_url()."uploads/images/featured/fail/".$image['fail_image'] : '';
		$repair_image = ($image['repair_image']!='')? base_url()."uploads/images/featured/repair/".$image['repair_image'] : '';
	?>
	<script>
		$(function() {
			$("#pop<?php print "_pass_".$image['id'];?>").on("click", function() {
			   $('#imagepreview<?php print "_pass_".$image['id'];?>').attr('src', $(this).find('img').attr('src')); // here asign the image to the modal when the user click the enlarge link
			   $('#imagemodal<?php print "_pass_".$image['id'];?>').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
			});
			
			$("#pop<?php print "_fail_".$image['id'];?>").on("click", function() {
			   $('#imagepreview<?php print "_fail_".$image['id'];?>').attr('src', $(this).find('img').attr('src')); // here asign the image to the modal when the user click the enlarge link
			   $('#imagemodal<?php print "_fail_".$image['id'];?>').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
			});
			
			$("#pop<?php print "_repair_image_".$image['id'];?>").on("click", function() {
			   $('#imagepreview<?php print "_repair_image_".$image['id'];?>').attr('src', $(this).find('img').attr('src')); // here asign the image to the modal when the user click the enlarge link
			   $('#imagemodal<?php print "_repair_image_".$image['id'];?>').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
			});
		});	
	</script>
	<div class="row">
	<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading home-heading">
			<span>FEATURED IMAGES  LIST</span>
		</div>
		<div class="panel-body">
			<table class="table">
			<tr>
			<th>Pass</th>
			<th>Fail</th>
			<th>Repair</th>
			<th>Delete</th>
			</tr>
			<tr>
			
				<td>
					<a href="#" id="pop<?php print "_pass_".$image['id'];?>">
						<img src="<?php echo !empty($pass_image)?$pass_image:''; ?>"  alt="Pass Image" height="150" width="150" id="image"/>
					</a>
				</td>
				<td>
					<a href="#" id="pop<?php print "_fail_".$image['id'];?>">
						<img src="<?php echo  !empty($fail_image)?$fail_image:'';?>"   alt="Fail Image" height="150" width="150" id="image"/>
					</a>	
				</td>
				<td>
					<a href="#" id="pop<?php print "_repair_image_".$image['id'];?>">
						<img src="<?php echo !empty($repair_image)?$repair_image:''; ?>" alt="Repair Image" height="150" width="150" id="image"/>
					<a>
				</td>
				<td>
				<a href="<?php echo $base_url."manage_kare/upload_featured_images/".$image['id']; ?>" class="text-primary"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="<?php echo $base_url."manage_kare/delete_featured_images/".$image['id']; ?>" class="text-danger delete" ><span class="glyphicon glyphicon-trash"></span></a>
				</td>
			</tr>
			</table>
			
			<div class="modal fade" id="imagemodal<?php print "_pass_".$image['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">pass</h4>
				  </div>
				  <div class="modal-body">
						<img src="" id="imagepreview<?php print "_pass_".$image['id'];?>" style="width: 400px; height: 264px;" >
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  </div>
				</div>
			  </div>
			</div>
			
			<div class="modal fade" id="imagemodal<?php print "_fail_".$image['id'];?>" tabindex="-1" role="dialog"  aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">fail</h4>
				  </div>
				  <div class="modal-body">
						<img src="" id="imagepreview<?php print "_fail_".$image['id'];?>" style="width: 400px; height: 264px;" >
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  </div>
				</div>
			  </div>
			</div>
			
			<div class="modal fade" id="imagemodal<?php print "_repair_image_".$image['id'];?>" tabindex="-1" role="dialog"  aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">repair</h4>
				  </div>
				  <div class="modal-body">
						<img src="" id="imagepreview<?php print "_repair_image_".$image['id'];?>" style="width: 400px; height: 264px;" >
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  </div>
				</div>
			  </div>
			</div>
			
		</div>
	</div>
	</div>
	</div>
	<?php } ?>
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
