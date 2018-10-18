<?php $this->load->view('includes/header'); ?> 
	<?php $this->load->view('includes/head'); ?> 
	
	<div class="row">
		<div class="col-md-12">
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
		</div>
		<div class="col-md-12">
			<h1>Dashboard</h1>
		</div>

	</div>
	<!-- Footer -->  
	<?php $this->load->view('includes/footer'); ?> 
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
