<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
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
		<?php
			if(isset($inspection_list)){
				$title = ($lang["edit_action_proposed"]['description'] !='')? $lang["aedit_action_proposededit_action_proposed_action_proposed"]['description'] :'EDIT ACTION PROPOSED';
				$button_text = 'UPDATE';
				$button_name = 'edit_inspection_result';
			}else{
				$title = ($lang["add_action_proposed"]['description'] !='')? $lang["add_action_proposed"]['description'] :'ADD ACTION PROPOSED';
				$button_text = 'SAVE';
				$button_name = 'save_inspection_result';
			}
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading home-heading">
						<span><?php echo $title; ?></span>
					</div>
					<div class="panel-body">
						<?php echo form_open_multipart(current_url() , 'class="form-horizontal" id="inspection_resultForm"'); ?>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["add_new_action_proposed"]['description'] !='' ){ echo $lang["add_new_action_proposed"]['description']; }else{ echo "Add New Action Proposed"; }  ?></label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="type_name" name="type_name" 
									value="<?php echo (!isset($inspection_list))? set_value('type_name') : $inspection_list['type_name'];?>" required />
									<?php echo form_error('type_name'); ?>
								</div>
							</div>

							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["select_type"]['description'] !='' ){ echo $lang["select_type"]['description']; }else{ echo "Select Type"; }  ?></label>
								<div class="col-md-6">
									<select  id="type" name="type" class="form-control tooltip_trigger" required>
										<option selected value=""> - Observation - </option>
										<?php
										if(!empty($type_category)){
											foreach($type_category as $type){
												$selected = (isset($inspection_list) && $inspection_list['type_category'] == $type['id'])? 'Selected' : '' ;
												echo "<option ".$selected." value='".$type['id']."'>".$type['type_name']."</option>";
											}
										}else{
											#echo '<option value="0">New Type</option>';
										}
										?>
									</select>
								 <?php echo form_error('type'); ?> 
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; }  ?></label>
								<div class="col-md-6">
									<select  id="status" name="status" class="form-control tooltip_trigger" required>
									<option selected value=""> - Status - </option>
										
										<option <?php echo (isset($inspection_list) && $inspection_list['status'] == 1)? 'Selected' : '' ; ?> value="1">Active</option>
										<option <?php echo (isset($inspection_list) && $inspection_list['status'] == 0)? 'Selected' : '' ; ?> value="0">Inactive</option>
									</select>
								 <?php echo form_error('status'); ?> 
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-md-offset-4 col-md-6">
									<input type="submit" name="<?php echo $button_name;?>" class="btn btn-primary" id="submit" value="<?php echo $button_text; ?>"  title="Click to Submit Form" />
									<?php if(isset($inspection_list)){ ?>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('subassets_kare/inspection_observation_list'); ?>" class="btn btn-default" title="Click to Go Back"><?php if( $lang["back"]['description'] !='' ){ echo $lang["back"]['description']; }else{ echo "BACK"; }  ?></a>
									<?php } ?>
								</div>
							</div>

						<!--</form>-->
						<?php echo form_close();?>
					</div>
				</div>
			</div>
		</div>
		<div class="row" id="inspection_list">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading home-heading">
						<span ><?php if( $lang["action_proposed_list"]['description'] !='' ){ echo $lang["action_proposed_list"]['description']; }else{ echo "ACTION PROPOSED LIST"; }  ?></span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered" id="inspection_result_table">
							<thead>
								<th><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo "Action"; }  ?></th><th><?php if( $lang["s_no"]['description'] !='' ){ echo $lang["s_no"]['description']; }else{ echo "S. No"; }  ?></th><th><?php if( $lang["name"]['description'] !='' ){ echo $lang["name"]['description']; }else{ echo "Name"; }  ?></th><th><?php if( $lang["type"]['description'] !='' ){ echo $lang["type"]['description']; }else{ echo "Type"; }  ?></th><th><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; }  ?></th>
							</thead>
							<tbody>
								<?php
								$sno=0;
								foreach($inspection_result_list as $res){
								$sno++;
								?>
								<tr>
									<td class="text-center"><a href="<?php echo $base_url."subassets_kare/inspection_observation_list/".$res['id']; ?>"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> 
										<a class="delete" style="margin:0px 10px;" href="<?php echo $base_url."subassets_kare/delete_action_proposed_result/".$res['id']; ?>"><span class="glyphicon glyphicon-trash error"></span></a>
									</td>
									<td><?php echo $sno; ?></td>
									<td><?php echo $res['type_name']; ?></td>
									<td><?php echo $res['parent_name']; ?></td>
									<td><center><?php echo ($res['status'] ==1)? '<font color="green">Active</font>':'<font color="red">Inactive</font>'; ?></center></td>
								</tr>
								<?php } ?>
							</tbody> 
						</table>
					</div>
				</div>
			</div>
		</div>
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 
<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?>