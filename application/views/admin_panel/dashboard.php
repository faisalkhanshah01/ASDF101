<?php
$this->load->view('admin_panel/common/header', $header_data);
?>

<div class="col-md-10 col-md-offset-1 after-login-forms" id="admin-dashboard">
	<div class="col-md-8 form">
		<div class="row">
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
            

	</div>
</div>

<div class="col-md-8 col-md-offset-1">
        <h2>Admin Dashboard</h2>
        <div class="row">
            <div class="col-md-4 col-md-offset-1">
            </div>    

            <div class="col-md-4 col-md-offset-1">
            </div>

        </div>
        <div class="row">
        </div>

</div>                    
                         
<?php 
 $this->load->view('admin_panel/common/footer_after_login');
?>

