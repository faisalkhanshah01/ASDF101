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
	
	<?php
		if(isset($specification) && !empty($specification)){
			$btnName	= "update_specification";
			$btnValue 	= "Update Specification";
			$title 	= "Update Specification";
			$_SESSION['specification']['updateID'] = $specification['id'];
		}else{
			$btnName	= "add_specification";
			$btnValue 	= "Upload Specification";
			$title 	= "Upload Specification";
		}
	?>
	<div id="global_searchAllView">
		<div class="row">
			<div class="col-md-12">
				<legend class="home-heading"><?php echo $title; ?></legend>
				<div class="col-md-12 text-center">
					<?php if(!empty($this->session->flashdata('msg'))){
								echo $this->session->flashdata('msg');
					} ?>
				</div>
				<a href='<?php echo base_url(); ?>specification/specifications/back' class="btn btn-primary pull-right">Back</a><br /><br />
			</div>
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<span><?php echo $_SESSION['specification']['selected_type_val'];?></span>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" id="upload_specification_form" action="" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<div class="col-md-12">
									<table id="upload_specification_table" class="table table-hover table-bordered home_table" >
										<tbody>
											<?php
											// FORM EDIT
											if(isset($_SESSION['specification']['upload_data']) || (isset($specification) && !empty($specification))){
												$file = (isset($specification) && !empty($specification))? json_decode($specification['file'], true) : $_SESSION['specification']['upload_data'];
												$file_count =  count($file);
												$countVal = 0;
												foreach($file as $sKey=>$sVal){ ?>
													<tr data-id="<?php echo $countVal; ?>">
														<td class="col-md-4 alert-warning text-center">
															<div class="col-md-2">
															<label for="" class="control-label"></label></div>
															<div class="col-md-10">
															
															<input type='text' class='form-control' name='upload[<?php echo $countVal; ?>][title]' id='title<?php echo $countVal; ?>' placeholder='enter name' value='<?php echo $sVal['title']; ?>'
	<?php if($countVal < 5){ echo "readonly"; } ?> />
															</div>
														</td>
														<td class="col-md-8 text-center alert-warning">
															<div class="col-md-2 ">
															<label for="" class="control-label ">UPLOAD</label>
															</div>
															<div class="col-md-10">
																<input class='form-control' type="file" name="upload[<?php echo $countVal; ?>][url]" />
															</div>
														</td>
														<td><?php if($sVal["url"] !=''){ ?>
															<a href="<?php echo $sVal["url"]; ?>">View</a>
														<?php  } ?>
														</td>
														<td>
															<?php if($sVal["url"] !=''){ ?>
																<a href="<?php echo base_url();?>specification/delete_uploadFile?title=<?php echo $sVal['title']; ?>&fileName=<?php echo $sVal['file_name']; ?>" class="text-danger delete"><span class="glyphicon glyphicon-trash"></span></a>
															<?php  } ?>
														</td>
											<?php if($countVal ==4){ ?>
													</tr><tr><td></td><td></td><td></td><td></td>
															<td><a class="btn btn-info"  id="btnAddSpecification" href="javascript:void(0);" /><i class="glyphicon glyphicon-plus"></i></a></td>
														</tr>
											<?php }elseif($countVal > 4){ ?>
															<td><a class="btn btn-danger removeData" href="javascript:void(0)" data-id="<?php echo $countVal; ?>"><i class="glyphicon glyphicon-trash"></i></a></td>
															<?php } ?>
													</tr>
												<?php  $countVal++; }
											}else{ $file_count = 5; $countVal = 4; // FORM INSERT ?>
											<tr>
												<td class="col-md-4 alert-warning text-center">
													<label for="" class="control-label alert-warning">Product Image</label>
													<input type='hidden' class='form-control' name='upload[0][title]' id='title0' value="Product Image" required />
												</td>
												<td class="col-md-8" colspan="2">
													<label for="" class="control-label alert-warning col-md-2 ">UPLOAD</label>
													<div class="col-md-10">
														<input class='form-control' type="file" name="upload[0][url]" />
													</div>
												</td>
											</tr>
										
											<tr>
												<td class="col-md-4 alert-warning text-center">
													<label for="" class="control-label alert-warning">Technical Specification</label>
													<input type='hidden' class='form-control' name='upload[1][title]' id='title1' value="Technical Specification" required />
												</td>
												<td class="col-md-8" colspan="2">
													<label for="" class="control-label alert-warning col-md-2 ">UPLOAD</label>
													<div class="col-md-10">
														<input class='form-control' type="file" name="upload[1][url]" />
													</div>
												</td>
											</tr>
										
											<tr>
												<td class="col-md-4 alert-warning text-center">
													<label for="" class="control-label alert-warning">Technical Data Sheet</label>
													<input type='hidden' class='form-control' name='upload[2][title]' id='title2' value="Technical Data Sheet" required />
												</td>
												<td class="col-md-8" colspan="2">
													<label for="" class="control-label alert-warning col-md-2 ">UPLOAD</label>
													<div class="col-md-10">
														<input class='form-control' type="file" name="upload[2][url]" />
													</div>
												</td>
											</tr>
											<tr>
												<td class="col-md-4 alert-warning text-center">
													<label for="" class="control-label alert-warning">Certificate</label>
													<input type='hidden' class='form-control' name='upload[3][title]' id='title3' value="Certificate" required />
												</td>
												<td class="col-md-8" colspan="2">
													<label for="" class="control-label alert-warning col-md-2 ">UPLOAD</label>
													<div class="col-md-10">
														<input class='form-control' type="file" name="upload[3][url]" />
													</div>
												</td>
											</tr>
											<tr>
												<td class="col-md-4 alert-warning text-center">
													<label for="" class="control-label">Presentation</label>
													<input type='hidden' class='form-control' name='upload[4][title]' id='title4' value="Presentation" required />
														</div>
												</td>
												<td class="col-md-8" colspan="2">
													<label for="" class="control-label alert-warning col-md-2 ">UPLOAD</label>
													<div class="col-md-10">
														<input class='form-control' type="file" name="upload[4][url]" />
													</div>
												</td>
											</tr>
												<tr>
													<td class="col-md-4 alert-warning"></td>
													<td class="col-md-8 alert-warning"></td>
													<td class="alert-warning"></td>
													<td><a class="btn btn-info" id="btnAddSpecification" href="javascript:void(0);" /><i class="glyphicon glyphicon-plus"></i></a></td>
												</tr>
											<?php } ?>
											
										</tbody>
									</table>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-6 col-md-offset-5">
									<button class="btn btn-primary"  name="<?php echo $btnName; ?>" id="upload_specification" value="upload_specification" type="submit"><?php echo $btnValue; ?></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
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
	jQuery(document).ready(function(){
	var count = <?php echo $countVal+1; ?>;
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
												'<label for="" class="control-label ">UPLOAD</label>'+
												'</div>'+
												'<div class="col-md-10">'+
													'<input class="form-control" type="file" name="upload['+count+'][url]" />'+
												'</div>'+
											'</td><td></td>'+
											'<td>'+
												'<a class="btn btn-danger removeData" href="javascript:void(0)" data-id="'+count+'"><i class="glyphicon glyphicon-trash"></i></a>'+
											'</td>'+
										'</tr>');
									count++;
			});
	});
	</script>