<?php $this->load->view('includes/header'); ?>
	<div class="row">
		<div class="col-md-offset-2 col-md-8 form-cont">
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
					<span>Register</span>
				</div>
				<div class="panel-body">
					<?php echo form_open(current_url() , 'class="form-horizontal"'); ?>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">First Name</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="first_name" name="register_first_name" value="<?php echo set_value('register_first_name');?>"/>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Last Name</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="last_name" name="register_last_name" value="<?php echo set_value('register_last_name');?>"/>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Phone Number</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="phone_number" name="register_phone_number" value="<?php echo set_value('register_phone_number');?>"/>
							</div>
						</div>

						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Email</label>
							<div class="col-md-8">
							<input type="text" id="email_address" name="register_email_address" value="<?php echo set_value('register_email_address');?>" class="form-control tooltip_trigger" />
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Username</label>
							<div class="col-md-8">
								<input type="text" id="username" name="register_username" value="<?php echo set_value('register_username');?>" class="form-control tooltip_trigger" />
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-md-4 control-label">Password</label>
							<div class="col-md-8">
								<input type="password" class="form-control" id="password" name="register_password" value="<?php echo set_value('register_password');?>" placeholder="Generate Password" required/>
								<small>
									Password length must be more than <?php echo $this->flexi_auth->min_password_length(); ?> characters in length.<br/>Only alpha-numeric, @, dashes, underscores, periods and comma characters are allowed.
								</small>
							</div>
						</div>
						
						<div class="form-group">
							<label for="password" class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-8">
								<input type="password" class="form-control" id="confirm_password" name="register_confirm_password" value="<?php echo set_value('register_confirm_password');?>" required/>
							</div>
						</div>

						<div class="form-group">
							<label for="register company" class="col-md-4 control-label">Company</label>
							<div class="col-md-8" id= "testresult">
								<input type="text" id="register_company" name="register_company" value="<?php echo set_value('register_company');?>" class="form-control tooltip_trigger" />
							</div>
						</div>

						<div class="form-group">
							<label for="register country" class="col-md-4 control-label">Country</label>
							<div class="col-md-8">
								<select name="register_country" id="register_country" class="form-control" required >
								<option value="" > --Select-- </option>
								<?php 
								foreach($country AS $key=>$val){ ?>
								<option value="<?php echo $key;  ?>" > <?php echo $val;  ?> </option>
								<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="register zone" class="col-md-4 control-label">Zone</label>
							<div class="col-md-8" id= "testresult">
								<select name="register_zone" id="register_zone" class="form-control" required >
								<option value="" > -- Select--- </option>
                                                               
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="password" class="col-md-4 control-label">City</label>
							<div class="col-md-8" id= "testresult">
								<select name="register_city" id="register_city" class="form-control" >
								<option value="" > -- Select--- </option>
                                                                
								</select>								
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<input type="submit" name="register_user" class="btn btn-primary act-but-prm" id="submit" value="Register" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">Already registered ? <a href="<?php echo $base_url;?>" class="act-link-prm">Sign In</a> </div>
						</div>
					<!--</form>-->
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>


<script type="text/javascript">
$(document).ready(function(){
    $('#register_country').on('change',function(e){   
        var country_id = $(this).val();
		
		if(country_id){            
			jQuery.ajax({
                type:'POST',
                url:"<?php echo $base_url."Auth/ajax_get_zone_list" ?>",
                data:'country_id='+country_id,
                success:function(html){ 
                   $('#register_zone').html(html);  
                }
            });		
        }else{
             $('#register_zone').html('<option value="">Select country first</option>');
            
        }

    });


    $('#register_zone').on('change',function(e){   
        var province_id = $(this).val();
		
		if(province_id){            
		jQuery.ajax({
                type:'POST',
                url:"<?php echo $base_url."auth/ajax_get_cities_list" ?>",
                data:'province_id='+province_id,
                success:function(html){ 
                   $('#register_city').html(html);  
                }
            });		
        }else{
             $('#register_city').html('<option value="">Select state first</option>');
            
        }

		
    });

    
    
});
</script>