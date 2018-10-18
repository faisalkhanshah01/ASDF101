<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?> 
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>
			<legend class="home-heading">Inspect Asset</legend>
			<div class="panel-default">
				<div class="panel-heading"><label>Site ID :</label> <?php echo $_SESSION['inspector']['siteid']; ?><p class="pull-right"><label>Date :</label> <?php echo $_SESSION['inspector']['form_submitting_date']; ?></p></div>
			
				<div class="panel-body inspectionFirst" >
					<form class="form-horizontal" id="inspect_asset_form" action="" method="post" enctype="multipart/form-data" >

							<div class="form-group">
								<label for="asset_name" class="col-md-3 control-label">Asset Name:</label>
								<div class="col-md-8">
									<input type="text" class="form-control inspection_form" id="asset_name" name="asset_name" value="<?php echo $_SESSION['inspector']['asset_code']; ?>" readonly />
								</div>
							</div>
							
							<div class="form-group">
								<label for="before_repair_image" class="col-md-3 control-label">Before Repair Image</label>
								<div class="col-md-8">
									<input type="file"  class="form-control inputFile" id="before_repair_image" name="before_repair_image[]" placeholder="Before Repair Image" required multiple>
									<p>(only jpg,png,jpeg,gif image accepted)</p>
								</div>
							</div> 
							
							<div class="form-group">
								<label for="after_repair_image" class="col-md-3 control-label">After Repair Image</label>
								<div class="col-md-8">
									<input type="file"  class="form-control inputFile" id="after_repair_image" name="after_repair_image[]" placeholder="After Repair Image" required multiple>
									<p>(only jpg,png,jpeg,gif image accepted)</p>
								</div>
							</div>  
							
							<div class="form-group">
								<label for="after_repair_image" class="col-md-3 control-label">Expected Results</label>
								<div class="col-md-8">
									<div style="background-color:#e6e6ff; padding:1% 0">
									<ol>
										<?php foreach($_SESSION['inspector']['result'] as $key=>$result) { ?>
										<li style="padding:1% 2%"><?php echo $result ; ?></li>
										<?php } ?>
									</ol>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label for="inspection_type" class="col-md-3 control-label">Type of Inspection:</label>
								<div class="col-md-8">
									<div class="form-control"><?php echo $_SESSION['inspector']['inspection'] ?></div>
								</div>
							</div>
							<div id='observationPanel' class="col-md-offset-1 col-md-10 panel panel-default">
								<div class="col-md-12 panel panel-default">
									<div class="panel-heading">
										<span> Select Observations</span>
									</div>
									<div class="panel-body">
										<div class="col-md-10">
											<div class="form-group">
												<label for="observation_type" class="col-md-3 control-label">Type of Observation:</label>
												<div class="col-md-9">
													<select class="form-control observation_type" id="observation_type" rel='0' name="obs[0][observation_type]" required>
														<option value=""> - Select Observations - </option>
														<?php foreach($_SESSION['inspector']['observation'] as $key=>$obser) { ?>
														<option value="<?php echo $key ; ?>"><?php echo $obser ; ?></option>
														<?php } ?>
													</select>	
												</div>
											</div>

											<div class="form-group">
												<label for="action_proposed" class="col-md-3 control-label">Type of Action Proposed:</label>
												<div class="col-md-9">
													<select class="form-control" id="action_proposed0" rel='0' name="obs[0][action_proposed]" required>
														<option value=""> - Select Action Proposed - </option>
													</select>
												</div>
											</div>
											
											<div class="form-group">
												<label for="result" class="col-md-3 control-label">Action Taken:</label>
												<div class="col-md-9">
													<select class="form-control" id="result" name="obs[0][result]" required >
														<option value=""> - Select Action Taken - </option>
														<option value="ok"> Ok </option>
														<option value="repaired"> Repaired </option>
														<option value="replacement"> Replacement </option>
														<option value="no action taken"> No Action taken </option>
													</select>	
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group pull-right">
												<div class="col-md-12">
													<a class="btn btn-info" id="btnAddObservations" href="javascript:void(0);" /><i class="glyphicon glyphicon-plus"></i></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<!--	
							
							<div id='observationPanel' class="col-md-offset-1 col-md-10 panel panel-default">
								<div class="col-md-12 panel panel-default">
									<div class="panel-heading">
										<span> Select Observations</span>
									</div>
									<div class="panel-body">
										<div class="col-md-10">
											<div class="form-group">
												<label for="observation_type" class="col-md-3 control-label">Type of Observation:</label>
												<div class="col-md-9">
													<select class="form-control observation_type" rel='0' id="observation_type" name="obs[0][observation_type]" required>
														<option value=""> - Select Observation - </option>
														<?php foreach($_SESSION['inspector']['observation'] as $key=>$obser) { ?>
														<option value="<?php echo $key ; ?>"><?php echo $obser ; ?></option>
														<?php } ?>
													</select>
												</div>
											</div>

											<div class="form-group">
												<label for="action_proposed" rel='0' class="col-md-3 control-label">Type of Action Proposed:</label>
												<div class="col-md-9">
													<select class="form-control" id="action_proposed0" name="obs[0][action_proposed]" required>
														<option value=""> - Select Action Proposed - </option>
													</select>
												</div>
											</div>

											<div class="form-group">
												<label for="result" class="col-md-3 control-label">Action Taken:</label>
												<div class="col-md-9">
													<select class="form-control" id="result" rel='0' name="obs[0][result]" required >
														<option value=""> - Select Action Taken - </option>
														<option value="ok"> Ok </option>
														<option value="repaired"> Repaired </option>
														<option value="replacement"> Replacement </option>
														<option value="no action taken"> No Action taken </option>
													</select>	
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group pull-right">
													<div class="col-md-12">
														<a class="btn btn-info" id="btnAddObservations" href="javascript:void(0);" /><i class="glyphicon glyphicon-plus"></i></a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
