<?php $this->load->view('includes/header'); ?> 
<script type="text/javascript" src="<?php echo $includes_dir;?>js/components/assets.js"></script>
<!-- Navigation -->
<?php $this->load->view('includes/head'); ?>
    <?php 
            $CI=& get_instance();
            $CI->load->model('Subassets_model');
            $subAssets=$CI->Subassets_model->get_sub_assets_list('sub_assets_code');
            if(!empty($subAssets)){
                    foreach($subAssets as $sAssets){
                            $sub_assets_list[]=$sAssets['sub_assets_code'];
                    }
            }else{
                    $sub_assets_list = '';
            }
    ?>
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
    
    
	<div class="row">
  		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span >EDIT ASSETS</span>
				</div>
				<div class="panel-body">
			<?php echo form_open_multipart(current_url() ,'class="form-horizontal"'); ?>
				<div class="form-group">
					<div class="col-md-10">
						<input type="hidden" class="form-control" id="speci_file_id" name="speci_file_id" 
							value="<?php print key($_SESSION['flexi_auth']['group']);?>" required />
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Asset Code</label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="component_code" name="component_code" 
                        value="<?php echo set_value('component_code',$component['component_code']);?>"/>
                        <?php echo form_error('component_code'); ?>
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Description</label>
					<div class="col-md-8">
                    	<textarea id="component_description" name="component_description"  class="form-control tooltip_trigger" ><?php echo set_value('component_description',$component['component_description']);?></textarea>
                    
					</div>
				</div>
				
				
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Add Sub Assets</label>	
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-5"> 
								<input type="text" id="search_tool_sub_assets" name="search_tool_sub_assets" class="form-control tooltip_trigger"  placeholder="Search sub assets"/> 
							</div>
						</div>


						<div class="row">
							<div class="col-md-5"> 
								<div class="search-container search-subAssets" id='sel_subAssets'>
								<?php	if(is_array($sub_assets_list)){
									foreach($sub_assets_list as $sub_assets){ ?>
										<p><?php echo $sub_assets; ?>
										<input class="pull-right" type="checkbox" name="sub_assets[]" id="<?php echo "chk_".$sub_assets; ?>" value="<?php echo $sub_assets; ?>" /></p>
								<?php } } ?> 
								</div>  
							</div>
							<div class="col-md-2">
								<button id="com_sel_btn_subAssets" class="btn" type="button" style="margin-top:50px;"> >> </button>
							</div>
							<div class="col-md-5" >
								<div class="component-container" id="selected_subAssets">
								<?php if(!empty($component['component_sub_assets'])){
								foreach((json_decode($component['component_sub_assets'],true)) as $comp){
									if(in_array($comp,$sub_assets_list)){ ?>
										<p id='<?php echo $comp; ?>' class='bg-success'><?php echo $comp; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
										<input type="hidden" class="sel-component subAssets" name="sub_assets[]" value="<?php echo $comp; ?>"/>
										</p>
									<?php	
									}else{ ?>
										<p id='<?php echo $comp; ?>' class='bg-danger'><?php echo $comp; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
										<input type="hidden" class="sel-component" name="sub_assets[]" value="<?php echo $comp; ?>"/>
										</p>
								<?php	}	}	} ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Upload Image</label>
					<div class="col-md-8">
						<input type="file" class="form-control" id="product_image" name="product_image" />
					</div>
				</div>

				<div class="form-group">
					<label for="email" class="col-md-3 control-label">UOM</label>
					<div class="col-md-8">
						<select id='component_uom' class='form-control' name="component_uom">
								<option value=''> - Select - </option>
								<?php 
									$data = array('mtr' => 'MTR', 'nos' =>'NOS');
									foreach($data as $id=>$val){
										$selected = ($component['component_uom'] == $id)? 'Selected' : '';
										echo "<option ".$selected." value='".$id."'>".$val."</option>";
									}
								?>
						</select>
						<?php echo form_error('component_uom'); ?>
					</div>
				</div>
				
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Type of Inspection</label>
					<div class="col-md-8">
					<select  id="component_inspectiontype" name="component_inspectiontype"  class="form-control tooltip_trigger">
						<option value="">Select Inspection Type</option>
						<?php
							if(!empty($inspection)){
								foreach($inspection as $insKey=>$insValue){
									$selected = ($insKey == $component['component_inspectiontype'])? 'Selected' : '' ;
									echo "<option ".$selected." value='".$insKey."'>".$insValue."</option>";
								}
							}
						?>
                    </select>
                      <?php echo form_error('component_inspectiontype'); ?>  
					</div>
				</div>
                
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Expected Result </label>	
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-5"> 
								<input type="text" id="search_tool_expected_result" name="search_tool_expected_result" class="form-control tooltip_trigger"  placeholder="Search Expected Result"/> 
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-5">
								<div class="component-container search-expectedResult" id='sel_expectedResult'>
									<?php foreach($result as $resultKey=>$resultValue){ 
									?>
									<p><?php echo $resultValue; ?>
									<input class="pull-right" type="checkbox" name="expectedResult[]" id="<?php echo "chk_".$resultKey; ?>" value="<?php echo $resultKey; ?>" rel="<?php echo $resultValue; ?>" /></p>
									<?php }?>
								</div> 
							</div>
							<div class="col-md-2">
								<button id="com_sel_btn_expectedResult" class="btn" type="button" style="margin-top:50px;"> >> </button>
							</div>
							<div class="col-md-5" >
								<div class="component-container" id="selected_expectedResult">
									<?php if(!empty($component['component_expectedresult'])){
											$excpected_result = json_decode($component['component_expectedresult'],true);
											foreach($excpected_result as $comp){
												if(array_key_exists($comp,$result)){ ?>
												
													<p id='<?php echo $comp; ?>' class='bg-success'><?php echo $result[$comp]; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
														<input type="hidden" class="sel-component subresult" name="expectedResult[]" value="<?php echo $comp; ?>"/>
													</p>
										<?php	
												}else{ ?>
													<p id='<?php echo $comp; ?>' class='bg-success'><?php echo $result[$comp]; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
														<input type="hidden" class="sel-component" name="expectedResult[]" value="<?php echo $comp; ?>"/>
													</p>
										<?php	}
											}
										}
									?>
								</div>
							</div>
						</div> 
					</div>
				</div>
				
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Observations</label>	
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-5"> 
								<input type="text" id="search_tool_observations" name="search_tool_observations" class="form-control tooltip_trigger"  placeholder="Search Expected Result"/> 
							</div>
						</div>
						<div class="row">
							<div class="col-md-5">
								<div class="component-container search-observation" id='sel_observation'>
									<?php if(is_array($observation)){
											foreach($observation as $obsKey=>$obsValue){
									?>
									<p><?php echo $obsValue; ?>
									<input class="pull-right" type="checkbox" name="observation[]" id="<?php echo "chk_".$obsKey; ?>" value="<?php echo $obsKey; ?>" rel="<?php echo $obsValue; ?>" /></p>
									<?php } } ?> 
								</div> 
							</div>
							<div class="col-md-2">
								<button id="com_sel_btn_observation" class="btn" type="button" style="margin-top:50px;"> >> </button>
							</div>
							<div class="col-md-5" >
								<div class="component-container" id="selected_observation">
								<?php if(!empty($component['component_observation'])){
											$obs_result = json_decode($component['component_observation'],true);
											foreach($obs_result as $comp){
												if(is_array($observation) && array_key_exists($comp,$observation)){ ?>
												
													<p id='<?php echo $comp; ?>' class='bg-success'><?php echo $observation[$comp]; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
														<input type="hidden" class="sel-component" name="observation[]" value="<?php echo $comp; ?>"/>
													</p>
										<?php	
												}else{ ?>
													<p id='<?php echo $comp; ?>' class='bg-danger'><?php echo $comp; ?><span rel="<?php echo $comp; ?>" class="pull-right text-danger  glyphicon glyphicon-trash"></span>
														<input type="hidden" class="sel-component" name="observation[]" value="<?php echo $comp; ?>"/>
													</p>
										<?php	}
											}
										}
								?>
								</div>
							</div>
						</div> 
					</div>
				</div>

				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Repair</label>
					<div class="col-md-8">
                    <span class="col-md-3">
					<input  type="radio" id="component_repair" name="component_repair" value="yes"<?php echo set_radio_state($component['component_repair'],'yes');?> /> Yes
                    </span>
                    <input type="radio" id="component_repair" name="component_repair" value="no"  <?php echo set_radio_state($component['component_repair'],'no');?>/> No
                    
                      <?php echo form_error('component_repair'); ?>  
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Geo Fancing</label>
					<div class="col-md-8">
						<span class="col-md-3">
							<input  type="radio" id="geo_fancing" name="geo_fancing" value="yes" <?php echo set_radio_state($component['component_geo_fancing'],'yes');?> /> Yes
						</span>
						<input type="radio" id="geo_fancing" name="geo_fancing" value="no"  <?php echo set_radio_state($component['component_geo_fancing'],'no');?>/> No
						<?php echo form_error('geo_fancing'); ?>  
					</div>
				</div>
											
											<div class="form-group">
					<label for="email" class="col-md-3 control-label">Work Permit </label>
					<div class="col-md-8">
						<span class="col-md-3">
							<input  type="radio" id="work_permit" name="work_permit" value="yes" <?php echo set_radio_state($component['component_work_permit'],'yes');?> /> Yes
						</span>
						<input type="radio" id="work_permit" name="work_permit" value="no"  <?php echo set_radio_state($component['component_work_permit'],'no');?>/> No
						<?php echo form_error('work_permit'); ?>  
					</div>
				</div>
                                    
                                        <div class="form-group">
                                        <label for="frequency_month" class="col-md-3 control-label">Frequency of Product (in month)</label>
                                        <div class="col-md-8">
                                        <input type="number" class="form-control" id="frequency_asset" name="frequency_asset" 
                                        value="<?php echo $component['component_frequency_asset'];?>" min="1" max="24" />
                                        </div>
                                        </div>
                                        <div class="form-group">
                                                        <label for="frequency_hours" class="col-md-3 control-label">Frequency of Product (in hours)</label>
                                                        <div class="col-md-8">
                                                            <input type="number" class="form-control" id="frequency_hours" name="frequency_hours" 
                                                            value="<?php echo $component['component_frequency_hours'];?>" />
                                                        </div>
                                        </div>

                                        <div class="form-group">
                                                        <label for="lifespan_month" class="col-md-3 control-label">Life span of Product (in month)</label>
                                                        <div class="col-md-8">
                                                            <input type="number" class="form-control" id="lifespan_month" name="lifespan_month" 
                                                            value="<?php echo $component['component_lifespan_month'];?>" min="1" max="24" />
                                                        </div>
                                        </div>
                                        <div class="form-group">
                                                        <label for="lifespan_hours" class="col-md-3 control-label">Life span of Product (in hours)</label>
                                                        <div class="col-md-8">
                                                            <input type="number" class="form-control" id="lifespan_hours" name="lifespan_hours" 
                                                            value="<?php echo $component['component_lifespan_hours'];?>" />
                                                        </div>
                                        </div> 
                                        <div class="form-group">
                                                       <label for="permain" class="col-md-3 control-label">Frequency of Periodic Maintenance (in Days)</label>
                                                       <div class="col-md-8">
                                                           <input type="number" class="form-control" id="pdm_frequency" name="pdm_frequency" 
                                                           value="<?php echo $component['component_pdm_frequency']; ?>" />
                                                       </div>
                                        </div>    
                                    
											
				<!--<div class="form-group">
						<label for="email" class="col-md-3 control-label">Frequency of Asset Series</label>
						<div class="col-md-8">
								<input type="number" class="form-control" id="frequency_asset" name="frequency_asset" 
								value="<?php //echo $component['component_frequency_asset'];?>" min="1" max="24" />
						</div>
				</div>-->
                                    
                                    
				<div class="form-group">
					<label class="control-label col-md-3" for="product_category_status">Infonet Status</label>
					<div class="col-md-8">
						<select class="form-control" id="infonet_status_status" name="infonet_status_status" required="required">
							<option value=""> - Select Status - </option>
							<option <?php set_option_state($component['infonet_status'],1); ?> value="Active" >Active</option>
							<option <?php set_option_state($component['infonet_status'],0); ?> value="Inactive">Inactive</option>
						</select>
					</div>
				</div>

				<!-- Start from manage_certificate --->
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Standards</label>
					<div class="col-md-8">								    
						<select  id="standards_certificate" name="standards_certificate" class="form-control chosen" required>
							<option  value=""> - Status - </option>
								<?php if(is_array($standCerticate)){
									foreach($standCerticate as $obsKey=>$obsValue){
										if( $component['standard_certificate_id'] == $obsValue['id'] ){ ?>
									           <option  value="<?php echo $obsValue['id']; ?>" SELECTED ><?php echo $obsValue['name']; ?></option>
								<?php 		}else{
								?>
								<option  value="<?php echo $obsValue['id']; ?>" ><?php echo $obsValue['name']; ?></option>
								<?php } } } ?>
						</select>
					 <?php echo form_error('standards'); ?> 
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">EC Type Certificate</label>
					<div class="col-md-8">
						<input type="text"   id="ec_type_certificate_txt" name="ec_type_certificate_txt" class="form-control" value="<?php echo set_value('ec_type_certificate_text',$component['ec_type_certificate_text']);?>" required>
							
					 <?php echo form_error('ec_type_certificate'); ?> 
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Notified Body( certification )</label>
					<div class="col-md-8">
						<select  id="notified_certified" name="notified_certified" class="form-control chosen" required>
							<option  value=""> - Status - </option>
								<?php if(is_array($NotifiedBodyCerticate)){
									foreach($NotifiedBodyCerticate as $obsKey=>$obsValue){
										if( $component['notified_body_certificate_id'] == $obsValue['id'] ){ ?>
									<option  value="<?php echo $obsValue['id']; ?>" SELECTED ><?php echo $obsValue['name']; ?></option>
								<?php 		}else{
								?>
								<option  value="<?php echo $obsValue['id']; ?>" ><?php echo $obsValue['name']; ?></option>
								<?php } } } ?>										
						</select>
					 <?php echo form_error('notified_certified'); ?> 
					</div>
				</div>

				<div class="form-group">
					<label for="email" class="col-md-3 control-label">Notified Body( Article 11B)</label>
					<div class="col-md-8">
						<select id="article_11b_certificate" name="article_11b_certificate" class="form-control chosen" />
						  <option  value=""> - Status - </option>
								<?php if(is_array($Article11BCerticate)){
									foreach($Article11BCerticate as $obsKey=>$obsValue){
									if( $component['article_11b_certificate_id'] == $obsValue['id'] ){ ?>
									<option  value="<?php echo $obsValue['id']; ?>" SELECTED ><?php echo $obsValue['name']; ?></option>
									<?php 	}else{
								?>
								<option  value="<?php echo $obsValue['id']; ?>" ><?php echo $obsValue['name']; ?></option>
								<?php } } } ?>
						</select>
						<?php echo form_error('article_11b'); ?>  
					 
					</div>
				</div>
				<!-- End from manage_certificate --->


               	<div class="form-group">
					<label for="email" class="col-md-3 control-label">Status</label>
					<div class="col-md-8">
							<select  id="status" name="status"  class="form-control tooltip_trigger" required>
								<option value="" > - Status -</option>
								<option <?php set_option_state($component['status'],'Active'); ?> value="Active" >Active</option>
								<option <?php set_option_state($component['status'],'Inactive'); ?> value="Inactive">Inactive</option>
							</select>
						  <?php echo form_error('status'); ?>
					</div>
				</div>		
				
				<div class="form-group">
					<div class="col-md-offset-3 col-md-8">
						<input type="submit" name="edit_assets" class="btn btn-primary" id="edit_component" value="Update" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url('manage_kare/assets_list'); ?>" class="btn btn-default">BACK</a>
					</div>
				</div>

			<!--</form>-->
				<?php echo form_close();?>
			</div>
			</div>
		</div>
	</div>
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>