<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?> 
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>
			<legend class="home-heading">Inspector Information</legend>
			<div class="panel-default">
				<div class="panel-heading"><label>Site ID :</label> <?php echo $_SESSION['inspector']['siteid']; ?><p class="pull-right"><label>Date :</label> <?php echo $_SESSION['inspector']['form_submitting_date']; ?></p></div>
			
				<div class="panel-body inspectionFirst" >
					<form class="form-horizontal" id="inspector_information_form" action="" method="post" enctype="multipart/form-data" >

							<div class="form-group">
								<label for="asset_name" class="col-md-3 control-label">Inspector Name:</label>
								<div class="col-md-8">
									<div class="form-control inspection_form"><?php echo $_SESSION['inspector']['selected_inspector_name']; ?></div>
								</div>
							</div>
							<div class="form-group">
								<label for="inspection_type" class="col-md-3 control-label">Designation:</label>
								<div class="col-md-8">
									<div class="form-control"><?php echo $_SESSION['inspector']['user_designation'] ?></div>
								</div>
							</div>
							
							<div class="form-group">
								<label for="signature_image" class="col-md-3 control-label">Upload Signature Image</label>
								<div class="col-md-8">
									<input type="file"  class="form-control inputFile" id="signature_image" name="signature_image" placeholder="Inspector Signature Image" required >
									<p>(only jpg,png,jpeg,gif image accepted)</p>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-6 col-md-6">
									<button type="submit" class="btn btn-info" name="inspector_information_form" id="inspector_information" value="inspector_information_form">Submit</button>
								</div>
							</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>