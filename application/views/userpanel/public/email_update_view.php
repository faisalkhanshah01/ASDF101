<?php $this->load->view('includes/header'); ?>
	<?php	//$this->load->view('includes/head');	?>
	 	<?php
		$groupId = $_SESSION['flexi_auth']['group'];
		foreach($groupId as $k=>$v){
			$name = $v;
			$groupID = $k;
		}
		
	?>
	<?php 	if ( $groupID == 11 || $groupID == 10){
				$this->load->view('includes/head_infonet');
			}else{ 
				$this->load->view('includes/head'); 
			}
	?>
	<div id="global_searchAllView">
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
				<div class="panel panel-default">
					<div class="panel-heading home-heading">
							<span><?php if( $lang["change_email_via_email_verification"]['description'] !='' ){ echo $lang["change_email_via_email_verification"]['description']; }else{ echo "Change Email via Email Verification"; }  ?></span>
						</div>
					<div class="panel-body">
						<div class="col-md-offset-3 col-md-6">
							<div class="col-md-offset-10 col-md-3 ">
								<a href="<?php echo $base_url;?>auth_public/update_account" class="btn btn-info"><?php if( $lang["update_account_details"]['description'] !='' ){ echo $lang["update_account_details"]['description']; }else{ echo "Update account Details"; }  ?></a>
								</br></br>
							</div>
						
							<?php echo form_open(current_url(), 'class="form-horizontal"');	?>
								<legend><?php if( $lang["change_email"]['description'] !='' ){ echo $lang["change_email"]['description']; }else{ echo "Change Email"; }  ?></legend>
								<div class="form-group">
									<label for="email_address" class="col-md-4 control-label"><?php if( $lang["new_email_address"]['description'] !='' ){ echo $lang["new_email_address"]['description']; }else{ echo "New Email Address"; }  ?>:</label>
									<div class="col-md-8">
										<input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo set_value('email_address');?>"/>
									</div>
								</div>
								
								<div class="form-group">
									<div class=" col-md-offset-4 col-md-8">
										<input type="submit" name="update_email" id="submit" value="<?php if( $lang["update_email"]['description'] !='' ){ echo $lang["update_email"]['description']; }else{ echo "Update Email"; }  ?>" class="btn btn-primary act-but-prm"/>
									</div>
										</div>
							<?php echo form_close();?>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<div id="global_searchViewShow">
		<div class="row">
			<div class="col-md-12">
				 <div id="global_search_view"></div>
			</div>
		</div>	
	</div>	
	
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>