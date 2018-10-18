<?php
$this->load->view('admin_panel/common/header', $header_data);
?>
<div class="col-md-10 col-md-offset-1 after-login-forms" id="admin-dashboard">
	<div class="col-md-12 form">
		<div class="row">
			<?php echo $this->session->flashdata('msg'); ?>
		</div>
            
	<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>Manage Language</span>
				</div>
            
				<div class="panel-body">
                                    
                                        <fieldset>
				            <legend>Add New Language</legend>
					<?php 
                                        $attr=array('class'=>'form-horizontal',name=>"languageAddForm");
                                                     echo form_open_multipart(current_url() ,$attr); ?>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Language </label>
							<div class="col-md-8">
                                                            <input type="text" class="form-control" id="lang_name" name="lang_name"  required="1" />
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Language status </label>
							<div class="col-md-8">
                                                            <select  class="form-control" id="language" name="lang_status" required="1">
                                                                <option value="1">Active</option>
                                                                <option value="0">Inactive</option>
                                                            </select>
                                                        
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<input type="submit" name="lang_add" class="btn btn-primary act-but-prm" id="submit" value="ADD Language" />
							</div>
						</div>
					<!--</form>-->
					<?php echo form_close();?>
                                        </fieldset>
                                    
                                    	<fieldset>
				            <legend>Uplaod Language  File </legend>
					<?php 
                                        $attr=array('class'=>'form-horizontal',name=>"languageUploadForm");
                                                     echo form_open_multipart(current_url() ,$attr); ?>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Language</label>
							<div class="col-md-8">
                                                            <select  class="form-control" id="language" name="language" >
                                                                <?php foreach($lang_list as $lang){ ?>
                                                                <option value="<?php echo strtoupper($lang['lang_name']); ?>"><?php echo $lang['lang_name'];?></option>
                                                                <?php } ?>    
                                                            </select>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Import Language File</label>
							<div class="col-md-8">
								<input type="File" class="form-control" id="lang_file_upload" name="lang_file_upload"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<input type="submit" name="lang_upload_submit" class="btn btn-primary act-but-prm" id="submit" value="Upload Language File" />
							</div>
						</div>
					<!--</form>-->
					<?php echo form_close();?>
                                        </fieldset>  
                                        
				</div>
			</div>

		<div class="row client-list">
			<h4> Supported Laguage</h4>
			<table class="table table-striped table-bordered" id="client-search-table">
				<thead>
					<tr>
                                            <th>Sr. No.</th>
                                            <th>Language</th>
                                            <th>Status</th>
                                            <th>Action</th>
					</tr>
				</thead>
				<tbody>
                                    <?php
                                      $count=0;
                                      foreach($lang_list as $lang){
                                      $count++;
                                     ?>
                                    <tr id="cls-user-<?php echo  $client['customer_id']; ?>" class="">
						<td><?php echo $count; ?></td>
						<td><?php echo $lang['lang_name'] ?></td>
                                                <td class="cls-user-status"><?php echo ($lang['lang_status']==1)?'Active':'Inactive'; ?></td>
						<td>
                                                    <?php 
                                                     $customer_id=$client['customer_id'];
                                                     $action_class= ($client['activation_flag']==1)?'btn-danger':'btn-success';
                                                     $action= ($client['activation_flag']==1)?'Inactive':'Active';
                                                     ?>
<!--                                                    <button  class="btn <?php //echo $action_class; ?> cls-action-status"   data-user-id="<?php //echo $customer_id; ?>"><?php //echo $action; ?></button>-->
<!--                                                    <a href="<?php //echo $base_url."/auth_admin/client_edit/".$client['customer_id']; ?>">Edit</a> ||
                                                    <a href="<?php //echo $base_url."/auth_admin/language_delete/".$client['customer_id']; ?>">Delete</a>-->
                                                </td>
					</tr>
			<?php }?>
					
		      </tbody>
			</table>
		</div>
	</div>
</div>
 
<?php 
 $this->load->view('admin_panel/common/footer_after_login');
?>

