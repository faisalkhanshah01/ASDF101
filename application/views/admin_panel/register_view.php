<?php $this->load->view('admin_panel/common/header', $header_data);?>
	<div class="row">
		<div class="col-md-offset-2 col-md-8 form-cont">
			<div class="row" class="msg-display">
				<div class="col-md-12">
					<?php 
                             $message = !empty($this->session->flashdata('message'))? $this->session->flashdata('message'):$message;
                                        if ($message) { 
                                          echo "<p>$message</p>";
					 } 
                                       ?>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>Register</span>
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart(current_url() , 'class="form-horizontal"'); ?>
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
                                                            <input type="text" id="email_address" name="register_email_address" value="<?php echo $_GET['email'];?>" class="form-control tooltip_trigger" readonly="1" />
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Username</label>
							<div class="col-md-8">
								<input type="text" id="username" name="register_username" value="" class="form-control tooltip_trigger" />
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
							<label for="register_company" class="col-md-4 control-label">Company</label>
							<div class="col-md-8" id= "testresult">
                                                            <input type="text" id="register_company" name="register_company" value="<?php echo set_value('register_company');?>" class="form-control tooltip_trigger" required />
							</div>
						</div> 

						<div class="form-group">
                                                    <label for="register_company_slug" class="col-md-4 control-label">Company URL Slug <br/><span style="font-size: 12px;">*(No white space & special character's allowed )</span></label>
							<div class="col-md-8" id= "testresult">
                                                            <input type="text" id="register_client_slug" name="register_company_slug" value="<?php echo set_value('register_company');?>" class="form-control tooltip_trigger" required />
							</div>
						</div>
                                    
                                                <div class="form-group">
							<label for="register_company_address" class="col-md-4 control-label">Address</label>
							<div class="col-md-8" id= "testresult">
                                                            <textarea id="register_company_address" name="register_company_address"  class="form-control tooltip_trigger" required><?php echo set_value('register_company');?></textarea>
							</div>
						</div>
                                               	<div class="form-group">
							<label for="register_color_code" class="col-md-4 control-label">Color Theme </label>
							<div class="col-md-8" id= "">
                                                            <input class="jscolor" value="ab2567" name="register_color_code" id="color_code">
							</div>
						</div>
                                    
                                                <div class="form-group">
							<label for="register_client_logo" class="col-md-4 control-label">Client Logo </label>
							<div class="col-md-8" id= "">
                                                            <input type="file" name="register_client_logo" id="register_client_logo">
							</div>
						</div>
                                    
                                  
                                               <?php /* ?>             
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
                                               
                                               <?php */?>

                                                    
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
<?php   $this->load->view('admin_panel/common/footer'); ?>

<script src="http://jscolor.com/release/2.0/jscolor-2.0.5/jscolor.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#register_country').on('change',function(e){   
        var country_id = $(this).val();
		
		if(country_id){            
			jQuery.ajax({
                type:'POST',
                url:"<?php echo base_url('Auth/ajax_get_zone_list') ?>",
                data:'country_id='+country_id,
                success:function(html){ 
                   $('#register_zone').html(html);  
				    e.preventDefault();
                }
            });		
        }else{
             $('#register_zone').html('<option value="">Select country first</option>');
			 e.preventDefault();
            
        }

		
    });


    $('#register_zone').on('change',function(e){   
        var province_id = $(this).val();
		
		if(province_id){            
			jQuery.ajax({
                type:'POST',
                url:"<?php echo base_url('Auth/ajax_get_cities_list') ?>",
                data:'province_id='+province_id,
                success:function(html){ 
                   $('#register_city').html(html);  
				    e.preventDefault();
                }
            });		
        }else{
             $('#register_city').html('<option value="">Select state first</option>');
			 e.preventDefault();
            
        }

		
    });

    
    
});
</script>