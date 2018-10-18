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
				<legend class="home-heading">Insert New User Group</legend>
				<div class="col-md-offset-9">
					<a href="<?php echo $base_url;?>auth_admin/manage_user_groups" class="btn btn-info">Manage User Groups</a>
					<br><br>
				</div>
                                
                                                        <?php
//                                                        $output="";
//                                                        $edit_id=66;
//                                                        echo  CategoryTree($output,59,"",$edit_id); 

                                                        #echo $this->flexi_auth->groupTree(59); die;
                                                         $client_group_id=$_SESSION['client']['group_id'];
                                                        ?>
                                
				<?php echo form_open(current_url(), 'class="form-horizontal"');	?>
						<legend>Group Details</legend>
                                                <div class="form-group">
							<label for="group" class="col-md-4 control-label">Select Group Name:</label>
							<div class="col-md-8"> 
                                                            <select name="insert_group_parent"  id="insert_group_parent" class="form-control">
                                                                <option value="<?php echo $client_group_id; ?>"> --Select parent --</option>
                                                                  <?php
                                                                     echo $this->flexi_auth->groupTree($client_group_id);
                                                                  ?>
                                                            </select>
							</div>
						</div>
						<div class="form-group">
							<label for="group" class="col-md-4 control-label">Group Name:</label>
							<div class="col-md-8">
							<input type="text" id="group" class="form-control" name="insert_group_name" value="<?php echo set_value('insert_group_name');?>"
								title="The name of the user group."/>
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-md-4 control-label">Group Description:</label>
							<div class="col-md-8">
							<textarea id="description"  class="form-control"name="insert_group_description" class="width_400 tooltip_trigger"
								title="A short description of the purpose of the user group."><?php echo set_value('insert_group_description');?></textarea>
							</div>	
						</div>
						<div class="form-group">
							<label for="admin" class="col-md-4 control-label">Is Admin Group:</label>
							<div class="col-md-8">
							<input type="checkbox" id="admin" name="insert_group_admin" value="1" <?php echo set_checkbox('insert_group_admin',1);?> class="tooltip_trigger"
								title="If checked, the user group is set as an 'Admin' group."/>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<input type="submit" name="insert_user_group" id="submit" value="Insert New User Group" class="btn btn-primary"/>
							</div>
						</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>