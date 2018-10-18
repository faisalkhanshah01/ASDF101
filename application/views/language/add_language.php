<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?> 
	<script>
	function rtl(element)
{   
    if(element.setSelectionRange){
        element.setSelectionRange(0,0);
    }
}

	</script>	
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
				<div class="panel-heading home-heading">
					<span >Add Language Level</span>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart($base_url.'language_controller/language_level_add', 'class="form-horizontal"', 'id="update_clients"', 'method="post"'); ?>
					
						<div class="form-group">
							  <label for="client_name" class="col-md-4 control-label">Level</label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->level; ?>" id="lang_level" name="lang_level" placeholder="Level" required>
							  </div>
							</div>
							
							<div class="form-group">
							  <label for="group_name" class="col-md-4 control-label">Group Name</label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->group_name; ?>" id="group_name" name="group_name" placeholder="Groupt" required>
							  </div>
							</div>

							<fieldset>
								<legend>English</legend>
							<div class="form-group">
							  <label for="English Decription" class="col-md-4 control-label">English Decription</label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->en_description; ?>" id="en_description" name="en_description" placeholder="English description" required>
							  </div>
							</div>

							<div class="form-group">
							  <label for="English Decription" class="col-md-4 control-label">English Long Decription</label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->en_long_description; ?>" id="en_long_description" name="en_long_description" placeholder="English description" required>
							  </div>
							</div>
							</fieldset>

							<fieldset>
								<legend>French</legend>
							<div class="form-group">
							  <label for="French Decription" class="col-md-4 control-label">French Decription</label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->fr_description; ?>" id="fr_description" name="fr_description" placeholder="French description" required>
							  </div>
							</div>
							<div class="form-group">
							  <label for="French Decription" class="col-md-4 control-label">French Long Decription</label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->fr_long_description; ?>" id="fr_long_description" name="fr_long_description" placeholder="French description" required>
							  </div>
							</div>
							</fieldset>

							<fieldset>
								<legend>Arabic</legend>
							<div class="form-group">
							  <label for="Arabic Decription" class="col-md-4 control-label">Arabic Decription</label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->arabic_description; ?>" id="arabic_description" name="arabic_description" placeholder="Arabic description" style="direction:RTL;" onkeyup="rtl(this);" required>
							  </div>
							</div>

							<div class="form-group">
							  <label for="Arabic Decription" class="col-md-4 control-label">Arabic Long  Decription</label>
							  <div class="col-md-6">
								<input type="text" class="form-control" value="<?php echo $postt->arabic_long_description; ?>" id="arabic_long_description" name="arabic_long_description" placeholder="Arabic description" style="direction:RTL;" onkeyup="rtl(this);" required>
							  </div>
							</div>
							</fieldset>
							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">
									<input type="submit" name="add_lang" class="btn btn-primary" id="add_level" value="Add Level" />
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $base_url;?>language_controller/index"><button class="btn btn-info" type="button">Back</button>
								</div>
							</div>
					
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?>   
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
