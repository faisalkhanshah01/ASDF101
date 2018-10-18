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
				<legend class="home-heading"><?php echo 'Arresto Clients/Customer List';  ?></legend>
				<table class="table table-bordered home_table" >
					<thead>
                                            <tr>
                                                    <th class="text-center">Sr.No.</th>
                                                    <th class="text-center">Custome Name </th>
                                                    <th class="text-center">Email </th>
                                                    <th class="text-center">Company Name </th>
                                                    <th class="text-center">Company Slug </th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                            </tr>
					</thead>
					<tbody>
                                            <?php  $count=0;
                                                foreach($clients as $client){
                                                    if($client['uacc_email']=='varun@flashonmind.com'){ continue;}
                                                  $count++;
                                                ?>
                                            <tr>
                                                <td><?php echo $count; ?></td> 
                                                <td><?php echo $client['upro_first_name']; ?></td> 
                                                <td><?php echo $client['uacc_email']; ?></td> 
                                                <td><?php echo $client['customer_company_name']; ?></td> 
                                                <td><?php echo $client['customer_company_slug']; ?></td>
                                                <td><?php echo  ($client['activation_flag']==1)? 'Active':'Inactive'; ?></td>                           
                                                <td><a href="<?php echo $base_url."auth_admin/client_edit/".$client['customer_id']; ?>">Edit</a> || 
                                                        <a href="<?php echo $base_url."auth_admin/client_delete/".$client['customer_id']; ?>">Delete</a>|| 
                                                        <a href="#">Change_status</a></td>   
                                            </tr>
                                            <?php } ?>
	
					</tbody>
				</table>
			</div>
	</div>
	
<?php $this->load->view('admin_includes/footer'); ?>
<?php $this->load->view('admin_includes/scripts'); ?>
