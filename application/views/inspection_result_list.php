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
				$title = ( $lang["edit_inspection_result_client_type"]['description'] !='' ) ? $lang["edit_inspection_result_client_type"]['description']: 'EDIT INSPECTION / RESULT / CLIENT TYPE';
				$button_text = 'UPDATE';
				$button_name = 'edit_inspection_result';
			}else{
				$title = ( $lang["add_inspection_result_client_type"]['description'] !='' ) ? $lang["add_inspection_result_client_type"]['description']: 'ADD INSPECTION / RESULT / CLIENT TYPE';
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
                                            
                                            <div class="row">
                                                <div class="col-md-8"> 
						<?php echo form_open_multipart(current_url() , 'class="form-horizontal" id="inspection_resultForm"'); ?>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["add_type_name"]['description'] !='' ){ echo $lang["add_type_name"]['description']; }else{ echo "Add Type Name"; }  ?></label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="type_name" name="type_name" 
									value="<?php echo (!isset($inspection_list))? set_value('type_name') : $inspection_list['type_name'];?>" required />
									<?php echo form_error('type_name'); ?>
								</div>
							</div>
							<?php 
							 ?>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label"><?php if( $lang["select_type"]['description'] !='' ){ echo $lang["select_type"]['description']; }else{ echo "Select Type"; }  ?></label>
								<div class="col-md-6">
									<select  id="type" name="type" class="form-control tooltip_trigger" required>
										<option selected value=""> - Type - </option>
										<?php
										if(!empty($type_category)){
											foreach($type_category as $type){
												$selected = (isset($inspection_list) && $inspection_list['type_category'] == $type['id'])? 'Selected' : '' ;
												echo "<option ".$selected." value='".$type['id']."'>".$type['type_name']."</option>";
											}
										}else{
											echo '<option value="0">New Type</option>';
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
								<div class="col-md-5 col-md-8">
									<input type="submit" name="<?php echo $button_name;?>" class="btn btn-primary" id="submit" value="<?php echo $button_text; ?>" />
									<?php if(isset($inspection_list)){ ?>
										<a href="<?php echo base_url('subassets_kare/inspection_result_list'); ?>" class="btn btn-default"><?php if( $lang["back"]['description'] !='' ){ echo $lang["back"]['description']; }else{ echo "BACK"; }  ?></a>
									<?php } ?>
								</div>
							</div>
						<!--</form>-->
						<?php echo form_close();?> 
                                                </div>
                                                <div class="col-md-4">
							<?php echo form_open_multipart($base_url.'subassets_kare/import_assets_result_type', 'class="form-horizontal"'); ?>
							<legend class="home-heading">IMPORT Observations/ Expected Result FROM XLS/CSV"</legend>
								<div class="form-group">
								<label for="email" class="col-md-4 control-label"><?php if( $lang["upload_xls_file"]['description'] !='' ){ echo $lang["upload_xls_file"]['description']; }else{ echo "Upload Xls FIle"; } ?></label>
								<div class="col-md-8">
									<input type="file" id="file_upload" name="file_upload" class="form-control tooltip_trigger" /> <br/>Supported Only : (.xls /.xlsx /.csv)
									<?php echo form_error('file_upload'); ?>    
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-4 col-md-8">
									<input type="submit" name="import_result_type" class="btn btn-primary" id="import_result_type" value="Uplaod XLS" />
								</div>
							</div>
							<?php echo form_close();?>
                                                </div>
                                                
                                            </div>
                                            
                                            
                                            
					</div>
				</div>
			</div>
		</div>

		<div class="row" id="inspection_list">
			<div class="col-md-12">
				 <div class="panel panel-default">
					<div class="panel-heading home-heading">
						<span><?php if( $lang["inspection_result_client_list"]['description'] !='' ){ echo $lang["inspection_result_client_list"]['description']; }else{ echo "INSPECTION / RESULT / CLIENT LIST"; }  ?></span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered" id="inspection_result_table">
							<thead>
								<th><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo "Action"; }  ?></th><th><?php if( $lang["s_no"]['description'] !='' ){ echo $lang["s_no"]['description']; }else{ echo "S. No."; }  ?></th><th><?php if( $lang["name"]['description'] !='' ){ echo $lang["name"]['description']; }else{ echo "Name"; }  ?></th><th><?php if( $lang["type"]['description'] !='' ){ echo $lang["type"]['description']; }else{ echo "Type"; }  ?></th><th><?php if( $lang["status"]['description'] !='' ){ echo $lang["status"]['description']; }else{ echo "Status"; }  ?></th>
							</thead>
							<tbody>
								<?php
								$sno=0;
								foreach($inspection_result_list as $res){
									$sno++;
								?>
								<tr>
									<td><a href="<?php echo $base_url."subassets_kare/inspection_result_list/".$res['id']; ?>"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> 
										<a class="delete" style="margin:0px 10px;" href="<?php echo $base_url."subassets_kare/delete_inspection_result/".$res['id']; ?>"><span class="glyphicon glyphicon-trash error"></span></a>
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