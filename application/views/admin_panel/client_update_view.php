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
					<span>Client Account Update Form </span>
				</div>
				<div class="panel-body">
					<?php 
                                        $attributes = array("name" => "update_client_form", "class" => "form-horizontal");
                                        echo form_open_multipart(current_url() , $attributes); ?>
                                    
                                            <div class="form-group">
							<label for="email" class="col-md-4 control-label">Email</label>
							<div class="col-md-8">
							<input type="text" disabled="1" id="email_address" name="register_email_address" value="<?php echo $client['uacc_email'];?>" class="form-control tooltip_trigger" />
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Username</label>
							<div class="col-md-8">
                                                            <input  disabled="1" type="text" id="username" name="register_username" value="<?php echo $client['uacc_username'];?>" class="form-control tooltip_trigger" />
							</div>
						</div>

                                    
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">First Name</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="first_name" name="register_first_name" value="<?php echo $client['upro_first_name'];?>"/>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Last Name</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="last_name" name="register_last_name" value="<?php echo $client['upro_first_name'];?>"/>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-md-4 control-label">Phone Number</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="phone_number" name="register_phone_number" value="<?php echo $client['upro_phone'];?>"/>
							</div>
						</div>
                                                <div class="form-group">
							<label for="register_company" class="col-md-4 control-label">Company</label>
							<div class="col-md-8" id= "testresult">
                                                            <input type="text" id="register_company" name="register_company" value="<?php echo $client['customer_company_name'];?>" class="form-control tooltip_trigger" required />
							</div>
						</div> 

						<div class="form-group">
                                                    <label for="register_company_slug" class="col-md-4 control-label">Company URL Slug <br/><span style="font-size: 12px;">*(No white space & special character's allowed )</span></label>
							<div class="col-md-8" id= "testresult">
                                                            <input disabled="1" type="text" id="register_client_slug" name="register_company_slug" value="<?php echo $client['customer_company_slug'];?>" class="form-control tooltip_trigger" required />
							</div>
						</div>
                                    
                                                <div class="form-group">
							<label for="register_company_address" class="col-md-4 control-label">Address</label>
							<div class="col-md-8" id= "testresult">
                                                            <textarea id="register_company_address" name="register_company_address"  class="form-control tooltip_trigger" required><?php echo $client['customer_address'];?></textarea>
							</div>
						</div>
                                               	<div class="form-group">
							<label for="register_color_code" class="col-md-4 control-label">Color Theme </label>
							<div class="col-md-8" id= "">
                                                            <input class="jscolor" value="<?php echo $client['customer_theme_color']; ?>" name="register_theme_color" id="register_theme_color">
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
								<input type="submit" name="update_client" class="btn btn-primary act-but-prm" id="submit" value="Update" />
							</div>
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