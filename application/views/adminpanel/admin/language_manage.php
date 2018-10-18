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
		<div class="col-md-10 col-md-offset-1">
				 
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
		</div>
	</div>
	
<?php $this->load->view('admin_includes/footer'); ?>
<?php $this->load->view('admin_includes/scripts'); ?>
