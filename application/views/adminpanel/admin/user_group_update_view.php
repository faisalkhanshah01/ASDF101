<?php
#echo "<pre>";
#print_r($group); 
?>
<style>
    select.groups-tree option:disabled{
        background-color: #cccccc;
    }
    
</style>

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
			<div class="col-md-offset-3 col-md-6">
				<legend class="home-heading">Update User Group</legend>
				<div class="col-md-offset-8 col-md-4 ">
					<a href="<?php echo $base_url;?>auth_admin/manage_user_groups" class="btn btn-info">Manage User Groups</a>
					<br><br>
				</div>
                                 <?php
                                    $client_id=$_SESSION['client']['client_id'];
                                    $client_group_id=$_SESSION['client']['group_id'];
                                    $selected_id=$group['ugrp_pid'];
                                  ?>
                                
			
				<?php echo form_open(current_url(), 'class="form-horizontal"');	?>  	
						<legend>Group Details</legend>
                                                
                                                        <div class="form-group">
								<label for="group" class="col-md-4 control-label">Group Name:</label>
								<div class="col-md-8">
								<input type="text" id="group" class="form-control" name="update_group_name" value="<?php echo set_value('update_group_name', $group[$this->flexi_auth->db_column('user_group', 'name')]);?>"
									title="The name of the user group." required/>
								</div>	
							</div>
                                                
                                                        <div class="form-group">
								<label for="group" class="col-md-4 control-label">Select Parent Group Name:</label>
								<div class="col-md-8">
                                                                    
                                                                <select name="update_group_parent" class="form-control groups-tree">    
                                                                 <option value="<?php echo $client_group_id; ?>"> --Select parent --</option>
                                                                    <?php
                                                                        $client_id=$_SESSION['client']['client_id'];
                                                                        $client_group_id=$_SESSION['client']['group_id'];
                                                                        $selected_id=$group['ugrp_pid'];
                                                                       echo  $this->flexi_auth->groupTree($client_group_id,$group['ugrp_id'],$selected_id);
                                                                    ?>
                                                                </select>
                                                                
								</div>	
							</div>
							
							<div class="form-group">
								<label for="description" class="col-md-4 control-label">Group Description:</label>
								<div class="col-md-8">
								<textarea id="description" name="update_group_description" class="form-control"
									title="A short description of the purpose of the user group."><?php echo set_value('update_group_description', $group[$this->flexi_auth->db_column('user_group', 'description')]);?></textarea>
								</div>	
							</div>
							<div class="form-group">
								<?php $ugrp_admin = ($group[$this->flexi_auth->db_column('user_group', 'admin')] == 1) ;?>
								<label for="admin" class="col-md-4 control-label">Is Admin Group:</label>
								<div class="col-md-8">
								<input type="checkbox" id="admin" name="update_group_admin" value="1" <?php echo set_checkbox('update_group_admin', 1, $ugrp_admin);?> class="tooltip_trigger"
									title="If checked, the user group is set as an 'Admin' group."/>
								</div>
							</div>
							<div class="form-group">
								<label for="admin" class="col-md-4 control-label">User Group Privileges:</label>
								<div class="col-md-8">
								<a href="<?php echo $base_url;?>auth_admin/update_group_privileges/<?php echo $group['ugrp_id']; ?>">Manage Privileges for this User Group</a>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">	
									<input type="submit" name="update_user_group" id="submit" value="Update User Group" class="btn btn-primary"/>
								</div>
							</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>