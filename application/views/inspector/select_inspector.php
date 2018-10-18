<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?> 
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
							echo $this->session->flashdata('msg');
				} ?>
			</div>
			<legend class="home-heading"><?php if( $lang["select_inspector"]['description'] !='' ){ echo $lang["select_inspector"]['description']; }else{ echo 'Select Inspector'; } ?></legend>

			<div class="col-md-12" >
				<form class="form-horizontal" id="inspector_form" action="" method="post" >
					<div class="form-group">
						<label for="asset_name" class="col-md-3 control-label"><?php if( $lang["select_inspector"]['description'] !='' ){ echo $lang["select_inspector"]['description']; }else{ echo 'Select Inspector'; } ?>:</label>
						<div class="col-md-8">
							<select class="form-control" id="inspector_name" name='selected_inspector' required>
							<option value=''> Select Inspector </option>
							<?php foreach($inspector_list as $key=>$value){
							?>
							<option value='<?php echo $key; ?>'><?php echo $value; ?></option>
							<?php }?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-6 col-md-6">
							<button type="submit" class="btn btn-info" name="inspector_name" id="inspector_name" value="inspector_name"> <?php if( $lang["submit"]['description'] !='' ){ echo $lang["submit"]['description']; }else{ echo 'Submit'; } ?> </button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>