-->
							<div class="form-group">
								<div class="col-md-offset-6 col-md-6">
									<button type="submit" class="btn btn-info" name="get_inspect_asset" id="get_inspect_asset" value="get_inspect_asset">Get Inspect Asset</button>
								</div>
							</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
<script type="text/javascript">
	/*** Entering rows in the table dynamically in Observations */
	
	jQuery(document).ready(function(){
		var count = 1;
		jQuery("#btnAddObservations").on("click",function(){
			jQuery("#observationPanel").append('<div id="observation_'+count+'" class="col-md-12 panel panel-default" data-id="'+count+'">'+
												'<div class="panel-heading">'+
													'<span>Select Observation</span>'+
												'</div>'+
												'<div class="panel-body">'+
													'<div class="col-md-10">'+
														'<div class="form-group">'+
															'<label for="observation_type" class="col-md-3 control-label">Type of Observation:</label>'+
															'<div class="col-md-9">'+
																'<select class="form-control observation_type" rel="'+count+'" id="observation_type'+count+'" name="obs['+count+'][observation_type]" required>'+
																	'<option value=""> - Select Observations - </option>'+
																	<?php foreach($_SESSION['inspector']['observation'] as $key=>$obser) { ?>
																	'<option value="<?php echo $key ; ?>"><?php echo $obser ; ?></option>'+
																	<?php } ?>
																'</select>'+
															'</div>'+
														'</div>'+

														'<div class="form-group">'+
															'<label for="action_proposed" class="col-md-3 control-label">Type of Action Proposed:</label>'+
															'<div class="col-md-9">'+
																'<select class="form-control" rel="'+count+'" id="action_proposed'+count+'" name="obs['+count+'][action_proposed]" required>'+
																	'<option value=""> - Select Action Proposed - </option>'+
																'</select>'+
															'</div>'+
														'</div>'+
														
														'<div class="form-group">'+
															'<label for="result" class="col-md-3 control-label">Action Taken:</label>'+
															'<div class="col-md-9">'+
																'<select class="form-control" id="result'+count+'" name="obs['+count+'][result]" required >'+
																	'<option value=""> - Select Action Taken - </option>'+
																	'<option value="ok"> Ok </option>'+
																	'<option value="repaired"> Repaired </option>'+
																	'<option value="replacement"> Replacement </option>'+
																	'<option value="no action taken"> No Action taken </option>'+
																'</select>'+
															'</div>'+
														'</div>'+
													'</div>'+
													'<div class="col-md-2">'+
														'<div class="form-group">'+
															'<div class="col-md-12">'+
																'<a class="pull-right btn btn-danger removeData" href="javascript:void(0)" data-id="'+count+'"><i class="glyphicon glyphicon-trash"></i></a>'+
															'</div>'+
														'</div>'+
													'</div>'+
												'</div>'+
											'</div>');
		
										count++;
		});
	});
</script>