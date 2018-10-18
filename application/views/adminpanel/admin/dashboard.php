<?php $this->load->view('admin_includes/header'); ?>
<?php $this->load->view('admin_includes/head'); ?>
        
	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if (!empty($this->session->flashdata('msg'))||isset($msg)) {
						echo (isset($msg))? $msg : $this->session->flashdata('msg');
					}
					if(validation_errors() !=''){
						echo '<div class="alert alert-danger capital">'.validation_errors().'</div>';
					}
				?>
			</div>
			
		</div>
	</div> 

	<div class="row" >
			<div class="col-md-12">
				<legend class="home-heading"><?php if( $lang['inspection_reports']['description'] !='' ){ echo $lang['inspection_reports']['description']; }else{ echo 'Dashboard'; }  ?></legend>
				<table class="table table-bordered home_table" >
					<thead>
                                            <tr>
                                                    <th class="text-center"><?php if( $lang['details']['description'] ){ echo $lang['details']['description']; }else{ echo 'details'; }  ?></th>
                                                    <th class="text-center"><?php if( $lang['total_numbers']['description'] ){ echo $lang['total_numbers']['description']; }else{ echo 'Total Numbers'; }  ?> </th>
                                                    <th class="text-center"><?php if( $lang['action']['description'] ){ echo $lang['action']['description']; }else{ echo 'Action'; }  ?></th>
                                            </tr>
					</thead>
					<tbody>
						
						<tr>
						</tr>
						
						
					</tbody>
				</table>
			</div>
	</div>
	
<?php $this->load->view('admin_includes/footer'); ?>
<?php $this->load->view('admin_includes/scripts'); ?>
